<?php
defined("COOKIE_CONSENT_SETTINGS_COOKIE_NAME") || define("COOKIE_CONSENT_SETTINGS_COOKIE_NAME","consent");

class CookieConsentSettings {

	protected $settings = [];
	protected $request;
	protected $current_time = null;

	protected function __construct($request,$options = []){
		$options += [
			"current_time" => null,
		];
		$this->current_time = $options["current_time"];
		$this->request = $request;

		// default settings
		$categories = [];
		foreach(CookieConsentCategory::GetActiveInstances() as $ccc){
			$code = $ccc->getCode();
			$categories[$code] = [
				"accepted" => null, // true, false, null,
				"timestamp" => null,
				"version" => null, // 1,2,3,4,5...
			];
		}
		$settings = [
			"cookie" => [
				"cookie_consent_version" => null, // CookieConsent::VERSION
				"timestamp" => null,
			],
			"all" => [
				"accepted" => null, // true, false, null
				"timestamp" => null,
			],
			"saved_on_http_host" => null,
			"categories" => $categories,
		];

		// adding data from cookie to settings
		$cookie_data = $this->getCookieData();
		if($ar = $this->_cleanCookieValues($cookie_data,["c_v","c_t"],true)){
			list($c_v,$c_t) = $ar;
			$settings["cookie"]["cookie_consent_version"] = $c_v;
			$settings["cookie"]["timestamp"] = $c_t;
		}
		if($ar = $this->_cleanCookieValues($cookie_data,["all_a","all_t"],true)){
			list($all_a,$all_t) = $ar;
			$settings["all"]["accepted"] = $all_a;
			$settings["all"]["timestamp"] = $all_t;
		}
		if(isset($cookie_data["h"]) && is_string($cookie_data["h"]) && strlen($cookie_data["h"])<=253){
			$settings["saved_on_http_host"] = (string)$cookie_data["h"];
		}
		if(isset($cookie_data["cs"]) && is_array($cookie_data["cs"])){
			foreach($cookie_data["cs"] as $code => $data){
				if(!CookieConsentCategory::GetInstanceByCode($code)){ continue; }
				if($ar = $this->_cleanCookieValues($data,["a","t","v"],true)){
					if(!isset($settings["categories"][$code])){ $settings["categories"][$code] = []; }
					list($accepted,$timestamp,$version) = $ar;
					$settings["categories"][$code]["accepted"] = $accepted;
					$settings["categories"][$code]["timestamp"] = $timestamp;
					$settings["categories"][$code]["version"] = $version;
				}
			}
		}

		$this->settings = $settings;
	}

	/**
	 *
	 *	$ar = $this->_parseValues($cookie_data,["c_v","c_t"]);
	 */
	function _cleanCookieValues($data,$keys,$required = false){
		$out = [];
		foreach($keys as $key){
			if(!isset($data[$key])){
				return;
			}
			$value = (string)$data[$key];
			if($required && !strlen($value)){
				return;
			}
			if(!strlen($value)){
				$out[] = null;
				continue;
			}
			// CookieConsent version
			if($key==='c_v'){
				if(!preg_match('/^(0|[1-9]\d{0,2})(\.(0|[1-9]\d{0,2})){1,2}$/',$value)){ return; }
				$out[] = $value;
				continue;
			}
			// timestamp
			if(preg_match('/^(t|.*_t)$/',$key)){
				if(!preg_match('/^[1-9]\d{0,10}$/',$value)){ return; }
				if($value<strtotime("2018-11-11 11:11:11")){ return; }
				if($value>$this->_time() + 60 * 60 * 24){ return; } // a little bit in the future is ok :)
				$out[] = (int)$value;
				continue;
			}
			// acceptance flag
			if(preg_match('/^(a|.*_a)$/',$key)){
				if(!in_array($value,["a","r"])){ return; }
				$out[] = $value==="a";
				continue;
			}
			// CookieConsentCategory version
			if($key === 'v'){
				if(!preg_match('/^[1-9]\d{0,3}$/',$value)){ return; } // 1 .. 9999
				$out[] = (int)$value;
				continue;
			}
		}
		$empty_ar = array_filter($out,function($v){ return is_null($v); });
		if(sizeof($empty_ar)!==0 && sizeof($empty_ar)!==sizeof($out)){
			return;
		}
		return $out;
	}

	static function GetInstance($request = null,$options = []){
		if(!$request){
			$request = isset($GLOBALS["HTTP_REQUEST"]) ? $GLOBALS["HTTP_REQUEST"] : new HTTPRequest();
		}
		return new CookieConsentSettings($request,$options);
	}

