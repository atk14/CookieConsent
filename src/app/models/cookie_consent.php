<?php
class CookieConsent extends ApplicationModel implements Translatable {

	const VERSION = "0.1";

	static function GetTranslatableFields(){ return ["banner_title", "banner_text", "dialog_title", "dialog_header_text", "dialog_footer_text"]; }

	static function GetInstance(){
		return Cache::Get("CookieConsent",1);
	}

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

	function __construct(){
		parent::__construct("cookie_consents");
	}
}
