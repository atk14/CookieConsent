<?php
class CookieConsentMigration extends ApplicationMigration {

	// DROP TABLE cookie_consent_categories; DROP SEQUENCE seq_cookie_consent_categories;
	// ./scripts/migrate -f 0020_cookie_consent_migration.php

	function up(){
		$this->dbmole->doQuery("
			CREATE SEQUENCE seq_cookie_consent_categories START WITH 11;
			CREATE TABLE cookie_consent_categories (
				id INT PRIMARY KEY DEFAULT NEXTVAL('seq_cookie_consent_categories'),
				--
				code VARCHAR(255) NOT NULL,
				--
				active BOOLEAN NOT NULL DEFAULT TRUE,
				necessary BOOLEAN NOT NULL DEFAULT FALSE,
				cookies_regexp VARCHAR(255), -- e.g. '/^ab_/', '/^(gm_tracking|gm_identity)/'
				version INT NOT NULL DEFAULT 1, -- when something important is changed in this category, version needs to be increased
				--
				rank INTEGER DEFAULT 999 NOT NULL,
				--
				created_by_user_id INT,
				updated_by_user_id INT,
				--
				created_at TIMESTAMP NOT NULL DEFAULT NOW(),
				updated_at TIMESTAMP,
				--
				CONSTRAINT unq_cookieconsentcategories_code UNIQUE (code),
				CONSTRAINT fk_cookieconsentcategories_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users,
				CONSTRAINT fk_cookieconsentcategories_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
			);
		");

		if(TEST){ return; }

		CookieConsentCategory::CreateNewRecord([
			"id" => 1,
			"code" => "necessary",
			"necessary" => true,
			"title_en" => "Required Cookies",
			"title_cs" => "Technické cookies",
			"description_cs" => "Technické cookies jsou nezbytné pro správné fungování webu a všech funkcí, které nabízí. Jsou odpovědné mj. za uchovávání produktů v košíku, zobrazování seznamu oblíbených výrobků (schránka), působení filtrů, nákupní proces a ukládání nastavení soukromí. Nepožadujeme Váš souhlas s využitím technických cookies na našem webu. Z tohoto důvodu technické cookies nemohou být individuálně deaktivovány nebo aktivovány.",
			"description_en" => "These cookies are required for basic website functionality. Without these cookies, the site would not work and you would be unable to browse.",
		]);

		CookieConsentCategory::CreateNewRecord([
			"id" => 2,
			"code" => "analytics",
			"cookies_regexp" => "/^(_ga.*|_gid.*|_utm.*)$/",
			"necessary" => false,
			"title_en" => "Analytic Cookies",
			"title_cs" => "Analytická cookies",
			"description_cs" => "Analytické cookies nám umožňují měření výkonu našeho webu a našich reklamních kampaní. Jejich pomocí určujeme počet návštěv a zdroje návštěv našich internetových stránek. Data získaná pomocí těchto cookies zpracováváme souhrnně, bez použití identifikátorů, které ukazují na konkrétní uživatelé našeho webu. Pokud vypnete používání analytických cookies ve vztahu k Vaší návštěvě, ztrácíme možnost analýzy výkonu a optimalizace našich opatření.",
		]);

		CookieConsentCategory::CreateNewRecord([
			"id" => 3,
			"code" => "advertising",
			"cookies_regexp" => "/^(_fbp)$/",
			"necessary" => false,
			"title_cs" => "Reklamní cookies",
			"title_en" => "Marketing & Advertising",
			"description_cs" => "Reklamní cookies používáme my nebo naši partneři, abychom Vám mohli zobrazit vhodné obsahy nebo reklamy jak na našich stránkách, tak na stránkách třetích subjektů. Díky tomu můžeme vytvářet profily založené na Vašich zájmech, tak zvané pseudonymizované profily. Na základě těchto informací není zpravidla možná bezprostřední identifikace Vaší osoby, protože jsou používány pouze pseudonymizované údaje. Pokud nevyjádříte souhlas, nebudete příjemcem obsahů a reklam přizpůsobených Vašim zájmům.",
			"description_en" => "These cookies enable us to make sure you see more relevant adverts on other sites on the internet. You will still see the same number of ads without them but they would be less relevant. ",
		]);
	}
}
