<?php

function smarty_function_cookie_consent_datalayer_command($params, $template) {
	$gtm_consent_equivalents = [
		"advertising" => "ad_storage",
		"analytics" => "analytics_storage",
		"necessary" => "functionality_storage",
#		"??" => "personalization_storage",
#		"??" => "security_storage",
	];
	$settings = CookieConsent::GetSettings();

	$granted = [];
	foreach( CookieConsentCategory::GetAllInstances() as $cat ) {
		$code = $cat->getCode();
		if ( !isset($gtm_consent_equivalents[$code]) ){ continue; }
		if ( $settings->accepted($cat) ) {
			$granted[$gtm_consent_equivalents[$code]] = "granted";
		}
	}

	$out = [
		"window.dataLayer = window.dataLayer || []",
#		"function gtag(){dataLayer.push(arguments);}",
	];
	$out[] = sprintf('dataLayer.push( %s)', json_encode(["event" => "consentUpdate", "grantedConsents" => $granted]));

	Atk14Require::Helper("function.javascript_tag");

	return smarty_block_javascript_tag($params, join(";\n", $out), $template, $false);

}
