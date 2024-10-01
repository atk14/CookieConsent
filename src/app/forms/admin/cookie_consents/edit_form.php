<?php
class EditForm extends AdminForm {

	function set_up(){
		$this->add_translatable_field("banner_title", new CharField([
			"label" => _("Titulek cookie lišty"),
			"max_length" => 255,
		]));

		$this->add_translatable_field("banner_text", new MarkdownField([
			"label" => _("Text cookie lišty"),
			"help_text" => _("Formátování Markdown"),
		]));

		$this->add_translatable_field("dialog_title", new CharField([
			"label" => _("Titulek dialogu"),
		]));

		$this->add_translatable_field("dialog_header_text", new MarkdownField([
			"label" => _("Úvodní text dialogu"),
			"help_text" => _("Formátování Markdown"),
		]));

		$this->add_translatable_field("dialog_footer_text", new MarkdownField([
			"label" => _("Text v patičce dialogu"),
			"help_text" => _("Formátování Markdown"),
		]));

		$this->add_field("send_consent_default_command", new BooleanField([
			"label" => _("Odeslat příkaz pro výchozí nastavení souhlasu"),
			"help_text" => _("gtag('consent','default', { ... }"),
			"required" => false,
		]));

		$this->add_field("send_consent_update_command", new BooleanField([
			"label" => _("Odeslat příkaz pro aktualizaci nastavení souhlasu"),
			"help_text" => _("gtag('consent','update', { ... }"),
			"required" => false,
		]));
	}
}
