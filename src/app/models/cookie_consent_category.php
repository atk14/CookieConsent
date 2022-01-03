<?php
class CookieConsentCategory extends ApplicationModel implements Rankable, Translatable {

	static function CreateNewRecord($values,$options = []){
		$values += [
			"cookie_consent_id" => CookieConsent::GetInstance(),
		];

		return parent::CreateNewRecord($values,$options);
	}

	static function GetTranslatableFields(){ return ["title", "description"]; }

	static function GetAllInstances(){
		static $instances;
		if(is_null($instances)){
			$instances = CookieConsentCategory::FindAll();
		}
		return $instances;
	}

	static function GetActiveInstances(){
		$out = [];
		foreach(self::GetAllInstances() as $ccc){
			if($ccc->isActive()){ $out[] = $ccc; }
		}
		return $out;
	}

	static function GetInstanceByCode($code){
		$code = (string)$code;
		foreach(self::GetAllInstances() as $ccc){
			if($ccc->getCode()===$code){
				return $ccc;
			}
		}
	}

	function __construct(){
		parent::__construct("cookie_consent_categories",[
			"sequence_name" => "seq_cookie_consent_categories",
		]);
	}

	function getCookieConsent(){
		return Cache::Get("CookieConsent",$this->getCookieConsentId());
	}

	function setRank($rank){
		$this->_setRank($rank,[
			"cookie_consent_id" => 1,
		]);
	}

	function isActive(){
		return $this->g("active");
	}

	function isNecessary(){
		return $this->g("necessary");
	}

	function isDeletable(){
		return $this->getCode()!=="necessary";
	}
}
