<?php

namespace Controllers;

use Entities\Elallas;
use mkw\store;
use Traits\GetsFieldValue;

class elallasController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Elallas::class);
        $this->setKarbFormTplName('elallaskarbform.tpl');
        $this->setKarbTplName('elallaskarb.tpl');
        $this->setListBodyRowTplName('elallaslista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        if (!$t) {
            $t = new \Entities\Elallas();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['created'] = $t->getCreatedStr();
        $x['lastmod'] = $t->getLastmodStr();
        return $x;
    }

    protected function loadNaplok($t)
    {
        $naploCtrl = new elallasnaploController();
        $x = [];
        if ($t) {
            foreach ($t->getNaplok() as $n) {
                $x[] = $naploCtrl->loadVars($n);
            }
        }
        return $x;
    }

    protected function setFields($obj)
    {
        $this->setEntityFieldsFromRequest($obj);
        $naploids = $this->params->getArrayRequestParam('naploid');
        foreach ($naploids as $naploid) {
            $kuldo = $this->params->getStringRequestParam('naplokuldo_' . $naploid, '');
            $fogado = $this->params->getStringRequestParam('naplofogado_' . $naploid, '');
            $szoveg = $this->params->getStringRequestParam('naploszoveg_' . $naploid, '');
            $esemenyido = $this->params->getStringRequestParam('naploesemenyido_' . $naploid, '');
            $irany = $this->params->getIntRequestParam('naploirany_' . $naploid, 1);
            if ($kuldo === '' && $fogado === '' && $szoveg === '' && $esemenyido === '') {
                continue;
            }
            $oper = $this->params->getStringRequestParam('naplooper_' . $naploid);
            if ($oper == 'add') {
                $naplo = new \Entities\Elallasnaplo();
                $obj->addNaplo($naplo);
                $naplo->setKuldo($kuldo);
                $naplo->setFogado($fogado);
                $naplo->setSzoveg($szoveg);
                $naplo->setEsemenyido($esemenyido);
                $naplo->setIrany($irany);
                $this->getEm()->persist($naplo);
            } elseif ($oper == 'edit') {
                /** @var \Entities\Elallasnaplo $naplo */
                $naplo = \mkw\store::getEm()->getRepository(\Entities\Elallasnaplo::class)->find($naploid);
                if ($naplo) {
                    $naplo->setKuldo($kuldo);
                    $naplo->setFogado($fogado);
                    $naplo->setSzoveg($szoveg);
                    $naplo->setEsemenyido($esemenyido);
                    $naplo->setIrany($irany);
                    $this->getEm()->persist($naplo);
                }
            }
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('elallaslista_tbody.tpl');

        $filter = [];
        if (!is_null($this->params->getRequestParam('idfilter', null))) {
            $filter['fields'][] = ['nev', 'email', 'bizonylat'];
            $filter['values'][] = $this->params->getStringRequestParam('idfilter');
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
        $view = $this->createView('elallaslista.tpl');

        $view->setVar('pagetitle', t('Elállás a szerződéstől'));
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('elallaslista.tpl');

        $view->setVar('pagetitle', t('Elállás a szerződéstől'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Elállás a szerződéstől'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record));
        $view->setVar('naplok', $this->loadNaplok($record));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['created' => 'DESC']);
        $res = [];
        foreach ($rec as $sor) {
            $caption = trim((string)$sor->getNev());
            if ($sor->getBizonylat()) {
                $caption = ($caption !== '' ? $caption . ' - ' : '') . $sor->getBizonylat();
            }
            if ($caption === '') {
                $caption = '#' . $sor->getId();
            }
            $res[] = ['id' => $sor->getId(), 'caption' => $caption, 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

}
