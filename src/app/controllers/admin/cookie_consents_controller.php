<?php
class CookieConsentsController extends AdminController {

	function index(){
		$this->params["id"] = 1;
		$this->_execute_action("edit");
	}

	function edit(){
		$this->_edit([
			"page_title" => _("Nastavení cookies")
		]);
	}
}
