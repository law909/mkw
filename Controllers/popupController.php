<?php

namespace Controllers;

use Entities\Popup;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class popupController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Popup::class);
        $this->setKarbFormTplName('popupkarbform.tpl');
        $this->setKarbTplName('popupkarb.tpl');
        $this->setListBodyRowTplName('popuplista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_popup');
        parent::__construct();
    }

    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Popup();
            $this->getEm()->detach($t);
        }
        return $this->getEntityFieldsArray($t);
    }

    /**
     * @param \Entities\Popup $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function getlistbody()
    {
        $view = $this->createView('popuplista_tbody.tpl');

        $this->initPager($this->getRepo()->getCount([]));
        $egyedek = $this->getRepo()->getAll(
            [],
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'popuplista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('popuplista.tpl');
        $view->setVar('pagetitle', t('Popupok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Popup'));
        $view->setVar('oper', $oper);

        $bp = $this->getRepo()->find($id);

        $view->setVar('egyed', $this->loadVars($bp, true));

        $view->printTemplateResult();
    }

    public function setflag()
    {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag');
        /** @var \Entities\Popup $obj */
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($flag) {
                case 'inaktiv':
                    $obj->setInaktiv($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function getForShow()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('inaktiv', '=', false);
        $popups = $this->getRepo()->getAll($filter, ['popuporder' => 'ASC']);
        $res = [];
        foreach ($popups as $popup) {
            $res[] = $this->loadVars($popup);
        }
        return $res;
    }

    public function regenerateid()
    {
        $id = $this->params->getIntRequestParam('id');
        $popup = $this->getRepo()->find($id);
        if ($popup) {
            $this->getEm()->remove($popup);
            $this->getEm()->flush();
            $this->getEm()->persist($popup);
            $this->getEm()->flush();
        }
    }

    public function getPopupTeszt()
    {
        $popup = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        if ($popup) {
            $view = $this->createView('popupteszt.tpl');
            $view->setVar('popup', $this->loadVars($popup));
            $view->printTemplateResult();
        }
    }
}