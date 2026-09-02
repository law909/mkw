<?php

namespace Controllers;

use Entities\Bankbizonylatfej;
use Entities\Bankbizonylattetel;
use Entities\Bankszamla;
use Entities\Bizonylatfej;
use Entities\Bizonylattipus;
use Entities\Emailtemplate;
use Entities\Fizmod;
use Entities\Idopont;
use Entities\Idopontfoglalas;
use Entities\Jogcim;
use Entities\Partner;
use Entities\Penztar;
use Entities\Penztarbizonylatfej;
use Entities\Penztarbizonylattetel;
use Entities\Termek;
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
        $x['idopontnev'] = $idopont ? $idopont->getNev() : '';
        $x['idopontteljesnev'] = $idopont ? $idopont->getTeljesNev() : '';
        $x['idopontdatum'] = $idopont ? $idopont->getDatumStr() : '';
        $x['idoponttipus'] = $idopont ? $idopont->getTipus() : '';
        $x['partnercim'] = $t->getPartner()?->getCim();
        $x['partnervezeteknev'] = $t->getPartner()?->getVezeteknev();
        $x['partnerkeresztnev'] = $t->getPartner()?->getKeresztnev();
        $x['partnerirszam'] = $t->getPartner()?->getIrszam();
        $x['partnervaros'] = $t->getPartner()?->getVaros();
        $x['partnerutca'] = $t->getPartner()?->getUtca();
        $x['partnerhazszam'] = $t->getPartner()?->getHazszam();
        $x['emailemlekeztetodatum'] = $t->getEmailemlekeztetodatumStr();
        $x['emaildijbekerodatum'] = $t->getEmaildijbekerodatumStr();
        $x['visszautalasdatum'] = $t->getVisszautalasdatumStr();
        $x['visszautalaspenztarnev'] = $t->getVisszautalaspenztar()?->getNev();
        $x['visszautalasbankszamlaszam'] = $t->getVisszautalasbankszamla()?->getSzamlaszam();
        $x['visszautalasfizmodnev'] = $t->getVisszautalasfizmod()?->getNev();
        $x['visszautalaspenztarbizonylatszamlink'] =
            $this->getBizonylatUrl($t->getVisszautalaspenztarbizonylatszam(), Penztarbizonylatfej::class);
        $x['visszautalasbankbizonylatszamlink'] =
            $this->getBizonylatUrl($t->getVisszautalasbankbizonylatszam(), Bankbizonylatfej::class);
        $x['lemondasdatum'] = $t->getLemondasdatumStr();
        $x['fizetesdatum'] = $t->getFizetesdatumStr();
        $x['fizmodnev'] = $t->getFizmodNev();
        $x['fizetvepenztarnev'] = $t->getFizetvepenztar()?->getNev();
        $x['fizetvebankszamlaszam'] = $t->getFizetvebankszamla()?->getSzamlaszam();
        $x['fizetvepenztarbizonylatszamlink'] =
            $this->getBizonylatUrl($t->getFizetvepenztarbizonylatszam(), Penztarbizonylatfej::class);
        $x['fizetvebankbizonylatszamlink'] =
            $this->getBizonylatUrl($t->getFizetvebankbizonylatszam(), Bankbizonylatfej::class);
        $x['szamlazasdatum'] = $t->getSzamlazasdatumStr();
        $x['szamlazvakelt'] = $t->getSzamlazvakeltStr();
        $x['szamlazvateljesites'] = $t->getSzamlazvateljesitesStr();
        $x['szamlaszamlink'] = $this->getBizonylatUrl($t->getSzamlaszam(), Bizonylatfej::class);
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
        // a rendezvény eredetű soroknál az időpont saját terméke is számlázhatóvá teszi a jelentkezést
        $view->setVar('szamlazhato', true);
        $view->setVar('dijbekerosablonvan', (bool)\mkw\store::getParameter(\mkw\consts::RendezvenySablonDijbekero));
        $view->setVar('kezdessablonvan', (bool)\mkw\store::getParameter(\mkw\consts::RendezvenySablonKezdesEmlekezteto));
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
        $obj->setVarolistas($this->params->getBoolRequestParam('varolistas'));
        $obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
        $fizmod = $this->getRepo(Fizmod::class)->find($this->params->getIntRequestParam('fizmod', 0));
        if ($fizmod) {
            $obj->setFizmod($fizmod);
        }

        if ($oper !== $this->addOperation) {
            // rendezvény eredetű jelentkezésnél az időpont/partner/dátum hármas eddig is szerkeszthető
            // volt, időpont foglalásnál viszont az a foglalás azonossága
            if ($obj->getIdopont()?->isRendezveny()) {
                $ujidopont = $this->getRepo(Idopont::class)->find($this->params->getIntRequestParam('idopont', 0));
                if ($ujidopont) {
                    $obj->setIdopont($ujidopont);
                }
                $partner = $this->getRepo(Partner::class)->find($this->params->getIntRequestParam('partner', 0));
                if ($partner) {
                    $obj->setPartner($partner);
                }
                $datum = $this->params->getStringRequestParam('datum');
                if ($datum !== '') {
                    $obj->setDatum($datum);
                }
                return $obj;
            }
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
        $f = $this->params->getIntRequestParam('idfilter');
        if ($f) {
            $filter->addFilter('id', '=', $f);
        }
        // a jelentkezéskori pillanatképre szűrünk: a partnertörzs neve azóta változhatott
        $f = $this->params->getStringRequestParam('partnernevfilter');
        if ($f) {
            $filter->addFilter('partnernev', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('partneremailfilter');
        if ($f) {
            $filter->addFilter('partneremail', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getStringRequestParam('tipusfilter');
        if ($f) {
            $filter->addFilter('idopont.tipus', '=', $f);
        }
        $f = $this->params->getIntRequestParam('fizmodfilter');
        if ($f) {
            $filter->addFilter('fizmod', '=', $f);
        }
        $f = $this->params->getIntRequestParam('varolistasfilter', 9);
        if ($f !== 9) {
            $filter->addFilter('varolistas', '=', $f);
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
        if (!is_null($this->params->getRequestParam('idopontfilter', null))) {
            $filter->addFilter('idopont.id', '=', $this->params->getIntRequestParam('idopontfilter'));
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
        $view->setVar('pagetitle', t('Időpont jelentkezések'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->setVar('dolgozolist', (new dolgozoController())->getSelectList());
        $view->setVar('idoponttemalist', (new idoponttemaController())->getSelectList());
        // a szűrő az inaktív időpontokat is kínálja: a régi foglalásokat is meg kell találni
        $view->setVar('idopontlist', (new idopontController())->getSelectList(null, false));
        $view->setVar('fizmodlist', (new fizmodController())->getSelectList(null, null, null, false));
        $view->setVar('jogcimlist', (new jogcimController())->getSelectList());
        $view->setVar('penztarlist', (new penztarController())->getSelectList());
        $view->setVar('bankszamlalist', (new bankszamlaController())->getSelectList());
        if (!\mkw\store::isPartnerAutocomplete()) {
            $view->setVar('partnerlist', (new partnerController())->getSelectList());
        }
        $this->setVars($view);
        $view->printTemplateResult();
    }

    public function viewselect()
    {
        $view = $this->createView('idopontfoglalaslista.tpl');
        $view->setVar('pagetitle', t('Időpont jelentkezések'));
        $view->setVar('idopontlist', (new idopontController())->getSelectList(null, false));
        $view->setVar('fizmodlist', (new fizmodController())->getSelectList(null, null, null, false));
        if (!\mkw\store::isPartnerAutocomplete()) {
            $view->setVar('partnerlist', (new partnerController())->getSelectList());
        }
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname)
    {
        $view = $this->createView($tplname);
        $oper = $this->params->getRequestParam('oper', '');
        $view->setVar('pagetitle', t('Időpont jelentkezés'));
        $view->setVar('oper', $oper);
        $view->setVar('formaction', '/admin/idopontfoglalas/save');
        /** @var \Entities\Idopontfoglalas $record */
        $record = $this->getRepo()->findWithJoins($this->params->getRequestParam('id', 0));
        $view->setVar('egyed', $this->loadVars($record, true));
        $view->setVar('fizmodlist', (new fizmodController())->getSelectList($record?->getFizmod()?->getId()));
        $view->setVar('jogcimlist', (new jogcimController())->getSelectList());
        // felvitelnél csak az aktív időpontok közül lehet választani, meglévő rendezvény
        // jelentkezésnél az inaktívak is kellenek, hogy a rekord menthető maradjon
        if ($oper === $this->addOperation) {
            $view->setVar('idopontlist', (new idopontController())->getSelectList());
        } else {
            $view->setVar('idopontlist', (new idopontController())->getSelectList($record?->getIdopontId(), false));
        }
        if (!\mkw\store::isPartnerAutocomplete()) {
            $view->setVar('partnerlist', (new partnerController())->getSelectList($record?->getPartner()?->getId()));
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
        $foglalas->setVarolistas(false);
        $this->getEm()->persist($foglalas);
        $this->getEm()->flush();

        $msg = at('A foglalás lemondva.');
        if ($this->sendFoglalasEmail($foglalas, \mkw\consts::IdopontfoglalasSablonLemondas, 'idopontfoglalaslemondasemail.html')) {
            $msg .= ' ' . at('A lemondásról levél ment ki.');
        }
        $this->ertesitVarolistasokat($foglalas->getIdopont());
        echo json_encode(['msg' => $msg]);
    }

    /**
     * A felszabadult helyre a várólistások értesítést kapnak – a rendezvény oldalról átvéve.
     */
    private function ertesitVarolistasokat($idopont)
    {
        if (!$idopont || !$idopont->isVarolistavan()) {
            return;
        }
        $filter = new FilterDescriptor();
        $filter->addFilter('idopont', '=', $idopont);
        $filter->addFilter('lemondva', '=', false);
        $filter->addFilter('varolistas', '=', true);
        foreach ($this->getRepo()->getAll($filter) as $varolistas) {
            $this->sendFelszabadultHelyEmail($varolistas->getId());
        }
    }

    /**
     * A lista sorának „Díjbekérő email" gombja.
     */
    public function sendDijbekeroEmail()
    {
        /** @var \Entities\Idopontfoglalas $jel */
        $jel = $this->getRepo()->findWithJoins($this->params->getIntRequestParam('id'));
        if (!$jel) {
            echo json_encode(['msg' => at('A jelentkezés nem található.')]);
            return;
        }
        if (!$this->sendFoglalasEmail($jel, \mkw\consts::RendezvenySablonDijbekero, 'rendezvenydijbekeroemail.html')) {
            echo json_encode(['msg' => at('Díjbekérő levél sablon nem található, vagy a jelentkezőnek nincs emailcíme.')]);
            return;
        }
        $jel->setEmaildijbekero(true);
        $jel->setEmaildijbekerodatum('');
        $this->getEm()->persist($jel);
        $this->getEm()->flush();
        echo json_encode(['msg' => at('A díjbekérő levél kiküldve.')]);
    }

    /**
     * Kezdés emlékeztető. Az időpont képernyő tömegesen is hívja, ezért van id paramétere.
     */
    public function sendKezdesEmail($id = null)
    {
        $kellecho = !$id;
        $id = $id ?: $this->params->getIntRequestParam('id');
        /** @var \Entities\Idopontfoglalas $jel */
        $jel = $this->getRepo()->findWithJoins($id);
        $ret = ['msg' => at('A kezdés emlékeztető levél kiküldve.')];
        if (!$jel) {
            $ret['msg'] = at('A jelentkezés nem található.');
        } elseif (!$this->sendFoglalasEmail($jel, \mkw\consts::RendezvenySablonKezdesEmlekezteto, 'rendezvenykezdesemail.html')) {
            $ret['msg'] = at('Kezdés emlékeztető levél sablon nem található.');
        } else {
            $jel->setEmailemlekezteto(true);
            $jel->setEmailemlekeztetodatum('');
            $this->getEm()->persist($jel);
            $this->getEm()->flush();
        }
        if ($kellecho) {
            echo json_encode($ret);
        }
    }

    public function sendFelszabadultHelyEmail($id = null)
    {
        $kellecho = !$id;
        $id = $id ?: $this->params->getIntRequestParam('id');
        /** @var \Entities\Idopontfoglalas $jel */
        $jel = $this->getRepo()->findWithJoins($id);
        $ret = ['msg' => at('A felszabadult helyről szóló levél kiküldve.')];
        if (!$jel) {
            $ret['msg'] = at('A jelentkezés nem található.');
        } elseif (!$this->sendFoglalasEmail(
            $jel,
            \mkw\consts::RendezvenySablonFelszabadultHelyErtesito,
            'rendezvenyfelszabadulthelyemail.html'
        )) {
            $ret['msg'] = at('Felszabadult hely értesítő levél sablon nem található.');
        }
        if ($kellecho) {
            echo json_encode($ret);
        }
    }

    /**
     * A lemondott, kifizetett jelentkezés visszautalása – a rendezvény oldalon is csonk volt.
     */
    public function visszautal()
    {
        echo json_encode(['result' => 'error', 'msg' => at('A visszautalás még nincs megvalósítva.')]);
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
     * A kifizető doboz ezzel tölti fel az összeget: az alkalom (early bird szerinti) ára,
     * ár híján a hozzárendelt termék bruttó ára a partner árkategóriájával.
     */
    public function getar()
    {
        /** @var \Entities\Idopontfoglalas $foglalas */
        $foglalas = $this->getRepo()->findWithJoins($this->params->getIntRequestParam('id'));
        if (!$foglalas) {
            echo json_encode(['result' => 'error', 'msg' => at('A foglalás nem található.')]);
            return;
        }
        $ar = $foglalas->getErvenyesAr();
        if ($ar) {
            echo json_encode(['result' => 'ok', 'price' => $ar]);
            return;
        }
        $termek = $foglalas->getIdopont()?->getTermek();
        $partner = $foglalas->getPartner();
        if ($termek && $partner) {
            echo json_encode(['result' => 'ok', 'price' => $termek->getBruttoAr(null, $partner)]);
            return;
        }
        echo json_encode(['result' => 'ok', 'price' => 0]);
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
     * A számlázó doboz ezzel tölti fel az összeget: a már kifizetett összeg.
     */
    public function getfizetettosszeg()
    {
        /** @var \Entities\Idopontfoglalas $foglalas */
        $foglalas = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        if (!$foglalas) {
            echo json_encode(['result' => 'error', 'msg' => at('A foglalás nem található.')]);
            return;
        }
        echo json_encode(['result' => 'ok', 'price' => $foglalas->getFizetveosszeghuf()]);
    }

    /**
     * A lista sorának „Számláz" gombja: a kifizetéskor rögzített fizetési móddal állít ki
     * bizonylatot a foglaló partnerre. A tétel terméke a beállításokból jön
     * (\mkw\consts::IdopontfoglalasTermek), az összeg a kifizetett összeg.
     */
    public function szamlaz()
    {
        if (!\mkw\store::csinalhatUjSzamlat()) {
            echo json_encode(['result' => 'error', 'msg' => at('Amíg van beküldetlen számla, nem állíthat ki újat!')]);
            return;
        }
        /** @var \Entities\Idopontfoglalas $foglalas */
        $foglalas = $this->getRepo()->findWithJoins($this->params->getIntRequestParam('id'));
        $kelt = $this->params->getStringRequestParam('kelt');
        $teljesites = $this->params->getStringRequestParam('teljesites');
        $osszeg = $this->params->getNumRequestParam('osszeg');

        $biztipusstr = $this->params->getStringRequestParam('biztipus');
        $biztipus = in_array($biztipusstr, ['szamla', 'egyeb'], true)
            ? $this->getRepo(Bizonylattipus::class)->find($biztipusstr)
            : null;

        if (!$foglalas) {
            echo json_encode(['result' => 'error', 'msg' => at('A foglalás nem található.')]);
            return;
        }
        if ($foglalas->getLemondva() || !$foglalas->getFizetve()) {
            echo json_encode(['result' => 'error', 'msg' => at('Csak kifizetett, le nem mondott foglalás számlázható.')]);
            return;
        }
        if ($foglalas->getSzamlazva()) {
            echo json_encode(['result' => 'error', 'msg' => at('A foglalás már ki van számlázva.')]);
            return;
        }
        // a rendezvény eredetű időpontnak saját terméke van; ennek híján a beállításbeli termék
        /** @var \Entities\Termek $termek */
        $termek = $foglalas->getIdopont()?->getTermek()
            ?: $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::IdopontfoglalasTermek));
        if (!$termek) {
            echo json_encode(['result' => 'error', 'msg' => at('Nincs termék az időponton, és nincs beállítva időpont foglalás termék sem.')]);
            return;
        }
        if (!$biztipus || !$kelt || !$teljesites || !$osszeg) {
            echo json_encode(['result' => 'error', 'msg' => at('Nem adott meg minden adatot!')]);
            return;
        }

        if ($foglalas->getFizetvebanktetelid()) {
            /** @var \Entities\Bankbizonylatfej $bankfej */
            $bankfej = $this->getRepo(Bankbizonylatfej::class)->find($foglalas->getFizetvebankbizonylatszam());
            /** @var \Entities\Bankbizonylattetel $banktetel */
            $banktetel = $this->getRepo(Bankbizonylattetel::class)->find($foglalas->getFizetvebanktetelid());
            $penztartetel = null;
        } else {
            $bankfej = null;
            $banktetel = null;
            /** @var \Entities\Penztarbizonylattetel $penztartetel */
            $penztartetel = $this->getRepo(Penztarbizonylattetel::class)->find($foglalas->getFizetvepenztartetelid());
        }

        $biz = new Bizonylatfej();
        $bt = new \Entities\Bizonylattetel();

        $biz->setBizonylattipus($biztipus);
        $biz->setPersistentData();
        $biz->addBizonylattetel($bt);

        $biz->setPartner($foglalas->getPartner());
        if (!$biz->getPartnervatstatus() && !$biz->getPartneradoszam()) {
            $biz->setPartnervatstatus(2);
        }
        $biz->setFizmod($foglalas->getFizmod());
        $biz->setKelt($kelt);
        $biz->setTeljesites($teljesites);
        $biz->setEsedekesseg(\mkw\store::calcEsedekesseg($kelt, $foglalas->getFizmod(), $foglalas->getPartner()));
        $biz->setValutanem(\mkw\store::getParameter(\mkw\consts::Valutanem));
        $biz->setArfolyam(1);
        if ($bankfej) {
            $biz->setBankszamla($bankfej->getBankszamla());
        }
        $biz->setBelsomegjegyzes(at('Automatikus bizonylat'));
        // a kifizetéskor már készült bank-/pénztárbizonylat, azt kötjük rá lentebb a számlára
        $biz->setNincsautopenztarbizonylat(true);
        $biz->setRaktar(\mkw\store::getDefaultRaktarId());
        $biz->setSzallitasimod(\mkw\store::getParameter(\mkw\consts::Szallitasimod));

        $bt->setPersistentData();
        $bt->setTermek($termek);
        $bt->setBruttoegysarhuf($osszeg);
        $bt->setBruttoegysar($osszeg);
        $bt->setMennyiseg(1);
        $bt->calc();

        $this->getEm()->persist($biz);
        $this->getEm()->flush($biz);

        $foglalas->setSzamlazva(true);
        $foglalas->setSzamlazasdatum();
        $foglalas->setSzamlaszam($biz->getId());
        $foglalas->setSzamlazvabizonylattipus($biztipusstr);
        $foglalas->setSzamlazvakelt($kelt);
        $foglalas->setSzamlazvateljesites($teljesites);
        $foglalas->setSzamlazvaosszeghuf($osszeg);

        if ($banktetel) {
            $banktetel->setHivatkozottbizonylat($biz->getId());
            $this->getEm()->persist($banktetel);
        } elseif ($penztartetel) {
            $penztartetel->setHivatkozottbizonylat($biz->getId());
            $this->getEm()->persist($penztartetel);
        }

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
     * Publikus lemondás – a köszönő levél lemondás linkje és a foglalási űrlap "Lemondom"
     * gombja hozza ide. Az azonosítás ugyanaz, mint a rendezvény jelentkezésnél
     * (idopontController::regLemond): az alkalom uid-ja és a foglaló emailcíme.
     */
    public function cancelBooking()
    {
        $uid = trim($this->params->getStringRequestParam('rid'));
        /** @var \Entities\Idopont $idopont */
        $idopont = $uid === '' ? null : $this->getRepo(Idopont::class)->findOneBy(['uid' => $uid]);
        $datum = $this->getOccurrenceDatum($idopont);
        $email = trim($this->params->getStringRequestParam('email'));

        $foglalas = null;
        if ($idopont && $email) {
            $keres = ['idopont' => $idopont, 'partneremail' => $email];
            if ($datum) {
                $keres['datum'] = $datum;
            }
            /** @var \Entities\Idopontfoglalas $foglalas */
            $foglalas = $this->getRepo()->findOneBy($keres);
        }

        $view = $this->createView('idopontfoglalaslemondas.tpl');
        $this->setBookingFormVars($view, $idopont, $datum);
        $view->setVar('hiba', '');
        $view->setVar('partnernev', $foglalas ? $foglalas->getPartnerNev() : '');
        if (!$foglalas) {
            $view->setVar('hiba', t('Ezzel az emailcímmel nem találunk foglalást erre az alkalomra.'));
            $view->printTemplateResult();
            return;
        }
        if ($foglalas->getLemondva()) {
            $view->setVar('hiba', t('Ez a foglalás már le van mondva.'));
            $view->printTemplateResult();
            return;
        }

        $foglalas->setLemondva(true);
        $foglalas->setLemondasdatum();
        $foglalas->setLemondasoka(t('A foglaló mondta le a weboldalon.'));
        $foglalas->setVarolistas(false);
        $this->getEm()->persist($foglalas);
        $this->getEm()->flush();

        $this->sendFoglalasEmail($foglalas, \mkw\consts::IdopontfoglalasSablonLemondas, 'idopontfoglalaslemondasemail.html');
        $this->ertesitVarolistasokat($idopont);

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
        $body = \mkw\store::getTemplateFactory()->createMainView(
            'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
        );
        foreach ([$subject, $body] as $v) {
            $v->setVar('foglalas', $tpldata);
            // a rendezvény sablonok ezen a néven hivatkoznak ugyanerre
            $v->setVar('jelentkezes', $tpldata);
        }
        $helyszin = $foglalas->getIdopont()?->getJogahelyszin();
        if ($helyszin) {
            $body->setVar('helyszin', $helyszin->getEmailsablon());
        }
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
        $view->setVar('idopontuid', $idopont ? $idopont->getUid() : '');
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
