<?php
// migration from package atk14/cookie-consent

class CookieConsentsDataMigration extends ApplicationMigration {

	function up(){

		// there is a fixture for testing
		if(TEST){ return; }

		CookieConsentCategory::FindFirst("code", "functional") || ( CookieConsentCategory::CreateNewRecord([
			"id" => 4,
			"cookie_consent_id" => 1,
			"code" => "functional",
			"necessary" => false,
			"active" => true,
			"title_en" => "Functional cookies",
			"title_cs" => "Funkční cookies",
			"description_en" => "Functional cookies used to provide additional functionality. This includes your preferences, such as language settings.",
			"description_cs" => "Funkční cookies používané k zajištění doplňující funkčnosti. To zahrnuje vaše předvolby, např. nastavení jazyka.",
		]) );

		CookieConsentCategory::FindFirst("code", "personalization") || ( CookieConsentCategory::CreateNewRecord([
			"id" => 5,
			"cookie_consent_id" => 1,
			"code" => "personalization",
			"necessary" => false,
			"active" => true,
			"title_en" => "Personalization cookies",
			"title_cs" => "Personalizační cookies",
			"description_en" => "Personalisation cookies help to tailor the content of the website to your interests or based on your previous browsing behaviour (e.g. products recommended based on previous orders).",
			"description_cs" => "Personalizační cookies pomáhají obsah webových stránek přizpůsobit Vašim zájmům nebo na základě Vašeho předchozího chování při prohlížení webových stránek, (např. produkty doporučené na základě předchozích objednávek).",
		]) );

		CookieConsentCategory::FindFirst("code", "security") || ( CookieConsentCategory::CreateNewRecord([
			"id" => 6,
			"cookie_consent_id" => 1,
			"code" => "security",
			"active" => true,
			"necessary" => false,
			"title_en" => "Security Cookies",
			"title_cs" => "Bezpečnostní cookies",
			"description_en" => "Security cookies allow the storage of security-related information, such as authentication, fraud protection and other means to protect the user.",
			"description_cs" => "Bezpečnostní cookies umožňují ukládání informací souvisejících se zabezpečením, např. ověřování, ochrana před podvody a další prostředky na ochranu uživatele.",
		]) );

	}
}
