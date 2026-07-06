<?php

namespace Controllers;

use Entities\Bizonylattipus;

class bizonylattipusController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Bizonylattipus::class);
        parent::__construct();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'azonosito' => $sor->getAzonosito(),
                'selected' => ($sor->getId() == $selid)
            ];
        }
        return $res;
    }
}