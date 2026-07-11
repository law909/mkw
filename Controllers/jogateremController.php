<?php

namespace Controllers;

use Entities\Jogaterem;
use mkw\store;

class jogateremController extends \mkwhelpers\JQGridController
{

    public function __construct()
    {
        $this->setEntityName(Jogaterem::class);
        parent::__construct();
    }

    /**
     * @param $sor \Entities\Jogaterem
     *
     * @return array
     */
    protected function loadCells($sor)
    {
        return [$sor->getNev(), $sor->getMaxferohely(), $sor->getInaktiv(), $sor->getOrarendclass()];
    }

    /**
     * @param $obj \Entities\Jogaterem
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getParam('nev', null))) {
                $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nev') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        /** @var \Entities\Jogaterem $sor */
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'maxferohely' => $sor->getMaxferohely()
            ];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        $ret = '<select>';
        $ret .= '<option value="0">Válasszon</option>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
