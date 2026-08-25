<?php

namespace Controllers;

use Entities\Afa;
use Entities\Arsav;
use Entities\ME;
use Entities\Meret;
use Entities\Partner;
use Entities\Raktar;
use Entities\Szin;
use Entities\Termek;
use Entities\TermekAr;
use Entities\Termekcimketorzs;
use Entities\Termekcsoport;
use Entities\TermekDok;
use Entities\TermekFa;
use Entities\TermekKapcsolodo;
use Entities\TermekKep;
use Entities\TermekMenu;
use Entities\TermekMenu2;
use Entities\TermekMinkeszlet;
use Entities\TermekValtozat;
use Entities\TermekValtozatAdatTipus;
use Entities\TermekValtozatMinkeszlet;
use Entities\TermekValtozatErtek;
use Entities\Valutanem;
use Entities\Vtsz;
use mkw\store;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class termekController extends \mkwhelpers\MattableController
{

    /** a GS1 export AC oszlopa: a saját azonosító melyik törzsre mutat */
    private const GS1AZONOSITOTIPUS_TERMEK = 'termek';
    private const GS1AZONOSITOTIPUS_VALTOZAT = 'valtozat';

    private const GS1MARKANEV = 'MUGENRACE';
    private const GS1NETTOMENNYISEG = 1;

    private $kaphatolett = false;
    private $vanshowarsav = false;

    public function __construct()
    {
        $this->setEntityName(Termek::class);
        $this->setKarbFormTplName('termekkarbform.tpl');
        $this->setKarbTplName('termekkarb.tpl');
        $this->setListBodyRowTplName('termeklista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_termek');
        parent::__construct();
    }

    protected function loadVars($t, $forKarb = false)
    {
        $termekarCtrl = new termekarController();
        $kepCtrl = new termekkepController();
        $valtozatCtrl = new termekvaltozatController();
        $kapcsolodoCtrl = new termekkapcsolodoController();
        $dokCtrl = new termekdokController();
        $ar = [];
        $kep = [];
        $valtozat = [];
        $lvaltozat = [];
        $kapcsolodo = [];
        $dok = [];
        if (!$t) {
            $t = new \Entities\Termek();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['nev_locale'] = $t->getLocalizedFieldValue('nev');
        $x['leiras_locale'] = $t->getLocalizedFieldValue('leiras');
        $x['rovidleiras_locale'] = $t->getLocalizedFieldValue('rovidleiras');
        $x['oldalcim_locale'] = $t->getLocalizedFieldValue('oldalcim');
        $x['vtsznev'] = $t->getVtszNev();
        $x['afanev'] = $t->getAfaNev();
        if (!\mkw\store::isArsavok()) {
            $x['akciostartstr'] = $t->getAkciostartStr();
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
        $x['termekmenu2nev'] = $t->getTermekmenu2Nev();
        $x['termekmenu2'] = $t->getTermekmenu2Id();
        $x['termekmenu2path'] = implode(' / ', $t->getTermekmenu2Path());

        $x['kepurlsmall'] = $t->getKepurlSmall();
        $x['kepurlmedium'] = $t->getKepurlMedium();
        $x['kepurllarge'] = $t->getKepurlLarge();
        $x['kepurl400'] = $t->getKepurl400();
        $x['kepurl2000'] = $t->getKepurl2000();

        $x['lastmodstr'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();

        $x['gyartonev'] = $t->getGyartoNev();
        $x['keszlet'] = $t->getKeszlet();
        $x['termekcsoportnev'] = $t->getTermekcsoportNev();
        $x['foglaltmennyiseg'] = $t->getFoglaltMennyiseg();
        if (\mkw\store::getSetupValue('termekvaltozat')) {
            foreach ($t->getValtozatok() as $tvaltozat) {
                $mozgasdb = $tvaltozat->getMozgasDb();
                if ($mozgasdb) {
                    $lvaltozat[] = $valtozatCtrl->loadVars($tvaltozat, $t, true);
                }
            }
        }
        $x['valtozatkeszlet'] = $lvaltozat;
        if ($forKarb) {
            $matrix = $this->getMinKeszletMatrix($t);
            $x['minkeszletraktarak'] = $matrix['raktarak'];
            $x['minkeszletsorok'] = $matrix['sorok'];

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

            if (\mkw\store::getSetupValue('termekvaltozat')) {
                foreach ($t->getValtozatok() as $tvaltozat) {
                    $valtozat[] = $valtozatCtrl->loadVars($tvaltozat, $t, true);
                }
                //$valtozat[]=$valtozatCtrl->loadVars(null);
                $x['valtozatok'] = $valtozat;

                $szinlista = [];
                $szinmap = [];
                /** @var TermekValtozat $tvaltozat */
                foreach ($t->getValtozatok() as $tvaltozat) {
                    $szin = $tvaltozat->getSzinObject();
                    if ($szin) {
                        $szinmap[$szin->getId()] = $szin;
                    }
                }
                /*
                foreach ($t->getTermekSzinKepek() as $szinkep) {
                    $szin = $szinkep->getSzin();
                    if ($szin) {
                        $szinmap[$szin->getId()] = $szin;
                    }
                }
                */
                $szinkepmap = [];
                foreach ($t->getTermekSzinKepek() as $szinkep) {
                    $szinid = $szinkep->getSzinId();
                    // kép nélküli sor = a termék főképe (lásd termekkepController::FOKEP_ID)
                    $kepid = $szinkep->getKepId() ?: termekkepController::FOKEP_ID;
                    if ($szinid) {
                        $szinkepmap[$szinid][$kepid] = $szinkep->getSorrend();
                    }
                }
                foreach ($szinmap as $szin) {
                    $selids = $szinkepmap[$szin->getId()] ?? [];
                    $szinlista[] = [
                        'id' => $szin->getId(),
                        'nev' => $szin->getNev(),
                        //'kepids' => $selids,
                        'kepek' => $kepCtrl->getSelectList($t, $selids, true)
                    ];
                }
                $x['szinkepek'] = $szinlista;
            }
            if (\mkw\store::isArsavok()) {
                foreach ($t->getTermekArak() as $tar) {
                    $ar[] = $termekarCtrl->loadVars($tar, true);
                }
                $x['arak'] = $ar;
            }
        }
        return $x;
    }

    /**
     * A „Min. bolti készlet" fül mátrixa: soronként a termék és a változatai, oszloponként a
     * „Minden raktár" (globális) érték és a raktárak. Ami nincs beállítva, az üresen jelenik meg –
     * a nulla a feloldási létrában is „nincs beállítva", ezért nem írjuk ki.
     *
     * Változatszámtól függetlenül két lekérdezés: egy a termék, egy az összes változat soraira.
     *
     * @param \Entities\Termek $t
     *
     * @return array ['raktarak' => [['id','nev'],…], 'sorok' => [['valtozatid','nev','globalis',…],…]]
     */
    private function getMinKeszletMatrix($t)
    {
        $termekid = $t->getId();
        $valtozatok = [];
        if ($termekid && \mkw\store::getSetupValue('termekvaltozat')) {
            foreach ($t->getValtozatok() as $valtozat) {
                $valtozatok[] = $valtozat;
            }
        }
        $valtozatids = array_map(static fn($valtozat) => $valtozat->getId(), $valtozatok);

        $termeksorok = $termekid
            ? $this->getRepo(TermekMinkeszlet::class)->getRowsByTermek($termekid)
            : [];
        $valtozatsorok = $valtozatids
            ? $this->getRepo(TermekValtozatMinkeszlet::class)->getRowsByTermekValtozatIds($valtozatids)
            : [];

        $raktarnevek = [];
        foreach ($this->getRepo(Raktar::class)->getAllActive() as $raktar) {
            $raktarnevek[$raktar->getId()] = $raktar->getNev();
        }
        // az archivált raktáron ragadt érték is jelenjen meg, különben csendben tovább élne
        foreach ($termeksorok as $rid => $sor) {
            $raktarnevek[$rid] ??= $sor->getRaktar()?->getNev();
        }
        foreach ($valtozatsorok as $sorok) {
            foreach ($sorok as $rid => $sor) {
                $raktarnevek[$rid] ??= $sor->getRaktar()?->getNev();
            }
        }
        uasort($raktarnevek, static fn($a, $b) => strnatcasecmp(mb_strtolower((string)$a), mb_strtolower((string)$b)));

        $raktarak = [];
        foreach ($raktarnevek as $rid => $nev) {
            $raktarak[] = ['id' => $rid, 'nev' => $nev];
        }

        $sorok = [];

        $cellak = [];
        foreach ($raktarnevek as $rid => $nev) {
            $cellak[] = [
                'raktarid' => $rid,
                'ertek' => $this->beallitottErtek($termeksorok[$rid] ?? null),
            ];
        }
        $sorok[] = [
            'valtozatid' => null,
            'nev' => t('Termék'),
            'globalis' => $this->beallitottErtek($t),
            'cellak' => $cellak,
            // változatos terméken a termék sora csak mutat: a minimumot a változatokhoz kell megadni
            'zarolt' => (bool)$valtozatok,
        ];

        /** @var TermekValtozat $valtozat */
        foreach ($valtozatok as $valtozat) {
            $vid = $valtozat->getId();
            $cellak = [];
            foreach ($raktarnevek as $rid => $nev) {
                $cellak[] = [
                    'raktarid' => $rid,
                    'ertek' => $this->beallitottErtek($valtozatsorok[$vid][$rid] ?? null),
                ];
            }
            $sorok[] = [
                'valtozatid' => $vid,
                'nev' => $this->getValtozatNev($valtozat),
                'globalis' => $this->beallitottErtek($valtozat),
                'cellak' => $cellak,
                'zarolt' => false,
            ];
        }

        return ['raktarak' => $raktarak, 'sorok' => $sorok];
    }

    /**
     * A „Min. bolti készlet" mátrix mentése. Csak a ténylegesen kirajzolt rácsot érintjük –
     * ezt írja le a két rejtett tömb (minkeszletraktarid[], valtozatminkeszletid[]) –,
     * a ki nem rajzolt sorok érintetlenek maradnak.
     *
     * Üres vagy nulla cella ⇒ a sor törlése: a létrában a tárolt 0 és a hiányzó sor azonos.
     *
     * Változatos terméken a termékszint kötelezően nulla: a minimumot csak a változatokhoz
     * lehet megadni, a termék globális értékét és raktáras sorait ilyenkor töröljük.
     *
     * @param \Entities\Termek $obj
     * @param \Entities\TermekValtozat[] $valtozatmap űrlapkulcs => változat, a fenti ciklusból –
     *        „mentés új termékként" esetén az űrlapon a RÉGI termék változatainak id-je jön vissza,
     *        adatbázisból feloldva a régi termékre írnánk
     */
    private function saveMinKeszletMatrix($obj, array $valtozatmap)
    {
        $em = $this->getEm();
        $raktaridk = $this->getIdList('minkeszletraktarid');
        // a getValtozatok() null a változatot nem ismerő témákon (darshan, kisszamlazo)
        $vanvaltozat = \mkw\store::getSetupValue('termekvaltozat') && count($obj->getValtozatok() ?? []) > 0;

        if ($vanvaltozat) {
            $obj->setMinkeszlet(0);
            foreach ($this->getRepo(TermekMinkeszlet::class)->getRowsByTermek($obj->getId()) as $sor) {
                $em->remove($sor);
            }
        }

        $raktarmap = [];
        if ($raktaridk) {
            $rfilter = new FilterDescriptor();
            $rfilter->addFilter('id', 'IN', $raktaridk);
            foreach ($this->getRepo(Raktar::class)->getAll($rfilter, []) as $raktar) {
                $raktarmap[$raktar->getId()] = $raktar;
            }

            if (!$vanvaltozat) {
                $termeksorok = $this->getRepo(TermekMinkeszlet::class)->getRowsByTermek($obj->getId());
                foreach ($raktarmap as $rid => $raktar) {
                    $ertek = $this->params->getNumRequestParam('termekraktariminkeszlet_' . $rid);
                    $sor = $termeksorok[$rid] ?? null;
                    if ($ertek * 1) {
                        if (!$sor) {
                            $sor = new TermekMinkeszlet();
                            $sor->setTermek($obj);
                            $sor->setRaktar($raktar);
                        }
                        $sor->setMinkeszlet($ertek);
                        $em->persist($sor);
                    } elseif ($sor) {
                        $em->remove($sor);
                    }
                }
            }
        }

        // csak a kirajzolt rács változatai, és csak azok, amiket a fenti ciklus tényleg megtartott
        $erintett = [];
        foreach ($this->params->getArrayRequestParam('valtozatminkeszletid') as $vid) {
            $vid = (string)$vid;
            if (isset($valtozatmap[$vid])) {
                $erintett[$vid] = $valtozatmap[$vid];
            }
        }
        if (!$erintett) {
            return;
        }

        foreach ($erintett as $vid => $valtozat) {
            $valtozat->setMinkeszlet($this->params->getNumRequestParam('valtozatminkeszlet_' . $vid));
            $em->persist($valtozat);
        }

        if (!$raktarmap) {
            return;
        }
        // az új változatnak még nincs id-je, arra nem is lehet meglévő sor
        $valtozatsorok = $this->getRepo(TermekValtozatMinkeszlet::class)->getRowsByTermekValtozatIds(
            array_filter(array_map(static fn($valtozat) => $valtozat->getId(), $erintett))
        );
        foreach ($erintett as $vid => $valtozat) {
            foreach ($raktarmap as $rid => $raktar) {
                // a mezőnév az űrlapkulcsot viseli, a meglévő sorok viszont a valódi id-re állnak
                $ertek = $this->params->getNumRequestParam('valtozatraktariminkeszlet_' . $vid . '_' . $rid);
                $sor = $valtozatsorok[$valtozat->getId()][$rid] ?? null;
                if ($ertek * 1) {
                    if (!$sor) {
                        $sor = new TermekValtozatMinkeszlet();
                        $sor->setTermekvaltozat($valtozat);
                        $sor->setRaktar($raktar);
                    }
                    $sor->setMinkeszlet($ertek);
                    $em->persist($sor);
                } elseif ($sor) {
                    $em->remove($sor);
                }
            }
        }
    }

    private function getIdList($parameter): array
    {
        return array_values(
            array_unique(
                array_filter(
                    array_map(
                        'intval',
                        $this->params->getArrayRequestParam($parameter)
                    )
                )
            )
        );
    }

    private function getValtozatNev(TermekValtozat $valtozat)
    {
        if (\mkw\store::isFixSzinMode()) {
            $nev = trim($valtozat->getSzinNev() . ' - ' . $valtozat->getMeretNev(), ' -');
        } else {
            $nev = trim($valtozat->getErtek1() . ' - ' . $valtozat->getErtek2(), ' -');
        }
        return $nev ?: ($valtozat->getCikkszam() ?: '#' . $valtozat->getId());
    }

    /**
     * A mátrix egy cellájának értéke: ami nincs beállítva, az üres. A nulla is „nincs beállítva”
     * (a feloldási létra így kezeli), ezért nem íratjuk ki. A decimal stringként hidratál,
     * ezért a teszt numerikus.
     *
     * @param \Entities\Termek|\Entities\TermekValtozat|\Entities\TermekMinkeszlet|\Entities\TermekValtozatMinkeszlet|null $hordozo
     */
    private function beallitottErtek($hordozo)
    {
        $ertek = $hordozo?->getMinkeszlet();
        return ($ertek * 1) ? $ertek : '';
    }

    /**
     * @param \Entities\Termek $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $oldnemkaphato = $obj->getNemkaphato();
        $vtsz = \mkw\store::getEm()->getRepository(Vtsz::class)->find($this->params->getIntRequestParam('vtsz'));
        if ($vtsz) {
            $obj->setVtsz($vtsz);
        }
        $afa = \mkw\store::getEm()->getRepository(Afa::class)->find($this->params->getIntRequestParam('afa'));
        if ($afa) {
            $obj->setAfa($afa);
        }
        $valt = \mkw\store::getEm()->getRepository(TermekValtozatAdatTipus::class)->find($this->params->getIntRequestParam('valtozatadattipus'));
        if ($valt) {
            $obj->setValtozatadattipus($valt);
        } else {
            $obj->setValtozatadattipus(null);
        }
        $ck = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('gyarto'));
        if ($ck) {
            $obj->setGyarto($ck);
        } else {
            $obj->setGyarto(null);
        }
        $csoport = $this->getRepo(Termekcsoport::class)->find($this->params->getIntRequestParam('termekcsoport'));
        if ($csoport) {
            $obj->setTermekcsoport($csoport);
        } else {
            $obj->setTermekcsoport(null);
        }
        $me = \mkw\store::getEm()->getRepository(ME::class)->find($this->params->getIntRequestParam('me'));
        if ($me) {
            $obj->setMekod($me);
        }
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setNevL1($this->params->getStringRequestParam('nev_l1'));
        $obj->setKiirtnev($this->params->getStringRequestParam('kiirtnev'));
        $obj->setCikkszam($this->params->getStringRequestParam('cikkszam'));
        $obj->setIdegencikkszam($this->params->getStringRequestParam('idegencikkszam'));
        $obj->setVonalkod($this->params->getStringRequestParam('vonalkod'));
        // a mező csak `unas` kapcsolóval van a formon – hiányában ne nullázzuk a párosítást
        if ($this->params->existsRequestParam('unasid')) {
            $obj->setUnasid($this->params->getStringRequestParam('unasid'));
        }
        $obj->setIdegenkod($this->params->getStringRequestParam('idegenkod'));
        $obj->setOldalcim($this->params->getStringRequestParam('oldalcim'));
        // `unas` kapcsolóval a rövid leírás CKEditor-os, tehát HTML – azt nyersen kell venni
        $rovidleiras = \mkw\store::isUnas() ? 'getOriginalStringRequestParam' : 'getStringRequestParam';
        $obj->setRovidleiras($this->params->$rovidleiras('rovidleiras'));
        $obj->setLeiras($this->params->getOriginalStringRequestParam('leiras'));
        $obj->setOldalcimL1($this->params->getStringRequestParam('oldalcim_l1'));
        $obj->setRovidleirasL1($this->params->$rovidleiras('rovidleiras_l1'));
        $obj->setLeirasL1($this->params->getOriginalStringRequestParam('leiras_l1'));
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
        $obj->setUj($this->params->getBoolRequestParam('uj'));
        $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
        $obj->setKellegyediazonosito($this->params->getBoolRequestParam('kellegyediazonosito'));
        $obj->setEladhato($this->params->getBoolRequestParam('eladhato'));
        $obj->setMozgat($this->params->getBoolRequestParam('mozgat'));
        $obj->setHparany($this->params->getFloatRequestParam('hparany'));
        $obj->setSzelesseg($this->params->getFloatRequestParam('szelesseg'));
        $obj->setMagassag($this->params->getFloatRequestParam('magassag'));
        $obj->setHosszusag($this->params->getFloatRequestParam('hosszusag'));
        $obj->setSuly($this->params->getFloatRequestParam('suly'));
        $obj->setOsszehajthato($this->params->getBoolRequestParam('osszehajthato'));
        $obj->setGyujto($this->params->getFloatRequestParam('gyujto'));
        $obj->setSordoboz($this->params->getFloatRequestParam('sordoboz'));
        $obj->setBonthato($this->params->getBoolRequestParam('bonthato'));
        $obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
        $obj->setKepleiras($this->params->getStringRequestParam('kepleiras', ''));
        $obj->setRegikepurl($this->params->getStringRequestParam('regikepurl', ''));
        $obj->setTermekexportbanszerepel($this->params->getBoolRequestParam('termekexportbanszerepel'));
        $obj->setNemkaphato($this->params->getBoolRequestParam('nemkaphato'));
        $obj->setFuggoben($this->params->getBoolRequestParam('fuggoben'));
        $obj->setSzallitasiido($this->params->getIntRequestParam('szallitasiido'));
        // a két mező csak `unas` kapcsolóval van a formon – hiányában ne írjuk felül az importált értéket
        if ($this->params->existsRequestParam('szallitasiidostr')) {
            $obj->setSzallitasiidostr($this->params->getStringRequestParam('szallitasiidostr'));
        }
        if ($this->params->existsRequestParam('unasalaptipus')) {
            $obj->setUnasalaptipus($this->params->getStringRequestParam('unasalaptipus'));
        }
        $obj->setKozvetitett($this->params->getBoolRequestParam('kozvetitett'));
        // a mező nincs minden téma sablonjában (darshan) – enélkül minden mentés nullázná
        if ($this->params->existsRequestParam('minkeszlet')) {
            $obj->setMinkeszlet($this->params->getFloatRequestParam('minkeszlet'));
        }
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
        $menu2repo = \mkw\store::getEm()->getRepository(TermekMenu2::class);
        $menu2 = $menu2repo->find($this->params->getIntRequestParam('termekmenu2'));
        $obj->setTermekmenu2($menu2 ?: null);
        $obj->removeAllCimke();
        $cimkekpar = $this->params->getArrayRequestParam('cimkek');
        foreach ($cimkekpar as $cimkekod) {
            $cimke = $this->getEm()->getRepository(Termekcimketorzs::class)->find($cimkekod);
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
        // űrlapkulcs => kép: a szín képek és a változat képe erre hivatkoznak. "Mentés új
        // termékként" esetén minden kép ÚJ sorként jön létre, tehát az űrlapon lévő (régi
        // termékhez tartozó) id-t nem szabad adatbázisból feloldani.
        $kepmap = [];
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
                    $kepmap[(string)$kepid] = $kep;
                } elseif ($oper == 'edit') {
                    /** @var TermekKep $kep */
                    $kep = \mkw\store::getEm()->getRepository(TermekKep::class)->find($kepid);
                    if ($kep) {
                        $kep->setUrl($this->params->getStringRequestParam('kepurl_' . $kepid));
                        $kep->setLeiras($this->params->getStringRequestParam('kepleiras_' . $kepid));
                        $kep->setRejtett($this->params->getBoolRequestParam('keprejtett_' . $kepid));
                        $this->getEm()->persist($kep);
                        $kepmap[(string)$kepid] = $kep;
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
                    $dok = \mkw\store::getEm()->getRepository(TermekDok::class)->find($dokid);
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
                    $ar = $this->getEm()->getRepository(TermekAr::class)->find($arid);
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
        if (\mkw\store::getSetupValue('kapcsolodotermekek')) {
            $kapcsolodoids = $this->params->getArrayRequestParam('kapcsolodoid');
            foreach ($kapcsolodoids as $kapcsolodoid) {
                if (($this->params->getIntRequestParam('kapcsolodoaltermek_' . $kapcsolodoid) > 0)) {
                    $oper = $this->params->getStringRequestParam('kapcsolodooper_' . $kapcsolodoid);
                    $altermek = $this->getEm()->getRepository(Termek::class)->find(
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
                        $kapcsolodo = $this->getEm()->getRepository(TermekKapcsolodo::class)->find($kapcsolodoid);
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
        // űrlapkulcs => változat, ugyanazért, amiért a képeknél: "mentés új termékként" esetén a
        // változatok is újként jönnek létre, a min.készlet mátrix viszont a régi id-ket küldi vissza
        $valtozatmap = [];
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
                    // az UNAS azonosító mező csak `unas` kapcsolóval van a formon – hiányában
                    // ne nullázzuk a meglévő párosítást
                    if ($this->params->existsRequestParam('valtozatunasid_' . $valtozatid)) {
                        $valtozat->setUnasid($this->params->getStringRequestParam('valtozatunasid_' . $valtozatid));
                        $valtozat->setUnasalaptipus($this->params->getStringRequestParam('valtozatunasalaptipus_' . $valtozatid));
                    }
                    $valtozat->setBeerkezesdatum($this->params->getStringRequestParam('valtozatbeerkezesdatum_' . $valtozatid));

                    if (\mkw\store::isFixSzinMode()) {
                        $szin = $this->getEm()->getRepository(Szin::class)->find(
                            $this->params->getIntRequestParam('valtozatszin_' . $valtozatid)
                        );
                        if ($szin) {
                            $valtozat->setSzin($szin);
                            $valtdb++;
                            $at = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
                                \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)
                            );
                            if ($at) {
                                $valtozat->setAdatTipus1($at);
                                $valtozat->setErtek1($szin->getNev());
                            }
                        }
                        $meret = $this->getEm()->getRepository(Meret::class)->find(
                            $this->params->getIntRequestParam('valtozatmeret_' . $valtozatid)
                        );
                        if ($meret) {
                            $valtozat->setMeret($meret);
                            $valtdb++;
                            $at = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
                                \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)
                            );
                            if ($at) {
                                $valtozat->setAdatTipus2($at);
                                $valtozat->setErtek2($meret->getNev());
                            }
                        }
                    } else {
                        $at = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
                            $this->params->getIntRequestParam('valtozatadattipus1_' . $valtozatid)
                        );
                        $valtert = $this->params->getStringRequestParam('valtozatertek1_' . $valtozatid);
                        if ($at && $valtert) {
                            $valtozat->setAdatTipus1($at);
                            $valtozat->setErtek1($valtert);
                            $valtdb++;
                        }

                        $at = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
                            $this->params->getIntRequestParam('valtozatadattipus2_' . $valtozatid)
                        );
                        $valtert = $this->params->getStringRequestParam('valtozatertek2_' . $valtozatid);
                        if ($at && $valtert) {
                            $valtozat->setAdatTipus2($at);
                            $valtozat->setErtek2($valtert);
                            $valtdb++;
                        }
                    }
                    $at = $kepmap[$this->params->getStringRequestParam('valtozatkepid_' . $valtozatid)] ?? null;
                    if ($at) {
                        $valtozat->setKep($at);
                    }

                    if ($valtdb > 0) {
                        $this->getEm()->persist($valtozat);
                        $valtozatmap[(string)$valtozatid] = $valtozat;
                    } else {
                        $obj->removeValtozat($valtozat);
                    }
                } elseif ($oper == 'edit') {
                    $valtozat = $this->getEm()->getRepository(TermekValtozat::class)->find($valtozatid);
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
                        // az UNAS azonosító mező csak `unas` kapcsolóval van a formon – hiányában
                        // ne nullázzuk a meglévő párosítást
                        if ($this->params->existsRequestParam('valtozatunasid_' . $valtozatid)) {
                            $valtozat->setUnasid($this->params->getStringRequestParam('valtozatunasid_' . $valtozatid));
                            $valtozat->setUnasalaptipus($this->params->getStringRequestParam('valtozatunasalaptipus_' . $valtozatid));
                        }
                        $valtozat->setBeerkezesdatum($this->params->getStringRequestParam('valtozatbeerkezesdatum_' . $valtozatid));

                        if (\mkw\store::isFixSzinMode()) {
                            $szin = $this->getEm()->getRepository(Szin::class)->find(
                                $this->params->getIntRequestParam('valtozatszin_' . $valtozatid)
                            );
                            if ($szin) {
                                $valtozat->setSzin($szin);
                                $at = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
                                    \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)
                                );
                                if ($at) {
                                    $valtozat->setAdatTipus1($at);
                                    $valtozat->setErtek1($szin->getNev());
                                }
                            }
                            $meret = $this->getEm()->getRepository(Meret::class)->find(
                                $this->params->getIntRequestParam('valtozatmeret_' . $valtozatid)
                            );
                            if ($meret) {
                                $valtozat->setMeret($meret);
                                $at = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
                                    \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)
                                );
                                if ($at) {
                                    $valtozat->setAdatTipus2($at);
                                    $valtozat->setErtek2($meret->getNev());
                                }
                            }
                        } else {
                            $at = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
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

                            $at = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
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
                        }

                        if ($valtozat->getTermekfokep()) {
                            $valtozat->setKep(null);
                        } else {
                            $at = $kepmap[$this->params->getStringRequestParam('valtozatkepid_' . $valtozatid)] ?? null;
                            if ($at) {
                                $valtozat->setKep($at);
                            } else {
                                $valtozat->setKep(null);
                            }
                        }

                        $this->getEm()->persist($valtozat);
                        $valtozatmap[(string)$valtozatid] = $valtozat;
                    }
                }
            }
        }

        // A szín képek a változatok után: a szűrésük a változatok színéből indul, ami "mentés új
        // termékként" esetén csak a fenti ciklusban jön létre.
        $szinids = $this->params->getArrayRequestParam('szinkepid');
        if ($szinids) {
            $validSzinIds = [];
            foreach ($obj->getValtozatok() as $valtozat) {
                $valtozatSzinId = $valtozat->getSzinId();
                if ($valtozatSzinId) {
                    $validSzinIds[$valtozatSzinId] = true;
                }
            }
            if ($validSzinIds) {
                $szinids = array_values(array_filter($szinids, function ($szinId) use ($validSzinIds) {
                    return isset($validSzinIds[(int)$szinId]);
                }));
            } else {
                $szinids = [];
            }
            $szinrepo = $this->getEm()->getRepository(Szin::class);
            $szinkepmap = [];
            foreach ($obj->getTermekSzinKepek() as $szinkep) {
                $szinid = $szinkep->getSzinId();
                $kepid = $szinkep->getKepId() ?: termekkepController::FOKEP_ID;
                if ($szinid) {
                    $szinkepmap[$szinid][$kepid] = $szinkep;
                }
            }
            $szinidset = array_flip($szinids);
            foreach ($szinkepmap as $szinid => $kepmap) {
                if (!isset($szinidset[$szinid])) {
                    foreach ($kepmap as $szinkep) {
                        $obj->removeTermekSzinKep($szinkep);
                        $this->getEm()->remove($szinkep);
                    }
                }
            }
            foreach ($szinids as $szinid) {
                $szinid = (int)$szinid;
                $kepids = $this->params->getArrayRequestParam('szinkepimg_' . $szinid);
                $sorrendek = $this->params->getArrayRequestParam('szinkepsorrend_' . $szinid);
                $kepids = array_values(array_unique(array_filter(array_map('intval', $kepids))));
                $existing = $szinkepmap[$szinid] ?? [];

                foreach ($existing as $existingKepid => $szinkep) {
                    if (!in_array($existingKepid, $kepids, true)) {
                        $obj->removeTermekSzinKep($szinkep);
                        $this->getEm()->remove($szinkep);
                    } else {
                        // Update sorrend for existing
                        $kepidIndex = array_search($existingKepid, $kepids);
                        if ($kepidIndex !== false && isset($sorrendek[$kepidIndex])) {
                            $szinkep->setSorrend((int)$sorrendek[$kepidIndex]);
                            $this->getEm()->persist($szinkep);
                        }
                    }
                }

                if ($kepids) {
                    $szin = $szinrepo->find($szinid);
                    if ($szin) {
                        foreach ($kepids as $index => $kepid) {
                            if (!isset($existing[$kepid])) {
                                // az űrlapon lévő kulcs alapján, nem az adatbázisból: új terméknél
                                // a kép is most jött létre, és még nincs id-je
                                $fokep = ($kepid === termekkepController::FOKEP_ID);
                                $kep = $fokep ? null : ($kepmap[(string)$kepid] ?? null);
                                if ($kep || $fokep) {
                                    $szinkep = new \Entities\TermekSzinKep();
                                    $szinkep->setSzin($szin);
                                    // a főkép nem termekkep sor, ilyenkor a hivatkozás marad üres
                                    $szinkep->setKep($kep);
                                    if (isset($sorrendek[$index])) {
                                        $szinkep->setSorrend((int)$sorrendek[$index]);
                                    }
                                    $obj->addTermekSzinKep($szinkep);
                                    $this->getEm()->persist($szinkep);
                                }
                            }
                        }
                    }
                } elseif ($existing) {
                    foreach ($existing as $szinkep) {
                        $obj->removeTermekSzinKep($szinkep);
                        $this->getEm()->remove($szinkep);
                    }
                }
            }
        }

        $this->saveMinKeszletMatrix($obj, $valtozatmap);
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
        \Services\KeszletService::clearCache();
        if ($this->kaphatolett) {
            $tec = new termekertesitoController();
            $tec->sendErtesito($o);
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
            $nflike = '%' . str_replace("'", "''", $this->params->getStringRequestParam('nevfilter')) . '%';
            $lit = "'" . $nflike . "'";
            $filter->addSql(
                '_xx.nev LIKE ' . $lit
                . ' OR _xx.rovidleiras LIKE ' . $lit
                . ' OR _xx.cikkszam LIKE ' . $lit
                . ' OR _xx.vonalkod LIKE ' . $lit
                . ' OR _xx.idegencikkszam LIKE ' . $lit
                . ' OR EXISTS (SELECT vnf.id FROM Entities\\TermekValtozat vnf'
                . ' WHERE IDENTITY(vnf.termek) = _xx.id AND ((vnf.cikkszam LIKE ' . $lit . ') OR (vnf.vonalkod LIKE ' . $lit . ')))'
            );
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
            $res = \mkw\store::getEm()->getRepository(Termekcimketorzs::class)->getTermekIdsWithCimke($fv);
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
                            'cikkszam' => $valt->getCikkszam(),
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

    protected function _getMeretSzinhez()
    {
        $ret = [];
        $merettip = \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret);
        $termekid = $this->params->getIntRequestParam('t');
        $szin = $this->params->getStringRequestParam('sz');
        if ($termekid) {
            $valtozatok = $this->getRepo(TermekValtozat::class)->getOtherProperties(
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
                    // a méretválasztó sablon keszlet <= 0-t tesztel, ezért itt nincs nullára vágás
                    'keszlet' => $valt->getAvailableStock(null, null, null, false)
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

    protected function _getMeretSzinhezFix()
    {
        $ret = [];
        $termekid = $this->params->getIntRequestParam('t');
        $szinid = $this->params->getIntRequestParam('sz');
        if ($termekid) {
            $valtozatok = $this->getRepo(TermekValtozat::class)->getSizesByColor($termekid, $szinid);
            /** @var \Entities\TermekValtozat $valt */
            foreach ($valtozatok as $valt) {
                $ret[] = [
                    'id' => $valt->getId(),
                    'caption' => $valt->getMeretNev(),
                    'selected' => false,
                    // a méretválasztó sablon keszlet <= 0-t tesztel, ezért itt nincs nullára vágás
                    'keszlet' => $valt->getAvailableStock(null, null, null, false)
                ];
            }
        }
        $v = $this->getTemplateFactory()->createMainView('meretselect.tpl');
        $v->setVar('sizes', $ret);
        $v->setVar('meretek', $ret);
        $v->setVar('termekid', $termekid);
        echo $v->getTemplateResult();
    }

    public function getMeretSzinhez()
    {
        if (\mkw\store::isFixSzinMode()) {
            $this->_getMeretSzinhezFix();
        } else {
            $this->_getMeretSzinhez();
        }
    }

    public function getKapcsolodoSelectList()
    {
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = [];
        if ($term) {
            $filter = new FilterDescriptor();
            $filter->addFilter(['_xx.nev', '_xx.cikkszam', '_xx.vonalkod'], 'LIKE', '%' . $term . '%');
            $res = \mkw\store::getEm()->getRepository(Termek::class)->getAllForSelectList($filter);
            foreach ($res as $r) {
                $ret[] = [
                    'id' => $r['id'],
                    'value' => $r['nev']
                ];
            }
        }
        echo json_encode($ret);
    }

    /**
     * Egy termék adatai a bizonylattétel-sor kitöltéséhez – ezt kapja az autocomplete és az
     * xlsx/csv alapú tételimport is, hogy a kétféle úton azonos mezők kerüljenek a sorba.
     *
     * @param \Entities\Termek $termek
     * @param int $valtozatid a kód alapján beazonosított változat, ha volt ilyen
     */
    public function getBizonylattetelAdat($termek, $valtozatid = 0)
    {
        $ret = [
            'value' => $termek->getKiirtnev() ?: $termek->getNev(),
            'id' => $termek->getId(),
            'kellegyediazonosito' => (bool)$termek->getKellegyediazonosito(),
            'valtozat' => $valtozatid,
            'me' => $termek->getMekodId(),
            'cikkszam' => $termek->getCikkszam(),
            'vtsz' => $termek->getVtszId(),
            'afa' => $termek->getAfaId(),
            'afakulcs' => $termek->getAfa()->getErtek(),
            'kozepeskepurl' => $termek->getKepurlMedium(),
            'kiskepurl' => $termek->getKepurlSmall(),
            'kepurl400' => $termek->getKepurl400(),
            'kepurl2000' => $termek->getKepurl2000(),
            'kepurl' => $termek->getKepUrlLarge(),
            'slug' => $termek->getSlug(),
            'link' => \mkw\store::getRouter()->generate('showtermek', \mkw\store::getConfigValue('mainurl'), ['slug' => $termek->getSlug()]),
            'mainurl' => \mkw\store::getConfigValue('mainurl'),
            'nemlathato' => (!$termek->getXLathato() || $termek->getInaktiv() || $termek->getNemkaphato()),
            'defaultmennyiseg' => \mkw\store::getParameter(\mkw\consts::BizonylatMennyiseg, 0),
            'kartonurl' => \mkw\store::getRouter()->generate('admintermekkartonview', false, [], ['id' => $termek->getId()])
        ];
        if (\mkw\store::isSuperzoneB2B()) {
            $ret['label'] = $termek->getCikkszam() . ' ' . $termek->getNev();
        }
        return $ret;
    }

    public function getBizonylattetelSelectList()
    {
        $ret = [];
        if (!\mkw\store::isTermekAutocomplete()) {
            /** @var \Entities\Termek $termek */
            $termek = \mkw\store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('id'));
            if ($termek) {
                $ret = $this->getBizonylattetelAdat($termek);
                unset($ret['valtozat']);
            }
        } else {
            $term = trim($this->params->getStringRequestParam('term'));
            if ($term) {
                $res = \mkw\store::getEm()->getRepository(Termek::class)->getBizonylattetelLista($term);
                $termekidk = [];
                foreach ($res as $_t) {
                    $termekidk[] = $_t->getId();
                }
                $valtozatmatch = \mkw\store::getEm()->getRepository(TermekValtozat::class)
                    ->getCikkszamMatchMap($termekidk, $term);
                foreach ($res as $r) {
                    $ret[] = $this->getBizonylattetelAdat($r, ($valtozatmatch[$r->getId()] ?? 0));
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
        $tcc = new termekcimkekatController();
        $view->setVar('cimkekat', $tcc->getWithCimkek(null));
        $gyarto = new partnerController();
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList(0));
        $tcs = new termekcsoportController();
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
        $tcc = new termekcimkekatController();
        $cimkek = $termek ? $termek->getAllCimkeId() : null;
        $view->setVar('cimkekat', $tcc->getWithCimkek($cimkek));

        $view->setVar('egyed', $this->loadVars($termek, true));

        $vtsz = new vtszController();
        $view->setVar('vtszlist', $vtsz->getSelectList(($termek ? $termek->getVtszId() : 0)));

        $afa = new afaController();
        $view->setVar('afalist', $afa->getSelectList(($termek ? $termek->getAfaId() : 0)));

        $valtozatadattipus = new termekvaltozatadattipusController();
        $view->setVar('valtozatadattipuslist', $valtozatadattipus->getSelectList(($termek ? $termek->getValtozatadattipusId() : 0)));

        $meretsor = new meretsorController();
        $view->setVar('meretsorlist', $meretsor->getSelectList());

        $kep = new termekkepController();
        $view->setVar('keplist', $kep->getSelectList($termek, null));

        $gyarto = new partnerController();
        $view->setVar('gyartolist', $gyarto->getSzallitoSelectList(($termek ? $termek->getGyartoId() : 0)));

        $csoport = new termekcsoportController();
        $view->setVar('termekcsoportlist', $csoport->getSelectList(($termek ? $termek->getTermekcsoportId() : 0)));

        $me = new meController();
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
                case 'uj':
                    $obj->setUj($kibe);
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
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
            if ($kaphatolett) {
                $tec = new termekertesitoController();
                $tec->sendErtesito($obj);
            }
        }
    }

    public function getbrutto()
    {
        $id = $this->params->getIntRequestParam('id');
        $netto = $this->params->getFloatRequestParam('value');
        $afa = $this->getEm()->getRepository(Afa::class)->find($this->params->getIntRequestParam('afakod'));
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
        $afa = $this->getEm()->getRepository(Afa::class)->find($this->params->getIntRequestParam('afakod'));
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

        $ret = [];

        if (\mkw\store::isMugenrace2026() || \mkw\store::isSuperzoneHu()) {
            $tf = new termekmenuController();
            if ($termek->getTermekmenu1()) {
                $ret['navigator'] = $tf->getNavigator($termek->getTermekmenu1(), true);
            } else {
                $ret['navigator'] = [];
            }
        } else {
            $tfc = new termekfaController();
            if ($termek->getTermekfa1()) {
                $ret['navigator'] = $tfc->getNavigator($termek->getTermekfa1(), true);
            } else {
                $ret['navigator'] = [];
            }
        }
        $ret['termek'] = $termek->toTermekLap(null, $ujtermekminid, $top10min);

        $termek->incMegtekintesdb();
        if (\mkw\store::isMindentkapni()) {
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
        $mc = new mainController();
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
        $mc = new mainController();
        $mc->show404('HTTP/1.1 410 Gone');
    }

    public function getKeszletByRaktar()
    {
        $termekid = $this->params->getIntRequestParam('termekid');
        $termek = $this->getRepo()->find($termekid);

        $raktarak = $this->getRepo(Raktar::class)->getAllActive();
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
                ->setCellValue(\mkw\store::getExcelCoordinate($oszlop) . '1', $nettobrutto . '_' . $arsav['valutanem'] . '_' . $arsav['azonosito']);
            $oszlop++;
        }

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', 'IN', $ids);
        $termekek = $this->getRepo()->getWithArak($filter, []);
        $sor = 2;
        foreach ($termekek as $termek) {
            $excel->setActiveSheetIndex(0)
                ->setCellValue(\mkw\store::getExcelCoordinate(0) . $sor, $termek->getId())
                ->setCellValue(\mkw\store::getExcelCoordinate(1) . $sor, $termek->getCikkszam())
                ->setCellValue(\mkw\store::getExcelCoordinate(2) . $sor, $termek->getNev());
            $arak = $termek->getTermekArak();
            /** @var TermekAr $ar */
            foreach ($arak as $ar) {
                $i = array_search(
                    [
                        'id' => $ar->getArsav()?->getId(),
                        'valutanemid' => $ar->getValutanem()?->getId(),
                        'valutanem' => $ar->getValutanem()?->getNev(),
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
                        ->setCellValue(\mkw\store::getExcelCoordinate(3 + $i) . $sor, $nettobrutto);
                }
            }
            $sor++;
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('termekarak') . '.xlsx';
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

    public function fcmotoexport()
    {
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
            $nev = $termek->getLocalizedFieldValue('nev', 'en_us');
            $leiras = $termek->getLocalizedFieldValue('leiras', 'en_us');

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
                    ->setCellValue('K' . $sor, $termek->getNettoAr(null, $partner))
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

    /**
     * GS1 számkiadási export: azoknak a termékeknek/változatoknak, amelyeknek még nincs
     * vonalkódjuk, a GS1 által várt Excel (exporttemplates/gs1template.xlsx). A visszakapott
     * fájlt a {@see gs1import()} tölti vissza – ezért írjuk az utolsó két oszlopba a saját
     * azonosítót és azt, hogy termékről vagy változatról van szó.
     *
     * A GS1 sablon a 3. sortól várja az adatokat, a fejléc két sor (kódok + magyar címkék).
     */
    public function gs1export()
    {
        $ids = explode(',', $this->params->getStringRequestParam('ids'));

        $filenev = \mkw\store::exporttemplatePath('gs1template.xlsx');
        $reader = IOFactory::createReader(IOFactory::identify($filenev));
        $excel = $reader->load($filenev);
        $sheet = $excel->setActiveSheetIndex(0);

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('id', 'IN', $ids);
        $termekek = $this->getRepo()->getWithValtozatok($filter);

        $sor = 3;
        /** @var Termek $termek */
        foreach ($termekek as $termek) {
            if ($termek->getValtozatok() && count($termek->getValtozatok())) {
                /** @var TermekValtozat $valtozat */
                foreach ($termek->getValtozatok() as $valtozat) {
                    if (!$valtozat->getVonalkod()) {
                        $this->gs1ExportSor(
                            $sheet,
                            $sor++,
                            $termek,
                            trim($valtozat->getSzin() . ' ' . $valtozat->getMeret()),
                            $valtozat->getCikkszam(),
                            $valtozat->getId(),
                            self::GS1AZONOSITOTIPUS_VALTOZAT
                        );
                    }
                }
            } elseif (!$termek->getVonalkod()) {
                $this->gs1ExportSor(
                    $sheet,
                    $sor++,
                    $termek,
                    '',
                    $termek->getCikkszam(),
                    $termek->getId(),
                    self::GS1AZONOSITOTIPUS_TERMEK
                );
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

    /**
     * Egy sor a GS1 exportba. A GTIN oszlopot (C) szándékosan üresen hagyjuk: azt adja ki a GS1.
     *
     * A márkanév, az almárka és a funkcionális név hármasát a termék angol nevéből bontjuk:
     * az utolsó szó az almárka (modellnév), az előtte lévő rész a funkcionális név –
     * "KEVLAR JEANS CHINOS" → "KEVLAR JEANS" + "CHINOS".
     *
     * @param \Entities\Termek $termek
     */
    private function gs1ExportSor($sheet, int $sor, $termek, string $variansnev, $cikkszam, $sajatid, string $azonositotipus): void
    {
        $nev = trim((string)$termek->getLocalizedFieldValue('nev', 'en_us'));
        $szokoz = mb_strrpos($nev, ' ');
        $almarka = ($szokoz === false) ? '' : mb_substr($nev, $szokoz + 1);
        $funkcionalisnev = ($szokoz === false) ? $nev : mb_substr($nev, 0, $szokoz);

        $kereskedelminev = implode(' ', array_filter([
            self::GS1MARKANEV,
            $almarka,
            $funkcionalisnev,
            $variansnev,
            self::GS1NETTOMENNYISEG . ' Piece',
        ], fn($v) => $v !== ''));

        $sheet
            ->setCellValue('A' . $sor, \mkw\store::getParameter(\mkw\consts::GS1Datasource))
            ->setCellValue('B' . $sor, \mkw\store::getParameter(\mkw\consts::GS1DatasourceName))
            ->setCellValue('D' . $sor, 'Igen')                  // Fogyasztói egység?
            ->setCellValue('E' . $sor, 'Alaptermék')            // Csomagolási (hierarchia) szint
            ->setCellValue('F' . $sor, 'Nem')                   // Változó mennyiségű egység?
            ->setCellValue('G' . $sor, 'Igen')                  // Fizikai méretekkel rendelkező termék?
            ->setCellValue('H' . $sor, $this->gs1Gpc($termek))
            ->setCellValue('I' . $sor, self::GS1MARKANEV)
            ->setCellValue('J' . $sor, $almarka)
            ->setCellValue('K' . $sor, $funkcionalisnev)
            ->setCellValue('L' . $sor, 'Angol')
            ->setCellValue('M' . $sor, $variansnev)
            ->setCellValue('N' . $sor, 'Angol')
            ->setCellValue('O' . $sor, self::GS1NETTOMENNYISEG)
            ->setCellValue('P' . $sor, 'Darab')
            ->setCellValue('Q' . $sor, $kereskedelminev)
            ->setCellValue('R' . $sor, 'Angol')
            ->setCellValue('S' . $sor, $termek->getSuly())
            ->setCellValue('T' . $sor, 'Kilogramm')
            ->setCellValue('U' . $sor, 'Centiméter')            // mindhárom befoglaló méret egysége
            ->setCellValue('V' . $sor, $termek->getMagassag())
            ->setCellValue('W' . $sor, $termek->getHosszusag())
            ->setCellValue('X' . $sor, $termek->getSzelesseg())
            ->setCellValue('Y' . $sor, 'Alap')                  // Attribútum készlet
            ->setCellValue('Z' . $sor, $cikkszam)
            ->setCellValue('AA' . $sor, 'Beszállító által kiadott (Belső azonosító)')
            ->setCellValue('AB' . $sor, $sajatid)
            ->setCellValue('AC' . $sor, $azonositotipus);
    }

    /**
     * A termék GS1 besorolása (GPC brick kód) a kategóriájából, a fastruktúrában felfelé
     * keresve. Ha sehol nincs beállítva, üresen marad – a GS1 kötelező mezője, tehát a
     * kategóriákon ki kell tölteni.
     *
     * @param \Entities\Termek $termek
     */
    private function gs1Gpc($termek): string
    {
        /** @var TermekFa|null $kat */
        $kat = $termek->getTermekfa1();
        return $kat ? $kat->getOroklottGpc() : '';
    }

    /**
     * A GS1-től visszakapott számkiadási fájl visszatöltése: a C oszlopban álló GTIN a
     * vonalkód, az AB oszlop a mi azonosítónk, az AC pedig megmondja, termékre vagy változatra
     * vonatkozik (üresen – a régebbi exportoknál – változatra).
     *
     * Meglévő vonalkódot nem írunk felül, azt hibaként jelezzük vissza.
     */
    public function gs1import()
    {
        header('Content-Type: application/json; charset=utf-8');

        $filepath = \mkw\store::moveUploadedFile('toimport', 'gs1import', ['xls', 'xlsx']);
        if (!$filepath) {
            echo json_encode(['ok' => false, 'error' => t('Csak .xls vagy .xlsx fájl tölthető fel.')]);
            return;
        }

        try {
            $reader = IOFactory::createReader(IOFactory::identify($filepath));
            $reader->setReadDataOnly(true);
            $excel = $reader->load($filepath);
        } catch (\Exception $e) {
            \unlink($filepath);
            echo json_encode(['ok' => false, 'error' => t('A fájl nem olvasható táblázatként') . ': ' . $e->getMessage()]);
            return;
        }

        $sheet = $excel->getSheetByName('Data') ?: $excel->getActiveSheet();
        $maxrow = (int)$sheet->getHighestRow();

        $sorok = 0;
        $frissitett = 0;
        $hibak = [];
        for ($row = 3; $row <= $maxrow; ++$row) {
            $vonalkod = trim((string)$sheet->getCell('C' . $row)->getValue());
            $sajatid = (int)$sheet->getCell('AB' . $row)->getValue();
            if (!$vonalkod && !$sajatid) {
                continue;
            }
            $sorok++;
            if (!$vonalkod) {
                $hibak[] = sprintf(t('%d. sor: nincs kiadott GTIN.'), $row);
                continue;
            }
            if (!$sajatid) {
                $hibak[] = sprintf(t('%d. sor (%s): nincs benne a mi azonosítónk.'), $row, $vonalkod);
                continue;
            }

            $tipus = trim((string)$sheet->getCell('AC' . $row)->getValue());
            $egyed = ($tipus === self::GS1AZONOSITOTIPUS_TERMEK)
                ? $this->getRepo()->find($sajatid)
                : $this->getRepo(TermekValtozat::class)->find($sajatid);
            if (!$egyed) {
                $hibak[] = sprintf(t('%d. sor (%s): a hivatkozott azonosító (%d) nincs meg.'), $row, $vonalkod, $sajatid);
                continue;
            }
            $regi = trim((string)$egyed->getVonalkod());
            if ($regi === $vonalkod) {
                continue;
            }
            if ($regi !== '') {
                $hibak[] = sprintf(t('%d. sor: %s már kapott vonalkódot (%s), nem írjuk felül.'), $row, $sajatid, $regi);
                continue;
            }
            $egyed->setVonalkod($vonalkod);
            $this->getEm()->persist($egyed);
            $frissitett++;
        }
        $this->getEm()->flush();
        $excel->disconnectWorksheets();
        \unlink($filepath);

        echo json_encode([
            'ok' => true,
            'msg' => sprintf(t('%d sor feldolgozva, %d vonalkód került fel.'), $sorok, $frissitett),
            'hibak' => $hibak,
        ]);
    }

    public function gs1importView()
    {
        $view = $this->createView('gs1import.tpl');
        $view->setVar('pagetitle', t('GS1 vonalkód import'));
        $view->printTemplateResult();
    }

    public function colorexport()
    {
        $tvec = new termekvaltozatertekController();
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
                        ->setCellValue(\mkw\store::getExcelCoordinate(0) . $sor, $termek->getId())
                        ->setCellValue(\mkw\store::getExcelCoordinate(1) . $sor, $termek->getNev())
                        ->setCellValue(\mkw\store::getExcelCoordinate(2) . $sor, $termek->getCikkszam())
                        ->setCellValue(\mkw\store::getExcelCoordinate(3) . $sor, $termek->getIdegencikkszam())
                        ->setCellValue(\mkw\store::getExcelCoordinate(4) . $sor, $valtozat->getId())
                        ->setCellValue(\mkw\store::getExcelCoordinate(5) . $sor, $valtozat->getAdatTipus1Nev())
                        ->setCellValue(\mkw\store::getExcelCoordinate(6) . $sor, $valtozat->getAdatTipus2Nev())
                        ->setCellValue(\mkw\store::getExcelCoordinate(7) . $sor, $valtozat->getCikkszam())
                        ->setCellValue(\mkw\store::getExcelCoordinate(8) . $sor, $valtozat->getIdegencikkszam());
                    $sor++;
                }
            } else {
                $excel->setActiveSheetIndex(0)
                    ->setCellValue(\mkw\store::getExcelCoordinate(0) . $sor, $termek->getId())
                    ->setCellValue(\mkw\store::getExcelCoordinate(1) . $sor, $termek->getNev())
                    ->setCellValue(\mkw\store::getExcelCoordinate(2) . $sor, $termek->getCikkszam())
                    ->setCellValue(\mkw\store::getExcelCoordinate(3) . $sor, $termek->getIdegencikkszam());
                $sor++;
            }
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');

        $filename = uniqid('cikkszamosexport') . '.xlsx';
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

    /**
     * A minimum készletek karbantartó Excelje. Kijelölés nélkül a teljes törzs – a minimum
     * készlet megadása jellemzően pont az egész készletre megy.
     */
    public function minKeszletExport()
    {
        $ids = array_filter(explode(',', $this->params->getStringRequestParam('ids')));
        $excel = (new \Services\MinKeszletExcelService())->export($ids);

        $filename = uniqid('minkeszlet') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        IOFactory::createWriter($excel, 'Xlsx')->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename=' . $filename);

        readfile($filepath);
        \unlink($filepath);
    }

    public function setTermekcsoport()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        //$ids = explode(',', $ids);
        if ($ids) {
            $tcsid = $this->params->getIntRequestParam('tcs');
            $tcs = $this->getRepo(Termekcsoport::class)->find($tcsid);

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
                if (($termekdb % $batchsize) === 0) {
                    $this->getEm()->flush();
                }
            }
            $this->getEm()->flush();
            $this->getEm()->clear();
        }
    }

    public function setKategoria()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        if ($ids) {
            $faid = $this->params->getIntRequestParam('fa');
            /** @var \Entities\TermekFa $fa */
            $fa = $this->getRepo(TermekFa::class)->find($faid);

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('id', 'IN', $ids);
            $termekek = $this->getRepo()->getAll($filter, []);
            $termekdb = 0;
            $batchsize = 20;
            /** @var Termek $termek */
            foreach ($termekek as $termek) {
                $termekdb++;
                $termek->setTermekfa1($fa ?: null);
                $this->getEm()->persist($termek);
                if (($termekdb % $batchsize) === 0) {
                    $this->getEm()->flush();
                }
            }
            $this->getEm()->flush();
            $this->getEm()->clear();
        }
    }

    private function getLeirasTisztitoSanitizer()
    {
        return new \mkwhelpers\HtmlPurifierSanitizer([
            'HTML.ForbiddenAttributes' => 'style,class',
            'HTML.SafeIframe' => true,
            'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
            'Attr.AllowedFrameTargets' => ['_blank'],
        ]);
    }

    public function leirasTisztitas()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $db = 0;
        if ($ids) {
            $puri = $this->getLeirasTisztitoSanitizer();

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('id', 'IN', $ids);
            $termekek = $this->getRepo()->getAll($filter, []);
            $batchsize = 20;
            /** @var Termek $termek */
            foreach ($termekek as $termek) {
                $db++;
                $leiras = $termek->getLeiras();
                if ($leiras) {
                    $termek->setLeiras($puri->sanitize($leiras));
                }
                $leirasl1 = $termek->getLeirasL1();
                if ($leirasl1) {
                    $termek->setLeirasL1($puri->sanitize($leirasl1));
                }
                $this->getEm()->persist($termek);
                if (($db % $batchsize) === 0) {
                    $this->getEm()->flush();
                }
            }
            $this->getEm()->flush();
            $this->getEm()->clear();
        }
        echo json_encode(['db' => $db]);
    }

    public function createTermekKepekFromFields()
    {
        $termekek = $this->getRepo()->getAll();
        $batchsize = 20;
        $i = 0;
        /** @var Termek $termek */
        foreach ($termekek as $termek) {
            if ($termek->getKepurl()) {
                $termekkep = new TermekKep();
                $termekkep->setTermek($termek);
                $termekkep->setUrl($termek->getKepurl());
                $termekkep->setLeiras($termek->getKepleiras());
                $this->getEm()->persist($termekkep);
                $i++;
                if (($i % $batchsize) === 0) {
                    $this->getEm()->flush();
                }
            }
        }
        $this->getEm()->flush();
        echo 'Done';
    }
}