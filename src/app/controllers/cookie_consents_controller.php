<?php
class CookieConsentsController extends ApplicationController {

	function dump(){
		$this->page_title = $this->breadcrumbs[] = _("Nastavení cookies");
		$this->tpl_data["settings"] = CookieConsent::GetSettings($this->request);
	}

	function edit(){
		$cookie_consent = $this->tpl_data["cookie_consent"] = CookieConsent::GetInstance();
		$this->page_title = $cookie_consent->getDialogTitle();
		if(isset($this->breadcrumbs)){
			$this->breadcrumbs[] = $cookie_consent->getDialogTitle();
		}

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
			$this->_log_saving("saved_in_dialog",$settings);

			if(!$this->request->xhr()){
				$this->flash->success(_("Nastavení cookies bylo uloženo"));
				$this->_redirect_to("delete_rejected_cookies");
			}
		}
	}

	function delete_rejected_cookies(){
		$settings = CookieConsent::GetSettings($this->request);
		$settings->deleteRejectedCookies($this->response);

		$this->_redirect_to("main/index");
	}

	function accept_all(){
		$this->_accept_or_reject_all("acceptAll",[
			"action_taken" => $this->params->defined("dialog") ? "accept_all_in_dialog" : "accept_all",
			"flash_message" => _("Používání všech cookies bylo přijato"),
		]);
	}

	function reject_all(){
		$this->_accept_or_reject_all("rejectAll",[
			"action_taken" => $this->params->defined("dialog") ? "reject_all_in_dialog" : "reject_all",
			"flash_message" => _("Používání cookies bylo zamítnuto"),
		]);
	}

	function _accept_or_reject_all($method,$options = []){
		$options += [
			"accept_all" => "???",
			"flash_message" => _("Nastavení cookies bylo uloženo"),
		];
		if(!$this->request->post()){
			$this->_redirect_to("edit");
			return;
		}
		$settings = CookieConsent::GetSettings($this->request);
		$settings->$method();
		$settings->saveSettings($this->response);
		$this->_log_saving($options["action_taken"],$settings);
		if($this->request->xhr()){
			$this->template_name = "close_dialog";
			return;
		}
		$this->flash->success($options["flash_message"]);
		$this->_redirect_to("main/index");
	}

	function _log_saving($action_taken,$settings){
		$data = [
			"action_taken" => $action_taken,
			"action_taken_at" => date("Y-m-d H:i:s"),
			"remote_addr" => $this->request->getRemoteAddr(),
			"remote_hostname" => $this->request->getRemoteHostname(),
			"settings" => $settings->toArray(),
		];
		$json = json_encode($data);
		$this->logger->info("cookie_consent_saved: $json");
	}
}
