<?php

namespace Controllers;

use Entities\Idopont;
use Entities\Idopontfoglalas;
use Entities\Partner;
use mkwhelpers\FilterDescriptor;

class idopontfoglalasController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Idopontfoglalas::class);
        $this->setKarbFormTplName('idopontfoglalaskarbform.tpl');
        $this->setKarbTplName('idopontfoglalaskarb.tpl');
        $this->setListBodyRowTplName('idopontfoglalaslista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    /**
     * @param \Entities\Idopontfoglalas $t
     * @param bool $forKarb
     *
     * @return array
     */
    protected function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Idopontfoglalas();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['datum'] = $t->getDatumStr();
        $x['napnev'] = $t->getNapNev();
        $x['foglalasido'] = $t->getFoglalasidoStr();
        $x['partnerid'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnerNev();
        $x['partneremail'] = $t->getPartnerEmail();
        $x['partnertelefon'] = $t->getPartnerTelefon();
        $x['idopontid'] = $t->getIdopontId();
        $idopont = $t->getIdopont();
        $x['idopontkezdet'] = $idopont ? $idopont->getIdotartamStr() : '';
        $x['idoponttemanev'] = $idopont ? $idopont->getIdoponttemaNev() : '';
        $x['idopontdolgozonev'] = $idopont ? $idopont->getDolgozoNev() : '';
        $x['idoponthelyszinnev'] = $idopont ? $idopont->getJogahelyszinNev() : '';
        return $x;
    }

    /**
     * Adminban új foglalás vehető fel; a meglévőn már csak az élő/online jelölés állítható,
     * mert az időpont/nap/partner hármas a foglalás azonossága.
     *
     * @param \Entities\Idopontfoglalas $obj
     * @param string $oper
     *
     * @return \Entities\Idopontfoglalas
     */
    protected function setFields($obj, $oper = null)
    {
        $obj->setOnline($this->params->getBoolRequestParam('online'));
        if ($oper !== $this->addOperation) {
            if (!$obj->getIdopont() || !$obj->getPartner()) {
                throw new \RuntimeException(t('A foglalás hiányos.'));
            }
            return $obj;
        }

        $hiba = $this->validateNewBooking();
        if ($hiba) {
            throw new \RuntimeException($hiba);
        }
        $obj->setIdopont($this->getRepo(Idopont::class)->find($this->params->getIntRequestParam('idopont')));
        $obj->setPartner($this->resolvePartner());
        $obj->setDatum($this->parseDatum($this->params->getStringRequestParam('datum')));
        return $obj;
    }

    /**
     * Az admin felvitel ellenőrzése mentés nélkül – a form ezt kérdezi meg előre (idopontfoglalas.js),
     * a setFields() pedig ugyanezzel utasítja el a nem megengedett beküldést.
     *
     * @return string üres, ha felvihető
     */
    private function validateNewBooking()
    {
        /** @var \Entities\Idopont $idopont */
        $idopont = $this->getRepo(Idopont::class)->find($this->params->getIntRequestParam('idopont'));
        if (!$idopont) {
            return t('Válasszon időpontot!');
        }
        $datum = $this->parseDatum($this->params->getStringRequestParam('datum'));
        if (!$datum || !$idopont->isValidOccurrenceDate($datum)) {
            return t('A megadott nap nem illik a választott időponthoz.');
        }

        $partner = null;
        $partnerkod = $this->params->getIntRequestParam('partner');
        if ($partnerkod > 0) {
            $partner = $this->getRepo(Partner::class)->find($partnerkod);
            if (!$partner) {
                return t('A választott partner nem található.');
            }
        } else {
            $email = trim($this->params->getStringRequestParam('partneremail'));
            $nev = trim($this->params->getStringRequestParam('partnernev'));
            if (!$email || !$nev) {
                return t('Válasszon partnert, vagy adja meg az új partner nevét és emailcímét!');
            }
            $partner = $this->getRepo(Partner::class)->findOneBy(['email' => $email]);
        }

        if ($partner && $this->getRepo()->findOneBy(['idopont' => $idopont, 'partner' => $partner, 'datum' => $datum])) {
            return t('Ennek a partnernek erre az alkalomra már van foglalása.');
        }
        if (!$idopont->isBookable($datum)) {
            return t('Erre az alkalomra már nincs szabad hely.');
        }
        return '';
    }

    /**
     * A form mentés előtti ellenőrzése; a mattkarb beforeSubmit hookja hívja.
     */
    public function check()
    {
        $hiba = $this->validateNewBooking();
        echo json_encode($hiba ? ['result' => 'error', 'msg' => $hiba] : ['result' => 'ok']);
    }

    /**
     * A partner-autocomplete (és a select) -1-gyel jelzi az új felvitelt.
     *
     * @return \Entities\Partner|null
     */
    private function resolvePartner()
    {
        $partnerkod = $this->params->getIntRequestParam('partner');
        if ($partnerkod > 0) {
            return $this->getRepo(Partner::class)->find($partnerkod);
        }
        $email = trim($this->params->getStringRequestParam('partneremail'));
        $nev = trim($this->params->getStringRequestParam('partnernev'));
        if (!$email || !$nev) {
            return null;
        }
        /** @var \Entities\Partner $partner */
        $partner = $this->getRepo(Partner::class)->findOneBy(['email' => $email]);
        if (!$partner) {
            $partner = new Partner();
            $partner->setEmail($email);
            $partner->setVatstatus(2);
        }
        $partner->setNev($nev);
        $telefon = trim($this->params->getStringRequestParam('partnertelefon'));
        if ($telefon !== '') {
            $partner->setTelefon($telefon);
        }
        $this->getEm()->persist($partner);
        return $partner;
    }

    /**
     * Az ős csendben elnyeli a setFields() hibáit; itt viszont az admin valódi bevitelt csinál,
     * ezért a hibaüzenet visszamegy a formra (lásd idopontfoglalas.js onSubmit).
     */
    public function save()
    {
        try {
            $ret = $this->saveData();
            if ($ret['operation'] === $this->delOperation) {
                echo $ret['id'];
            } else {
                echo json_encode($this->getListBodyRow($ret['obj'], $ret['operation']));
            }
        } catch (\Exception $ex) {
            // 409-cel a mattkarb nem navigál vissza a listára, így a form és az adatok megmaradnak
            http_response_code(409);
            echo json_encode(['error' => $ex->getMessage()]);
        }
    }

    public function getlistbody()
    {
        $view = $this->createView('idopontfoglalaslista_tbody.tpl');

        $filter = new FilterDescriptor();
        $f = $this->params->getStringRequestParam('partnernevfilter');
        if ($f) {
            $filter->addFilter('partner.nev', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('partneremailfilter');
        if ($f) {
            $filter->addFilter('partner.email', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('datumtolfilter');
        if ($f) {
            $filter->addFilter('datum', '>=', new \DateTime(\mkw\store::convDate($f)));
        }
        $f = $this->params->getStringRequestParam('datumigfilter');
        if ($f) {
            $filter->addFilter('datum', '<=', new \DateTime(\mkw\store::convDate($f)));
        }
        if (!is_null($this->params->getRequestParam('dolgozofilter', null))) {
            $filter->addFilter('dolgozo.id', '=', $this->params->getIntRequestParam('dolgozofilter'));
        }
        if (!is_null($this->params->getRequestParam('idoponttemafilter', null))) {
            $filter->addFilter('idoponttema.id', '=', $this->params->getIntRequestParam('idoponttemafilter'));
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
        $view = $this->createView('idopontfoglalaslista.tpl');
        $view->setVar('pagetitle', t('Időpont foglalások'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->setVar('dolgozolist', (new dolgozoController())->getSelectList());
        $view->setVar('idoponttemalist', (new idoponttemaController())->getSelectList());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $view = $this->createView($tplname);
        $oper = $this->params->getRequestParam('oper', '');
        $view->setVar('pagetitle', t('Időpont foglalás'));
        $view->setVar('oper', $oper);
        /** @var \Entities\Idopontfoglalas $record */
        $record = $this->getRepo()->findWithJoins($this->params->getRequestParam('id', 0));
        $view->setVar('egyed', $this->loadVars($record, true));
        if ($oper === $this->addOperation) {
            $view->setVar('idopontlist', (new idopontController())->getSelectList());
            if (!\mkw\store::isPartnerAutocomplete()) {
                $view->setVar('partnerlist', (new partnerController())->getSelectList());
            }
        }
        return $view->getTemplateResult();
    }

    /**
     * Publikus foglalási űrlap – a heti megjelenítő "Foglalok" gombja hozza ide.
     */
    public function showBookingForm()
    {
        /** @var \Entities\Idopont $idopont */
        $idopont = $this->getRepo(Idopont::class)->findWithJoins($this->params->getIntRequestParam('id'));
        $datum = $this->getOccurrenceDatum($idopont);

        $view = $this->createView('idopontfoglalasform.tpl');
        $this->setBookingFormVars($view, $idopont, $datum);
        $view->setVar('hiba', $this->checkBooking($idopont, $datum));
        $view->setVar('egyed', $this->getBookingFormFields());
        $view->printTemplateResult();
    }

    public function saveBooking()
    {
        /** @var \Entities\Idopont $idopont */
        $idopont = $this->getRepo(Idopont::class)->findWithJoins($this->params->getIntRequestParam('id'));
        $datum = $this->getOccurrenceDatum($idopont);

        $nev = trim($this->params->getStringRequestParam('nev'));
        $email = trim($this->params->getStringRequestParam('email'));
        $telefon = trim($this->params->getStringRequestParam('telefon'));
        $online = $idopont && $idopont->isOnlinevalaszthato() && $this->params->getStringRequestParam('reszvetel') === 'online';

        $hiba = $this->checkBooking($idopont, $datum);
        if (!$hiba) {
            if (!$nev || !$email || !$telefon) {
                $hiba = t('A név, az emailcím és a telefonszám megadása kötelező.');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $hiba = t('Az emailcím formátuma hibás.');
            }
        }

        /** @var \Entities\Partner $partner */
        $partner = $hiba ? null : $this->getRepo(Partner::class)->findOneBy(['email' => $email]);
        // a meglévő foglalás nem módosítható a publikus űrlapról
        if (!$hiba && $partner && $this->getRepo()->findOneBy(['idopont' => $idopont, 'partner' => $partner, 'datum' => $datum])) {
            $hiba = t('Erre az alkalomra ezzel az emailcímmel már van foglalásod. Ha módosítanál rajta, keress minket!');
        }

        if ($hiba) {
            $view = $this->createView('idopontfoglalasform.tpl');
            $this->setBookingFormVars($view, $idopont, $datum);
            $view->setVar('hiba', $hiba);
            $view->setVar('egyed', $this->getBookingFormFields());
            $view->printTemplateResult();
            return;
        }

        if (!$partner) {
            $partner = new Partner();
            $partner->setEmail($email);
            $partner->setVatstatus(2);
        }
        $partner->setNev($nev);
        $partner->setTelefon($telefon);
        // a cím nem kötelező: üresen hagyva a meglévő partner címét nem írjuk felül
        $irszam = substr($this->params->getStringRequestParam('irszam'), 0, 10);
        if ($irszam !== '') {
            $partner->setIrszam($irszam);
        }
        $varos = $this->params->getStringRequestParam('varos');
        if ($varos !== '') {
            $partner->setVaros($varos);
        }
        $utca = $this->params->getStringRequestParam('utca');
        if ($utca !== '') {
            $partner->setUtca($utca);
        }
        $this->getEm()->persist($partner);

        $foglalas = new Idopontfoglalas();
        $foglalas->setIdopont($idopont);
        $foglalas->setPartner($partner);
        $foglalas->setDatum($datum);
        $foglalas->setOnline($online);
        $this->getEm()->persist($foglalas);
        $this->getEm()->flush();

        $view = $this->createView('idopontfoglalaskoszono.tpl');
        $this->setBookingFormVars($view, $idopont, $datum);
        $view->setVar('partnernev', $partner->getNev());
        $view->setVar('online', $online);
        $view->printTemplateResult();
    }

    /**
     * @return string üres, ha az alkalom foglalható
     */
    private function checkBooking($idopont, $datum)
    {
        if (!$idopont) {
            return t('A választott időpont nem található.');
        }
        if (!$datum) {
            return t('A választott alkalom napja hiányzik vagy nem érvényes.');
        }
        if (!$idopont->isBookable($datum)) {
            return t('Erre az alkalomra sajnos már nincs szabad hely.');
        }
        return '';
    }

    /**
     * A foglalás mindig egy naptári napra szól: az ismétlődő időpontnál a `d` paraméter mondja meg,
     * melyik heti alkalomról van szó, az egyszerinél a saját dátuma.
     *
     * @return \DateTime|null
     */
    private function getOccurrenceDatum($idopont)
    {
        if (!$idopont) {
            return null;
        }
        $datum = $this->parseDatum($this->params->getStringRequestParam('d'));
        if (!$datum && !$idopont->isIsmetlodo() && $idopont->getKezdet()) {
            $datum = new \DateTime($idopont->getKezdet()->format(\mkw\store::$SQLDateFormat));
        }
        return ($datum && $idopont->isValidOccurrenceDate($datum)) ? $datum : null;
    }

    /**
     * @return \DateTime|null a nap 00:00-ra normalizálva
     */
    private function parseDatum($ertek)
    {
        $ertek = trim((string)$ertek);
        if ($ertek === '') {
            return null;
        }
        try {
            $datum = new \DateTime(\mkw\store::convDate($ertek));
        } catch (\Exception $e) {
            return null;
        }
        return new \DateTime($datum->format(\mkw\store::$SQLDateFormat));
    }

    /**
     * A publikus űrlap mezői – hibás beküldés után is a látogató által beírt értékekkel.
     */
    private function getBookingFormFields()
    {
        return [
            'nev' => $this->params->getStringRequestParam('nev'),
            'email' => $this->params->getStringRequestParam('email'),
            'telefon' => $this->params->getStringRequestParam('telefon'),
            'irszam' => $this->params->getStringRequestParam('irszam'),
            'varos' => $this->params->getStringRequestParam('varos'),
            'utca' => $this->params->getStringRequestParam('utca'),
            'reszvetel' => $this->params->getStringRequestParam('reszvetel', 'elo')
        ];
    }

    private function setBookingFormVars($view, $idopont, $datum)
    {
        // a heti nezet szuroje vegigkiseri a foglalast, hogy a "vissza" ugyanoda vigyen
        $tanarkod = $this->params->getIntRequestParam('t', 0);
        $temakod = $this->params->getIntRequestParam('tema', 0);
        $view->setVar('tanarkod', $tanarkod);
        $view->setVar('temakod', $temakod);
        $view->setVar(
            'visszaurl',
            '/idopont/wp?o=0' . ($tanarkod ? '&t=' . $tanarkod : '') . ($temakod ? '&tema=' . $temakod : '')
        );

        /** @var \Entities\Idopont $idopont */
        $view->setVar('idopontid', $idopont ? $idopont->getId() : 0);
        $view->setVar('datum', $datum ? $datum->format(\mkw\store::$DateFormat) : '');
        $view->setVar('datumparam', $datum ? $datum->format(\mkw\store::$SQLDateFormat) : '');
        $view->setVar('napnev', $datum ? \mkw\store::getDayname($datum->format('N')) : '');
        $view->setVar('temanev', $idopont ? $idopont->getIdoponttemaNev() : '');
        $view->setVar('tanar', $idopont ? $idopont->getDolgozoNev() : '');
        $view->setVar('helyszin', $idopont ? $idopont->getJogahelyszinNev() : '');
        $view->setVar('helyszincim', $idopont ? $idopont->getJogahelyszinCim() : '');
        $view->setVar('idotartam', $idopont ? $idopont->getIdotartamStr() : '');
        $view->setVar('ar', $idopont ? $idopont->getAr() : 0);
        $view->setVar('onlinevalaszthato', $idopont && $idopont->isOnlinevalaszthato());
        $view->setVar('szabadhely', ($idopont && $datum) ? max(0, $idopont->getFreePlaces($datum)) : 0);
    }

}
