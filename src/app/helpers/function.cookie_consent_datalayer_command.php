<?php

function smarty_function_cookie_consent_datalayer_command($params, $template) {
	$settings = CookieConsent::GetSettings();

	$out = [
		"window.dataLayer = window.dataLayer || [];",
		"function gtag(){dataLayer.push(arguments);}",
	];
	if ($settings->sendConsentDefaultCommand()) {
		$out[] = sprintf("gtag('consent', 'default', %s);", json_encode($settings->getDefaultConsentStates()));
	}

	if($settings->needsToBeConfirmed()){
		$out[] = 'document.addEventListener( "consentupdate", function( ev ){';
		$out[] = "\tgtag('consent', 'update', ev.grantedConsents );";
		$out[] = "\twindow.dataLayer.push({
			event:'atk14_consent_updated',
			grantedConsents: ev.grantedConsents
		})";
		$out[] = '} );';
	} else {
		if ($settings->sendConsentUpdateCommand()) {
			$out[] = sprintf("gtag('consent', 'update', %s)", json_encode($settings->getGtmGrantedConsents()));
		}
		$out[] = sprintf("window.dataLayer.push(%s)", json_encode([
			"event" => "atk14_consent_updated",
			"grantedConsents" => $settings->getGtmGrantedConsents()
		]));
	}

	Atk14Require::Helper("block.javascript_tag");

	return smarty_block_javascript_tag($params, join("\n", $out), $template, $false);

}
