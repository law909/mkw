<?php

namespace Controllers;

use Entities\Partner;
use Entities\Termek;
use Entities\TermekErtekeles;
use Entities\TermekValtozat,
	Entities\TermekRecept;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class termekertekelesController extends \mkwhelpers\MattableController {

    public function __construct($params) {
		$this->setEntityName(TermekErtekeles::class);
		$this->setKarbFormTplName('termekertekeleskarbform.tpl');
		$this->setKarbTplName('termekertekeleskarb.tpl');
		$this->setListBodyRowTplName('termekertekeleslista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
		parent::__construct($params);
	}

	protected function loadVars($t, $forKarb = false) {
		$x = array();
		if (!$t) {
			$t = new \Entities\TermekErtekeles();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
		$x['szoveg'] = $t->getSzoveg();
		$x['elony'] = $t->getElony();
		$x['hatrany'] = $t->getHatrany();
        $x['valasz'] = $t->getValasz();
        $x['ertekeles'] = $t->getErtekeles();
        $x['created'] = $t->getCreatedStr();
        $x['partner'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnerNev();
        $x['termek'] = $t->getTermekId();
        $x['termeknev'] = $t->getTermekNev();
		return $x;
	}

    /**
     * @param \Entities\TermekErtekeles $obj
     * @return mixed
     */
	protected function setFields($obj) {
		$ck = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner'));
		if ($ck) {
			$obj->setPartner($ck);
		}
        else {
            $obj->setPartner(null);
        }
        $ck = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termek'));
        if ($ck) {
            $obj->setTermek($ck);
        }
        else {
            $obj->setTermek(null);
        }
		$obj->setSzoveg($this->params->getStringRequestParam('szoveg'));
        $obj->setElony($this->params->getStringRequestParam('elony'));
        $obj->setHatrany($this->params->getStringRequestParam('hatrany'));
        $obj->setValasz($this->params->getStringRequestParam('valasz'));
        $obj->setErtekeles($this->params->getIntRequestParam('ertekeles'));

		return $obj;
	}

	public function getlistbody() {
		$view = $this->createView('termekertekeleslista_tbody.tpl');

		$filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('partnerfilter', null))) {
            $filter->addFilter('partner' , '=', $this->params->getIntRequestParam('partnerfilter'));
        }

        $this->initPager($this->getRepo()->getCount($filter));
        $egyedek = $this->getRepo()->getWithJoins(
            $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'termekertekeleslista', $view));
	}

	public function viewlist() {
		$view = $this->createView('termekertekeleslista.tpl');
		$view->setVar('pagetitle', t('Termék értékelések'));
		$view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList(0));
		$tcs = new termekController($this->params);
		$view->setVar('termeklist', $tcs->getSelectList());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id = $this->params->getRequestParam('id', 0);
		$oper = $this->params->getRequestParam('oper', '');
		$view = $this->createView($tplname);
		$view->setVar('pagetitle', t('Termék értékelés'));
		$view->setVar('oper', $oper);

        /** @var TermekErtekeles $te */
		$te = $this->getRepo()->findWithJoins($id);
		$view->setVar('egyed', $this->loadVars($te, true));

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList(($te ? $te->getPartnerId() : 0)));

        $csoport = new termekController($this->params);
        $view->setVar('termeklist', $csoport->getSelectList(($te ? $te->getTermekId() : 0)));

        $view->printTemplateResult();
	}

}