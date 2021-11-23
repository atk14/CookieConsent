<?php
class CookieConsentCategoriesForm extends AdminForm {

	function set_up(){
		$this->add_field("code", new RegexField("/^[a-z0-9_]+$/",[
			"label" => _("Kód sekce"),
			"max_legth" => 255,
			"hints" => [
				"advertising",
				"analytics",
				"tracking",
			],
		]));

		$this->add_translatable_field("title", new CharField([
			"label" => _("Title"),
			"max_length" => 255,
		]));

		$this->add_field("necessary", new BooleanField([
			"label" => _("Je to nezbytná sekce?"),
			"required" => false,
		]));

		$this->add_field("active", new BooleanField([
			"label" => _("Aktivní?"),
			"required" => false,
		]));

		$this->add_field("cookies_regexp", new CharField([
			"label" => _("Regulární výraz pro detekci cookies"),
			"required" => false,
			"max_length" => 255,
			"null_empty_output" => true,
			"hints" => [
				"/^tracking_cookie$/",
				"/^tracking_.*$/",
				"/^(cookie_name|another_cookie)$/",
				"/^(_ga_.*|_gmt_.*)$/",
			],
		]));

		$this->add_field("version", new IntegerField([
			"label" => _("Číslo verze"),
			"initial" => 1,
			"min_value" => 1,
			"max_value" => 9999,
			"required" => true,
			"help_text" => _("Zvyšte číslo verze, pokud je vyžadováno opakované schválení souhlasu s použitím cookies v této kategorii."),
		]));

		$this->add_translatable_field("description", new MarkdownField([
			"label" => _("Popis"),
			"help_text" => _("Formátování Markdown")
		]));
	}
}
