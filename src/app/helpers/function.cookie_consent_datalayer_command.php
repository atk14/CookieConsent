<?php

function smarty_function_cookie_consent_datalayer_command($params, $template) {
	$settings = CookieConsent::GetSettings();

	$out = [
		"window.dataLayer = window.dataLayer || [];",
#		"function gtag(){dataLayer.push(arguments);}",
	];
	$out[] = sprintf('window.dataLayer.push( %s );', json_encode(["event" => "consentUpdate", "grantedConsents" => $settings->getGtmGrantedConsents()]));

	if($settings->needsToBeConfirmed()){
		$out[] = 'document.addEventListener( "consentupdate", function( ev ){';
		//$out[] = '  console.log( ev.grantedConsents );';
		$out[] = '  window.dataLayer.push( { "event": "consentUpdate", "grantedConsents": ev.grantedConsents } );';
		$out[] = '} );';
	}

	Atk14Require::Helper("function.javascript_tag");

	return smarty_block_javascript_tag($params, join("\n", $out), $template, $false);

}
