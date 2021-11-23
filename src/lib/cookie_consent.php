<?php
class CookieConsent {

	const VERSION = "0.1";

	static function GetSettings($request = null, $options = []){
		return CookieConsentSettings::GetInstance($request,$options);
	}

	/**
	 *
	 *	if(CookieConsent::Accepted("advertising")){
	 *		// cookie section advertising has been accepted by the user
	 *	}
	 */
	static function Accepted($cookie_consent_category){
		$settings = self::GetSettings();
		return $settings->accepted($cookie_consent_category);
	}
}
