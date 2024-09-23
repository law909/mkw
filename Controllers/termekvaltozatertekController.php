<?php

namespace Controllers;

use Entities\TermekValtozat;
use Entities\TermekValtozatAdatTipus;
use Entities\TermekValtozatErtek;
use mkw\store;

class termekvaltozatertekController extends \mkwhelpers\JQGridController
{

    public function __construct($params)
    {
        $this->setEntityName(TermekValtozatErtek::class);
        parent::__construct($params);
    }

    public function fill()
    {
        $tipusertekek = $this->getRepo(TermekValtozat::class)->getTipusErtek();
        foreach ($tipusertekek as $tipusertek) {
            $tve = $this->getRepo(TermekValtozatErtek::class)->findOneBy(['adattipus' => $tipusertek['adattipus'], 'ertek' => $tipusertek['ertek']]);
            if (!$tve && $tipusertek['adattipus'] && $tipusertek['ertek']) {
                $tve = new TermekValtozatErtek();
                $tve->setAdattipus($this->getRepo(TermekValtozatAdatTipus::class)->find($tipusertek['adattipus']));
                $tve->setErtek($tipusertek['ertek']);
                \mkw\store::getEm()->persist($tve);
                \mkw\store::getEm()->flush();
            }
        }
    }

    public function uploadToWc()
    {
        $this->fill();

        $tvatarr = [];
        $tvats = $this->getRepo(TermekValtozatAdatTipus::class)->getAll();
        /** @var TermekValtozatAdatTipus $tvat */
        foreach ($tvats as $tvat) {
            $tvatarr[$tvat->getId()] = $tvat->getWcid();
        }

        $wc = \mkw\store::getWcClient();
        $ertekek = $this->getRepo()->getAll();
        /** @var TermekValtozatErtek $ertek */
        foreach ($ertekek as $ertek) {
            if (!$ertek->getWcid()) {
                $data = [
                    'name' => $ertek->getErtek()
                ];
                $result = $wc->post('products/attributes/' . $tvatarr[$ertek->getAdatTipusId()] . '/terms', $data);

                $ertek->setWcid($result->id);
                $ertek->setWcdate();
                \mkw\store::getEm()->persist($ertek);
                \mkw\store::getEm()->flush();
            }
        }
    }

}