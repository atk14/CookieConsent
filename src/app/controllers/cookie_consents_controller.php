<?php
class CookieConsentsController extends ApplicationController {

	function dump(){
		$this->page_title = $this->breadcrumbs[] = _("Nastavení cookies");
		$this->tpl_data["settings"] = CookieConsent::GetSettings($this->request);
	}

	function edit(){
		$this->page_title = $this->breadcrumbs[] = _("Nastavení cookies");

		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$settings = CookieConsent::GetSettings($this->request);
			$settings->clearAcceptAllStatus();
			foreach($d as $k => $v){
				$category_id = preg_replace('/^category_/','',$k);
				$category = CookieConsentCategory::GetInstanceById($category_id);
				if($v){
					$settings->accept($category);
				}else{
					$settings->reject($category);
				}
			}

			$settings->saveSettings($this->response);

			if(!$this->request->xhr()){
				$this->flash->success(_("Nastavení cookies bylo uloženo"));
				$this->_redirect_to("main/index");
			}
		}
	}

	function accept_all(){
		$this->_accept_or_reject_all("acceptAll");
	}

	function reject_all(){
		$this->_accept_or_reject_all("rejectAll");
	}

	function _accept_or_reject_all($method){
		if(!$this->request->post()){
			$this->_redirect_to("edit");
			return;
		}
		$settings = CookieConsent::GetSettings($this->request);
		$settings->$method();
		$settings->saveSettings($this->response);
		if($this->request->xhr()){
			$this->template_name = "close_dialog";
			return;
		}
		$this->flash->success(_("Nastavení cookies bylo uloženo"));
		$this->_redirect_to("main/index");
	}
}
