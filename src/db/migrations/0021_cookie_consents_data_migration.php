<?php
// migration from package atk14/cookie-consent

class CookieConsentsDataMigration extends ApplicationMigration {

	function up(){
		$cookie_consent = CookieConsent::CreateNewRecord([
			"id" => 1,
			//
			"banner_title_en" => 'Cookies',
			"banner_title_cs" => 'Cookies',
			"banner_text_en" => 'We use so-called cookies to operate our website. Cookies are files used to personalize the content of the website, to measure its functionality and to ensure your maximum satisfaction. You consent to the use of cookies by clicking on the "OK" button.',
			"banner_text_cs" => 'K provozování našeho webu využíváme takzvané cookies. Cookies jsou soubory sloužící k přizpůsobení obsahu webu, k měření jeho funkčnosti a k zajištění vaší maximální spokojenosti. Souhlas s používáním cookies udělíte kliknutím na tlačítko „OK“.',
			//
			"dialog_title_en" => 'Cookie Settings',
			"dialog_title_cs" => 'Nastavení cookies',
			"dialog_header_text_en" => 'Here you have the option to adjust the use of cookies based on your own preferences.',
			"dialog_header_text_cs" => "Zde máte možnost upravit si používání cookies na základě vlastních preferencí.",
			"dialog_footer_text_en" => 'For more information about cookies, see <a href="#">Privacy Policy</a>.',
			"dialog_footer_text_cs" => 'Více o cookies najdete v <a href="#">Zásadách ochrany osobních údajů</a>.',
		]);

		// there is a fixture for testing
		if(TEST){ return; }

		CookieConsentCategory::CreateNewRecord([
			"id" => 1,
			"code" => "necessary",
			"necessary" => true,
			"active" => true,
			"title_en" => "Technical cookies",
			"title_cs" => "Technické cookies",
			"description_cs" => "Technické cookies jsou nezbytné pro správné fungování webu a všech funkcí, které nabízí. Jsou odpovědné mj. za uchovávání produktů v košíku, zobrazování seznamu oblíbených výrobků nebo nákupní proces. Používání technických cookies nemůže být zakázáno.",
			"description_en" => "Technical cookies are necessary for the proper functioning of the website and all the functions it offers. They are responsible for, among other things, storing products in the basket, displaying a list of favourites or the shopping process. The use of technical cookies cannot be prohibited.",
		]);

		CookieConsentCategory::CreateNewRecord([
			"id" => 2,
			"code" => "analytics",
			"active" => DEVELOPMENT,
			"cookies_regexp" => "/^(_ga.*|_gid.*|_gcl.*|_utm.*)$/",
			"necessary" => false,
			"title_en" => "Analytic Cookies",
			"title_cs" => "Analytické cookies",
			"description_en" => "Analytical cookies allow us to measure the performance of our website and the effects of the adjustments we make to the website on an ongoing basis. We process the data obtained through these cookies in aggregate, without using identifiers that point to specific users of our website. If you disable the use of analytics cookies in relation to your visit, we lose the ability to analyse performance and optimise our measures.",
			"description_cs" => "Analytické cookies nám umožňují měření výkonu našeho webu a účinků úprav, které průběžně na web nasazujeme. Data získaná pomocí těchto cookies zpracováváme souhrnně, bez použití identifikátorů, které ukazují na konkrétní uživatelé našeho webu. Pokud vypnete používání analytických cookies ve vztahu k vaší návštěvě, ztrácíme možnost analýzy výkonu a optimalizace našich opatření.",
		]);

		CookieConsentCategory::CreateNewRecord([
			"id" => 3,
			"code" => "advertising",
			"active" => DEVELOPMENT,
			"cookies_regexp" => "/^(_fb.*)$/",
			"necessary" => false,
			"title_cs" => "Reklamní cookies",
			"title_en" => "Marketing & Advertising",
			"description_en" => "Advertising cookies allow us to show you relevant content or ads tailored to your interests both on our site and on third party sites.",
			"description_cs" => "Reklamní cookies nám umožňují zobrazovat vám vhodný obsah nebo reklamy přizpůsobené vašim zájmům jak na našich stránkách, tak na stránkách třetích subjektů.",
		]);
	}
}
