<?php

define("COOKIE_CONSENT_STATE_COMMAND_FORMAT", "gtm"); # gtag

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
		$out[] = '} );';
	}

	Atk14Require::Helper("block.javascript_tag");

	return smarty_block_javascript_tag($params, join("\n", $out), $template, $false);

}
