<?php
class CookieConsentStatisticsController extends AdminController {

	function index(){
		global $ATK14_GLOBAL;
		$this->page_title = _("Statistika klikání na souhlas s použitím cookies");
		$this->breadcrumbs[] = _("Statistika klikání");

		$days = null;
		if ($this->params->defined("days")) {
			$days = $this->params->getInt("days");
		}
		if($this->params->getString("format")==="csv"){
			$env = strtolower($ATK14_GLOBAL->getEnvironment()); // "production", "development"
			$cmd = "ATK14_ENV=$env php ".ATK14_DOCUMENT_ROOT."/local_scripts/export_cookie_consent_statistics".($days ? " {$days}" : "");
			$content = `$cmd`;
			$this->render_template = false;
			$this->response->setContentType("text/csv");
			$this->response->setHeader("Content-Disposition",sprintf('attachment; filename="cookie_consent_statistics_%s.csv"',$this->request->getHttpHost()));
			$this->response->write($content);
		}
	}
}
