<?php

function smarty_function_cookie_consent_datalayer_command($params, $template) {
	$settings = CookieConsent::GetSettings();
	$out = [
		"window.dataLayer = window.dataLayer || []",
#		"function gtag(){dataLayer.push(arguments);}",
	];
	$out[] = sprintf('dataLayer.push( %s)', json_encode(["event" => "consentUpdate", "grantedConsents" => $settings->getGtmGrantedConsents()]));

	Atk14Require::Helper("function.javascript_tag");

	return smarty_block_javascript_tag($params, join(";\n", $out), $template, $false);

}
