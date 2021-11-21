<?php
class EditForm extends ApplicationForm {

	function set_up(){
		$settings = CookieConsent::GetSettings();

		Atk14Require::Helper("modifier.markdown");
		foreach(CookieConsentCategory::GetActiveInstances() as $ccc){
			$initial = $ccc->isNecessary() ? true : $settings->accepted($ccc);
			$disabled = $ccc->isNecessary() ? true : false;
			$this->add_field("category_".$ccc->getId(),new BooleanField([
				"label" => $ccc->getTitle(),
				"required" => false,
				"initial" => $initial,
				"disabled" => $disabled,
				"help_text" => smarty_modifier_markdown($ccc->getDescription()),
			]));
		}
	}
}
