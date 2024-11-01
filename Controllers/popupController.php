<?php

namespace Controllers;

use Entities\Popup;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class popupController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(Popup::class);
        $this->setKarbFormTplName('popupkarbform.tpl');
        $this->setKarbTplName('popupkarb.tpl');
        $this->setListBodyRowTplName('popuplista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_popup');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Popup();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['displaytime'] = $t->getDisplaytime();
        $x['backgroundimageurl'] = $t->getBackgroundimageurl();
        $x['overlaybackgroundcolor'] = $t->getOverlaybackgroundcolor();
        $x['overlayopacity'] = $t->getOverlayopacity();
        $x['headertext'] = $t->getHeadertext();
        $x['bodytext'] = $t->getBodytext();
        $x['closebuttontext'] = $t->getClosebuttontext();
        $x['popuporder'] = $t->getPopuporder();
        $x['triggerafterprevious'] = $t->isTriggerafterprevious();
        $x['inaktiv'] = $t->isInaktiv();
        $x['contentwidth'] = $t->getContentwidth();
        $x['contentheight'] = $t->getContentheight();
        $x['closebuttoncolor'] = $t->getClosebuttoncolor();
        $x['closebuttonbackgroundcolor'] = $t->getClosebuttonbackgroundcolor();
        $x['contenttop'] = $t->getContenttop();
        $x['kepurl'] = $t->getBackgroundimageurl();
        if ($forKarb) {
        }
        return $x;
    }

    /**
     * @param \Entities\Popup $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setDisplaytime($this->params->getIntRequestParam('displaytime'));
        $obj->setOverlaybackgroundcolor($this->params->getStringRequestParam('overlaybackgroundcolor'));
        $obj->setOverlayopacity($this->params->getFloatRequestParam('overlayopacity'));
        $obj->setHeadertext($this->params->getStringRequestParam('headertext'));
        $obj->setBodytext($this->params->getOriginalStringRequestParam('bodytext'));
        $obj->setClosebuttontext($this->params->getStringRequestParam('closebuttontext'));
        $obj->setPopuporder($this->params->getIntRequestParam('popuporder'));
        $obj->setTriggerafterprevious($this->params->getBoolRequestParam('triggerafterprevious'));
        $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
        $obj->setContentwidth($this->params->getStringRequestParam('contentwidth'));
        $obj->setContentheight($this->params->getStringRequestParam('contentheight'));
        $obj->setClosebuttoncolor($this->params->getStringRequestParam('closebuttoncolor'));
        $obj->setClosebuttonbackgroundcolor($this->params->getStringRequestParam('closebuttonbackgroundcolor'));
        $obj->setContenttop($this->params->getStringRequestParam('contenttop'));
        $obj->setBackgroundimageurl($this->params->getStringRequestParam('kepurl'));
        return $obj;
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