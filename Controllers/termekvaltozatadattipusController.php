<?php

namespace Controllers;

use Entities\TermekValtozatAdatTipus;
use mkw\store;

class termekvaltozatadattipusController extends \mkwhelpers\JQGridController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\TermekValtozatAdatTipus');
        parent::__construct($params);
    }

    protected function loadCells($obj)
    {
        return [$obj->getNev()];
    }

    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        return $obj;
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getRequestParam('nev', null))) {
                $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nev') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
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

    public function uploadToWc()
    {
        if (\mkw\store::isWoocommerceOn()) {
            $wc = \mkw\store::getWcClient();
            $attributes = $this->getRepo()->getAll();
            /** @var TermekValtozatAdatTipus $attr */
            foreach ($attributes as $attr) {
                if ($attr->getId() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                    $attrnev = 'Color';
                } elseif ($attr->getId() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)) {
                    $attrnev = 'Size';
                } else {
                    $attrnev = $attr->getNev();
                }
                if (!$attr->getWcid()) {
                    $data = [
                        'name' => $attrnev,
                        'type' => 'select'
                    ];
                    $result = $wc->post('products/attributes', $data);

                    $attr->setWcid($result->id);
                    $attr->setWcdate('');
                    \mkw\store::getEm()->persist($attr);
                    \mkw\store::getEm()->flush();
                } else {
                    $data = [
                        'name' => $attrnev,
                        'type' => 'select'
                    ];
                    $wc->put('products/attributes/' . $attr->getWcid(), $data);

                    $attr->setWcdate('');
                    \mkw\store::getEm()->persist($attr);
                    \mkw\store::getEm()->flush();
                }
            }
        }
    }

}