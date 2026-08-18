<?php

namespace Controllers;

use Entities\Bankbizonylatfej;
use Entities\Bankszamla;
use Entities\Bizonylattipus;
use Entities\Emailtemplate;
use Entities\Fizmod;
use Entities\Idopont;
use Entities\Idopontfoglalas;
use Entities\Jogcim;
use Entities\Partner;
use Entities\Penztar;
use Entities\Penztarbizonylatfej;
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
        $x['emailemlekeztetodatum'] = $t->getEmailemlekeztetodatumStr();
        $x['lemondasdatum'] = $t->getLemondasdatumStr();
        $x['fizetesdatum'] = $t->getFizetesdatumStr();
        $x['fizmodnev'] = $t->getFizmodNev();
        $x['fizetvepenztarnev'] = $t->getFizetvepenztar()?->getNev();
        $x['fizetvebankszamlaszam'] = $t->getFizetvebankszamla()?->getSzamlaszam();
        $x['fizetvepenztarbizonylatszamlink'] =
            $this->getBizonylatUrl($t->getFizetvepenztarbizonylatszam(), Penztarbizonylatfej::class);
        $x['fizetvebankbizonylatszamlink'] =
            $this->getBizonylatUrl($t->getFizetvebankbizonylatszam(), Bankbizonylatfej::class);
        return $x;
    }

    /**
     * A foglaláson a bizonylatszám denormalizált szövegmező, a listanézet URL-jéhez fel kell
     * oldani a bizonylatra. Ha nincs meg (törölt bizonylat), null – a sablon ilyenkor szöveget mutat.
     */
    private function getBizonylatUrl($bizonylatszam, $entityclass)
    {
        if (!$bizonylatszam) {
            return null;
        }
        return $this->getRepo($entityclass)->find($bizonylatszam)?->getListaUrl();
    }

    /**
     * A sorokon megjelenő gombokat a beállított levélsablonok kapcsolják – a lista törzse és a
     * mentés utáni egy sor is ezen keresztül kapja meg őket.
     */
    protected function setVars($view)
    {
        $view->setVar('emlekeztetosablonvan', (bool)\mkw\store::getParameter(\mkw\consts::IdopontfoglalasSablonEmlekezteto));
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

        /** @var \Entities\Idopontfoglalas $meglevo */
        $meglevo = $partner
            ? $this->getRepo()->findOneBy(['idopont' => $idopont, 'partner' => $partner, 'datum' => $datum])
            : null;
        if ($meglevo) {
            return $meglevo->getLemondva()
                ? t('Ennek a partnernek erre az alkalomra lemondott foglalása van, azt kell visszaállítani.')
                : t('Ennek a partnernek erre az alkalomra már van foglalása.');
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

        $this->setVars($view);
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
        $view->setVar('fizmodlist', (new fizmodController())->getSelectList());
        $view->setVar('jogcimlist', (new jogcimController())->getSelectList());
        $view->setVar('penztarlist', (new penztarController())->getSelectList());
        $view->setVar('bankszamlalist', (new bankszamlaController())->getSelectList());
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
     * A lista sorának „Emlékeztető email" gombja.
     */
    public function sendEmlekeztetoEmail()
    {
        /** @var \Entities\Idopontfoglalas $foglalas */
        $foglalas = $this->getRepo()->findWithJoins($this->params->getIntRequestParam('id'));
        if (!$foglalas) {
            echo json_encode(['msg' => at('A foglalás nem található.')]);
            return;
        }
        if (!$this->sendFoglalasEmail($foglalas, \mkw\consts::IdopontfoglalasSablonEmlekezteto, 'idopontfoglalasemlekeztetoemail.html')) {
            echo json_encode(['msg' => at('Emlékeztető levél sablon nem található, vagy a foglalónak nincs emailcíme.')]);
            return;
        }
        $foglalas->setEmailemlekezteto(true);
        $foglalas->setEmailemlekeztetodatum('');
        $this->getEm()->persist($foglalas);
        $this->getEm()->flush();
        echo json_encode(['msg' => at('Az emlékeztető levél kiküldve.')]);
    }

    /**
     * A lista sorának „Lemond" gombja. A lemondott foglalás a helyet is felszabadítja
     * (IdopontfoglalasRepository::getCountForIdopont()).
     */
    public function lemond()
    {
        /** @var \Entities\Idopontfoglalas $foglalas */
        $foglalas = $this->getRepo()->findWithJoins($this->params->getIntRequestParam('id'));
        if (!$foglalas) {
            echo json_encode(['msg' => at('A foglalás nem található.')]);
            return;
        }
        if ($foglalas->getLemondva()) {
            echo json_encode(['msg' => at('A foglalás már le van mondva.')]);
            return;
        }
        $foglalas->setLemondva(true);
        $foglalas->setLemondasdatum($this->params->getStringRequestParam('datum'));
        $foglalas->setLemondasoka($this->params->getStringRequestParam('ok'));
        $this->getEm()->persist($foglalas);
        $this->getEm()->flush();

        $msg = at('A foglalás lemondva.');
        if ($this->sendFoglalasEmail($foglalas, \mkw\consts::IdopontfoglalasSablonLemondas, 'idopontfoglalaslemondasemail.html')) {
            $msg .= ' ' . at('A lemondásról levél ment ki.');
        }
        echo json_encode(['msg' => $msg]);
    }

    /**
     * A „Lemond" visszavonása: a foglalás újra helyet foglal, ezért csak akkor megy át,
     * ha az alkalmon van még szabad hely.
     */
    public function visszaallit()
    {
        /** @var \Entities\Idopontfoglalas $foglalas */
        $foglalas = $this->getRepo()->findWithJoins($this->params->getIntRequestParam('id'));
        if (!$foglalas) {
            echo json_encode(['msg' => at('A foglalás nem található.')]);
            return;
        }
        if (!$foglalas->getLemondva()) {
            echo json_encode(['msg' => at('A foglalás nincs lemondva.')]);
            return;
        }
        if (!$foglalas->getIdopont() || !$foglalas->getIdopont()->isBookable($foglalas->getDatum())) {
            echo json_encode(['msg' => at('Erre az alkalomra már nincs szabad hely.')]);
            return;
        }
        $foglalas->setLemondva(false);
        $foglalas->setLemondasdatum(null);
        $foglalas->setLemondasoka('');
        $this->getEm()->persist($foglalas);
        $this->getEm()->flush();
        echo json_encode(['msg' => at('A foglalás visszaállítva.')]);
    }

    /**
     * A kifizető doboz ezzel tölti fel az összeget: az alkalom ára.
     */
    public function getar()
    {
        /** @var \Entities\Idopontfoglalas $foglalas */
        $foglalas = $this->getRepo()->findWithJoins($this->params->getIntRequestParam('id'));
        if (!$foglalas) {
            echo json_encode(['result' => 'error', 'msg' => at('A foglalás nem található.')]);
            return;
        }
        echo json_encode(['result' => 'ok', 'price' => $foglalas->getIdopont()?->getAr() ?: 0]);
    }

    /**
     * A lista sorának „Kifizet" gombja: a rendezvény jelentkezés kifizetésével azonos módon
     * bank- vagy pénztárbizonylatot képez, és a foglalásra írja a bizonylat azonosítóit.
     */
    public function fizet()
    {
        /** @var \Entities\Idopontfoglalas $foglalas */
        $foglalas = $this->getRepo()->findWithJoins($this->params->getIntRequestParam('id'));
        /** @var \Entities\Fizmod $fizmod */
        $fizmod = $this->getRepo(Fizmod::class)->find($this->params->getIntRequestParam('fizmod'));
        $bankszamla = $this->getRepo(Bankszamla::class)->find($this->params->getIntRequestParam('bankszamla'));
        $penztar = $this->getRepo(Penztar::class)->find($this->params->getIntRequestParam('penztar'));
        $jogcim = $this->getRepo(Jogcim::class)->find($this->params->getIntRequestParam('jogcim'));
        $osszeg = $this->params->getNumRequestParam('osszeg');
        $datum = $this->params->getStringRequestParam('datum');

        if (!$foglalas) {
            echo json_encode(['result' => 'error', 'msg' => at('A foglalás nem található.')]);
            return;
        }
        if ($foglalas->getLemondva()) {
            echo json_encode(['result' => 'error', 'msg' => at('Lemondott foglalás nem fizethető ki.')]);
            return;
        }
        if ($foglalas->getFizetve()) {
            echo json_encode(['result' => 'error', 'msg' => at('A foglalás már ki van fizetve.')]);
            return;
        }
        if (!$fizmod || !$jogcim || !$osszeg || !$datum || (!$bankszamla && !$penztar)) {
            echo json_encode(['result' => 'error', 'msg' => at('Nem adott meg minden adatot!')]);
            return;
        }

        $tipus = $fizmod->getTipus();
        if ($tipus === 'B' && $bankszamla) {
            $biz = new Bankbizonylatfej();
            $bt = new \Entities\Bankbizonylattetel();
            $biz->addBizonylattetel($bt);

            $biz->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('bank'));
            $biz->setMegjegyzes(at('Automatikus bizonylat'));
            $biz->setBankszamla($bankszamla);
            $biz->setPartner($foglalas->getPartner());
            $biz->setKelt('');
            $biz->setValutanem(\mkw\store::getParameter(\mkw\consts::Valutanem));

            $bt->setPartner($foglalas->getPartner());
            $bt->setValutanem(\mkw\store::getParameter(\mkw\consts::Valutanem));
            $bt->setDatum($datum);
            $bt->setHivatkozottdatum($datum);
            $bt->setBrutto($osszeg);
            $bt->setIrany(1);
            $bt->setJogcim($jogcim);

            $this->getEm()->persist($biz);
            $this->getEm()->flush($biz);

            $foglalas->setFizetvebankszamla($bankszamla);
            $foglalas->setFizetvebankbizonylatszam($biz->getId());
            $foglalas->setFizetvebanktetelid($bt->getId());
        } elseif ($tipus === 'P' && $penztar) {
            $biz = new Penztarbizonylatfej();
            $bt = new \Entities\Penztarbizonylattetel();
            $biz->addBizonylattetel($bt);

            $biz->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('penztar'));
            $biz->setMegjegyzes(at('Automatikus bizonylat'));
            $biz->setIrany(1);
            $biz->setKelt('');
            $biz->setPenztar($penztar);
            $biz->setPartner($foglalas->getPartner());

            $bt->setJogcim($jogcim);
            $bt->setBrutto($osszeg);
            $bt->setSzoveg(trim($foglalas->getIdopont()?->getIdoponttemaNev() . ' ' . $foglalas->getDatumStr()));
            $bt->setHivatkozottdatum($datum);

            $this->getEm()->persist($biz);
            $this->getEm()->flush($biz);

            $foglalas->setFizetvepenztar($penztar);
            $foglalas->setFizetvepenztarbizonylatszam($biz->getId());
            $foglalas->setFizetvepenztartetelid($bt->getId());
        } else {
            echo json_encode(['result' => 'error', 'msg' => at('A fizetési módhoz nem a megfelelő pénztárat / bankszámlát adta meg.')]);
            return;
        }

        $foglalas->setFizetesdatum($datum);
        $foglalas->setFizetveosszeghuf($osszeg);
        $foglalas->setFizmod($fizmod);
        $foglalas->setFizetve(true);
        $this->getEm()->persist($foglalas);
        $this->getEm()->flush();

        echo json_encode(['result' => 'ok']);
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
        /** @var \Entities\Idopontfoglalas $meglevo */
        $meglevo = $partner
            ? $this->getRepo()->findOneBy(['idopont' => $idopont, 'partner' => $partner, 'datum' => $datum])
            : null;
        // a meglévő foglalás nem módosítható a publikus űrlapról – a lemondott viszont újra felvehető
        if (!$hiba && $meglevo && !$meglevo->getLemondva()) {
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

        // a korábban lemondott foglalás éled újra: az (időpont, partner, nap) hármason egyedi index van
        $foglalas = $meglevo ?: new Idopontfoglalas();
        $foglalas->setIdopont($idopont);
        $foglalas->setPartner($partner);
        $foglalas->setDatum($datum);
        $foglalas->setOnline($online);
        $foglalas->setFoglalasido(new \DateTime());
        $foglalas->setLemondva(false);
        $foglalas->setLemondasdatum(null);
        $foglalas->setLemondasoka('');
        $this->getEm()->persist($foglalas);
        $this->getEm()->flush();

        if ($this->sendFoglalasEmail($foglalas, \mkw\consts::IdopontfoglalasSablonKoszono, 'idopontfoglalaskoszonoemail.html')) {
            $foglalas->setEmailkoszono(true);
            $this->getEm()->flush();
        }

        $view = $this->createView('idopontfoglalaskoszono.tpl');
        $this->setBookingFormVars($view, $idopont, $datum);
        $view->setVar('partnernev', $partner->getNev());
        $view->setVar('online', $online);
        $view->printTemplateResult();
    }

    /**
     * A foglalás levelei egy helyen: a sablont a paraméter adja, a Smarty változó neve
     * mindig `foglalas`. Sablon vagy emailcím híján nem küldünk semmit.
     *
     * @param \Entities\Idopontfoglalas $foglalas
     * @param string $parameter \mkw\consts::Idopontfoglalas… paraméternév
     * @param string $logfile developer módban ide írjuk a levelet küldés helyett
     *
     * @return bool ment-e ki levél
     */
    private function sendFoglalasEmail($foglalas, $parameter, $logfile)
    {
        if (!$foglalas || !$foglalas->getPartnerEmail()) {
            return false;
        }
        /** @var \Entities\Emailtemplate $emailtpl */
        $emailtpl = $this->getRepo(Emailtemplate::class)->find(\mkw\store::getParameter($parameter));
        if (!$emailtpl) {
            return false;
        }
        $tpldata = $foglalas->toLista();
        $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
        $subject->setVar('foglalas', $tpldata);
        $body = \mkw\store::getTemplateFactory()->createMainView(
            'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
        );
        $body->setVar('foglalas', $tpldata);
        if (\mkw\store::getConfigValue('developer')) {
            \mkw\store::writelog($subject->getTemplateResult(), $logfile);
            \mkw\store::writelog($body->getTemplateResult(), $logfile);
        } else {
            $mailer = \mkw\store::getMailer();
            $mailer->addTo($foglalas->getPartnerEmail());
            $mailer->setSubject($subject->getTemplateResult());
            $mailer->setMessage($body->getTemplateResult());
            $mailer->send();
        }
        return true;
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
