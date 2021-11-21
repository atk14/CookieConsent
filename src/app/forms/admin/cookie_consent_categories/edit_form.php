<?php
class EditForm extends CookieConsentCategoriesForm {

	function set_up(){
		parent::set_up();
		if($this->controller->cookie_consent_category->getCode()==="necessary"){
			$this->fields["code"]->disabled = true;
			$this->fields["necessary"]->disabled = true;
		}
	}
}
