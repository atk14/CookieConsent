<?php
class CookieConsentCategoriesController extends AdminController {

	function index(){
		$this->page_title = _("Kategorie souhlasu používání cookies");
		$this->tpl_data["cookie_consent_categories"] = CookieConsentCategory::FindAll();
	}

	function create_new(){
		$this->_create_new();
	}

	function edit(){
		$this->_edit([
			"label" => _(""),
		]);
	}

	function destroy(){

	}

	function set_rank(){
		$this->_set_rank();
	}

	function _before_filter(){
		if(in_array($this->action,["edit"])){
			$this->_find("cookie_consent_category");
		}
	}
}
