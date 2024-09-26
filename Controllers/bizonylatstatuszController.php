<?php

namespace Controllers;

use Entities\Bizonylatstatusz;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class bizonylatstatuszController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\Bizonylatstatusz');
        $this->setKarbFormTplName('bizonylatstatuszkarbform.tpl');
        $this->setKarbTplName('bizonylatstatuszkarb.tpl');
        $this->setListBodyRowTplName('bizonylatstatuszlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Bizonylatstatusz();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['sorrend'] = $t->getSorrend();
        $x['csoport'] = $t->getCsoport();
        $x['foglal'] = $t->getFoglal();
        $x['mozgat'] = $t->getMozgat();
        $x['emailtemplatenev'] = $t->getEmailtemplateNev();
        $x['fizmod'] = $t->getFizmodId();
        $x['fizmodnev'] = $t->getFizmodnev();
        $x['szallitasimod'] = $t->getSzallitasimodId();
        $x['szallitasimodnev'] = $t->getSzallitasimodnev();
        $x['nemertekelheto'] = $t->getNemertekelheto();
        $x['wcid'] = $t->getWcid();
        return $x;
    }

    /** @param \Entities\Bizonylatstatusz $obj */
    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setCsoport($this->params->getStringRequestParam('csoport'));
        $obj->setFoglal($this->params->getBoolRequestParam('foglal'));
        $obj->setMozgat($this->params->getBoolRequestParam('mozgat'));
        $obj->setNemertekelheto($this->params->getBoolRequestParam('nemertekelheto'));
        $obj->setWcid($this->params->getStringRequestParam('wcid'));
        $ck = store::getEm()->getRepository('Entities\Emailtemplate')->find($this->params->getIntRequestParam('emailtemplate'));
        if ($ck) {
            $obj->setEmailtemplate($ck);
        }
        $ck = \mkw\store::getEm()->getRepository('Entities\Fizmod')->find($this->params->getIntRequestParam('fizmod'));
        if ($ck) {
            $obj->setFizmod($ck);
        } else {
            $obj->removeFizmod();
        }
        $ck = \mkw\store::getEm()->getRepository('Entities\Szallitasimod')->find($this->params->getIntRequestParam('szallitasimod'));
        if ($ck) {
            $obj->setSzallitasimod($ck);
        } else {
            $obj->removeSzallitasimod();
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('bizonylatstatuszlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('bizonylatstatuszlista.tpl');

        $view->setVar('pagetitle', t('bizonylatstatusz'));
        $view->setVar('controllerscript', 'bizonylatstatuszlista.js');
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('bizonylatstatuszlista.tpl');

        $view->setVar('pagetitle', t('bizonylatstatusz'));
        $view->setVar('controllerscript', 'bizonylatstatuszlista.js');
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('bizonylatstatusz'));
        $view->setVar('controllerscript', 'bizonylatstatuszkarb.js');
        $view->setVar('formaction', '/admin/bizonylatstatusz/save');
        $view->setVar('oper', $oper);
        /** @var \Entities\Bizonylatstatusz $record */
        $record = $this->getRepo()->findWithJoins($id);
        $etpl = new emailtemplateController($this->params);
        $view->setVar('emailtemplatelist', $etpl->getSelectList(($record ? $record->getEmailtemplateId() : 0)));
        $fmc = new fizmodController($this->params);
        $view->setVar('fizmodlist', $fmc->getSelectList($record ? $record->getFizmodId() : 0));
        $fmc = new szallitasimodController($this->params);
        $view->setVar('szallitasimodlist', $fmc->getSelectList($record ? $record->getSzallitasimodId() : 0, true));
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null, $fizmodid = null, $szallmodid = null)
    {
        $filter = new FilterDescriptor();
        if ($szallmodid && $fizmodid) {
            $filter->addSql(
                '((_xx.fizmod=' . $fizmodid . ') AND (_xx.szallitasimod=' . $szallmodid . '))' .
                ' OR ((_xx.fizmod IS NULL) AND (_xx.szallitasimod IS NULL))'
            );
        } else {
            if ($fizmodid) {
                $filter->addSql('((_xx.fizmod=' . $fizmodid . ') OR (_xx.fizmod IS NULL))');
            }
            if ($szallmodid) {
                $filter->addSql('((_xx.szallitasimod=' . $szallmodid . ') OR (_xx.fizmod IS NULL))');
            }
        }
        $rec = $this->getRepo()->getAll($filter, ['sorrend' => 'ASC', 'nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function getCsoportSelectList($sel = null)
    {
        $rec = $this->getRepo()->getExistingCsoportok();
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor['csoport'],
                'caption' => $sor['csoport'],
                'selected' => (!$sel ? false : $sor['csoport'] == $sel)
            ];
        }
        return $res;
    }

}
