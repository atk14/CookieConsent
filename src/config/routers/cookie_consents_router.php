<?php
class CookieConsentsRouter extends Atk14Router {

	function setUp(){
		foreach([
			"en" => "cookie-consent",
			"cs" => "nastaveni-cookies",
			"sk" => "nastavenie-cookies",
		] as $l => $uri){
			$this->addRoute("/$uri/","$l/cookie_consents/edit");
		}
	}
}
