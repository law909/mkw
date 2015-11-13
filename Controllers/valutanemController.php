<?php
namespace Controllers;

use mkw\store;

class valutanemController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Valutanem');
        parent::__construct($params);
    }

    protected function loadCells($sor) {
        $bank = $sor->getBankszamla();
        return array($sor->getNev(), $sor->getKerekit(),
            $sor->getHivatalos(), $sor->getMincimlet(),
            (isset($bank) ? $bank->getSzamlaszam() : ''));
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setKerekit($this->params->getBoolRequestParam('kerekit'));
        $obj->setHivatalos($this->params->getBoolRequestParam('hivatalos'));
        $obj->setMincimlet($this->params->getIntRequestParam('mincimlet'));
        $bankszla = store::getEm()->getReference('Entities\Bankszamla', $this->params->getIntRequestParam('bankszamla'));
        try {
            $bankszla->getId();
            $obj->setBankszamla($bankszla);
        } catch (\Doctrine\ORM\EntityNotFoundException $e) {
        }
        return $obj;
    }

    public function jsonlist() {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('nev', NULL))) {
                $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nev') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid = null) {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'bankszamla' => $sor->getBankszamlaId()
            );
        }
        return $res;
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'asc'));
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function getRendszerValuta() {
        $p = $this->getRepo()->find(store::getParameter(\mkw\consts::Valutanem));
        return $p;
    }
}