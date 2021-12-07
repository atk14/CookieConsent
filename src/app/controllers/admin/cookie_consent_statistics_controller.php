<?php
class CookieConsentStatisticsController extends AdminController {

	function index(){
		$this->page_title = _("Statistika klikání na souhlas s použitím cookies");

		if($this->params->getString("format")==="csv"){
			$cmd = "php ".ATK14_DOCUMENT_ROOT."/local_scripts/export_cookie_consent_statistics";
			$content = `$cmd`;
			$this->render_template = false;
			$this->response->setContentType("text/csv");
			$this->response->setHeader("Content-Disposition",sprintf('attachment; filename="cookie_consent_statistics_%s.csv"',$this->request->getHttpHost()));
			$this->response->write($content);
		}
	}
}
