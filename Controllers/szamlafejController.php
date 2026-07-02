<?php

namespace Controllers;

class szamlafejController extends bizonylatfejController
{

    public function __construct()
    {
        $this->biztipus = 'szamla';
        $this->setPageTitle('Számla');
        $this->setPluralPageTitle('Számlák');
        parent::__construct();
    }

    public function onGetKarb($view, $record, $egyed, $oper, $id, $stornotip)
    {
        $source = $this->params->getStringRequestParam('source', '');
        switch ($oper) {
            case 'inherit':
                $egyed['id'] = \mkw\store::createUID();
                $egyed['parentid'] = $id;
                $kelt = date(\mkw\store::$DateFormat);
                $egyed['keltstr'] = $kelt;
                $egyed['teljesitesstr'] = $kelt;
                $egyed['esedekessegstr'] = \mkw\store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                $egyed['reportfile'] = '';
                $view->setVar('reportfilelist', $this->getRepo()->getReportfileSelectList('', $this->biztipus));
                switch ($source) {
                    case 'megrendeles':
                        $egyed['megjegyzes'] = \mkw\store::translate('Rendelés', $record->getBizonylatnyelv()) . ': ' . $id;
                        $arf = $this->getRepo('Entities\Arfolyam')->getActualArfolyam(
                            $egyed['valutanem'],
                            new \DateTime(\mkw\store::convDate($egyed['teljesitesstr']))
                        );
                        if ($arf) {
                            $egyed['arfolyam'] = $arf->getArfolyam();
                        }
                        $uk = $record->getUzletkoto();
                        if ($uk) {
                            $egyed['uzletkotojutalek'] = $uk->getJutalek();
                        }
                        $uk = $record->getBelsouzletkoto();
                        if ($uk) {
                            $egyed['belsouzletkotojutalek'] = $uk->getJutalek();
                        }
                        break;
                    case 'szallito':
                        $egyed['megjegyzes'] = \mkw\store::translate('Szállítólevél', $record->getBizonylatnyelv()) . ': ' . $id;
                        break;
                }
                $ttk = [];
                $cikl = 1;
                foreach ($egyed['tetelek'] as $tetel) {
                    $tetel['parentid'] = $tetel['id'];
                    $tetel['id'] = \mkw\store::createUID($cikl);
                    $tetel['oper'] = 'inherit';
                    if ($source == 'megrendeles') {
                        $tetel['nettoegysarhuf'] = $tetel['nettoegysar'] * $egyed['arfolyam'];
                        $tetel['bruttoegysarhuf'] = $tetel['bruttoegysar'] * $egyed['arfolyam'];
                        $tetel['nettohuf'] = $tetel['netto'] * $egyed['arfolyam'];
                        $tetel['bruttohuf'] = $tetel['brutto'] * $egyed['arfolyam'];
                        $tetel['afahuf'] = $tetel['afa'] * $egyed['arfolyam'];
                    }
                    $ttk[] = $tetel;
                    $cikl++;
                }
                $egyed['tetelek'] = $ttk;
                break;
            case 'storno':
                $egyed['id'] = \mkw\store::createUID();
                $egyed['parentid'] = $id;
                $egyed['stornotip'] = $stornotip;
                $kelt = date(\mkw\store::$DateFormat);
                $egyed['keltstr'] = $kelt;
//                $egyed['teljesitesstr'] = $kelt;
//                $egyed['esedekessegstr'] = \mkw\store::calcEsedekesseg($kelt, $record->getFizmod(), $record->getPartner());
                switch (\mkw\store::getTheme()) {
                    case 'mkwcansas':
                        $egyed['megjegyzes'] = $id . ' stornó bizonylata. Stornózás oka:';
                        break;
                    default:
                        $egyed['megjegyzes'] = $id . ' stornó bizonylata';
                }
                $ttk = [];
                $cikl = 1;
                foreach ($egyed['tetelek'] as $tetel) {
                    $tetel['parentid'] = $tetel['id'];
                    $tetel['id'] = \mkw\store::createUID($cikl);
                    $tetel['oper'] = 'storno';
                    $ttk[] = $tetel;
                    $cikl++;
                }
                $egyed['tetelek'] = $ttk;
                break;
        }
        return $egyed;
    }

