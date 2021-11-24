<?php
class EditForm extends AdminForm {

	function set_up(){
		$this->add_translatable_field("banner_title", new CharField([
			"label" => _("Titulek cookie lišty"),
			"max_length" => 255,
		]));

		$this->add_translatable_field("banner_text", new MarkdownField([
			"label" => _("Text cookie lišty"),
		]));

		$this->add_translatable_field("dialog_title", new CharField([
			"label" => _("Titulek dialogu"),
		]));

		$this->add_translatable_field("dialog_header_text", new MarkdownField([
			"label" => _("Úvodní text dialogu"),
		]));

		$this->add_translatable_field("dialog_footer_text", new MarkdownField([
			"label" => _("Text v patičce dialogu"),
		]));
	}
}
