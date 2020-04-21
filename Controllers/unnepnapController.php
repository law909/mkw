<?php
namespace Controllers;

class unnepnapController extends \mkwhelpers\JQGridController {

    public function __construct($params) {
        $this->setEntityName('Entities\Unnepnap');
        parent::__construct($params);
    }

    protected function loadCells($sor) {
        return array($sor->getDatumString());
    }

    protected function setFields($obj) {
        $obj->setDatum(new \DateTime(str_replace('.', '-', $this->params->getStringRequestParam('datum'))));
        return $obj;
    }

    public function jsonlist() {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if ($this->params->getStringRequestParam('datum', '') != '') {
                $filter->addFilter('datum', '=', $this->params->getStringRequestParam('datum', ''));
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid) {
        $rec = $this->getRepo()->getAll(array(), array('datum' => 'ASC'));
        $res = array();
        /** @var \Entities\Unnepnap $sor */
        foreach ($rec as $sor) {
            $res[] = array('id' => $sor->getId(), 'caption' => $sor->getDatumString(), 'selected' => ($sor->getId() == $selid));
        }
        return $res;
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('datum' => 'asc'));
        $ret = '<select>';
        /** @var \Entities\Unnepnap $sor */
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getDatumString() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}