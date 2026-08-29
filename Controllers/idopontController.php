<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\Emailtemplate;
use Entities\Idopont;
use Entities\IdopontDok;
use Entities\Idopontallapot;
use Entities\Idopontfoglalas;
use Entities\Idoponttema;
use Entities\Jogahelyszin;
use Entities\Partner;
use Entities\Termek;
use mkwhelpers\FilterDescriptor;
use Services\PartnerWriterService;

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
        $x['termeknev'] = $t->getTermekNev();
        $x['idopontallapotnev'] = $t->getIdopontallapotNev();
        $x['teljesnev'] = $t->getTeljesNev();
        $x['kezdet'] = $t->getKezdetStr();
        $x['veg'] = $t->getVegStr();
        $x['kezdetinput'] = $t->getKezdetInputStr();
        $x['veginput'] = $t->getVegInputStr();
        $x['kezdetido'] = $t->getKezdetidoStr();
        $x['vegido'] = $t->getVegidoStr();
        $x['napnev'] = $t->getNapNev();
        $x['idotartam'] = $t->getIdotartamStr();
        $x['earlybirdvege'] = $t->getEarlybirdvegeStr();
        // az ügyfél weboldalaiba beágyazott snippet – a fájlnév és a paraméterek nem változhatnak
        $x['reglink'] = '<script src=\'' . \mkw\store::getConfigValue('mainurl') . '/js/main/' . \mkw\store::getConfigValue(
                'main.theme'
            ) . '/rendezvenyregloader.js?r=' . $t->getUid() . '&i=' . $t->getId() . '\'></script>';
        // ismétlődőnél a hely naponként telik be, egyetlen összesített szám félrevezető lenne
        if (!$t->isIsmetlodo() && $t->getId()) {
            $x['foglalasdb'] = $t->getBookedCount();
            $x['szabadhely'] = $t->getFreePlaces();
        } else {
            $x['foglalasdb'] = 0;
            $x['szabadhely'] = $t->getMaxresztvevo();
        }

        if ($forKarb) {
            $dokCtrl = new idopontdokController();
            $dok = [];
            foreach ($t->getIdopontDokok() as $dokje) {
                $dok[] = $dokCtrl->loadVars($dokje);
            }
            $x['dokok'] = $dok;
        }
        return $x;
    }

    /**
     * @param \Entities\Idopont $obj
     *
     * @return \Entities\Idopont
     */
    protected function setFields($obj, $oper = null)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
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
        $obj->setEarlybirdvege($this->params->getStringRequestParam('earlybirdvege'));
        $obj->setDolgozo($this->getRepo(Dolgozo::class)->find($this->params->getIntRequestParam('dolgozo')));
        $obj->setIdoponttema($this->getRepo(Idoponttema::class)->find($this->params->getIntRequestParam('idoponttema')));
        $obj->setJogahelyszin($this->getRepo(Jogahelyszin::class)->find($this->params->getIntRequestParam('jogahelyszin')));
        $obj->setIdopontallapot($this->getRepo(Idopontallapot::class)->find($this->params->getIntRequestParam('idopontallapot')));
        $obj->setTermek($this->getRepo(Termek::class)->find($this->params->getIntRequestParam('termek')));

        $dokids = $this->params->getArrayRequestParam('dokid');
        foreach ($dokids as $dokid) {
            if (($this->params->getStringRequestParam('dokurl_' . $dokid, '') === '') &&
                ($this->params->getStringRequestParam('dokpath_' . $dokid, '') === '')) {
                continue;
            }
            $dokoper = $this->params->getStringRequestParam('dokoper_' . $dokid);
            if ($dokoper === 'add') {
                $dok = new IdopontDok();
                $obj->addIdopontDok($dok);
            } elseif ($dokoper === 'edit') {
                $dok = $this->getRepo(IdopontDok::class)->find($dokid);
            } else {
                continue;
            }
            if ($dok) {
                $dok->setUrl($this->params->getStringRequestParam('dokurl_' . $dokid));
                $dok->setPath($this->params->getStringRequestParam('dokpath_' . $dokid));
                $dok->setLeiras($this->params->getStringRequestParam('dokleiras_' . $dokid));
                $this->getEm()->persist($dok);
            }
        }
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
        $f = $this->params->getStringRequestParam('tipusfilter');
        if ($f) {
            $filter->addFilter('tipus', '=', $f);
        }
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        // a dátumszűrő csak az egyszeri időpontokra értelmezhető, az ismétlődők mindig bent maradnak
        $f = $this->params->getStringRequestParam('datumtolfilter') ?: $this->params->getStringRequestParam('tol');
        if ($f) {
            $filter->addFilter('kezdet', '>=', new \DateTime(\mkw\store::convDate($f) . ' 00:00:00'));
        }
        $f = $this->params->getStringRequestParam('datumigfilter') ?: $this->params->getStringRequestParam('ig');
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
        if (!is_null($this->params->getRequestParam('idopontallapotfilter', null))) {
            $filter->addFilter('idopontallapot', '=', $this->params->getIntRequestParam('idopontallapotfilter'));
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
        $this->setTorzsListak($view);
        $view->printTemplateResult();
    }

    public function viewselect()
    {
        $view = $this->createView('idopontlista.tpl');
        $view->setVar('pagetitle', t('Időpontok'));
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname)
    {
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Időpont'));
        $view->setVar('oper', $this->params->getRequestParam('oper', ''));
        $view->setVar('formaction', '/admin/idopont/save');

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
        $view->setVar('idopontallapotlist', (new idopontallapotController())->getSelectList($idopont?->getIdopontallapot()?->getId()));
        $view->setVar('termeklist', (new termekController())->getSelectList($idopont?->getTermek()?->getId()));
        $view->setVar('naplist', \mkw\store::getDaynameSelectList($idopont ? $idopont->getNap() : 0));

        return $view->getTemplateResult();
    }

    private function setTorzsListak($view)
    {
        $view->setVar('dolgozolist', (new dolgozoController())->getSelectList());
        $view->setVar('idoponttemalist', (new idoponttemaController())->getSelectList());
        $view->setVar('jogahelyszinlist', (new jogahelyszinController())->getSelectList());
        $view->setVar('idopontallapotlist', (new idopontallapotController())->getSelectList());
        $view->setVar('termeklist', (new termekController())->getSelectList(null));
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
     * Az admin jelentkezés-felvitel időpont választója; a nap/dátum ellenőrzéséhez az ismétlődés
     * adatait is visszaadja.
     */
    public function getSelectList($selid = null, $csakaktiv = true)
    {
        $filter = new FilterDescriptor();
        if ($csakaktiv) {
            $filter->addFilter('inaktiv', '=', false);
        }
        $rec = $this->getRepo()->getWithJoins($filter, ['ismetlodo' => 'ASC', 'kezdet' => 'DESC', 'nap' => 'ASC', 'kezdetido' => 'ASC']);
        $res = [];
        /** @var \Entities\Idopont $sor */
        foreach ($rec as $sor) {
            $mikor = $sor->isIsmetlodo()
                ? t('minden') . ' ' . $sor->getNapNev() . ' ' . $sor->getIdotartamStr()
                : $sor->getDatumStr() . ' ' . $sor->getIdotartamStr();
            $megnevezes = $sor->getNev() !== '' ? $sor->getNev() : $sor->getIdoponttemaNev();
            $res[] = [
                'id' => $sor->getId(),
                'caption' => trim($mikor . ' – ' . $megnevezes . ' (' . $sor->getDolgozoNev() . ')'),
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
            $korlatlan = !$item->hasLetszamkorlat();
            $szabadhely = $korlatlan ? null : max(0, (int)$item->getMaxresztvevo() - $foglalasdb);
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
                'megvanhely' => $korlatlan || $szabadhely > 0
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
     * A heti foglalónézet csak az időpontokat mutatja: a rendezvények a saját regisztrációs
     * űrlapjukon és az órarend exportban jelennek meg.
     *
     * @return array
     */
    private function getIdopontokForWeek($ismetlodo, $startdatum, $vegdatum, $tanarkod, $temakod)
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('tipus', '=', Idopont::TIPUS_IDOPONT);
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

    /**
     * A publikus regisztrációs űrlap. Az útvonal (/rendezveny/reg), a sablonnév és a mezőnevek
     * változatlanok: ügyfél weboldalakba ágyazott iframe hívja.
     */
    public function regView()
    {
        $idopont = $this->findByUid($this->params->getStringRequestParam('r'));
        if (!$idopont) {
            return;
        }
        $v = $this->getTemplateFactory()->createMainView('rendezvenyreg.tpl');
        $v->setVar('uid', $idopont->getUid());
        $v->setVar('kellszamlazasiadat', $idopont->getKellszamlazasiadat());
        $v->setVar('rendezvenynev', $idopont->getTeljesNev());
        $v->setVar('szabadhelykovetes', $idopont->hasLetszamkorlat());
        $v->setVar('varolistavan', $idopont->isVarolistavan());
        $v->setVar('szabadhelyszam', $idopont->getFreePlaces() ?? 0);
        $v->setVar('csomag', $idopont->isCsomag());
        echo $v->getTemplateResult();
    }

    public function regSave()
    {
        $kellszamlazasiadat = $this->params->getBoolRequestParam('kellszamlazasiadat', false);
        $idopont = $this->findByUid($this->params->getStringRequestParam('r'));
        if (!$idopont) {
            return;
        }
        $sendemails = false;
        $email = $this->params->getStringRequestParam('email');

        /** @var Idopontfoglalas $jel */
        $jel = $this->getRepo(Idopontfoglalas::class)->findOneBy([
            'idopont' => $idopont,
            'partneremail' => $email
        ]);

        $szabadhely = $idopont->getFreePlaces();

        if (!$jel) {
            $partner = $this->getRepo(Partner::class)->findOneBy(['email' => $email]);
            if (!$partner) {
                $partner = new Partner();
                $partner->setVatstatus(2);
            }
            (new PartnerWriterService($partner, $this->params))->nev()->kapcsolat()->munkahely()->hirlevel()->szamlacim();

            if (!$kellszamlazasiadat) {
                $partner->setNev($partner->getVezeteknev() . ' ' . $partner->getKeresztnev());
            }
            $this->getEm()->persist($partner);

            $jel = new Idopontfoglalas();
            $jel->setPartner($partner);
            $jel->setIdopont($idopont);
            $jel->setDatum($idopont->getKezdet() ?: '');
            $jel->setEmailkoszono(true);
            $jel->setVarolistas($idopont->isVarolistavan() && $szabadhely !== null && $szabadhely < 1);
            $this->getEm()->persist($jel);

            $this->getEm()->flush();
            $sendemails = true;
        } elseif (!$jel->getLemondva() && $jel->isVarolistas() && ($szabadhely === null || $szabadhely > 0)) {
            $jel->setVarolistas(false);
            $this->getEm()->persist($jel);
            $this->getEm()->flush();
            $sendemails = true;
        }

        if ($sendemails) {
            $this->sendRegEmail($jel, \mkw\consts::RendezvenySablonRegKoszono, $jel->getPartnerEmail(), 'rendezvenyregkoszonoemail.html');
            $this->sendRegEmail(
                $jel,
                \mkw\consts::RendezvenySablonRegErtesito,
                \mkw\store::getParameter(\mkw\consts::RendezvenyRegErtesitoEmail),
                'rendezvenyregertesitoemail.html'
            );
        }

        $v = $this->getTemplateFactory()->createMainView('rendezvenyregkoszono.tpl');
        $v->setVar('kellszamlazasiadat', $kellszamlazasiadat);
        $v->setVar('jelentkezes', $jel->toLista());
        echo $v->getTemplateResult();
    }

    public function regLemond()
    {
        $idopont = $this->findByUid($this->params->getStringRequestParam('rid'));
        /** @var Idopontfoglalas $jel */
        $jel = $idopont
            ? $this->getRepo(Idopontfoglalas::class)->findOneBy([
                'idopont' => $idopont,
                'partneremail' => $this->params->getStringRequestParam('email')
            ])
            : null;
        if (!$jel) {
            if ($idopont) {
                header(
                    'Location: ' . \mkw\store::getRouter()->generate(
                        'showrendezvenyreg',
                        true,
                        [],
                        ['r' => $idopont->getUid(), 'i' => $idopont->getId()]
                    )
                );
            }
            return;
        }

        $jel->setLemondva(true);
        $jel->setVarolistas(false);
        $jel->setLemondasdatum();
        $this->getEm()->persist($jel);
        $this->getEm()->flush();

        $this->sendRegEmail($jel, \mkw\consts::RendezvenySablonRegKoszono, $jel->getPartnerEmail(), 'rendezvenyregkoszonoemail.html', true);
        $this->sendRegEmail(
            $jel,
            \mkw\consts::RendezvenySablonRegErtesito,
            \mkw\store::getParameter(\mkw\consts::RendezvenyRegErtesitoEmail),
            'rendezvenyregertesitoemail.html',
            true
        );

        // a felszabadult helyre a várólistások értesítést kapnak
        $ifc = new idopontfoglalasController();
        $filter = new FilterDescriptor();
        $filter->addFilter('idopont', '=', $idopont);
        $filter->addFilter('lemondva', '=', false);
        $filter->addFilter('varolistas', '=', true);
        foreach ($this->getRepo(Idopontfoglalas::class)->getAll($filter) as $varolistas) {
            $ifc->sendFelszabadultHelyEmail($varolistas->getId());
        }

        $v = $this->getTemplateFactory()->createMainView('rendezvenyregkoszono.tpl');
        $v->setVar('lemondas', true);
        $v->setVar('jelentkezes', $jel->toLista());
        echo $v->getTemplateResult();
    }

    public function sendKezdesEmail()
    {
        $ret = ['msg' => at('A kezdés emlékeztető levelek kiküldve.')];
        $idopont = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        if ($idopont) {
            $ifc = new idopontfoglalasController();
            $filter = new FilterDescriptor();
            $filter->addFilter('idopont', '=', $idopont);
            $filter->addFilter('lemondva', '=', false);
            $filter->addFilter('varolistas', '=', false);
            foreach ($this->getRepo(Idopontfoglalas::class)->getAll($filter) as $jel) {
                $ifc->sendKezdesEmail($jel->getId());
            }
        }
        echo json_encode($ret);
    }

    /**
     * Üres uid-re nem keresünk: a beolvasztott sorok uid-ja generált, de egy üres `r` paraméter
     * véletlenül eltalálhatna egy sort.
     *
     * @return \Entities\Idopont|null
     */
    private function findByUid($uid)
    {
        $uid = trim((string)$uid);
        return $uid === '' ? null : $this->getRepo()->findOneBy(['uid' => $uid]);
    }

    /**
     * A regisztrációs és lemondó levelek. A Smarty változó neve `jelentkezes` marad: a DB-ben tárolt,
     * ügyfél által szerkesztett sablonok erre hivatkoznak.
     */
    private function sendRegEmail($jel, $parameter, $cimzett, $logfile, $lemondas = false)
    {
        $emailtpl = $this->getRepo(Emailtemplate::class)->find(\mkw\store::getParameter($parameter));
        if (!$emailtpl || !$cimzett) {
            return;
        }
        $tpldata = $jel->toLista();
        $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
        $body = \mkw\store::getTemplateFactory()->createMainView(
            'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
        );
        foreach ([$subject, $body] as $v) {
            $v->setVar('jelentkezes', $tpldata);
            $v->setVar('foglalas', $tpldata);
            if ($lemondas) {
                $v->setVar('lemondas', true);
            }
        }
        $helyszin = $jel->getIdopont()?->getJogahelyszin();
        if ($helyszin) {
            $body->setVar('helyszin', $helyszin->getEmailsablon());
        }
        if (\mkw\store::getConfigValue('developer')) {
            \mkw\store::writelog($subject->getTemplateResult(), $logfile);
            \mkw\store::writelog($body->getTemplateResult(), $logfile);
            return;
        }
        $mailer = \mkw\store::getMailer();
        $mailer->addTo($cimzett);
        $mailer->setSubject($subject->getTemplateResult());
        $mailer->setMessage($body->getTemplateResult());
        $mailer->send();
    }

}
