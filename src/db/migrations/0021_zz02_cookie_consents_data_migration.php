<?php
// migration from package atk14/cookie-consent

class Zz02CookieConsentsDataMigration extends ApplicationMigration {

	function up(){

		// there is a fixture for testing
		if(TEST){ return; }

		CookieConsentCategory::FindFirst("code", "ad_personalization") || ( CookieConsentCategory::CreateNewRecord([
			"id" => 5,
			"cookie_consent_id" => 1,
			"code" => "ad_personalization",
			"necessary" => false,
			"active" => false,
			"title_en" => "Consent with personalized ads",
			"title_cs" => "Souhlas s personalizovanou reklamou",
			"description_en" => "This consent is given for the purpose of targeting personalised advertising.",
			"description_cs" => "Tento souhlas uděluje za účelem cílení personalizované reklamy.",
		]) );

		CookieConsentCategory::FindFirst("code", "ad_user_data") || ( CookieConsentCategory::CreateNewRecord([
			"id" => 6,
			"cookie_consent_id" => 1,
			"code" => "ad_user_data",
			"active" => false,
			"necessary" => false,
			"title_en" => "Consent to marketing activities",
			"title_cs" => "Souhlas s marketingovými aktivitami",
			"description_en" => "Used to set up consent to send user data to Google for advertising purposes",
			"description_cs" => "Tento souhlas je určen pro zpracování údajů o uživatelích do Googlu pro reklamní účely",
		]) );

	}
}