	function accept($code){
		$code = is_object($code) ? $code->getCode() : $code;
		$cookie_consent_category = CookieConsentCategory::GetInstanceByCode($code);
		$this->settings["categories"][$code]["accepted"] = true;
		$this->settings["categories"][$code]["timestamp"] = $this->_time();
		$this->settings["categories"][$code]["version"] = $cookie_consent_category->getVersion();
	}

	function reject($code){
		$code = is_object($code) ? $code->getCode() : $code;
		$cookie_consent_category = CookieConsentCategory::GetInstanceByCode($code);
		if($cookie_consent_category->isNecessary()){ return; } // mandatory section can not be rejected
		$this->settings["categories"][$code]["accepted"] = false;
		$this->settings["categories"][$code]["timestamp"] = $this->_time();
		$this->settings["categories"][$code]["version"] = $cookie_consent_category->getVersion();
	}

	function acceptAll(){
		foreach(CookieConsentCategory::GetActiveInstances() as $ccc){
			$this->accept($ccc);
		}
		$this->settings["all"]["accepted"] = true;
		$this->settings["all"]["timestamp"] = $this->_time();
	}

	function rejectAll(){
		foreach(CookieConsentCategory::GetActiveInstances() as $ccc){
			$this->reject($ccc);
		}
		$this->settings["all"]["accepted"] = false;
		$this->settings["all"]["timestamp"] = $this->_time();
	}

	function clearAcceptAllStatus(){
		$this->settings["all"]["accepted"] = null;
		$this->settings["all"]["timestamp"] = null;
	}

	/**
	 *
	 *	$settings->accepted("necessary"); // true or false
	 *	$settings->accepted("advertising"); // true or false
	 */
	function accepted($cookie_consent_category){
		if(!is_object($cookie_consent_category)){
			$code = "$cookie_consent_category";
			$cookie_consent_category = CookieConsentCategory::GetInstanceByCode($code);
			if(!$cookie_consent_category){
				if(!defined("TEST") || !constant("TEST")){
					trigger_error("there is no such CookieConsentCategory with code '$code'");
				}
				return false;
			}
		}
		$code = $cookie_consent_category->getCode();
		if($cookie_consent_category->isNecessary()){
			return true;
		}
		return !!$this->settings["categories"][$code]["accepted"];
	}

	function acceptedAll(){
		return !!$this->settings["all"]["accepted"];
	}

	function rejectedAll(){
		if(is_null($this->settings["all"]["accepted"])){
			return false;
		}
		return !$this->settings["all"]["accepted"]; 
	}

	function saveSettings($response,$options = []){
		$options += [
			"delete_rejected_cookies" => true,
		];
		$this->settings["_save_"] = $this->_time();
		$this->settings["saved_on_http_host"] = $this->request->getHttpHost();
		$cookie_value = $this->compileCookieData();
		$cookie_value = json_encode($cookie_value);
		$cookie_value = base64_encode($cookie_value);
		$response->addCookie(COOKIE_CONSENT_SETTINGS_COOKIE_NAME,$cookie_value,[
			"expire" => $this->_time() + 60 * 60 * 24 * 365 * 2,
			"secure" => false,
			"path" => "/",
			"domain" => $this->_prepareDomainForCookie($this->request->getHttpHost()),
			"httponly" => false,
			"samesite" => "",
		]);

		if($options["delete_rejected_cookies"]){
			$this->deleteRejectedCookies($response);
		}
	}

	function deleteRejectedCookies($response){
		foreach(CookieConsentCategory::GetActiveInstances() as $ccc){
			if(!$this->accepted($ccc) && $ccc->getCookiesRegexp()){
				foreach($this->request->getCookieVars() as $k => $v){
					if(preg_match($ccc->getCookiesRegexp(),$k)){
						$this->_deleteCookie($response,$k);
					}
				}
			}
		}
	}

	function needsToBeConfirmed(){
		//if($this->acceptedAll() || $this->rejectedAll()){ return false; }
		foreach(CookieConsentCategory::GetActiveInstances() as $ccc){
			if($ccc->isNecessary()){ continue; }
			
			$code = $ccc->getCode();
			$item = $this->settings["categories"][$code];
			if(is_null($item["timestamp"])){
				return true;
			}
			if($item["version"]!==$ccc->getVersion()){
				return true;
			}
		}
		return false;
	}

	function compileCookieData(){
		$cs = [];
		foreach($this->settings["categories"] as $code => $item){
			$cs[$code] = [
				"a" => $this->_formatA($item["accepted"]),
				"t" => (string)$item["timestamp"],
				"v" => (string)$item["version"],
			];
		}
		$out = [
			"c_v" => CookieConsent::VERSION,
			"c_t" => (string)$this->_time(),
			"all_a" => $this->_formatA($this->settings["all"]["accepted"]),
			"all_t" => (string)$this->settings["all"]["timestamp"],
			"h" => $this->request->getHttpHost(),
			"cs" => $cs,
		];

		return $out;
	}

