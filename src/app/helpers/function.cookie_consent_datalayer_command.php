<?php

function smarty_function_cookie_consent_datalayer_command($params, $template) {
	$settings = CookieConsent::GetSettings();

	$out = [
		"window.dataLayer = window.dataLayer || [];",
		"function gtag(){dataLayer.push(arguments);}",
			sprintf("gtag('consent', 'default', %s);", json_encode([
				"analytics_storage" => "denied",
				"ad_storage" => "denied",
				"functionality_storage" => "denied",
				"personalization_storage" => "denied",
			])),
	];

	if($settings->needsToBeConfirmed()){
		$out[] = 'document.addEventListener( "consentupdate", function( ev ){';
		$out[] = "\tgtag('consent', 'update', ev.grantedConsents );";
		$out[] = "\twindow.dataLayer.push({
			event:'atk14_consent_updated',
			grantedConsents: ev.grantedConsents
		})";
		$out[] = '} );';
	} else {
		$out[] = sprintf("gtag('consent', 'update', %s)", json_encode($settings->getGtmGrantedConsents()));
#		$out[] = sprintf("gtag('event', 'atk14_consent_updated', %s)", json_encode(["grantedConsents" => $settings->getGtmGrantedConsents()]));
		$out[] = sprintf("window.dataLayer.push(%s)", json_encode([
			"event" => "atk14_consent_updated",
			"grantedConsents" => $settings->getGtmGrantedConsents()
		]));
	}

	Atk14Require::Helper("block.javascript_tag");

	return smarty_block_javascript_tag($params, join("\n", $out), $template, $false);

}
