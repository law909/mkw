<?php

namespace Controllers;

use Entities\Feketelista;
use mkw\store;

class feketelistaController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Feketelista::class);
        $this->setKarbFormTplName('feketelistakarbform.tpl');
        $this->setKarbTplName('feketelistakarb.tpl');
        $this->setListBodyRowTplName('feketelistalista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Feketelista();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['email'] = $t->getEmail();
        $x['ok'] = $t->getOk();
        $x['created'] = $t->getCreatedStr();
        return $x;
    }

    protected function setFields($obj)
    {
        $obj->setEmail($this->params->getStringRequestParam('email'));
        $obj->setOk($this->params->getStringRequestParam('ok'));
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('feketelistalista_tbody.tpl');

        $filter = [];
        if (!is_null($this->params->getRequestParam('emailfilter', null))) {
            $filter['fields'][] = 'email';
            $filter['values'][] = $this->params->getStringRequestParam('emailfilter');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('feketelistalista.tpl');

        $view->setVar('pagetitle', t('Feketelista'));
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('feketelistalista.tpl');

        $view->setVar('pagetitle', t('Feketelista'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Feketelista'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function add()
    {
        $ip = $this->params->getStringRequestParam('ip');
        $email = $this->params->getStringRequestParam('email');
        $ok = $this->params->getStringRequestParam('ok');
        if ($ip) {
            $e = $this->getRepo()->findOneBy(['email' => $ip]);
            if (!$e) {
                $e = new \Entities\Feketelista();
                $e->setEmail($ip);
                $e->setOk($ok);
                $this->getEm()->persist($e);
                $this->getEm()->flush();
            }
        }
        if ($email) {
            $e = $this->getRepo()->findOneBy(['email' => $email]);
            if (!$e) {
                $e = new \Entities\Feketelista();
                $e->setEmail($email);
                $e->setOk($ok);
                $this->getEm()->persist($e);
                $this->getEm()->flush();
            }
        }
    }
}