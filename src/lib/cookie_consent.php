<?php
class CookieConsent {

	const VERSION = "0.1";

	static function GetSettings($request = null, $options = []){
		return CookieConsentSettings::GetInstance($request,$options);
	}
}
