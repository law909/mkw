<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\Idopont;
use Entities\Idopontfoglalas;
use Entities\Idoponttema;
use Entities\Jogahelyszin;
use mkwhelpers\FilterDescriptor;

class idopontController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Idopont::class);
        $this->setKarbFormTplName('idopontkarbform.tpl');
        $this->setKarbTplName('idopontkarb.tpl');
        $this->setListBodyRowTplName('idopontlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    /**
     * @param \Entities\Idopont $t
     * @param bool $forKarb
     *
     * @return array
     */
    protected function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Idopont();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['dolgozonev'] = $t->getDolgozoNev();
        $x['idoponttemanev'] = $t->getIdoponttemaNev();
        $x['jogahelyszinnev'] = $t->getJogahelyszinNev();
        $x['jogahelyszincim'] = $t->getJogahelyszinCim();
        $x['kezdet'] = $t->getKezdetStr();
        $x['veg'] = $t->getVegStr();
        $x['kezdetinput'] = $t->getKezdetInputStr();
        $x['veginput'] = $t->getVegInputStr();
        $x['kezdetido'] = $t->getKezdetidoStr();
        $x['vegido'] = $t->getVegidoStr();
        $x['napnev'] = $t->getNapNev();
        $x['idotartam'] = $t->getIdotartamStr();
        // ismétlődőnél a hely naponként telik be, egyetlen összesített szám félrevezető lenne
        if (!$t->isIsmetlodo() && $t->getId()) {
            $x['foglalasdb'] = $t->getBookedCount();
            $x['szabadhely'] = $t->getFreePlaces();
        } else {
            $x['foglalasdb'] = 0;
            $x['szabadhely'] = $t->getMaxresztvevo();
        }
        return $x;
    }

    /**
     * @param \Entities\Idopont $obj
     *
     * @return \Entities\Idopont
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        if ($obj->getMaxresztvevo() < 1) {
            $obj->setMaxresztvevo(1);
        }
        // a két ág kizárja egymást: a nem használt oldal mezőit ürítjük
        if ($this->params->getBoolRequestParam('ismetlodo')) {
            $obj->setKezdet(null);
            $obj->setVeg(null);
            $obj->setKezdetido($this->params->getStringRequestParam('kezdetido'));
            $obj->setVegido($this->params->getStringRequestParam('vegido'));
        } else {
            $obj->setNap(0);
            $obj->setKezdetido(null);
            $obj->setVegido(null);
        }
        $obj->setDolgozo($this->getRepo(Dolgozo::class)->find($this->params->getIntRequestParam('dolgozo')));
        $obj->setIdoponttema($this->getRepo(Idoponttema::class)->find($this->params->getIntRequestParam('idoponttema')));
        $obj->setJogahelyszin($this->getRepo(Jogahelyszin::class)->find($this->params->getIntRequestParam('jogahelyszin')));
        return $obj;
    }

    protected function beforeRemove($o)
    {
        /** @var \Entities\Idopont $o */
        foreach ($o->getFoglalasok() as $foglalas) {
            $this->getEm()->remove($foglalas);
        }
    }

    public function getlistbody()
    {
        $view = $this->createView('idopontlista_tbody.tpl');

        $filter = new FilterDescriptor();
        // a dátumszűrő csak az egyszeri időpontokra értelmezhető, az ismétlődők mindig bent maradnak
        $f = $this->params->getStringRequestParam('datumtolfilter');
        if ($f) {
            $filter->addFilter('kezdet', '>=', new \DateTime(\mkw\store::convDate($f) . ' 00:00:00'));
        }
        $f = $this->params->getStringRequestParam('datumigfilter');
        if ($f) {
            $filter->addFilter('kezdet', '<=', new \DateTime(\mkw\store::convDate($f) . ' 23:59:59'));
        }
        $f = $this->params->getNumRequestParam('ismetlodofilter', 9);
        if ($f != 9) {
            $filter->addFilter('ismetlodo', '=', $f);
        }
        if (!is_null($this->params->getRequestParam('dolgozofilter', null))) {
            $filter->addFilter('dolgozo', '=', $this->params->getIntRequestParam('dolgozofilter'));
        }
        if (!is_null($this->params->getRequestParam('idoponttemafilter', null))) {
            $filter->addFilter('idoponttema', '=', $this->params->getIntRequestParam('idoponttemafilter'));
        }
        if (!is_null($this->params->getRequestParam('jogahelyszinfilter', null))) {
            $filter->addFilter('jogahelyszin', '=', $this->params->getIntRequestParam('jogahelyszinfilter'));
        }
        $f = $this->params->getNumRequestParam('inaktivfilter', 9);
        if ($f != 9) {
            $filter->addFilter('inaktiv', '=', $f);
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('idopontlista.tpl');
        $view->setVar('pagetitle', t('Időpontok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->setVar('dolgozolist', (new dolgozoController())->getSelectList());
        $view->setVar('idoponttemalist', (new idoponttemaController())->getSelectList());
        $view->setVar('jogahelyszinlist', (new jogahelyszinController())->getSelectList());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Időpont'));
        $view->setVar('oper', $this->params->getRequestParam('oper', ''));

        /** @var \Entities\Idopont $idopont */
        $idopont = $this->getRepo()->findWithJoins($this->params->getRequestParam('id', 0));
        $view->setVar('egyed', $this->loadVars($idopont, true));
        $view->setVar(
            'dolgozolist',
            $this->keepSelected(
                (new dolgozoController())->getSelectList($idopont ? $idopont->getDolgozoId() : 0),
                $idopont ? $idopont->getDolgozoId() : 0,
                $idopont ? $idopont->getDolgozoNev() : ''
            )
        );
        $view->setVar(
            'idoponttemalist',
            $this->keepSelected(
                (new idoponttemaController())->getSelectList($idopont ? $idopont->getIdoponttemaId() : 0),
                $idopont ? $idopont->getIdoponttemaId() : 0,
                $idopont ? $idopont->getIdoponttemaNev() : ''
            )
        );
        $view->setVar(
            'jogahelyszinlist',
            $this->keepSelected(
                (new jogahelyszinController())->getSelectList($idopont ? $idopont->getJogahelyszinId() : 0),
                $idopont ? $idopont->getJogahelyszinId() : 0,
                $idopont ? $idopont->getJogahelyszinNev() : ''
            )
        );
        $view->setVar('naplist', \mkw\store::getDaynameSelectList($idopont ? $idopont->getNap() : 0));

        return $view->getTemplateResult();
    }

    /**
     * A legördülők csak az aktív törzsadatokat kínálják, de a rekordon már beállított tétel akkor is
     * benne marad, ha időközben inaktívvá tették – különben a régi időpont nem lenne menthető.
     */
    private function keepSelected(array $lista, $id, $nev)
    {
        if (!$id) {
            return $lista;
        }
        foreach ($lista as $sor) {
            if ($sor['id'] == $id) {
                return $lista;
            }
        }
        $lista[] = ['id' => $id, 'caption' => trim($nev . ' (' . t('inaktív') . ')'), 'selected' => true];
        return $lista;
    }

    /**
     * Az admin foglalás-felvitel időpont választója; a nap/dátum ellenőrzéséhez az ismétlődés
     * adatait is visszaadja.
     */
    public function getSelectList($selid = null, $csakaktiv = true)
    {
        $filter = new FilterDescriptor();
        if ($csakaktiv) {
            $filter->addFilter('inaktiv', '=', false);
        }
        $rec = $this->getRepo()->getWithJoins($filter, ['ismetlodo' => 'ASC', 'kezdet' => 'ASC', 'nap' => 'ASC', 'kezdetido' => 'ASC']);
        $res = [];
        /** @var \Entities\Idopont $sor */
        foreach ($rec as $sor) {
            $mikor = $sor->isIsmetlodo()
                ? t('minden') . ' ' . $sor->getNapNev() . ' ' . $sor->getIdotartamStr()
                : $sor->getDatumStr() . ' ' . $sor->getIdotartamStr();
            $res[] = [
                'id' => $sor->getId(),
                'caption' => trim($mikor . ' – ' . $sor->getIdoponttemaNev() . ' (' . $sor->getDolgozoNev() . ')'),
                'selected' => ($sor->getId() == $selid),
                'ismetlodo' => $sor->isIsmetlodo() ? 1 : 0,
                'nap' => $sor->getNap(),
                'datum' => $sor->getDatumStr()
            ];
        }
        return $res;
    }

    public function setflag()
    {
        $kibe = $this->params->getBoolRequestParam('kibe');
        /** @var \Entities\Idopont $obj */
        $obj = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        if ($obj) {
            switch ($this->params->getStringRequestParam('flag')) {
                case 'inaktiv':
                    $obj->setInaktiv($kibe);
                    break;
                case 'onlinevalaszthato':
                    $obj->setOnlinevalaszthato($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    /**
     * Publikus, wordpress iframe-be szánt heti nézet – az órarendé (orarendController::exportToWordpress) mintájára.
     * Az egyszeri időpontok a saját napjukon, az ismétlődők minden héten a `nap` mezőjük szerint jelennek meg.
     * URL paraméterek: o = hetek eltolása, t = tanár (dolgozo) id, tema = időpont téma id.
     */
    public function exportToWordpress()
    {
        $offset = $this->params->getIntRequestParam('o', 0);
        $tanarkod = $this->params->getIntRequestParam('t', 0);
        $temakod = $this->params->getIntRequestParam('tema', 0);

        $startdatum = \mkw\store::startOfWeek();
        if ($offset < 0) {
            $startdatum->sub(new \DateInterval('P' . abs($offset) . 'W'));
        } else {
            $startdatum->add(new \DateInterval('P' . $offset . 'W'));
        }
        $vegdatum = \mkw\store::endOfWeek(clone $startdatum);

        $rec = array_merge(
            $this->getIdopontokForWeek(false, $startdatum, $vegdatum, $tanarkod, $temakod),
            $this->getIdopontokForWeek(true, $startdatum, $vegdatum, $tanarkod, $temakod)
        );

        $alkalmak = [];
        $ids = [];
        $datumok = [];
        /** @var \Entities\Idopont $item */
        foreach ($rec as $item) {
            $datum = $item->getOccurrenceDate($startdatum);
            if (!$datum) {
                continue;
            }
            $alkalmak[] = ['idopont' => $item, 'datum' => $datum];
            $ids[$item->getId()] = $item->getId();
            $datumok[$datum->format(\mkw\store::$SQLDateFormat)] = $datum;
        }
        $foglalasdbk = $this->getRepo(Idopontfoglalas::class)->getCountsForIdopontok(array_values($ids), array_values($datumok));

        $idopontok = [];
        foreach ($alkalmak as $alkalom) {
            /** @var \Entities\Idopont $item */
            $item = $alkalom['idopont'];
            $datum = $alkalom['datum'];
            $datumstr = $datum->format(\mkw\store::$SQLDateFormat);
            $nap = $datum->format('N');
            $foglalasdb = $foglalasdbk[$item->getId()][$datumstr] ?? 0;
            $szabadhely = max(0, $item->getMaxresztvevo() - $foglalasdb);
            if (!array_key_exists($nap, $idopontok)) {
                $idopontok[$nap] = [
                    'napnev' => \mkw\store::getDayname($nap),
                    'napdatum' => $datum->format(\mkw\store::$DateFormat),
                    'idopontok' => []
                ];
            }
            $idopontok[$nap]['idopontok'][] = [
                'id' => $item->getId(),
                'datum' => $datumstr,
                'kezdet' => $item->getStartTimeStr(),
                'veg' => $item->getEndTimeStr(),
                'ismetlodo' => $item->isIsmetlodo(),
                'temanev' => $item->getIdoponttemaNev(),
                'temaurl' => $item->getIdoponttemaUrl(),
                'tanar' => $item->getDolgozoNev(),
                'tanarurl' => $item->getDolgozoUrl(),
                'helyszin' => $item->getJogahelyszinNev(),
                'helyszincim' => $item->getJogahelyszinCim(),
                'ar' => $item->getAr(),
                'onlinevalaszthato' => $item->isOnlinevalaszthato(),
                'delelott' => $item->isDelelottKezdodik(),
                'maxresztvevo' => $item->getMaxresztvevo(),
                'foglalasdb' => $foglalasdb,
                'szabadhely' => $szabadhely,
                'megvanhely' => $szabadhely > 0
            ];
        }
        ksort($idopontok);
        foreach ($idopontok as $nap => $adat) {
            usort($idopontok[$nap]['idopontok'], function ($a, $b) {
                return strcmp($a['kezdet'], $b['kezdet']);
            });
        }

        $view = $this->createView('idopontwordpress.tpl');
        $view->setVar('idopontok', $idopontok);
        $view->setVar('prevoffset', $offset - 1);
        $view->setVar('nextoffset', $offset + 1);
        $view->setVar('tanarkod', $tanarkod);
        $view->setVar('temakod', $temakod);
        $view->setVar('szuroparam', ($tanarkod ? '&t=' . $tanarkod : '') . ($temakod ? '&tema=' . $temakod : ''));
        $view->printTemplateResult();
    }

    /**
     * Az ismétlődők minden hétre érvényesek, ezért csak az egyszerieket szűrjük a hét dátumaira.
     *
     * @return array
     */
    private function getIdopontokForWeek($ismetlodo, $startdatum, $vegdatum, $tanarkod, $temakod)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('inaktiv', '=', false);
        $filter->addFilter('ismetlodo', '=', $ismetlodo);
        if (!$ismetlodo) {
            $filter->addFilter('kezdet', '>=', new \DateTime($startdatum->format(\mkw\store::$SQLDateFormat) . ' 00:00:00'));
            $filter->addFilter('kezdet', '<=', new \DateTime($vegdatum->format(\mkw\store::$SQLDateFormat) . ' 23:59:59'));
        }
        if ($tanarkod) {
            $filter->addFilter('dolgozo', '=', $tanarkod);
        }
        if ($temakod) {
            $filter->addFilter('idoponttema', '=', $temakod);
        }
        return $this->getRepo()->getWithJoins($filter, $ismetlodo ? ['nap' => 'ASC', 'kezdetido' => 'ASC'] : ['kezdet' => 'ASC']);
    }

}
