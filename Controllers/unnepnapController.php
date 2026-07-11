<?php

namespace Controllers;

use Entities\Unnepnap;

class unnepnapController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Unnepnap::class);
        parent::__construct();
    }

    protected function loadCells($sor)
    {
        return [$sor->getDatumString()];
    }

    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if ($this->params->getStringRequestParam('datum', '') != '') {
                $filter->addFilter('datum', '=', $this->params->getStringRequestParam('datum', ''));
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['datum' => 'ASC']);
        $res = [];
        /** @var \Entities\Unnepnap $sor */
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getDatumString(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['datum' => 'asc']);
        $ret = '<select>';
        /** @var \Entities\Unnepnap $sor */
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getDatumString() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}