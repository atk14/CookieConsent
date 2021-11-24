<?php
class CookieConsentsController extends AdminController {

	function index(){
		$this->_redirect_to([
			"action" => "edit",
			"id" => 1
		]);
	}

	function edit(){
		$this->_edit();
	}
}
