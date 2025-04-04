<?php

namespace Controllers;

use Automattic\WooCommerce\Client;
use Entities\Arsav;
use Entities\Partner;
use Entities\Termek;
use Entities\TermekAr;
use Entities\TermekFa;
use Entities\TermekKep;
use Entities\TermekMenu;
use Entities\TermekValtozat,
    Entities\TermekRecept;
use Entities\TermekValtozatErtek;
use Entities\Valutanem;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class termekController extends \mkwhelpers\MattableController
{

    private $kaphatolett = false;
    private $vanshowarsav = false;

    public function __construct($params)
    {
        $this->setEntityName(Termek::class);
        $this->setKarbFormTplName('termekkarbform.tpl');
        $this->setKarbTplName('termekkarb.tpl');
        $this->setListBodyRowTplName('termeklista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_termek');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false)
    {
        $termekarCtrl = new termekarController($this->params);
        $kepCtrl = new termekkepController($this->params);
        $receptCtrl = new termekreceptController($this->params);
        $valtozatCtrl = new termekvaltozatController($this->params);
        $kapcsolodoCtrl = new termekkapcsolodoController($this->params);
        $translationsCtrl = new termektranslationController($this->params);
        $dokCtrl = new termekdokController($this->params);
        $ar = [];
        $kep = [];
        $recept = [];
        $valtozat = [];
        $lvaltozat = [];
        $kapcsolodo = [];
        $translations = [];
        $dok = [];
        $x = [];
        if (!$t) {
            $t = new \Entities\Termek();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['wcid'] = $t->getWcid();
        $x['vtsznev'] = $t->getVtszNev();
        $x['afanev'] = $t->getAfaNev();
        $x['nev'] = $t->getNev();
        $x['nev2'] = $t->getNev2();
        $x['nev3'] = $t->getNev3();
        $x['nev4'] = $t->getNev4();
        $x['nev5'] = $t->getNev5();
        $x['kiirtnev'] = $t->getKiirtnev();
        $x['slug'] = $t->getSlug();
        $x['me'] = $t->getMe();
        $x['cikkszam'] = $t->getCikkszam();
        $x['idegencikkszam'] = $t->getIdegencikkszam();
        $x['vonalkod'] = $t->getVonalkod();
        $x['idegenkod'] = $t->getIdegenkod();
        $x['oldalcim'] = $t->getOldalcim();
        $x['rovidleiras'] = $t->getRovidleiras();
        $x['leiras'] = $t->getLeiras();
        $x['seodescription'] = $t->getSeodescription();
        $x['feltoltheto'] = $t->getFeltoltheto();
        $x['feltoltheto1'] = $t->getFeltoltheto();
        $x['feltoltheto2'] = $t->getFeltoltheto2();
        $x['feltoltheto3'] = $t->getFeltoltheto3();
        $x['feltoltheto4'] = $t->getFeltoltheto4();
        $x['feltoltheto5'] = $t->getFeltoltheto5();
        $x['lathato'] = $t->getLathato();
        $x['lathato1'] = $t->getLathato();
        $x['lathato2'] = $t->getLathato2();
        $x['lathato3'] = $t->getLathato3();
        $x['lathato4'] = $t->getLathato4();
        $x['lathato5'] = $t->getLathato5();
        $x['lathato6'] = $t->getLathato6();
        $x['lathato7'] = $t->getLathato7();
        $x['lathato8'] = $t->getLathato8();
        $x['lathato9'] = $t->getLathato9();
        $x['lathato10'] = $t->getLathato10();
        $x['lathato11'] = $t->getLathato11();
        $x['lathato12'] = $t->getLathato12();
        $x['lathato13'] = $t->getLathato13();
        $x['lathato14'] = $t->getLathato14();
        $x['lathato15'] = $t->getLathato15();
        $x['hozzaszolas'] = $t->getHozzaszolas();
        $x['ajanlott'] = $t->getAjanlott();
        $x['kiemelt'] = $t->getKiemelt();
        $x['inaktiv'] = $t->getInaktiv();
        $x['eladhato'] = $t->getEladhato();
        $x['emagtiltva'] = $t->getEmagtiltva();
        $x['mozgat'] = $t->getMozgat();
        $x['kozvetitett'] = $t->getKozvetitett();
        $x['hparany'] = $t->getHparany();
        $x['cimkek'] = $t->getCimkeNevek();
        $x['minboltikeszlet'] = $t->getMinboltikeszlet();
        $x['garancia'] = $t->getGarancia();
        $x['arukeresofanev'] = $t->getArukeresofanev();
        if (!\mkw\store::isArsavok()) {
            $x['netto'] = $t->getNetto();
            $x['brutto'] = $t->getBrutto();
            $x['akciosnetto'] = $t->getAkciosnetto();
            $x['akciosbrutto'] = $t->getAkciosbrutto();
            $x['akciostart'] = $t->getAkciostart();
            $x['akciostartstr'] = $t->getAkciostartStr();
            $x['akciostop'] = $t->getAkciostop();
            $x['akciostopstr'] = $t->getAkciostopStr();
        } else {
            $x['netto'] = 0;
            $x['brutto'] = 0;
            if ($this->vanshowarsav) {
                $arsav = $t->getTermekArak();
                if (count($arsav)) {
                    $arsav = $arsav[0];
                    $x['netto'] = $arsav->getNetto();
                    $x['brutto'] = $arsav->getBrutto();
                }
            }
            /*            $x['netto'] = 0;
                        $x['brutto'] = 0;
                        if (!$this->showarsav) {
                            $this->showarsav = \mkw\store::getParameter(\mkw\consts::ShowTermekArsav);
                        }
                        if (!$this->showarsavvalutanem) {
                            $this->showarsavvalutanem = \mkw\store::getParameter(\mkw\consts::ShowTermekArsavValutanem);
                        }
                        if ($this->showarsav && $this->showarsavvalutanem) {
                            $arsav = \mkw\store::getEm()->getRepository('Entities\TermekAr')->getArsav($t, $this->showarsavvalutanem, $this->showarsav);
                            if ($arsav) {
                                $x['netto'] = $arsav->getNetto();
                                $x['brutto'] = $arsav->getBrutto();
                            }
                        }
             *
             */
        }
        $x['termekexportbanszerepel'] = $t->getTermekexportbanszerepel();
        $x['nemkaphato'] = $t->getNemkaphato();
        $x['fuggoben'] = $t->getFuggoben();
        $x['szallitasiido'] = $t->getSzallitasiido();
        if ($forKarb) {
            foreach ($t->getTermekKepek() as $kepje) {
                $kep[] = $kepCtrl->loadVars($kepje);
            }
            //$kep[]=$kepCtrl->loadVars(null);
            $x['kepek'] = $kep;

            foreach ($t->getTermekDokok() as $kepje) {
                $dok[] = $dokCtrl->loadVars($kepje);
            }
            $x['dokok'] = $dok;

            if (\mkw\store::getSetupValue('kapcsolodotermekek')) {
                foreach ($t->getTermekKapcsolodok() as $tkapcsolodo) {
                    $kapcsolodo[] = $kapcsolodoCtrl->loadVars($tkapcsolodo, true);
                }
                //$kapcsolodo[]=$kapcsolodoCtrl->loadVars(null,true);
                $x['kapcsolodok'] = $kapcsolodo;
            }

            if (\mkw\store::getSetupValue('receptura')) {
                foreach ($t->getTermekReceptek() as $trecept) {
                    $recept[] = $receptCtrl->loadVars($trecept, true);
                }
                //$recept[]=$receptCtrl->loadVars(null,true);
                $x['receptek'] = $recept;
            }
            if (\mkw\store::getSetupValue('termekvaltozat')) {
                foreach ($t->getValtozatok() as $tvaltozat) {
                    $valtozat[] = $valtozatCtrl->loadVars($tvaltozat, $t);
                }
                //$valtozat[]=$valtozatCtrl->loadVars(null);
                $x['valtozatok'] = $valtozat;
            }
            if (\mkw\store::isArsavok()) {
                foreach ($t->getTermekArak() as $tar) {
                    $ar[] = $termekarCtrl->loadVars($tar, true);
                }
                $x['arak'] = $ar;
            }
            if (\mkw\store::isMultilang()) {
                foreach ($t->getTranslations() as $tr) {
                    $translations[] = $translationsCtrl->loadVars($tr, true);
                }
                $x['translations'] = $translations;
            }
        }
        $x['termekfa1nev'] = $t->getTermekfa1Nev();
        $x['termekfa2nev'] = $t->getTermekfa2Nev();
        $x['termekfa3nev'] = $t->getTermekfa3Nev();
        $x['termekfa1'] = $t->getTermekfa1Id();
        $x['termekfa2'] = $t->getTermekfa2Id();
        $x['termekfa3'] = $t->getTermekfa3Id();
        $x['termekmenu1nev'] = $t->getTermekmenu1Nev();
        $x['termekmenu1'] = $t->getTermekmenu1Id();

        $x['termekmenu1path'] = implode(' / ', $t->getTermekmenu1Path());

        $x['kepurl'] = $t->getKepurl();
        $x['kepurlsmall'] = $t->getKepurlSmall();
        $x['kepurlmedium'] = $t->getKepurlMedium();
        $x['kepurllarge'] = $t->getKepurlLarge();
        $x['kepleiras'] = $t->getKepleiras();
        $x['regikepurl'] = $t->getRegikepurl();
        $x['szelesseg'] = $t->getSzelesseg();
        $x['magassag'] = $t->getMagassag();
        $x['hosszusag'] = $t->getHosszusag();
        $x['suly'] = $t->getSuly();
        $x['osszehajthato'] = $t->getOsszehajthato();
        $x['megtekintesdb'] = $t->getMegtekintesdb();
        $x['megvasarlasdb'] = $t->getMegvasarlasdb();
        $x['nepszeruseg'] = $t->getNepszeruseg();
        $x['gyartonev'] = $t->getGyartoNev();
        $x['keszlet'] = $t->getKeszlet();
        $x['termekcsoportnev'] = $t->getTermekcsoportNev();
        $x['jogaalkalom'] = $t->getJogaalkalom();
        $x['jogaervenyesseg'] = $t->getJogaervenyesseg();
        $x['jogaelszamolasalap'] = $t->getJogaelszamolasalap();
        $x['jogaervenyessegnap'] = $t->getJogaervenyessegnap();
        if (\mkw\store::getSetupValue('termekvaltozat')) {
            foreach ($t->getValtozatok() as $tvaltozat) {
                $mozgasdb = $tvaltozat->getMozgasDb();
                if ($mozgasdb) {
                    $lvaltozat[] = $valtozatCtrl->loadVars($tvaltozat, $t);
                }
            }
            $x['valtozatkeszlet'] = $lvaltozat;
        }
        return $x;
    }

    /**
     * @param \Entities\Termek $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $oldnemkaphato = $obj->getNemkaphato();
        $vtsz = \mkw\store::getEm()->getRepository('Entities\Vtsz')->find($this->params->getIntRequestParam('vtsz'));
        if ($vtsz) {
            $obj->setVtsz($vtsz);
        }
        $afa = \mkw\store::getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afa'));
        if ($afa) {
            $obj->setAfa($afa);
        }
        $valt = \mkw\store::getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find($this->params->getIntRequestParam('valtozatadattipus'));
        if ($valt) {
            $obj->setValtozatadattipus($valt);
        } else {
            $obj->setValtozatadattipus(null);
        }
        $ck = \mkw\store::getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('gyarto'));
        if ($ck) {
            $obj->setGyarto($ck);
        } else {
            $obj->setGyarto(null);
        }
        $csoport = $this->getRepo('Entities\Termekcsoport')->find($this->params->getIntRequestParam('termekcsoport'));
        if ($csoport) {
            $obj->setTermekcsoport($csoport);
        } else {
            $obj->setTermekcsoport(null);
        }
        $me = \mkw\store::getEm()->getRepository('Entities\ME')->find($this->params->getIntRequestParam('me'));
        if ($me) {
            $obj->setMekod($me);
        }
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setNev2($this->params->getStringRequestParam('nev2'));
        $obj->setNev3($this->params->getStringRequestParam('nev3'));
        $obj->setNev4($this->params->getStringRequestParam('nev4'));
        $obj->setNev5($this->params->getStringRequestParam('nev5'));
        $obj->setKiirtnev($this->params->getStringRequestParam('kiirtnev'));
        $obj->setCikkszam($this->params->getStringRequestParam('cikkszam'));
        $obj->setIdegencikkszam($this->params->getStringRequestParam('idegencikkszam'));
        $obj->setVonalkod($this->params->getStringRequestParam('vonalkod'));
        $obj->setIdegenkod($this->params->getStringRequestParam('idegenkod'));
        $obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
        $obj->setRovidleiras($this->params->getStringRequestParam('rovidleiras'));
        $obj->setLeiras($this->params->getOriginalStringRequestParam('leiras'));
        $obj->setSeodescription($this->params->getStringRequestParam('seodescription'));
        $obj->setFeltoltheto($this->params->getBoolRequestParam('feltoltheto'));
        $obj->setFeltoltheto2($this->params->getBoolRequestParam('feltoltheto2'));
        $obj->setFeltoltheto3($this->params->getBoolRequestParam('feltoltheto3'));
        $obj->setFeltoltheto4($this->params->getBoolRequestParam('feltoltheto4'));
        $obj->setFeltoltheto5($this->params->getBoolRequestParam('feltoltheto5'));
        $obj->setLathato($this->params->getBoolRequestParam('lathato'));
        $obj->setLathato2($this->params->getBoolRequestParam('lathato2'));
        $obj->setLathato3($this->params->getBoolRequestParam('lathato3'));
        $obj->setLathato4($this->params->getBoolRequestParam('lathato4'));
        $obj->setLathato5($this->params->getBoolRequestParam('lathato5'));
        $obj->setLathato6($this->params->getBoolRequestParam('lathato6'));
        $obj->setLathato7($this->params->getBoolRequestParam('lathato7'));
        $obj->setLathato8($this->params->getBoolRequestParam('lathato8'));
        $obj->setLathato9($this->params->getBoolRequestParam('lathato9'));
        $obj->setLathato10($this->params->getBoolRequestParam('lathato10'));
        $obj->setLathato11($this->params->getBoolRequestParam('lathato11'));
        $obj->setLathato12($this->params->getBoolRequestParam('lathato12'));
        $obj->setLathato13($this->params->getBoolRequestParam('lathato13'));
        $obj->setLathato14($this->params->getBoolRequestParam('lathato14'));
        $obj->setLathato15($this->params->getBoolRequestParam('lathato15'));
        $obj->setHozzaszolas($this->params->getBoolRequestParam('hozzaszolas'));
        $obj->setAjanlott($this->params->getBoolRequestParam('ajanlott'));
        $obj->setKiemelt($this->params->getBoolRequestParam('kiemelt'));
        $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
        $obj->setEladhato($this->params->getBoolRequestParam('eladhato'));
        $obj->setEmagtiltva($this->params->getBoolRequestParam('emagtiltva'));
        $obj->setMozgat($this->params->getBoolRequestParam('mozgat'));
        $obj->setHparany($this->params->getFloatRequestParam('hparany'));
        $obj->setSzelesseg($this->params->getFloatRequestParam('szelesseg'));
        $obj->setMagassag($this->params->getFloatRequestParam('magassag'));
        $obj->setHosszusag($this->params->getFloatRequestParam('hosszusag'));
        $obj->setSuly($this->params->getFloatRequestParam('suly'));
        $obj->setOsszehajthato($this->params->getBoolRequestParam('osszehajthato'));
        if ($obj->getKepurl() != $this->params->getStringRequestParam('kepurl', '')) {
            $obj->setKepwcid(null);
        }
        $obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
        $obj->setKepleiras($this->params->getStringRequestParam('kepleiras', ''));
        $obj->setRegikepurl($this->params->getStringRequestParam('regikepurl', ''));
        $obj->setTermekexportbanszerepel($this->params->getBoolRequestParam('termekexportbanszerepel'));
        $obj->setNemkaphato($this->params->getBoolRequestParam('nemkaphato'));
        $obj->setFuggoben($this->params->getBoolRequestParam('fuggoben'));
        $obj->setSzallitasiido($this->params->getIntRequestParam('szallitasiido'));
        $obj->setKozvetitett($this->params->getBoolRequestParam('kozvetitett'));
        $obj->setMinboltikeszlet($this->params->getFloatRequestParam('minboltikeszlet'));
        $obj->setGarancia($this->params->getIntRequestParam('garancia'));
        $obj->setArukeresofanev($this->params->getStringRequestParam('arukeresofanev'));

        if (\mkw\store::isDarshan()) {
            $obj->setJogaalkalom($this->params->getIntRequestParam('jogaalkalom'));
            $obj->setJogaervenyesseg($this->params->getIntRequestParam('jogaervenyesseg'));
            $obj->setJogaelszamolasalap($this->params->getIntRequestParam('jogaelszamolasalap'));
            $obj->setJogaervenyessegnap($this->params->getIntRequestParam('jogaervenyessegnap'));
        }

        $farepo = \mkw\store::getEm()->getRepository(TermekFa::class);
        $fa = $farepo->find($this->params->getIntRequestParam('termekfa1'));
        if ($fa) {
            $obj->setTermekfa1($fa);
        } else {
            $obj->setTermekfa1(null);
        }
        $fa = $farepo->find($this->params->getIntRequestParam('termekfa2'));
        if ($fa) {
            $obj->setTermekfa2($fa);
        } else {
            $obj->setTermekfa2(null);
        }
        $fa = $farepo->find($this->params->getIntRequestParam('termekfa3'));
        if ($fa) {
            $obj->setTermekfa3($fa);
        } else {
            $obj->setTermekfa3(null);
        }
        $menurepo = \mkw\store::getEm()->getRepository(TermekMenu::class);
        $menu = $menurepo->find($this->params->getIntRequestParam('termekmenu1'));
        if ($menu) {
            $obj->setTermekmenu1($menu);
        } else {
            $obj->setTermekmenu1(null);
        }
        $obj->removeAllCimke();
        $cimkekpar = $this->params->getArrayRequestParam('cimkek');
        foreach ($cimkekpar as $cimkekod) {
            $cimke = $this->getEm()->getRepository('Entities\Termekcimketorzs')->find($cimkekod);
            if ($cimke) {
                $obj->addCimke($cimke);
            }
        }
        $obj->setBrutto($this->params->getNumRequestParam('brutto'));
        $obj->setNetto($this->params->getNumRequestParam('netto'));
        $obj->setAkciosnetto($this->params->getNumRequestParam('akciosnetto'));
        //$obj->setAkciosbrutto($this->params->getNumRequestParam('akciosbrutto'));
        $obj->setAkciostart($this->params->getStringRequestParam('akciostart'));
        $obj->setAkciostop($this->params->getStringRequestParam('akciostop'));
        $kepids = $this->params->getArrayRequestParam('kepid');
        foreach ($kepids as $kepid) {
            if ($this->params->getStringRequestParam('kepurl_' . $kepid, '') !== '') {
                $oper = $this->params->getStringRequestParam('kepoper_' . $kepid);
                if ($oper == 'add') {
                    $kep = new \Entities\TermekKep();
                    $obj->addTermekKep($kep);
                    $kep->setUrl($this->params->getStringRequestParam('kepurl_' . $kepid));
                    $kep->setLeiras($this->params->getStringRequestParam('kepleiras_' . $kepid));
                    $kep->setRejtett($this->params->getBoolRequestParam('keprejtett_' . $kepid));
                    $this->getEm()->persist($kep);
                } elseif ($oper == 'edit') {
                    /** @var TermekKep $kep */
                    $kep = \mkw\store::getEm()->getRepository('Entities\TermekKep')->find($kepid);
                    if ($kep) {
                        if ($kep->getUrl() !== $this->params->getStringRequestParam('kepurl_' . $kepid)) {
                            $kep->setWcid(null);
                        }
                        $kep->setUrl($this->params->getStringRequestParam('kepurl_' . $kepid));
                        $kep->setLeiras($this->params->getStringRequestParam('kepleiras_' . $kepid));
                        $kep->setRejtett($this->params->getBoolRequestParam('keprejtett_' . $kepid));
                        $this->getEm()->persist($kep);
                    }
                }
            }
        }

        $dokids = $this->params->getArrayRequestParam('dokid');
        foreach ($dokids as $dokid) {
            if (($this->params->getStringRequestParam('dokurl_' . $dokid, '') !== '') ||
                ($this->params->getStringRequestParam('dokpath_' . $dokid, '') !== '')) {
                $dokoper = $this->params->getStringRequestParam('dokoper_' . $dokid);
                if ($dokoper === 'add') {
                    $dok = new \Entities\TermekDok();
                    $obj->addTermekDok($dok);
                    $dok->setUrl($this->params->getStringRequestParam('dokurl_' . $dokid));
                    $dok->setPath($this->params->getStringRequestParam('dokpath_' . $dokid));
                    $dok->setLeiras($this->params->getStringRequestParam('dokleiras_' . $dokid));
                    $this->getEm()->persist($dok);
                } elseif ($dokoper === 'edit') {
                    $dok = \mkw\store::getEm()->getRepository('Entities\TermekDok')->find($dokid);
                    if ($dok) {
                        $dok->setUrl($this->params->getStringRequestParam('dokurl_' . $dokid));
                        $dok->setPath($this->params->getStringRequestParam('dokpath_' . $dokid));
                        $dok->setLeiras($this->params->getStringRequestParam('dokleiras_' . $dokid));
                        $this->getEm()->persist($dok);
                    }
                }
            }
        }

        if (\mkw\store::isArsavok()) {
            $arids = $this->params->getArrayRequestParam('arid');
            foreach ($arids as $arid) {
                $oper = $this->params->getStringRequestParam('aroper_' . $arid);
                $valutanem = $this->getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('arvalutanem_' . $arid));
                if (!$valutanem) {
                    $valutanem = $this->getEm()->getRepository(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
                }
                $arsav = $this->getEm()->getRepository(Arsav::class)->find($this->params->getIntRequestParam('arsav_' . $arid));
                if ($oper == 'add') {
                    $ar = new \Entities\TermekAr();
                    $obj->addTermekAr($ar);
                    $ar->setArsav($arsav);
                    $ar->setNetto($this->params->getNumRequestParam('arnetto_' . $arid));
                    $brutto = $this->params->getNumRequestParam('arbrutto_' . $arid);
                    if ($brutto != 0) {
                        $ar->setBrutto($brutto);
                    }
                    if ($valutanem) {
                        $ar->setValutanem($valutanem);
                    }
                    $this->getEm()->persist($ar);
                } elseif ($oper == 'edit') {
                    $ar = $this->getEm()->getRepository('Entities\TermekAr')->find($arid);
                    if ($ar) {
                        $ar->setArsav($arsav);
                        $ar->setNetto($this->params->getNumRequestParam('arnetto_' . $arid));
                        $brutto = $this->params->getNumRequestParam('arbrutto_' . $arid);
                        if ($brutto != 0) {
                            $ar->setBrutto($brutto);
                        }
                        if ($valutanem) {
                            $ar->setValutanem($valutanem);
                        }
                        $this->getEm()->persist($ar);
                    }
                }
            }
        }
        if (\mkw\store::isMultilang()) {
            $_tf = \Entities\Termek::getTranslatedFields();
            $translationids = $this->params->getArrayRequestParam('translationid');
            foreach ($translationids as $translationid) {
                $oper = $this->params->getStringRequestParam('translationoper_' . $translationid);
                $mezo = $this->params->getStringRequestParam('translationfield_' . $translationid);
                $mezotype = $_tf[$mezo]['type'];
                switch ($mezotype) {
                    case 1:
                    case 3:
                        $mezoertek = $this->params->getStringRequestParam('translationcontent_' . $translationid);
                        break;
                    case 2:
                        $mezoertek = $this->params->getOriginalStringRequestParam('translationcontent_' . $translationid);
                        break;
                    default:
                        $mezoertek = $this->params->getStringRequestParam('translationcontent_' . $translationid);
                        break;
                }
                if ($oper === 'add') {
                    $translation = new \Entities\TermekTranslation(
                        $this->params->getStringRequestParam('translationlocale_' . $translationid),
                        $mezo,
                        $mezoertek
                    );
                    $obj->addTranslation($translation);
                    $this->getEm()->persist($translation);
                } elseif ($oper === 'edit') {
                    $translation = $this->getEm()->getRepository('Entities\TermekTranslation')->find($translationid);
                    if ($translation) {
                        $translation->setLocale($this->params->getStringRequestParam('translationlocale_' . $translationid));
                        $translation->setField($mezo);
                        $translation->setContent($mezoertek);
                        $this->getEm()->persist($translation);
                    }
                }
            }
        }
        if (\mkw\store::getSetupValue('kapcsolodotermekek')) {
            $kapcsolodoids = $this->params->getArrayRequestParam('kapcsolodoid');
            foreach ($kapcsolodoids as $kapcsolodoid) {
                if (($this->params->getIntRequestParam('kapcsolodoaltermek_' . $kapcsolodoid) > 0)) {
                    $oper = $this->params->getStringRequestParam('kapcsolodooper_' . $kapcsolodoid);
                    $altermek = $this->getEm()->getRepository('Entities\Termek')->find(
                        $this->params->getIntRequestParam('kapcsolodoaltermek_' . $kapcsolodoid)
                    );
                    if ($oper == 'add') {
                        $kapcsolodo = new \Entities\TermekKapcsolodo();
                        $obj->addTermekKapcsolodo($kapcsolodo);
                        if ($altermek) {
                            $kapcsolodo->setAlTermek($altermek);
                        }
                        $this->getEm()->persist($kapcsolodo);
                    } elseif ($oper == 'edit') {
                        $kapcsolodo = $this->getEm()->getRepository('Entities\TermekKapcsolodo')->find($kapcsolodoid);
                        if ($kapcsolodo) {
                            if ($altermek) {
                                $kapcsolodo->setAlTermek($altermek);
                            }
                            $this->getEm()->persist($kapcsolodo);
                        }
                    }
                }
            }
        }
        if (\mkw\store::getSetupValue('receptura')) {
            $receptids = $this->params->getArrayRequestParam('receptid');
            foreach ($receptids as $receptid) {
                if (($this->params->getIntRequestParam('receptaltermek_' . $receptid) > 0)) {
                    $oper = $this->params->getStringRequestParam('receptoper_' . $receptid);
                    $altermek = $this->getEm()->getRepository('Entities\Termek')->find($this->params->getIntRequestParam('receptaltermek_' . $receptid));
                    $recepttipus = $this->getEm()->getRepository('Entities\TermekReceptTipus')->find(
                        $this->params->getIntRequestParam('recepttipus_' . $receptid)
                    );
                    if ($oper == 'add') {
                        $recept = new TermekRecept();
                        $obj->addTermekRecept($recept);
                        if ($altermek) {
                            $recept->setAlTermek($altermek);
                        }
                        if ($recepttipus) {
                            $recept->setTipus($recepttipus);
                        }
                        $recept->setMennyiseg($this->params->getFloatRequestParam('receptmennyiseg_' . $receptid));
                        $recept->setKotelezo($this->params->getBoolRequestParam('receptkotelezo_' . $receptid));
                        $this->getEm()->persist($recept);
                    } elseif ($oper == 'edit') {
                        $recept = $this->getEm()->getRepository('Entities\TermekRecept')->find($receptid);
                        if ($recept) {
                            if ($altermek) {
                                $recept->setAlTermek($altermek);
                            }
                            if ($recepttipus) {
                                $recept->setTipus($recepttipus);
                            }
                            $recept->setMennyiseg($this->params->getFloatRequestParam('receptmennyiseg_' . $receptid));
                            $recept->setKotelezo($this->params->getBoolRequestParam('receptkotelezo_' . $receptid));
                            $this->getEm()->persist($recept);
                        }
                    }
                }
            }
        }
        if (\mkw\store::getSetupValue('termekvaltozat')) {
            $valtozatids = $this->params->getArrayRequestParam('valtozatid');
            foreach ($valtozatids as $valtozatid) {
                $valtdb = 0;
                $oper = $this->params->getStringRequestParam('valtozatoper_' . $valtozatid);
                if ($oper == 'add') {
                    $valtozat = new TermekValtozat();
                    $obj->addValtozat($valtozat);
                    $valtozat->setLathato($this->params->getBoolRequestParam('valtozatlathato_' . $valtozatid));
                    $valtozat->setLathato2($this->params->getBoolRequestParam('valtozatlathato2_' . $valtozatid));
                    $valtozat->setLathato3($this->params->getBoolRequestParam('valtozatlathato3_' . $valtozatid));
                    $valtozat->setLathato4($this->params->getBoolRequestParam('valtozatlathato4_' . $valtozatid));
                    $valtozat->setLathato5($this->params->getBoolRequestParam('valtozatlathato5_' . $valtozatid));
                    $valtozat->setLathato6($this->params->getBoolRequestParam('valtozatlathato6_' . $valtozatid));
                    $valtozat->setLathato7($this->params->getBoolRequestParam('valtozatlathato7_' . $valtozatid));
                    $valtozat->setLathato8($this->params->getBoolRequestParam('valtozatlathato8_' . $valtozatid));
                    $valtozat->setLathato9($this->params->getBoolRequestParam('valtozatlathato9_' . $valtozatid));
                    $valtozat->setLathato10($this->params->getBoolRequestParam('valtozatlathato10_' . $valtozatid));
                    $valtozat->setLathato11($this->params->getBoolRequestParam('valtozatlathato11_' . $valtozatid));
                    $valtozat->setLathato12($this->params->getBoolRequestParam('valtozatlathato12_' . $valtozatid));
                    $valtozat->setLathato13($this->params->getBoolRequestParam('valtozatlathato13_' . $valtozatid));
                    $valtozat->setLathato14($this->params->getBoolRequestParam('valtozatlathato14_' . $valtozatid));
                    $valtozat->setLathato15($this->params->getBoolRequestParam('valtozatlathato15_' . $valtozatid));

                    $valtozat->setElerheto($this->params->getBoolRequestParam('valtozatelerheto_' . $valtozatid));
                    $valtozat->setElerheto2($this->params->getBoolRequestParam('valtozatelerheto2_' . $valtozatid));
                    $valtozat->setElerheto3($this->params->getBoolRequestParam('valtozatelerheto3_' . $valtozatid));
                    $valtozat->setElerheto4($this->params->getBoolRequestParam('valtozatelerheto4_' . $valtozatid));
                    $valtozat->setElerheto5($this->params->getBoolRequestParam('valtozatelerheto5_' . $valtozatid));
                    $valtozat->setElerheto6($this->params->getBoolRequestParam('valtozatelerheto6_' . $valtozatid));
                    $valtozat->setElerheto7($this->params->getBoolRequestParam('valtozatelerheto7_' . $valtozatid));
                    $valtozat->setElerheto8($this->params->getBoolRequestParam('valtozatelerheto8_' . $valtozatid));
                    $valtozat->setElerheto9($this->params->getBoolRequestParam('valtozatelerheto9_' . $valtozatid));
                    $valtozat->setElerheto10($this->params->getBoolRequestParam('valtozatelerheto10_' . $valtozatid));
                    $valtozat->setElerheto11($this->params->getBoolRequestParam('valtozatelerheto11_' . $valtozatid));
                    $valtozat->setElerheto12($this->params->getBoolRequestParam('valtozatelerheto12_' . $valtozatid));
                    $valtozat->setElerheto13($this->params->getBoolRequestParam('valtozatelerheto13_' . $valtozatid));
                    $valtozat->setElerheto14($this->params->getBoolRequestParam('valtozatelerheto14_' . $valtozatid));
                    $valtozat->setElerheto15($this->params->getBoolRequestParam('valtozatelerheto15_' . $valtozatid));
//						$valtozat->setBrutto($this->params->getNumRequestParam('valtozatbrutto_'.$valtozatid));
                    $valtozat->setNetto($this->params->getNumRequestParam('valtozatnetto_' . $valtozatid));
                    $valtozat->setTermekfokep($this->params->getBoolRequestParam('valtozattermekfokep_' . $valtozatid));
                    $valtozat->setCikkszam($this->params->getStringRequestParam('valtozatcikkszam_' . $valtozatid));
                    $valtozat->setIdegencikkszam($this->params->getStringRequestParam('valtozatidegencikkszam_' . $valtozatid));
                    $valtozat->setVonalkod($this->params->getStringRequestParam('valtozatvonalkod_' . $valtozatid));
                    $valtozat->setBeerkezesdatum($this->params->getStringRequestParam('valtozatbeerkezesdatum_' . $valtozatid));

                    $at = $this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find(
                        $this->params->getIntRequestParam('valtozatadattipus1_' . $valtozatid)
                    );
                    $valtert = $this->params->getStringRequestParam('valtozatertek1_' . $valtozatid);
                    if ($at && $valtert) {
                        $valtozat->setAdatTipus1($at);
                        $valtozat->setErtek1($valtert);
                        $valtdb++;
                    }

                    $at = $this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find(
                        $this->params->getIntRequestParam('valtozatadattipus2_' . $valtozatid)
                    );
                    $valtert = $this->params->getStringRequestParam('valtozatertek2_' . $valtozatid);
                    if ($at && $valtert) {
                        $valtozat->setAdatTipus2($at);
                        $valtozat->setErtek2($valtert);
                        $valtdb++;
                    }

                    $at = $this->getEm()->getRepository('Entities\TermekKep')->find($this->params->getIntRequestParam('valtozatkepid_' . $valtozatid));
                    if ($at) {
                        $valtozat->setKep($at);
                    }

                    if ($valtdb > 0) {
                        $this->getEm()->persist($valtozat);
                    } else {
                        $obj->removeValtozat($valtozat);
                    }
                } elseif ($oper == 'edit') {
                    $valtozat = $this->getEm()->getRepository('Entities\TermekValtozat')->find($valtozatid);
                    if ($valtozat) {
                        $valtozat->setLathato($this->params->getBoolRequestParam('valtozatlathato_' . $valtozatid));
                        $valtozat->setLathato2($this->params->getBoolRequestParam('valtozatlathato2_' . $valtozatid));
                        $valtozat->setLathato3($this->params->getBoolRequestParam('valtozatlathato3_' . $valtozatid));
                        $valtozat->setLathato4($this->params->getBoolRequestParam('valtozatlathato4_' . $valtozatid));
                        $valtozat->setLathato5($this->params->getBoolRequestParam('valtozatlathato5_' . $valtozatid));
                        $valtozat->setLathato6($this->params->getBoolRequestParam('valtozatlathato6_' . $valtozatid));
                        $valtozat->setLathato7($this->params->getBoolRequestParam('valtozatlathato7_' . $valtozatid));
                        $valtozat->setLathato8($this->params->getBoolRequestParam('valtozatlathato8_' . $valtozatid));
                        $valtozat->setLathato9($this->params->getBoolRequestParam('valtozatlathato9_' . $valtozatid));
                        $valtozat->setLathato10($this->params->getBoolRequestParam('valtozatlathato10_' . $valtozatid));
                        $valtozat->setLathato11($this->params->getBoolRequestParam('valtozatlathato11_' . $valtozatid));
                        $valtozat->setLathato12($this->params->getBoolRequestParam('valtozatlathato12_' . $valtozatid));
                        $valtozat->setLathato13($this->params->getBoolRequestParam('valtozatlathato13_' . $valtozatid));
                        $valtozat->setLathato14($this->params->getBoolRequestParam('valtozatlathato14_' . $valtozatid));
                        $valtozat->setLathato15($this->params->getBoolRequestParam('valtozatlathato15_' . $valtozatid));

                        $valtozat->setElerheto($this->params->getBoolRequestParam('valtozatelerheto_' . $valtozatid));
                        $valtozat->setElerheto2($this->params->getBoolRequestParam('valtozatelerheto2_' . $valtozatid));
                        $valtozat->setElerheto3($this->params->getBoolRequestParam('valtozatelerheto3_' . $valtozatid));
                        $valtozat->setElerheto4($this->params->getBoolRequestParam('valtozatelerheto4_' . $valtozatid));
                        $valtozat->setElerheto5($this->params->getBoolRequestParam('valtozatelerheto5_' . $valtozatid));
                        $valtozat->setElerheto6($this->params->getBoolRequestParam('valtozatelerheto6_' . $valtozatid));
                        $valtozat->setElerheto7($this->params->getBoolRequestParam('valtozatelerheto7_' . $valtozatid));
                        $valtozat->setElerheto8($this->params->getBoolRequestParam('valtozatelerheto8_' . $valtozatid));
                        $valtozat->setElerheto9($this->params->getBoolRequestParam('valtozatelerheto9_' . $valtozatid));
                        $valtozat->setElerheto10($this->params->getBoolRequestParam('valtozatelerheto10_' . $valtozatid));
                        $valtozat->setElerheto11($this->params->getBoolRequestParam('valtozatelerheto11_' . $valtozatid));
                        $valtozat->setElerheto12($this->params->getBoolRequestParam('valtozatelerheto12_' . $valtozatid));
                        $valtozat->setElerheto13($this->params->getBoolRequestParam('valtozatelerheto13_' . $valtozatid));
                        $valtozat->setElerheto14($this->params->getBoolRequestParam('valtozatelerheto14_' . $valtozatid));
                        $valtozat->setElerheto15($this->params->getBoolRequestParam('valtozatelerheto15_' . $valtozatid));
//							$valtozat->setBrutto($this->params->getNumRequestParam('valtozatbrutto_'.$valtozatid));
                        $valtozat->setNetto($this->params->getNumRequestParam('valtozatnetto_' . $valtozatid));
                        $valtozat->setTermekfokep($this->params->getBoolRequestParam('valtozattermekfokep_' . $valtozatid));
                        $valtozat->setCikkszam($this->params->getStringRequestParam('valtozatcikkszam_' . $valtozatid));
                        $valtozat->setIdegencikkszam($this->params->getStringRequestParam('valtozatidegencikkszam_' . $valtozatid));
                        $valtozat->setVonalkod($this->params->getStringRequestParam('valtozatvonalkod_' . $valtozatid));
                        $valtozat->setBeerkezesdatum($this->params->getStringRequestParam('valtozatbeerkezesdatum_' . $valtozatid));

                        $at = $this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find(
                            $this->params->getIntRequestParam('valtozatadattipus1_' . $valtozatid)
                        );
                        $valtert = $this->params->getStringRequestParam('valtozatertek1_' . $valtozatid);
                        if ($at && $valtert) {
                            $valtozat->setAdatTipus1($at);
                            $valtozat->setErtek1($valtert);
                        } else {
                            $valtozat->setAdatTipus1(null);
                            $valtozat->setErtek1(null);
                        }

                        $at = $this->getEm()->getRepository('Entities\TermekValtozatAdatTipus')->find(
                            $this->params->getIntRequestParam('valtozatadattipus2_' . $valtozatid)
                        );
                        $valtert = $this->params->getStringRequestParam('valtozatertek2_' . $valtozatid);
                        if ($at && $valtert) {
                            $valtozat->setAdatTipus2($at);
                            $valtozat->setErtek2($valtert);
                        } else {
                            $valtozat->setAdatTipus2(null);
                            $valtozat->setErtek2(null);
                        }

                        if ($valtozat->getTermekfokep()) {
                            $valtozat->setKep(null);
                        } else {
                            $at = $this->getEm()->getRepository('Entities\TermekKep')->find($this->params->getIntRequestParam('valtozatkepid_' . $valtozatid));
                            if ($at) {
                                $valtozat->setKep($at);
                            } else {
                                $valtozat->setKep(null);
                            }
                        }

                        $this->getEm()->persist($valtozat);
                    }
                }
            }
            $valtozatmbkids = $this->params->getArrayRequestParam('valtozatminboltikeszletid');
            foreach ($valtozatmbkids as $vmbkid) {
                /** @var TermekValtozat $valtozat */
                $valtozat = $this->getEm()->getRepository(TermekValtozat::class)->find($vmbkid);
                if ($valtozat) {
                    $valtozat->setMinboltikeszlet($this->params->getNumRequestParam('valtozatminboltikeszlet_' . $vmbkid));
                    $this->getEm()->persist($valtozat);
                }
            }
        }
        $this->kaphatolett = $oldnemkaphato && !$obj->getNemkaphato();
        $obj->doStuffOnPrePersist();  // ha csak kapcsolódó adat változott, akkor prepresist/preupdate nem hívódik, de cimke gyorsítás miatt nekünk kell
        return $obj;
    }

    /**
     * @param Termek $o
     * @param $parancs
     *
     * @return void
     */
    protected function afterSave($o, $parancs = null)
    {
        if ($this->kaphatolett) {
            $tec = new termekertesitoController($this->params);
            $tec->sendErtesito($o);
        }
        switch ($parancs) {
            case $this->addOperation:
            case $this->editOperation:
                $tvec = new termekvaltozatertekController(null);
                $tvec->uploadToWc();
                //$tcc = new termekcimkeController(null);
                //$tcc->uploadToWc();
                $o->clearWcdate();
                $o->uploadToWc();
        }
        if ($parancs == $this->delOperation) {
            $o->deleteFromWc();
        }
        parent::afterSave($o, $parancs);
    }

    public function getlistbody()
    {
        $view = $this->createView('termeklista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('gyartofilter', null))) {
            $filter->addFilter('gyarto', '=', $this->params->getIntRequestParam('gyartofilter'));
        }
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter(['nev', 'rovidleiras', 'cikkszam', 'vonalkod', 'idegencikkszam'],
                'LIKE',
                '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        if (!is_null($this->params->getRequestParam('kepurlfilter', null))) {
            $filter->addFilter(['kepurl'], 'LIKE', '%' . $this->params->getStringRequestParam('kepurlfilter') . '%');
        }
        $f = $this->params->getNumRequestParam('lathatofilter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato2filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato2', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato3filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato3', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato4filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato4', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato5filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato5', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato6filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato6', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato7filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato7', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato8filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato8', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato9filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato9', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato10filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato10', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato11filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato11', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato12filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato12', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato13filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato13', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato14filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato14', '=', $f);
        }
        $f = $this->params->getNumRequestParam('lathato15filter', 9);
        if ($f != 9) {
            $filter->addFilter('lathato15', '=', $f);
        }
        $f = $this->params->getNumRequestParam('nemkaphatofilter', 9);
        if ($f != 9) {
            $filter->addFilter('nemkaphato', '=', $f);
        }
        $f = $this->params->getNumRequestParam('fuggobenfilter', 9);
        if ($f != 9) {
            $filter->addFilter('fuggoben', '=', $f);
        }
        $f = $this->params->getNumRequestParam('inaktivfilter', 9);
        if ($f != 9) {
            $filter->addFilter('inaktiv', '=', $f);
        }
        $f = $this->params->getNumRequestParam('ajanlottfilter', 9);
        if ($f != 9) {
            $filter->addFilter('ajanlott', '=', $f);
        }
        $f = $this->params->getNumRequestParam('kiemeltfilter', 9);
        if ($f != 9) {
            $filter->addFilter('kiemelt', '=', $f);
        }
        switch ($this->params->getNumRequestParam('akciosfilter', 9)) {
            case 1:
                $filter->addSql(
                    '(((_xx.akciostart IS NOT NULL) AND (_xx.akciostart <> \'\')) OR ((_xx.akciostop IS NOT NULL) AND (_xx.akciostart <> \'\')))' .
                    ' AND ' .
                    '((_xx.akciostart <= CURDATE()) AND (_xx.akciostop >= CURDATE()) OR ((_xx.akciostart <= CURDATE()) AND ((_xx.akciostop IS NULL) OR (_xx.akciostop = \'\'))) OR ((_xx.akciostart IS NULL) OR (_xx.akciostart = \'\')) AND (_xx.akciostop <= CURDATE()))'
                );
                break;
            case 0:
                $filter->addSql(
                    '(((_xx.akciostart IS NULL) OR (_xx.akciostart=\'\')) AND ((_xx.akciostop IS NULL) OR (_xx.akciostart=\'\'))) OR (_xx.akciostart>=CURDATE()) OR (_xx.akciostop<=CURDATE())'
                );
                break;
        }

        $fv = $this->params->getArrayRequestParam('cimkefilter');
        if (!empty($fv)) {
            $res = \mkw\store::getEm()->getRepository('Entities\Termekcimketorzs')->getTermekIdsWithCimke($fv);
            $cimkefilter = [];
            foreach ($res as $sor) {
                $cimkefilter[] = $sor['id'];
            }
            if ($cimkefilter) {
                $filter->addFilter('id', 'IN', $cimkefilter);
            } else {
                $filter->addFilter('id', '=', false);
            }
        }

        $fv = $this->params->getArrayRequestParam('fafilter');
        if (!empty($fv)) {
            $ff = new \mkwhelpers\FilterDescriptor();
            $ff->addFilter('id', 'IN', $fv);
            $res = \mkw\store::getEm()->getRepository(TermekFa::class)->getAll($ff, []);
            $faszuro = [];
            foreach ($res as $sor) {
                $faszuro[] = $sor->getKarkod() . '%';
            }
            $filter->addFilter(['_xx.termekfa1karkod', '_xx.termekfa2karkod', '_xx.termekfa3karkod'], 'LIKE', $faszuro);
        }

        $fv = $this->params->getArrayRequestParam('menufilter');
        if (!empty($fv)) {
            $ff = new \mkwhelpers\FilterDescriptor();
            $ff->addFilter('id', 'IN', $fv);
            $res = \mkw\store::getEm()->getRepository(TermekMenu::class)->getAll($ff, []);
            $faszuro = [];
            foreach ($res as $sor) {
                $faszuro[] = $sor->getKarkod() . '%';
            }
            $filter->addFilter(['_xx.termekmenu1karkod'], 'LIKE', $faszuro);
        }

        $this->vanshowarsav = false;

        if (!\mkw\store::isArsavok()) {
            $this->initPager($this->getRepo()->getCount($filter));
            $egyedek = $this->getRepo()->getWithJoins(
                $filter,
                $this->getOrderArray(),
                $this->getPager()->getOffset(),
                $this->getPager()->getElemPerPage()
            );
        } else {
            $showarsav = \mkw\store::getParameter(\mkw\consts::ShowTermekArsav);
            $showarsavvalutanem = \mkw\store::getParameter(\mkw\consts::ShowTermekArsavValutanem);
            if ($showarsav && $showarsavvalutanem) {
                $this->vanshowarsav = true;
                $this->initPager($this->getRepo()->getCount($filter));
                $egyedek = $this->getRepo()->getWithAr(
                    $showarsav,
                    $showarsavvalutanem,
                    $filter,
                    $this->getOrderArray(),
                    $this->getPager()->getOffset(),
                    $this->getPager()->getElemPerPage()
                );
            } else {
                $this->initPager($this->getRepo()->getCount($filter));
                $egyedek = $this->getRepo()->getWithJoins(
                    $filter,
                    $this->getOrderArray(),
                    $this->getPager()->getOffset(),
                    $this->getPager()->getElemPerPage()
                );
            }
        }

        echo json_encode($this->loadDataToView($egyedek, 'termeklista', $view));
    }

    public function getSelectList($selid = null)
    {
        // TODO sok termek eseten lassu lehet
        $rec = $this->getRepo()->getAllForSelectList([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor['id'],
                'caption' => $sor['nev'],
                'selected' => ($sor['id'] == $selid)
            ];
        }
        return $res;
    }

    public function getEladhatoSelectList($selid = null)
    {
        // TODO sok termek eseten lassu lehet
        $filter = new FilterDescriptor();
        $filter->addFilter('eladhato', '=', true);
        $rec = $this->getRepo()->getAllForSelectList($filter, ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor['id'],
                'caption' => $sor['nev'],
                'selected' => ($sor['id'] == $selid)
            ];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAllForSelectList([], ['nev' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor['id'] . '">' . $sor['nev'] . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function getValtozatList($termekid, $sel, $raktarid = null)
    {
        $ret = [];
        if ($termekid) {
            $termek = $this->getRepo()->findWithJoins($termekid);
            if ($termek) {
                $valtozatok = $termek->getValtozatok();
                if ($valtozatok) {
                    foreach ($valtozatok as $valt) {
                        $ret[] = [
                            'id' => $valt->getId(),
                            'caption' => $valt->getNev(),
                            'selected' => $sel == $valt->getId(),
                            'elerheto' => $valt->getXElerheto(),
                            'keszlet' => $valt->getKeszlet(null, $raktarid) * 1
                        ];
                    }
                }
            }
        }
        return $ret;
    }

    public function getMeretSzinhez()
    {
        $ret = [];
        $merettip = \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret);
        $termekid = $this->params->getIntRequestParam('t');
        $szin = $this->params->getStringRequestParam('sz');
        if ($termekid) {
            $valtozatok = $this->getRepo('Entities\TermekValtozat')->getOtherProperties(
                $termekid,
                [\mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)],
                [$szin]
            );
            /** @var \Entities\TermekValtozat $valt */
            foreach ($valtozatok as $valt) {
                $caption = '';
                if ($valt->getAdatTipus1Id() == $merettip) {
                    $caption = $valt->getErtek1();
                } elseif ($valt->getAdatTipus2Id() == $merettip) {
                    $caption = $valt->getErtek2();
                }
                $ret[] = [
                    'id' => $valt->getId(),
                    'caption' => $caption,
                    'selected' => false,
                    'keszlet' => $valt->getKeszlet() - $valt->getFoglaltMennyiseg() - $valt->calcMinboltikeszlet()
                ];
            }
        }

        $s = \mkw\store::getParameter(\mkw\consts::ValtozatSorrend);
        $sorrend = explode(',', $s);
        uasort($ret, function ($e, $f) use ($sorrend) {
            $ertek = $e['caption'];
            $ve = array_search($ertek, $sorrend);
            if ($ve === false) {
                $ve = 0;
            }
            $ve = str_pad((string)$ve, 6, '0', STR_PAD_LEFT);

            $ertek = $f['caption'];
            $vf = array_search($ertek, $sorrend);
            if ($vf === false) {
                $vf = 0;
            }
            $vf = str_pad((string)$vf, 6, '0', STR_PAD_LEFT);

            if ($ve === $vf) {
                return 0;
            }
            return ($ve < $vf) ? -1 : 1;
        });

        $v = $this->getTemplateFactory()->createMainView('meretselect.tpl');
        $v->setVar('sizes', $ret);
        $v->setVar('meretek', $ret);
        $v->setVar('termekid', $termekid);
        echo $v->getTemplateResult();
    }

    public function getKapcsolodoSelectList()
    {
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = [];
        if ($term) {
            $filter = new FilterDescriptor();
            $filter->addFilter(['_xx.nev', '_xx.cikkszam', '_xx.vonalkod'], 'LIKE', '%' . $term . '%');
            $r = \mkw\store::getEm()->getRepository('\Entities\Termek');
            $res = $r->getAllForSelectList($filter);
            foreach ($res as $r) {
                $ret[] = [
                    'id' => $r['id'],
                    'value' => $r['nev']
                ];
            }
        }
        echo json_encode($ret);
    }

    public function getBizonylattetelSelectList()
    {
        $ret = [];
        if (!\mkw\store::isTermekAutocomplete()) {
            $termekid = $this->params->getIntRequestParam('id');
            /** @var \Entities\Termek $termek */
            $termek = \mkw\store::getEm()->getRepository('\Entities\Termek')->find($termekid);
            if ($termek) {
                $ret = [
                    'value' => $termek->getNev(),
                    'id' => $termek->getId(),
                    'me' => $termek->getMekodId(),
                    'cikkszam' => $termek->getCikkszam(),
                    'vtsz' => $termek->getVtszId(),
                    'afa' => $termek->getAfaId(),
                    'afakulcs' => $termek->getAfa()->getErtek(),
                    'kozepeskepurl' => $termek->getKepUrlMedium(),
                    'kiskepurl' => $termek->getKepUrlSmall(),
                    'kepurl' => $termek->getKepUrlLarge(),
                    'slug' => $termek->getSlug(),
                    'link' => \mkw\store::getRouter()->generate('showtermek', \mkw\store::getConfigValue('mainurl'), ['slug' => $termek->getSlug()]),
                    'mainurl' => \mkw\store::getConfigValue('mainurl'),
                    'nemlathato' => (!$termek->getXLathato() || $termek->getInaktiv() || $termek->getNemkaphato()),
                    'defaultmennyiseg' => \mkw\store::getParameter(\mkw\consts::BizonylatMennyiseg, 0),
                    'kartonurl' => \mkw\store::getRouter()->generate('admintermekkartonview', false, [], ['id' => $termek->getId()])
                ];
                if ($termek->getKiirtnev()) {
                    $ret['value'] = $termek->getKiirtnev();
                }
            }
        } else {
            $term = trim($this->params->getStringRequestParam('term'));
            if ($term) {
                $r = \mkw\store::getEm()->getRepository('\Entities\Termek');
                $res = $r->getBizonylattetelLista($term);
                switch (true) {
                    case \mkw\store::isMindentkapni():
                        foreach ($res as $r) {
                            $x = [
                                'value' => $r->getNev(),
                                'id' => $r->getId(),
                                'me' => $r->getMekodId(),
                                'cikkszam' => $r->getCikkszam(),
                                'vtsz' => $r->getVtszId(),
                                'afa' => $r->getAfaId(),
                                'afakulcs' => $r->getAfa()->getErtek(),
                                'kozepeskepurl' => $r->getKepUrlMedium(),
                                'kiskepurl' => $r->getKepUrlSmall(),
                                'kepurl' => $r->getKepUrlLarge(),
                                'slug' => $r->getSlug(),
                                'link' => \mkw\store::getRouter()->generate('showtermek', \mkw\store::getConfigValue('mainurl'), ['slug' => $r->getSlug()]),
                                'mainurl' => \mkw\store::getConfigValue('mainurl'),
                                'nemlathato' => (!$r->getXLathato() || $r->getInaktiv() || $r->getNemkaphato()),
                                'defaultmennyiseg' => \mkw\store::getParameter(\mkw\consts::BizonylatMennyiseg, 0),
                                'kartonurl' => \mkw\store::getRouter()->generate('admintermekkartonview', false, [], ['id' => $r->getId()])
                            ];
                            if ($r->getKiirtnev()) {
                                $x['value'] = $r->getKiirtnev();
                            }
                            $ret[] = $x;
                        }
                        break;
                    case \mkw\store::isSuperzoneB2B():
                        foreach ($res as $r) {
                            $x = [
                                'value' => $r->getNev(),
                                'label' => $r->getCikkszam() . ' ' . $r->getNev(),
                                'id' => $r->getId(),
                                'me' => $r->getMekodId(),
                                'cikkszam' => $r->getCikkszam(),
                                'vtsz' => $r->getVtszId(),
                                'afa' => $r->getAfaId(),
                                'afakulcs' => $r->getAfa()->getErtek(),
                                'kozepeskepurl' => $r->getKepUrlMedium(),
                                'kiskepurl' => $r->getKepUrlSmall(),
                                'kepurl' => $r->getKepUrlLarge(),
                                'slug' => $r->getSlug(),
                                'link' => \mkw\store::getRouter()->generate('showtermek', \mkw\store::getConfigValue('mainurl'), ['slug' => $r->getSlug()]),
                                'mainurl' => \mkw\store::getConfigValue('mainurl'),
                                'nemlathato' => (!$r->getXLathato() || $r->getInaktiv() || $r->getNemkaphato()),
                                'defaultmennyiseg' => \mkw\store::getParameter(\mkw\consts::BizonylatMennyiseg, 0),
                                'kartonurl' => \mkw\store::getRouter()->generate('admintermekkartonview', false, [], ['id' => $r->getId()])
                            ];
                            if ($r->getKiirtnev()) {
                                $x['value'] = $r->getKiirtnev();
                            }
                            $ret[] = $x;
                        }
                        break;
                    default:
                        foreach ($res as $r) {
                            $x = [
                                'value' => $r->getNev(),
                                'id' => $r->getId(),
                                'me' => $r->getMekodId(),
                                'cikkszam' => $r->getCikkszam(),
                                'vtsz' => $r->getVtszId(),
                                'afa' => $r->getAfaId(),
                                'afakulcs' => $r->getAfa()->getErtek(),
                                'kozepeskepurl' => $r->getKepUrlMedium(),
                                'kiskepurl' => $r->getKepUrlSmall(),
                                'kepurl' => $r->getKepUrlLarge(),
                                'slug' => $r->getSlug(),
                                'link' => \mkw\store::getRouter()->generate('showtermek', \mkw\store::getConfigValue('mainurl'), ['slug' => $r->getSlug()]),
                                'mainurl' => \mkw\store::getConfigValue('mainurl'),
                                'nemlathato' => (!$r->getXLathato() || $r->getInaktiv() || $r->getNemkaphato()),
                                'defaultmennyiseg' => \mkw\store::getParameter(\mkw\consts::BizonylatMennyiseg, 0),
                                'kartonurl' => \mkw\store::getRouter()->generate('admintermekkartonview', false, [], ['id' => $r->getId()])
                            ];
                            if ($r->getKiirtnev()) {
                                $x['value'] = $r->getKiirtnev();
                            }
                            $ret[] = $x;
                        }
                        break;
                }
            }
        }
        echo json_encode($ret);
    }

    public function viewlist()
    {
        $view = $this->createView('termeklista.tpl');
        $view->setVar('pagetitle', t('Termékek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $tcc = new termekcimkekatController($this->params);
        $view->setVar('cimkekat', $tcc->getWithCimkek(null));
        $gyarto = new partnerController($this->params);
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList(0));
        $tcs = new termekcsoportController($this->params);
        $view->setVar('termekcsoportlist', $tcs->getSelectList());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Termék'));
        $view->setVar('oper', $oper);

        $termek = $this->getRepo()->findWithJoins($id);
        // LoadVars utan nem abc sorrendben adja vissza
        $tcc = new termekcimkekatController($this->params);
        $cimkek = $termek ? $termek->getAllCimkeId() : null;
        $view->setVar('cimkekat', $tcc->getWithCimkek($cimkek));

        $view->setVar('egyed', $this->loadVars($termek, true));

        $vtsz = new vtszController($this->params);
        $view->setVar('vtszlist', $vtsz->getSelectList(($termek ? $termek->getVtszId() : 0)));

        $afa = new afaController($this->params);
        $view->setVar('afalist', $afa->getSelectList(($termek ? $termek->getAfaId() : 0)));

        $valtozatadattipus = new termekvaltozatadattipusController($this->params);
        $view->setVar('valtozatadattipuslist', $valtozatadattipus->getSelectList(($termek ? $termek->getValtozatadattipusId() : 0)));

        $kep = new termekkepController($this->params);
        $view->setVar('keplist', $kep->getSelectList($termek, null));

        $gyarto = new partnerController($this->params);
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList(($termek ? $termek->getGyartoId() : 0)));

        $csoport = new termekcsoportController($this->params);
        $view->setVar('termekcsoportlist', $csoport->getSelectList(($termek ? $termek->getTermekcsoportId() : 0)));

        $me = new meController($this->params);
        $view->setVar('melist', $me->getSelectList(($termek ? $termek->getMekodId() : 0)));

        $view->printTemplateResult();
    }

    public function setflag()
    {
        $kaphatolett = false;
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag');
        /** @var \Entities\Termek $obj */
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($flag) {
                case 'inaktiv':
                    $obj->setInaktiv($kibe);
                    break;
                case 'feltoltheto':
                    $obj->setFeltoltheto($kibe);
                    break;
                case 'feltoltheto2':
                    $obj->setFeltoltheto2($kibe);
                    break;
                case 'feltoltheto3':
                    $obj->setFeltoltheto3($kibe);
                    break;
                case 'feltoltheto4':
                    $obj->setFeltoltheto4($kibe);
                    break;
                case 'feltoltheto5':
                    $obj->setFeltoltheto5($kibe);
                    break;
                case 'lathato':
                    $obj->setLathato($kibe);
                    break;
                case 'lathato2':
                    $obj->setLathato2($kibe);
                    break;
                case 'lathato3':
                    $obj->setLathato3($kibe);
                    break;
                case 'lathato4':
                    $obj->setLathato4($kibe);
                    break;
                case 'lathato5':
                    $obj->setLathato5($kibe);
                    break;
                case 'lathato6':
                    $obj->setLathato6($kibe);
                    break;
                case 'lathato7':
                    $obj->setLathato7($kibe);
                    break;
                case 'lathato8':
                    $obj->setLathato8($kibe);
                    break;
                case 'lathato9':
                    $obj->setLathato9($kibe);
                    break;
                case 'lathato10':
                    $obj->setLathato10($kibe);
                    break;
                case 'lathato11':
                    $obj->setLathato11($kibe);
                    break;
                case 'lathato12':
                    $obj->setLathato12($kibe);
                    break;
                case 'lathato13':
                    $obj->setLathato13($kibe);
                    break;
                case 'lathato14':
                    $obj->setLathato14($kibe);
                    break;
                case 'lathato15':
                    $obj->setLathato15($kibe);
                    break;
                case 'ajanlott':
                    $obj->setAjanlott($kibe);
                    break;
                case 'hozzaszolas':
                    $obj->setHozzaszolas($kibe);
                    break;
                case 'mozgat':
                    $obj->setMozgat($kibe);
                    break;
                case 'kiemelt':
                    $obj->setKiemelt($kibe);
                    break;
                case 'nemkaphato':
                    $oldnemkaphato = $obj->getNemkaphato();
                    $obj->setNemkaphato($kibe);
                    $kaphatolett = $oldnemkaphato && !$obj->getNemkaphato();
                    if ($obj->getNemkaphato()) {
                        $obj->setAjanlott(false);
                        $obj->setKiemelt(false);
                        $valtozatok = $obj->getValtozatok();
                        foreach ($valtozatok as $valt) {
                            $valt->setElerheto(false);
                            $this->getEm()->persist($valt);
                        }
                    }
                    break;
                case 'fuggoben':
                    $obj->setFuggoben($kibe);
                    break;
                case 'termekexportbanszerepel':
                    $obj->setTermekexportbanszerepel($kibe);
                    break;
                case 'eladhato':
                    $obj->setEladhato($kibe);
                    break;
                case 'emagtiltva':
                    $obj->setEmagtiltva($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
            $obj->uploadToWC();
            if ($kaphatolett) {
                $tec = new termekertesitoController($this->params);
                $tec->sendErtesito($obj);
            }
        }
    }

    public function getbrutto()
    {
        $id = $this->params->getIntRequestParam('id');
        $netto = $this->params->getFloatRequestParam('value');
        $afa = $this->getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afakod'));
        if (!$afa) {
            $termek = $this->getRepo()->find($id);
            if ($termek) {
                $afa = $termek->getAfa();
            }
        }
        if ($afa) {
            echo $afa->calcBrutto($netto);
        } else {
            echo $netto;
        }
    }

    public function getnetto()
    {
        $id = $this->params->getIntRequestParam('id');
        $brutto = $this->params->getFloatRequestParam('value');
        $afa = $this->getEm()->getRepository('Entities\Afa')->find($this->params->getIntRequestParam('afakod'));
        if (!$afa) {
            $termek = $this->getRepo()->find($id);
            if ($termek) {
                $afa = $termek->getAfa();
            }
        }
        if ($afa) {
            echo $afa->calcNetto($brutto);
        } else {
            echo $brutto;
        }
    }

    /**
     * @param Termek $termek
     *
     * @return array
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getTermekLap($termek)
    {
        $ujtermekminid = $this->getRepo()->getUjTermekId();
        $top10min = $this->getRepo()->getTop10Mennyiseg();

        $tfc = new termekfaController($this->params);

        $ret = [];

        if ($termek->getTermekfa1()) {
            $ret['navigator'] = $tfc->getNavigator($termek->getTermekfa1(), true);
        } else {
            $ret['navigator'] = [];
        }
        $ret['termek'] = $termek->toTermekLap(null, $ujtermekminid, $top10min);

        $termek->incMegtekintesdb();
        if (\mkw\store::getTheme() == 'mkwcansas') {
            $termek->incNepszeruseg();
        }
        $this->getEm()->persist($termek);
        $this->getEm()->flush();
        return $ret;
    }

    public function getAjanlottLista()
    {
        $termekek = $this->getRepo()->getAjanlottTermekek(\mkw\store::getParameter(\mkw\consts::Fooldalajanlotttermekdb, 5));
        $ret = [];
        foreach ($termekek as $termek) {
            $ret[] = $termek->toTermekLista();
        }
        return $ret;
    }

    public function getLegnepszerubbLista($db)
    {
        $termekek = $this->getRepo()->getLegnepszerubbTermekek($db);
        $ret = [];
        foreach ($termekek as $termek) {
            $ret[] = $termek->toKapcsolodo();
        }
        return $ret;
    }

    public function getAkciosLista($db)
    {
        $termekek = $this->getRepo()->getAkciosTermekek($db);
        $ret = [];
        foreach ($termekek as $termek) {
            $ret[] = $termek->toTermekLista();
        }
        return $ret;
    }

    public function clearNepszeruseg()
    {
        $this->getRepo()->clearNepszeruseg();
    }

    public function getLegujabbLista()
    {
        $termekek = $this->getRepo()->getLegujabbTermekek(\mkw\store::getParameter(\mkw\consts::Fooldalnepszerutermekdb, 5));
        $ret = [];
        foreach ($termekek as $termek) {
            $ret[] = $termek->toTermekLista();
        }
        return $ret;
    }

    public function getHozzavasaroltLista($termek)
    {
        $termekek = $this->getRepo()->getHozzavasaroltTermekek($termek);
        $ret = [];
        if ($termekek) {
            foreach ($termekek as $termek) {
                $ret[] = $termek->toKapcsolodo();
            }
        }
        return $ret;
    }

    public function feed()
    {
        $feedview = $this->getTemplateFactory()->createMainView('feed.tpl');
        $view = $this->getTemplateFactory()->createMainView('termekfeed.tpl');
        $feedview->setVar('title', \mkw\store::getParameter(\mkw\consts::Feedtermektitle, t('Termékeink')));
        $feedview->setVar('link', \mkw\store::getRouter()->generate('termekfeed', true));
        $d = new \DateTime();
        $feedview->setVar('pubdate', $d->format('D, d M Y H:i:s'));
        $feedview->setVar('lastbuilddate', $d->format('D, d M Y H:i:s'));
        $feedview->setVar('description', \mkw\store::getParameter(\mkw\consts::Feedtermekdescription, ''));
        $entries = [];
        $termekek = $this->getRepo()->getFeedTermek();
        foreach ($termekek as $termek) {
            $view->setVar('kepurl', $termek->getKepUrlSmall());
            $view->setVar('szoveg', $termek->getRovidLeiras());
            $view->setVar('url', \mkw\store::getRouter()->generate('showtermek', true, ['slug' => $termek->getSlug()]));
            $entries[] = [
                'title' => $termek->getNev(),
                'link' => \mkw\store::getRouter()->generate('showtermek', true, ['slug' => $termek->getSlug()]),
                'guid' => \mkw\store::getRouter()->generate('showtermek', true, ['slug' => $termek->getSlug()]),
                'description' => $view->getTemplateResult(),
                'pubdate' => $d->format('D, d M Y H:i:s')
            ];
        }
        $feedview->setVar('entries', $entries);
        header('Content-type: text/xml');
        $feedview->printTemplateResult(false);
    }

    public function redirectOldUrl()
    {
        $tid = $this->params->getStringRequestParam('pid');
        if ($tid) {
            $termek = $this->getRepo()->findOneByIdegenkod($tid);
            if ($termek) {
                $newlink = \mkw\store::getRouter()->generate('showtermek', false, ['slug' => $termek->getSlug()]);
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: ' . $newlink);
                return;
            }
        }
        $mc = new mainController($this->params);
        $mc->show404('HTTP/1.1 410 Gone');
    }

    public function redirectOldRSSUrl()
    {
        $newlink = \mkw\store::getRouter()->generate('termekfeed');
        header("HTTP/1.1 301 Moved Permanently");
        header('Location: ' . $newlink);
    }

    public function redirectRegikepUrl()
    {
        $filename = $this->params->getStringRequestParam('filename');
        if ($filename) {
            $termek = $this->getRepo()->findOneByRegikepurl($filename);
            if ($termek) {
                $newlink = \mkw\store::getFullUrl($termek->getKepurlLarge(), \mkw\store::getConfigValue('mainurl'));
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: ' . $newlink);
                return;
            }
        }
        $mc = new mainController($this->params);
        $mc->show404('HTTP/1.1 410 Gone');
    }

    public function getKeszletByRaktar()
    {
        $termekid = $this->params->getIntRequestParam('termekid');
        $termek = $this->getRepo()->find($termekid);

        $raktarak = $this->getRepo('Entities\Raktar')->getAllActive();
        if ($termek) {
            $klist = [];
            foreach ($raktarak as $raktar) {
                $klist[] = [
                    'raktarnev' => $raktar->getNev(),
                    'keszlet' => $termek->getKeszlet(null, $raktar->getId())
                ];
            }
            $view = $this->createView('termekkeszletreszletezo.tpl');
            $view->setVar('lista', $klist);
            $view->printTemplateResult();
        }
    }

    public function arexport()
    {
        function x($o)
        {
            return \mkw\store::getExcelCoordinate($o, '');
        }

        $ids = $this->params->getStringRequestParam('ids');
        $ids = explode(',', $ids);

        $arsavok = $this->getRepo(TermekAr::class)->getExistingArsavok();
        $defavaluta = \mkw\store::getParameter(\mkw\consts::Valutanem);

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'kod');
        $oszlop = 3;
        foreach ($arsavok as $arsav) {
            if ($arsav['valutanemid'] == $defavaluta) {
                $nettobrutto = 'brutto';
            } else {
                $nettobrutto = 'netto';
            }
            $excel->setActiveSheetIndex(0)
                ->setCellValue(x($oszlop) . '1', $nettobrutto . '_' . $arsav['valutanem'] . '_' . $arsav['azonosito']);
            $oszlop++;
        }

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', 'IN', $ids);
        $termekek = $this->getRepo()->getWithArak($filter, []);
        $sor = 2;
        foreach ($termekek as $termek) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue(x(0) . $sor, $termek->getId())
                ->setCellValue(x(1) . $sor, $termek->getCikkszam())
                ->setCellValue(x(2) . $sor, $termek->getNev());
            $arak = $termek->getTermekArak();
            foreach ($arak as $ar) {
                $i = array_search(
                    [
                        'id' => $ar->getArsav()?->getId(),
                        'valutanemid' => $ar->getValutanemId(),
                        'valutanem' => $ar->getValutanemnev(),
                        'azonosito' => $ar->getArsav()?->getNev(),
                    ],
                    $arsavok
                );
                if ($i !== false) {
                    if ($arsavok[$i]['valutanemid'] == $defavaluta) {
                        $nettobrutto = $ar->getBrutto();
                    } else {
                        $nettobrutto = $ar->getNetto();
                    }
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue(x(3 + $i) . $sor, $nettobrutto);
                }
            }
            $sor++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('termekarak') . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function fcmotoexport()
    {
        function x($o)
        {
            return \mkw\store::getExcelCoordinate($o, '');
        }

        $p = $this->params->getStringRequestParam('p');
        $ids = $this->params->getStringRequestParam('ids');
        $ids = explode(',', $ids);

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Variation ID')
            ->setCellValue('C1', 'SKU')
            ->setCellValue('D1', 'Title')
            ->setCellValue('E1', 'Description')
            ->setCellValue('F1', 'Main image')
            ->setCellValue('G1', 'Images')
            ->setCellValue('H1', 'Size')
            ->setCellValue('I1', 'Color')
            ->setCellValue('J1', 'EAN code')
            ->setCellValue('K1', 'Net price')
            ->setCellValue('L1', 'Customs tariff number')
            ->setCellValue('M1', 'Country of origin')
            ->setCellValue('N1', 'Weight')
            ->setCellValue('O1', 'Width')
            ->setCellValue('P1', 'Height')
            ->setCellValue('Q1', 'Length');

        $partner = match ($p) {
            'fcmoto' => $this->getRepo(Partner::class)->find(\mkw\store::getParameter(\mkw\consts::FCMoto)),
            'maximomoto' => $this->getRepo(Partner::class)->find(\mkw\store::getParameter(\mkw\consts::MaximoMoto)),
            default => null,
        };

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', 'IN', $ids);
        $termekek = $this->getRepo()->getWithValtozatok($filter);
        $sor = 2;
        /** @var Termek $termek */
        foreach ($termekek as $termek) {
            $kepek = $termek->getTermekKepek();
            $kepurlarr = [];
            /** @var TermekKep $kep */
            foreach ($kepek as $kep) {
                $kepurlarr[] = \mkw\store::getFullUrl($kep->getUrl(), \mkw\store::getConfigValue('mainurl'));
            }
            $ford = $termek->getTranslationsArray();
            $nev = $termek->getNevForditas($ford, 'en_us');
            $leiras = $termek->getLeirasForditas($ford, 'en_us');

            if ($termek->getValtozatok()) {
                /** @var TermekValtozat $valtozat */
                foreach ($termek->getValtozatok() as $valtozat) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $sor, $termek->getId())
                        ->setCellValue('B' . $sor, $valtozat->getId())
                        ->setCellValue('C' . $sor, strtoupper($termek->getCikkszam()))
                        ->setCellValue('D' . $sor, $nev)
                        ->setCellValue('E' . $sor, $leiras)
                        ->setCellValue('F' . $sor, \mkw\store::getFullUrl($termek->getKepurl(), \mkw\store::getConfigValue('mainurl')))
                        ->setCellValue('G' . $sor, implode(';', $kepurlarr))
                        ->setCellValue('H' . $sor, $valtozat->getMeret())
                        ->setCellValue('I' . $sor, $valtozat->getSzin())
                        ->setCellValue('J' . $sor, $valtozat->getVonalkod())
                        ->setCellValue('K' . $sor, $termek->getNettoAr($valtozat, $partner))
                        ->setCellValue('L' . $sor, $termek->getVtsz()?->getSzam())
                        ->setCellValue('M' . $sor, 'Pakistan')
                        ->setCellValue('N' . $sor, $termek->getSuly())
                        ->setCellValue('O' . $sor, $termek->getSzelesseg())
                        ->setCellValue('P' . $sor, $termek->getMagassag())
                        ->setCellValue('Q' . $sor, $termek->getHosszusag());
                    $excel->setActiveSheetIndex(0)
                        ->getCell('J' . $sor)->setDataType(DataType::TYPE_STRING);
                    $sor++;
                }
            } else {
                $excel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $sor, $termek->getId())
                    ->setCellValue('C' . $sor, strtoupper($termek->getCikkszam()))
                    ->setCellValue('D' . $sor, $nev)
                    ->setCellValue('E' . $sor, $leiras)
                    ->setCellValue('F' . $sor, \mkw\store::getFullUrl($termek->getKepurl(), \mkw\store::getConfigValue('mainurl')))
                    ->setCellValue('G' . $sor, implode(';', $kepurlarr))
                    ->setCellValue('J' . $sor, $termek->getVonalkod())
                    ->setCellValue('K' . $sor, $termek->getNettoAr($valtozat, $partner))
                    ->setCellValue('L' . $sor, $termek->getVtsz()?->getSzam())
                    ->setCellValue('M' . $sor, 'Pakistan')
                    ->setCellValue('N' . $sor, $termek->getSuly())
                    ->setCellValue('O' . $sor, $termek->getSzelesseg())
                    ->setCellValue('P' . $sor, $termek->getMagassag())
                    ->setCellValue('Q' . $sor, $termek->getHosszusag());

                $excel->setActiveSheetIndex(0)
                    ->getCell('J' . $sor)->setDataType(DataType::TYPE_STRING);
                $sor++;
            }
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('fcmotoexport') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filename);

        readfile($filepath);

        \unlink($filepath);
    }

    public function gs1export()
    {
        $ids = $this->params->getStringRequestParam('ids');
        $ids = explode(',', $ids);

        $filenev = \mkw\store::exporttemplatePath('gs1template.xlsx');
        $filetype = IOFactory::identify($filenev);
        $reader = IOFactory::createReader($filetype);
        $excel = $reader->load($filenev);
        $sheet = $excel->getActiveSheet();

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', 'IN', $ids);
        $termekek = $this->getRepo()->getWithValtozatok($filter);
        $sor = 3;
        /** @var Termek $termek */
        foreach ($termekek as $termek) {
            $ford = $termek->getTranslationsArray();
            $nev = $termek->getNevForditas($ford, 'en_us');
            $leiras = $termek->getLeirasForditas($ford, 'en_us');

            if ($termek->getValtozatok()) {
                /** @var TermekFa $kat */
                $kat = $termek->getTermekfa1();
                $kattrans = $kat->getTranslationsArray();
                $termektrans = $termek->getTranslationsArray();
                /** @var TermekValtozat $valtozat */
                foreach ($termek->getValtozatok() as $valtozat) {
                    if (!$valtozat->getVonalkod()) {
                        $excel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $sor, \mkw\store::getParameter(\mkw\consts::GS1Datasource))
                            ->setCellValue('B' . $sor, \mkw\store::getParameter(\mkw\consts::GS1DatasourceName))
                            ->setCellValue('C' . $sor, 'Alap')
                            ->setCellValue('E' . $sor, '10003707')
                            ->setCellValue('F' . $sor, 'MUGENRACE')
                            ->setCellValue('G' . $sor, $termektrans['en_us']['nev']) // Almárka
                            ->setCellValue('H' . $sor, $kattrans['en_us']['nev'])
                            ->setCellValue('I' . $sor, 'Angol')
                            ->setCellValue('J' . $sor, $valtozat->getSzin() . ' ' . $valtozat->getMeret())
                            ->setCellValue('K' . $sor, 'Angol')
                            ->setCellValue('L' . $sor, 1)
                            ->setCellValue('M' . $sor, 'Piece')
                            ->setCellValue(
                                'N' . $sor,
                                '=CONCATENATE(F' . $sor . '," ",G' . $sor . '," ",H' . $sor . '," ",J' . $sor . '," ",L' . $sor . '," ",M' . $sor . ')'
                            )
                            ->setCellValue('O' . $sor, 'Angol')
                            ->setCellValue('P' . $sor, 'Igen')
                            ->setCellValue('Q' . $sor, 'Alaptermék')
                            ->setCellValue('R' . $sor, $valtozat->getCikkszam())
                            ->setCellValue('S' . $sor, 'Beszállító által kiadott (Belső azonosító)')
                            ->setCellValue('T' . $sor, $termek->getSuly())
                            ->setCellValue('U' . $sor, 'Kilogramm')
                            ->setCellValue('V' . $sor, $termek->getMagassag())
                            ->setCellValue('W' . $sor, 'Centiméter')
                            ->setCellValue('X' . $sor, $termek->getHosszusag())
                            ->setCellValue('Y' . $sor, 'Centiméter')
                            ->setCellValue('Z' . $sor, $termek->getSzelesseg())
                            ->setCellValue('AA' . $sor, 'Centiméter')
                            ->setCellValue('AB' . $sor, 'Nem')
                            ->setCellValue('AC' . $sor, 'Nem')
                            ->setCellValue('AD' . $sor, $valtozat->getId());
                        $sor++;
                    }
                }
            } elseif (!$termek->getVonalkod()) {
                $excel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $sor, $termek->getId())
                    ->setCellValue('C' . $sor, $termek->getCikkszam())
                    ->setCellValue('D' . $sor, $nev)
                    ->setCellValue('E' . $sor, $leiras)
                    ->setCellValue('F' . $sor, \mkw\store::getFullUrl($termek->getKepurl(), \mkw\store::getConfigValue('mainurl')))
                    ->setCellValue('J' . $sor, $termek->getVonalkod());
                $sor++;
            }
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('gs1export') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filename);

        readfile($filepath);

        \unlink($filepath);
    }

    public function colorexport()
    {
        $tvec = new termekvaltozatertekController($this->params);
        $tvec->fill();

        $sor = 1;
        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A' . $sor, 'Old color')
            ->setCellValue('B' . $sor, 'New color')
            ->setCellValue('C' . $sor, 'Color code');
        $sor++;

        $colors = $this->getRepo(TermekValtozatErtek::class)->getAllColors();
        /** @var TermekValtozatErtek $color */
        foreach ($colors as $color) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $sor, $color->getErtek())
                ->setCellValue('B' . $sor, $color->getErtek())
                ->setCellValue('C' . $sor, $color->getCharkod());
            $sor++;
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('colors') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: attachment; filename=' . $filename);

        readfile($filepath);

        \unlink($filepath);
    }

    public function cikkszamosexport()
    {
        function x($o)
        {
            return \mkw\store::getExcelCoordinate($o, '');
        }

        $ids = $this->params->getStringRequestParam('ids');
        $ids = explode(',', $ids);

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'Név')
            ->setCellValue('C1', 'Cikkszám')
            ->setCellValue('D1', 'Szállítói cikkszám')
            ->setCellValue('E1', 'Változat ID')
            ->setCellValue('F1', '')
            ->setCellValue('G1', '')
            ->setCellValue('H1', 'Cikkszám')
            ->setCellValue('I1', 'Szállítói cikkszám');

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', 'IN', $ids);
        $termekek = $this->getRepo()->getWithValtozatok($filter);
        $sor = 2;
        /** @var Termek $termek */
        foreach ($termekek as $termek) {
            if ($termek->getValtozatok()) {
                /** @var TermekValtozat $valtozat */
                foreach ($termek->getValtozatok() as $valtozat) {
                    $excel->setActiveSheetIndex(0)
                        ->setCellValue(x(0) . $sor, $termek->getId())
                        ->setCellValue(x(1) . $sor, $termek->getNev())
                        ->setCellValue(x(2) . $sor, $termek->getCikkszam())
                        ->setCellValue(x(3) . $sor, $termek->getIdegencikkszam())
                        ->setCellValue(x(4) . $sor, $valtozat->getId())
                        ->setCellValue(x(5) . $sor, $valtozat->getAdatTipus1Nev())
                        ->setCellValue(x(6) . $sor, $valtozat->getAdatTipus2Nev())
                        ->setCellValue(x(7) . $sor, $valtozat->getCikkszam())
                        ->setCellValue(x(8) . $sor, $valtozat->getIdegencikkszam());
                    $sor++;
                }
            } else {
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(x(0) . $sor, $termek->getId())
                    ->setCellValue(x(1) . $sor, $termek->getNev())
                    ->setCellValue(x(2) . $sor, $termek->getCikkszam())
                    ->setCellValue(x(3) . $sor, $termek->getIdegencikkszam());
                $sor++;
            }
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filepath = \mkw\store::storagePath(uniqid('cikkszamosexport') . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $filepath);

        readfile($filepath);

        \unlink($filepath);
    }

    public function setTermekcsoport()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        //$ids = explode(',', $ids);
        if ($ids) {
            $tcsid = $this->params->getIntRequestParam('tcs');
            $tcs = $this->getRepo('Entities\Termekcsoport')->find($tcsid);

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('id', 'IN', $ids);
            $termekek = $this->getRepo()->getAll($filter, []);
            $termekdb = 0;
            $batchsize = 20;
            /** @var Termek $termek */
            foreach ($termekek as $termek) {
                $termekdb++;
                if ($tcs) {
                    $termek->setTermekcsoport($tcs);
                } else {
                    $termek->setTermekcsoport(null);
                }
                $this->getEm()->persist($termek);
                $termek->uploadToWC();
                if (($termekdb % $batchsize) === 0) {
                    $this->getEm()->flush();
                }
            }
            $this->getEm()->flush();
            $this->getEm()->clear();
        }
    }

    public function uploadToWc()
    {
        if (\mkw\store::isWoocommerceOn()) {
            /** @var Client $wc */
            $wc = store::getWcClient();
            $eur = $this->getRepo(Valutanem::class)->findOneBy(['nev' => 'EUR']);

            $filter = new FilterDescriptor();
            $filter->addSql('(_xx.wcid<>0) AND (_xx.wcid IS NOT NULL)');
            $categories = $this->getRepo(TermekMenu::class)->getAll($filter);

            $termekdone = [];

            /** @var TermekFa $category */
            foreach ($categories as $category) {
                $tfilter = new FilterDescriptor();
                $tfilter->addFilter(['_xx.termekmenu1'], '=', $category->getId());
                $tfilter->addFilter('wctiltva', '<>', 1);
                $termekek = $this->getRepo(Termek::class)->getAll($tfilter);
                /** @var Termek $termek */
                foreach ($termekek as $termek) {
                    if (!in_array($termek->getId(), $termekdone)) {
                        $termekdone[] = $termek->getId();
                        $termek->uploadToWC(true);
                    }
                }
            }
            echo 'OK';
        }
    }

}