	function getCookieData(){
		if($cookie_data = $this->request->getCookieVar(COOKIE_CONSENT_SETTINGS_COOKIE_NAME)){
			$cookie_data = @base64_decode($cookie_data);
			$cookie_data = @json_decode($cookie_data,true);
		}
		if(!is_array($cookie_data)){ $cookie_data = []; }
		return $cookie_data;
	}

	function toArray(){
		$out = $this->settings;
		$out["cookie"]["timestamp"] = $this->_timestampToDatetime($out["cookie"]["timestamp"]);
		$out["all"]["timestamp"] = $this->_timestampToDatetime($out["all"]["timestamp"]);
		foreach($out["categories"] as $code => $item){
			$out["categories"][$code]["timestamp"] = $this->_timestampToDatetime($out["categories"][$code]["timestamp"]);
		}
		return $out;
	}

	/**
	 *
	 *	print_r($settings->getGtmGrantedConsents());
	 *	// Array
	 *	// (
	 *	//     [functionality_storage] => granted
	 *	// )
	 *	//
	 *	// or
	 *	//
	 *	// Array
	 *	// (
	 *	//     [functionality_storage] => granted,
	 *	//     [analytics_storage] => granted
	 *	// )
	 *	//
	 *	// etc.
	 */
	function getGtmGrantedConsents(){
		$gtm_consent_equivalents = [
#			"necessary" => "necessary", # there is no equivalent category in GTM
			# codes mapped to values known to GTMs' Consent Mode API
			"advertising" => "ad_storage",
			"analytics" => "analytics_storage",
			"functional" => "functionality_storage",
			"personalization" => "personalization_storage",
			"security" => "security_storage",
			"ad_personalization" => "ad_personalization",
			"ad_user_data" => "ad_user_data",
		];

		$granted = [];
		$settings = $this->settings;
		foreach( CookieConsentCategory::GetActiveInstances() as $cat ) {
			$code = $cat->getCode();
			if (!isset($settings["categories"][$code]["accepted"])) {
				continue;
			}
			if ( !isset($gtm_consent_equivalents[$code]) ){ continue; }
			if ( $this->accepted($cat) ) {
				$granted[$gtm_consent_equivalents[$code]] = "granted";
			}
			if ( !$this->needsToBeConfirmed() && !$this->accepted($cat) ) {
				$granted[$gtm_consent_equivalents[$code]] = "denied";
			}
		}
		return $granted;
	}

	function sendConsentDefaultCommand() {
		$consent = CookieConsent::GetInstance();
		if (!$consent->hasKey("send_consent_default_command")) {
			return false;
		}
		return CookieConsent::GetInstance()->getSendConsentDefaultCommand();
	}

	function sendConsentUpdateCommand() {
		$consent = CookieConsent::GetInstance();
		if (!$consent->hasKey("send_consent_update_command")) {
			return false;
		}
		return CookieConsent::GetInstance()->getSendConsentUpdateCommand();
	}

	function _timestampToDatetime($timestamp){
		return $timestamp ? date("Y-m-d H:i:s",$timestamp) : null;
	}

	function _formatA($accepted){
		if(is_null($accepted)){ return ""; }
		return $accepted ? "a" : "r";
	}

	function _time(){
		return $this->current_time ? $this->current_time : time();
	}

	function _deleteCookie($response,$cookie_name){
		$http_host = $this->request->getHttpHost();
		$domain = $this->_prepareDomainForCookie($http_host);
		$domains = [$domain];
		if(preg_match('/[a-z]/',$http_host) && ".$http_host"!==$domain){ // not ip address
			$domains[] = ".$http_host";
		}

		foreach($domains as $d){
			$expire = $this->_time() - 60 * 60 * 24 * 365 * 2;
			$response->addCookie("$cookie_name","",[
				"expire" => $expire,
				"path" => "/",
				"domain" => $d,
				"httponly" => false,
			]);
		}
	}

	function _prepareDomainForCookie($domain){
		if(preg_match('/[a-z]/',$domain)){ // if not an IP address...
			$_domain = preg_replace('/^[^.]+\./','.',$domain);
			if(preg_match('/\.[^.]+\./',$_domain) && !in_array($_domain,[".co.uk"])){ // at least two dots
				$domain = $_domain;
			}else{
				$domain = ".$domain"; // "example.com" -> ".example.com"
			}
		}
		return $domain;
	}
}
