<?php

namespace Controllers;

use mkw\store;

class termekarController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\TermekAr');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $valutanem = new valutanemController($this->params);
        $arsav = new arsavController($this->params);
        $x = [];
        if (!$t) {
            $t = new \Entities\TermekAr();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['termek'] = $t->getTermek();
        $x['valutanem'] = $t->getValutanem();
        $x['arsav'] = $t->getArsav();
        $x['netto'] = $t->getNetto();
        $x['brutto'] = $t->getBrutto();
        if ($forKarb) {
            $x['valutanemlist'] = $valutanem->getSelectList(($t->getValutanem() ? $t->getValutanemId() : 0));
            $x['arsavlist'] = $arsav->getSelectList($t->getArsav()?->getId());
        }
        return $x;
    }

    protected function setFields($obj)
    {
        $ck = store::getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('altermek'));
        if ($ck) {
            $obj->setAlTermek($ck);
        }
        return $obj;
    }

    public function getemptyrow()
    {
        $view = $this->createView('termektermekarkarb.tpl');
        $view->setVar('ar', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getExistingArsavok();
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor['id'],
                'caption' => $sor['azonosito'],
                'selected' => ($sor['azonosito'] == $selid),
                'valutanemid' => $sor['valutanemid'],
                'valutanem' => $sor['valutanem']
            ];
        }
        return $res;
    }

    protected function afterSave($o, $parancs = null)
    {
        switch ($parancs) {
            case $this->delOperation:
                $o->getTermek()?->clearWcdate();
                $o->getTermek()?->uploadToWC();
        }
        parent::afterSave($o, $parancs);
    }

}