    /**
     * Bolti eladásból számla: a POS a kosarat a session-be tette (a bolti eladás bizonylatot
     * NEM mentjük le), innen egy memóriában felépített, nem mentett forrásbizonylatot adunk át
     * a meglévő inherit prefill-gépezetnek. A számla csak akkor kerül DB-be, ha a kezelő a
     * szerkesztőben ténylegesen menti.
     */
    public function getkarb($tplname = null, $id = null, $oper = null, $quick = null, $stornotip = null)
    {
        if ($this->params->getBoolRequestParam('boltiszamla')) {
            $this->prebuiltRecord = $this->buildBoltiszamlaRecord();
            if (!$this->prebuiltRecord) {
                echo t('Nincs előkészített bolti eladás a számlához.');
                return;
            }
            parent::getkarb($tplname, 0, $this->inheritOperation, $quick, $stornotip);
            return;
        }
        parent::getkarb($tplname, $id, $oper, $quick, $stornotip);
    }

    /**
     * A bolti eladás POS által a session-be tett kosárból épít egy NEM mentett Bizonylatfej-et
     * (számla típus, bolti vevő, alapértelmezett valutanem/raktár) a tételekkel együtt. A
     * denormalizált mezőket (partner, termeknev, ár, ÁFA...) a setterek szinkron kitöltik,
     * ezért a szerkesztő a mentés/flush előtt is helyesen jelenik meg.
     */
    private function buildBoltiszamlaRecord()
    {
        $tetelek = \mkw\store::getAdminSession()->boltiszamlatetelek;
        $fizmodid = \mkw\store::getAdminSession()->boltiszamlafizmod;
        // egyszer használatos: azonnal töröljük, hogy frissítéskor ne épüljön újra
        \mkw\store::getAdminSession()->boltiszamlatetelek = null;
        \mkw\store::getAdminSession()->boltiszamlafizmod = null;
        if (!is_array($tetelek) || !$tetelek) {
            return null;
        }

        $fej = new \Entities\Bizonylatfej();
        $fej->setPersistentData();
        $fej->setBizonylattipus($this->getRepo('Entities\Bizonylattipus')->find($this->biztipus));
        $fej->setKelt('');
        $fej->setTeljesites('');
        $fej->setEsedekesseg('');
        $fej->setHatarido('');
        $fej->setArfolyam(1);

        $partnerid = \mkw\store::getParameter(\mkw\consts::Boltivevo);
        $partner = $partnerid ? $this->getRepo('Entities\Partner')->find($partnerid) : null;
        if ($partner) {
            $fej->setPartner($partner);
        }
        $fizmod = $fizmodid ? $this->getRepo('Entities\Fizmod')->find($fizmodid) : null;
        if ($fizmod) {
            $fej->setFizmod($fizmod);
        }
        $valutanem = $this->getRepo('Entities\Valutanem')->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
        if ($valutanem) {
            $fej->setValutanem($valutanem);
            $fej->setBankszamla($valutanem->getBankszamla());
        }
        $raktar = $this->getRepo('Entities\Raktar')->find(\mkw\store::getParameter(\mkw\consts::Raktar));
        if ($raktar) {
            $fej->setRaktar($raktar);
        }

        foreach ($tetelek as $it) {
            $termek = $this->getRepo('Entities\Termek')->find((int)$it['termekid']);
            if (!$termek) {
                continue;
            }
            $valtozat = !empty($it['valtozatid']) ? $this->getRepo('Entities\TermekValtozat')->find((int)$it['valtozatid']) : null;
            $t = new \Entities\Bizonylattetel();
            $fej->addBizonylattetel($t);
            $t->setPersistentData();
            $t->setTermek($termek);
            $t->setTermekvaltozat($valtozat);
            if (!empty($it['afaid'])) {
                $t->setAfa((int)$it['afaid']);
            }
            $t->setMennyiseg(isset($it['mennyiseg']) ? (float)$it['mennyiseg'] : 1);
            $t->setKedvezmeny(isset($it['kedvezmeny']) ? (float)$it['kedvezmeny'] : 0);
            if (isset($it['enettoegysar'])) {
                $t->setEnettoegysar((float)$it['enettoegysar']);
                $t->setEnettoegysarhuf((float)$it['enettoegysar']);
            }
            if (isset($it['nettoegysar'])) {
                $t->setNettoegysar((float)$it['nettoegysar']);
            }
            if (isset($it['bruttoegysar'])) {
                $t->setBruttoegysar((float)$it['bruttoegysar']);
            }
            $t->setNettoegysarhuf($t->getNettoegysar());
            $t->setBruttoegysarhuf($t->getBruttoegysar());
            $t->calc();
        }
        if (!$fej->getBizonylattetelek() || !count($fej->getBizonylattetelek())) {
            return null;
        }
        $fej->calcOsszesen();
        return $fej;
    }

}
