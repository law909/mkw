<?php

namespace Controllers;

use Entities\Termekcimkekat;

class termekcimkekatController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Termekcimkekat::class);
        $this->setKarbFormTplName('termekcimkekatkarbform.tpl');
        $this->setKarbTplName('termekcimkekatkarb.tpl');
        $this->setListBodyRowTplName('termekcimkekatlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Termekcimkekat();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        return $x;
    }

    /**
     * @param \Entities\Termekcimkekat $obj
     *
     * @return \Entities\Termekcimkekat
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        $obj->setLathato($this->params->getBoolRequestParam('lathato', false));
        $obj->setTermeklaponlathato($this->params->getBoolRequestParam('termeklaponlathato', false));
        $obj->setTermekszurobenlathato($this->params->getBoolRequestParam('termekszurobenlathato', false));
        $obj->setTermeklistabanlathato($this->params->getBoolRequestParam('termeklistabanlathato', false));
        $obj->setTermekakciodobozbanlathato($this->params->getBoolRequestParam('termekakciodobozbanlathato', false));

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('termekcimkekatlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
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

    public function viewlist()
    {
        $view = $this->createView('termekcimkekatlista.tpl');

        $view->setVar('pagetitle', t('Termékcímke csoportok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Termékcímke csoport'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('admintermekcimkekatsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function getWithCimkek($selected)
    {
        // TODO sok cimke eseten ez meg lehet lassu, bar gyorsitottam
        $cimkekat = $this->getRepo()->getScalarWithJoins([], ['_xx.nev' => 'asc', '_xx.sorrend' => 'asc', 'c.nev' => 'asc']);
        $res = [];
        foreach ($cimkekat as $sor) {
            if (!array_key_exists($sor['id'], $res)) {
                $res[$sor['id']] = [
                    'id' => $sor['id'],
                    'caption' => $sor['nev'],
                    'sanitizedcaption' => str_replace('.', '', $sor['slug']),
                    'cimkek' => [
                        [
                            'id' => $sor['cid'],
                            'caption' => $sor['cnev'],
                            'selected' => $selected && (in_array($sor['cid'], $selected))
                        ]
                    ]
                ];
            } else {
                $res[$sor['id']]['cimkek'][] = [
                    'id' => $sor['cid'],
                    'caption' => $sor['cnev'],
                    'selected' => $selected && (in_array($sor['cid'], $selected))
                ];
            }
        }
        return $res;
    }

    private function termekidcount($mibol, $miben)
    {
        $cnt = 0;
        if (count($miben) == 0) {
            $cnt = count($mibol);
        } else {
            foreach ($mibol as $egy) {
                if (in_array($egy->getId(), $miben)) {
                    $cnt++;
                }
            }
        }
        return $cnt;
    }

    public function getForTermekSzuro($termekids, $selectedids)
    {
        $sid = [];
        foreach ($selectedids as $sids) {
            foreach ($sids as $ertek) {
                $sid[] = $ertek;
            }
        }
        $rec = $this->getRepo()->getForTermekSzuro($termekids, $sid);
        $res = [];
        foreach ($rec as $sor) {
            $crec = $sor->getCimkek();
            $cimkek = [];
            foreach ($crec as $csor) {
                $cimkek[] = [
                    'id' => $csor->getId(),
                    'caption' => $csor->getNev(),
                    'selected' => in_array($csor->getId(), $sid)
                    //'termekdb'=>$this->termekidcount($csor->getTermekek(),$szurttermekids)
                ];
            }
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'cimkek' => $cimkek
            ];
        }
        unset($sid);
        return $res;
    }
}
