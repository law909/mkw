<?php

namespace Controllers;

use Entities\Meretsor;
use Entities\Szin;
use Entities\Termek;
use Entities\TermekKep;
use Entities\TermekValtozat;
use Entities\TermekValtozatAdatTipus;
use Entities\TermekValtozatErtek;
use mkw\store;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Services\TermekValtozatCikkszamReportService;
use Services\TermekValtozatCikkszamService;
use Services\TermekValtozatMergeService;

class termekvaltozatController extends \mkwhelpers\MattableController
{

    /**
     * A legördülőket építő kontrollerek. Azért példánymezők, mert a loadVars() változatonként
     * fut: friss példánnyal a bennük lévő gyorsítótár soronként újraindulna.
     */
    private $tvatc;
    private $tkepc;
    private $szinc;
    private $meretc;

    public function __construct()
    {
        $this->setEntityName(TermekValtozat::class);
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $termek, $forKarb = false)
    {
        $tvatc = $this->tvatc ??= new termekvaltozatadattipusController();
        $tkepc = $this->tkepc ??= new termekkepController();
        $szinc = $this->szinc ??= new szinController();
        $meretc = $this->meretc ??= new meretController();
        $x = [];
        if (!$t) {
            $t = new \Entities\TermekValtozat();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
            $x['termek']['id'] = $termek ? $termek->getId() : null;
            $x['keplista'] = $termek ? $tkepc->getSelectList($termek, null) : [];
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
            $x['keplista'] = $tkepc->getSelectList($t->getTermek(), $t->getKepid());
        }
        $x = $this->getEntityFieldsArray($t, $x);
        $x['adattipus1id'] = $t->getAdatTipus1Id();
        $x['adattipus1nev'] = $t->getAdatTipus1Nev();
        $x['adattipus1lista'] = $tvatc->getSelectList(
            \mkw\store::isFixSzinMode() && $x['oper'] == 'add' ? \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin) : $t->getAdatTipus1Id()
        );
        $x['adattipus2id'] = $t->getAdatTipus2Id();
        $x['adattipus2nev'] = $t->getAdatTipus2Nev();
        $x['adattipus2lista'] = $tvatc->getSelectList(
            \mkw\store::isFixSzinMode() && $x['oper'] == 'add' ? \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret) : $t->getAdatTipus2Id()
        );
        $x['kepid'] = $t->getKepId();
        $x['keszlet'] = $t->getKeszlet();
        $x['fifo'] = \mkw\store::isFifo() ? \Services\FifoService::getErtek($t) : null;
        $x['foglaltmennyiseg'] = $t->getFoglaltMennyiseg();
        $x['szabadkeszlet'] = $t->getAvailableStock(null, null, null, false);
        $x['erkezik'] = $t->getIncomingStock();
        $x['beerkezesdatumstr'] = $t->getBeerkezesdatumStr();
        $x['elorendelheto'] = $t->isElorendelheto();
        if (\mkw\store::isFixSzinMode()) {
            $x['szinid'] = $t->getSzinId();
            $x['meretid'] = $t->getMeretId();
            if ($forKarb) {
                $x['szinlista'] = $szinc->getSelectList($t->getSzinId());
                $x['meretlista'] = $meretc->getSelectList($t->getMeretId());
            }
        }
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();

        return $x;
    }

    protected function setFields($obj)
    {
        $obj->setLathato($this->params->getBoolRequestParam('lathato', false));
        return $obj;
    }

    public function getemptyrow()
    {
        $termek = store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        $view = $this->createView('termektermekvaltozatkarb.tpl');
        $view->setVar('valtozat', $this->loadVars(null, $termek, true));
        echo $view->getTemplateResult();
    }

    /**
     * @param TermekValtozat $o
     *
     * @return void
     */
    protected function beforeRemove($o)
    {
        \Services\KeszletSzintService::removeByTermekValtozat($o);
        parent::beforeRemove($o);
    }

    /**
     * @param TermekValtozat $o
     * @param $parancs
     *
     * @return void
     */
    protected function afterSave($o, $parancs = null)
    {
        switch ($parancs) {
            case $this->delOperation:
                $termek = $o->getTermek();
                $szinId = $o->getSzinId();
                if ($termek && $szinId) {
                    foreach ($termek->getTermekSzinKepek() as $szinkep) {
                        if ($szinkep->getSzinId() === $szinId) {
                            $termek->removeTermekSzinKep($szinkep);
                            $this->getEm()->remove($szinkep);
                        }
                    }
                    $this->getEm()->flush();
                }
        }
    }

    public function delall()
    {
        $termek = store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        $valtozatok = $termek->getValtozatok();
        $ids = [];
        foreach ($valtozatok as $valt) {
            //$termek->removeValtozat($valt);
            \Services\KeszletSzintService::removeByTermekValtozat($valt);
            $this->getEm()->remove($valt);
        }
        $this->getEm()->flush();
    }

    public function cikkszamAtirasView()
    {
        $view = $this->createView('valtozatcikkszamatiras.tpl');
        $view->setVar('pagetitle', t('Változat cikkszám átírás'));
        $view->setVar('erintett', (new TermekValtozatCikkszamService())->countPending());
        $view->printTemplateResult();
    }

    public function cikkszamAtiras()
    {
        header('Content-Type: application/json; charset=utf-8');
        $counts = (new TermekValtozatCikkszamService())->rewrite();
        $uzenet = [];
        foreach ($counts as $mit => $db) {
            $uzenet[] = $db . ' ' . $mit;
        }
        echo json_encode([
            'ok' => true,
            'msg' => implode(', ', $uzenet) . '.',
            'erintett' => (new TermekValtozatCikkszamService())->countPending(),
        ]);
    }

    /** a kimutatás és a visszajelzés sorainak felirata */
    private const OSSZEVONASFELIRAT = [
        'bizonylattetel' => 'Bizonylattétel',
        'munkalap' => 'Munkalap (bizonylat feje)',
        'kosar' => 'Kosár',
        'leltartetel' => 'Leltártétel',
        'minkeszlet' => 'Raktáras minimum készlet',
        'optkeszlet' => 'Raktáras optimális készlet',
        'fiforeteg' => 'FIFO réteg',
        'fifoertek' => 'FIFO készletérték',
    ];

    /** ez alatt a jog alatt a képernyő és a hozzá tartozó végpontok sem érhetők el */
    private const OSSZEVONASJOG = 40;

    /**
     * Változat összevonás. A képernyő három lépésben dolgozik: termék- és változatválasztás,
     * az érintett sorok kimutatása, végül két megerősítés után a végrehajtás. Magát a műveletet
     * a Services\TermekValtozatMergeService végzi, itt csak a kérés és a válasz áll össze.
     */
    public function osszevonasView()
    {
        if (!store::haveJog(self::OSSZEVONASJOG)) {
            return;
        }
        $view = $this->createView('valtozatosszevonas.tpl');
        $view->setVar('pagetitle', t('Változat összevonás'));
        $view->printTemplateResult();
    }

    /** Termékkereső az összevonáshoz: név, cikkszám, vonalkód és a változatok cikkszáma szerint. */
    public function osszevonasTermekLista()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!store::haveJog(self::OSSZEVONASJOG)) {
            $this->jsonFail(t('Nincs jogosultsága a művelethez.'));
            return;
        }
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = [];
        if (mb_strlen($term) >= 2) {
            /** @var Termek $termek */
            foreach ($this->getRepo(Termek::class)->getBizonylattetelLista($term) as $termek) {
                $ret[] = [
                    'id' => $termek->getId(),
                    'label' => trim($termek->getCikkszam() . ' ' . $termek->getNev()),
                    'value' => $termek->getNev(),
                ];
            }
        }
        echo json_encode($ret);
    }

    /**
     * Egy termék változatai a két választóhoz. A getValtozatList()-tel szemben az inaktívakat is
     * adja: összevonni tipikusan éppen azokat kell.
     */
    public function osszevonasValtozatLista()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!store::haveJog(self::OSSZEVONASJOG)) {
            $this->jsonFail(t('Nincs jogosultsága a művelethez.'));
            return;
        }
        /** @var Termek|null $termek */
        $termek = $this->getRepo(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        if (!$termek) {
            $this->jsonFail(t('Nincs ilyen termék.'));
            return;
        }
        $valtozatok = [];
        /** @var TermekValtozat $valt */
        foreach ($termek->getValtozatok() ?? [] as $valt) {
            $valtozatok[] = [
                'id' => $valt->getId(),
                'nev' => $valt->getNev(),
                'cikkszam' => $valt->getCikkszam(),
                'inaktiv' => (bool)$valt->getInaktiv(),
                'lathato' => (bool)$valt->getLathato(),
                'keszlet' => $valt->getKeszlet() * 1,
            ];
        }
        echo json_encode([
            'ok' => true,
            'termeknev' => $termek->getNev(),
            'termekcikkszam' => $termek->getCikkszam(),
            // a függőben és az inaktív a terméké: a változatnak csak inaktív jelzője van
            'termekfuggoben' => (bool)$termek->getFuggoben(),
            'termekinaktiv' => (bool)$termek->getInaktiv(),
            'valtozatok' => $valtozatok,
        ]);
    }

    /** Az első OK: mit érint az összevonás. Nem módosít semmit. */
    public function osszevonasStat()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!store::haveJog(self::OSSZEVONASJOG)) {
            $this->jsonFail(t('Nincs jogosultsága a művelethez.'));
            return;
        }
        $service = new TermekValtozatMergeService();
        try {
            [$forras, $cel] = $this->getOsszevonasValtozatok();
            $service->check($forras, $cel);
            $adat = $service->collect($forras, $cel);
        } catch (\Throwable $e) {
            $this->jsonFail($e->getMessage());
            return;
        }
        $sorok = [];
        $osszes = 0;
        foreach ($adat['sorok'] as $sor) {
            $osszes += $sor['db'];
            $sorok[] = [
                'nev' => t(self::OSSZEVONASFELIRAT[$sor['kulcs']] ?? $sor['kulcs']),
                'db' => $sor['db'],
                'utkozes' => $sor['utkozes'],
            ];
        }
        echo json_encode([
            'ok' => true,
            'forras' => $this->getOsszevonasValtozatNev($forras),
            'cel' => $this->getOsszevonasValtozatNev($cel),
            'sorok' => $sorok,
            'osszes' => $osszes,
        ]);
    }

    /** A két megerősítés után: a tényleges összevonás. */
    public function osszevonas()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!store::haveJog(self::OSSZEVONASJOG)) {
            $this->jsonFail(t('Nincs jogosultsága a művelethez.'));
            return;
        }
        try {
            [$forras, $cel] = $this->getOsszevonasValtozatok();
            $forrasnev = $this->getOsszevonasValtozatNev($forras);
            $celnev = $this->getOsszevonasValtozatNev($cel);
            $forrastorles = $this->params->getBoolRequestParam('forrastorles');
            $riport = (new TermekValtozatMergeService())->merge($forras, $cel, $forrastorles);
        } catch (\Throwable $e) {
            store::writelog('Változat összevonás hiba: ' . $e->getMessage());
            $this->jsonFail($e->getMessage());
            return;
        }
        $reszek = [];
        foreach ($riport['atirt'] as $kulcs => $db) {
            if ($db) {
                $reszek[] = $db . ' ' . mb_strtolower(t(self::OSSZEVONASFELIRAT[$kulcs] ?? $kulcs));
            }
        }
        $uzenet = sprintf(t('%s → %s összevonva.'), $forrasnev, $celnev)
            . ($reszek ? ' ' . sprintf(t('Átírva: %s.'), implode(', ', $reszek)) : ' ' . t('Átírandó sor nem volt.'))
            . ($riport['forrastorolve'] ? ' ' . t('A forrás változat törölve.') : '');
        store::writelog('Változat összevonás: ' . $uzenet);
        echo json_encode(['ok' => true, 'msg' => $uzenet]);
    }

    /**
     * @return TermekValtozat[] [forrás, cél]
     * @throws \RuntimeException ha valamelyik változat nincs meg
     */
    private function getOsszevonasValtozatok(): array
    {
        $repo = $this->getRepo(TermekValtozat::class);
        $forras = $repo->find($this->params->getIntRequestParam('forrasid'));
        $cel = $repo->find($this->params->getIntRequestParam('celid'));
        if (!$forras || !$cel) {
            throw new \RuntimeException(t('Mindkét változatot ki kell választani.'));
        }
        return [$forras, $cel];
    }

    private function getOsszevonasValtozatNev(TermekValtozat $valtozat): string
    {
        return trim($valtozat->getCikkszam() . ' ' . $valtozat->getNev()) ?: ('#' . $valtozat->getId());
    }

    public function cikkszamReport()
    {
        $excel = (new TermekValtozatCikkszamReportService())->createSpreadsheet((new exportController())->getFcmotoStockValtozatIds());
        header('Cache-Control: private');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=cikkszam-utkozesek-' . date('Ymd') . '.xlsx');
        IOFactory::createWriter($excel, 'Xlsx')->save('php://output');
    }

    public function generate()
    {
        $termek = store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        if (!$termek) {
            return;
        }

        $adattipus1 = $this->params->getIntRequestParam('valtozatadattipus1');
        $ertek1 = $this->params->getStringRequestParam('valtozatertek1');
        $adattipus2 = $this->params->getIntRequestParam('valtozatadattipus2');
        $ertek2 = $this->params->getStringRequestParam('valtozatertek2');
        $netto = $this->params->getNumRequestParam('valtozatnettogen');
        $brutto = $this->params->getNumRequestParam('valtozatbruttogen');
        $cikkszam = $this->params->getStringRequestParam('valtozatcikkszamgen');
        $idegencikkszam = $this->params->getStringRequestParam('valtozatidegencikkszamgen');
        $unasalaptipus = $this->params->getStringRequestParam('valtozatunasalaptipusgen');
        $elerheto = $this->params->getBoolRequestParam('valtozatelerheto', false);
        $elerheto2 = $this->params->getBoolRequestParam('valtozatelerheto2', false);
        $elerheto3 = $this->params->getBoolRequestParam('valtozatelerheto3', false);
        $elerheto4 = $this->params->getBoolRequestParam('valtozatelerheto4', false);
        $elerheto5 = $this->params->getBoolRequestParam('valtozatelerheto5', false);
        $elerheto6 = $this->params->getBoolRequestParam('valtozatelerheto6', false);
        $elerheto7 = $this->params->getBoolRequestParam('valtozatelerheto7', false);
        $elerheto8 = $this->params->getBoolRequestParam('valtozatelerheto8', false);
        $elerheto9 = $this->params->getBoolRequestParam('valtozatelerheto9', false);
        $elerheto10 = $this->params->getBoolRequestParam('valtozatelerheto10', false);
        $elerheto11 = $this->params->getBoolRequestParam('valtozatelerheto11', false);
        $elerheto12 = $this->params->getBoolRequestParam('valtozatelerheto12', false);
        $elerheto13 = $this->params->getBoolRequestParam('valtozatelerheto13', false);
        $elerheto14 = $this->params->getBoolRequestParam('valtozatelerheto14', false);
        $elerheto15 = $this->params->getBoolRequestParam('valtozatelerheto15', false);
        $lathato = $this->params->getBoolRequestParam('valtozatlathato', false);
        $lathato2 = $this->params->getBoolRequestParam('valtozatlathato2', false);
        $lathato3 = $this->params->getBoolRequestParam('valtozatlathato3', false);
        $lathato4 = $this->params->getBoolRequestParam('valtozatlathato4', false);
        $lathato5 = $this->params->getBoolRequestParam('valtozatlathato5', false);
        $lathato6 = $this->params->getBoolRequestParam('valtozatlathato6', false);
        $lathato7 = $this->params->getBoolRequestParam('valtozatlathato7', false);
        $lathato8 = $this->params->getBoolRequestParam('valtozatlathato8', false);
        $lathato9 = $this->params->getBoolRequestParam('valtozatlathato9', false);
        $lathato10 = $this->params->getBoolRequestParam('valtozatlathato10', false);
        $lathato11 = $this->params->getBoolRequestParam('valtozatlathato11', false);
        $lathato12 = $this->params->getBoolRequestParam('valtozatlathato12', false);
        $lathato13 = $this->params->getBoolRequestParam('valtozatlathato13', false);
        $lathato14 = $this->params->getBoolRequestParam('valtozatlathato14', false);
        $lathato15 = $this->params->getBoolRequestParam('valtozatlathato15', false);
        $termekfokep = $this->params->getBoolRequestParam('valtozattermekfokep', false);
        $elorendelheto = $this->params->getBoolRequestParam('valtozatelorendelheto', false);
        $kepid = $this->params->getIntRequestParam('valtozatkepid');

        if (store::isFixSzinMode()) {
            // a termék fő kategóriája dönti el, kell-e a cikkszámba a szín- és a méretkód (a fa örökléses jelölése)
            $withCharkod = (bool)$termek->getTermekfa1()?->isSzinmeretcikkszamEnabled();
            $szinid = $this->params->getIntRequestParam('valtozatszinid');
            $meretsorid = $this->params->getIntRequestParam('valtozatmeretsorid');
            if ($szinid && $meretsorid) {
                $szin = $this->getEm()->getRepository(Szin::class)->find($szinid);
                $meretsor = $this->getEm()->getRepository(Meretsor::class)->find($meretsorid);
                if ($szin && $meretsor) {
                    $meretek = $meretsor->getMeretek();
                    $cikkszamok = explode(';', $cikkszam);
                    $idegencikkszamok = explode(';', $idegencikkszam);
                    $cikl = 0;
                    $atSzin = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
                        \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)
                    );
                    $atMeret = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find(
                        \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret)
                    );
                    foreach ($meretek as $meret) {
                        $valtdb = 0;
                        $valtozat = new \Entities\TermekValtozat();
                        $termek->addValtozat($valtozat);
                        $valtozat->setTermek($termek);
                        $valtozat->setLathato($lathato);
                        $valtozat->setLathato2($lathato2);
                        $valtozat->setLathato3($lathato3);
                        $valtozat->setLathato4($lathato4);
                        $valtozat->setLathato5($lathato5);
                        $valtozat->setLathato6($lathato6);
                        $valtozat->setLathato7($lathato7);
                        $valtozat->setLathato8($lathato8);
                        $valtozat->setLathato9($lathato9);
                        $valtozat->setLathato10($lathato10);
                        $valtozat->setLathato11($lathato11);
                        $valtozat->setLathato12($lathato12);
                        $valtozat->setLathato13($lathato13);
                        $valtozat->setLathato14($lathato14);
                        $valtozat->setLathato15($lathato15);
                        if ($termek->getNemkaphato()) {
                            $valtozat->setElerheto(false);
                            $valtozat->setElerheto2(false);
                            $valtozat->setElerheto3(false);
                            $valtozat->setElerheto4(false);
                            $valtozat->setElerheto5(false);
                            $valtozat->setElerheto6(false);
                            $valtozat->setElerheto7(false);
                            $valtozat->setElerheto8(false);
                            $valtozat->setElerheto9(false);
                            $valtozat->setElerheto10(false);
                            $valtozat->setElerheto11(false);
                            $valtozat->setElerheto12(false);
                            $valtozat->setElerheto13(false);
                            $valtozat->setElerheto14(false);
                            $valtozat->setElerheto15(false);
                        } else {
                            $valtozat->setElerheto($elerheto);
                            $valtozat->setElerheto2($elerheto2);
                            $valtozat->setElerheto3($elerheto3);
                            $valtozat->setElerheto4($elerheto4);
                            $valtozat->setElerheto5($elerheto5);
                            $valtozat->setElerheto6($elerheto6);
                            $valtozat->setElerheto7($elerheto7);
                            $valtozat->setElerheto8($elerheto8);
                            $valtozat->setElerheto9($elerheto9);
                            $valtozat->setElerheto10($elerheto10);
                            $valtozat->setElerheto11($elerheto11);
                            $valtozat->setElerheto12($elerheto12);
                            $valtozat->setElerheto13($elerheto13);
                            $valtozat->setElerheto14($elerheto14);
                            $valtozat->setElerheto15($elerheto15);
                        }
                        $valtozat->setNetto($netto);
                        $valtozat->setTermekfokep($termekfokep);
                        $valtozat->setElorendelheto($elorendelheto);
                        $valtozatCikkszam = count($cikkszamok) == 1 ? $cikkszamok[0] : ($cikkszamok[$cikl] ?? null);
                        if ($withCharkod) {
                            $generated = TermekValtozat::composeCikkszam(
                                trim((string)$valtozatCikkszam) !== '' ? $valtozatCikkszam : $termek->getCikkszam(),
                                $szin->getCharkod(),
                                $szin->getNev(),
                                $meret->getCharkod(),
                                $meret->getNev()
                            );
                            // a mező 50 karakteres: a csonkolt cikkszám ütközhetne, inkább üres marad
                            $valtozat->setCikkszam(mb_strlen($generated) <= 50 ? $generated : '');
                            $valtozat->setKodoltcikkszam($valtozat->getCikkszam() !== '');
                        } elseif ($valtozatCikkszam !== null) {
                            $valtozat->setCikkszam($valtozatCikkszam);
                        }
                        if (count($idegencikkszamok) > 0) {
                            if (count($idegencikkszamok) == 1) {
                                $valtozat->setIdegencikkszam($idegencikkszamok[0]);
                            } elseif (array_key_exists($cikl, $idegencikkszamok)) {
                                $valtozat->setIdegencikkszam($idegencikkszamok[$cikl]);
                            }
                        }
                        $valtozat->setUnasalaptipus($unasalaptipus);
                        if ($szin) {
                            $valtozat->setSzin($szin);
                            if ($atSzin) {
                                $valtozat->setAdatTipus1($atSzin);
                                $valtozat->setErtek1($szin->getNev());
                            }
                            $valtdb++;
                        }
                        if ($meret) {
                            $valtozat->setMeret($meret);
                            if ($atMeret) {
                                $valtozat->setAdatTipus2($atMeret);
                                $valtozat->setErtek2($meret->getNev());
                            }
                            $valtdb++;
                        }

                        $kep = $this->getEm()->getRepository(TermekKep::class)->find($kepid);
                        if ($kep) {
                            $valtozat->setKep($kep);
                        }

                        if ($valtdb > 0) {
                            $this->getEm()->persist($valtozat);
                        } else {
                            $termek->removeValtozat($valtozat);
                        }
                        $cikl++;
                    }
                    $this->getEm()->flush();
                }
            }
        } elseif (($adattipus1 && $ertek1) || ($adattipus2 && $ertek2)) {
            $ertekek1 = explode(';', $ertek1);
            $ertekek2 = explode(';', $ertek2);
            $cikkszamok = explode(';', $cikkszam);
            $idegencikkszamok = explode(';', $idegencikkszam);
            $cikl = 0;
            $at1 = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find($adattipus1);
            $at2 = $this->getEm()->getRepository(TermekValtozatAdatTipus::class)->find($adattipus2);
            foreach ($ertekek1 as $ertek1) {
                foreach ($ertekek2 as $ertek2) {
                    $valtdb = 0;
                    $valtozat = new \Entities\TermekValtozat();
                    $termek->addValtozat($valtozat);
                    $valtozat->setTermek($termek);
                    $valtozat->setLathato($lathato);
                    $valtozat->setLathato2($lathato2);
                    $valtozat->setLathato3($lathato3);
                    $valtozat->setLathato4($lathato4);
                    $valtozat->setLathato5($lathato5);
                    $valtozat->setLathato6($lathato6);
                    $valtozat->setLathato7($lathato7);
                    $valtozat->setLathato8($lathato8);
                    $valtozat->setLathato9($lathato9);
                    $valtozat->setLathato10($lathato10);
                    $valtozat->setLathato11($lathato11);
                    $valtozat->setLathato12($lathato12);
                    $valtozat->setLathato13($lathato13);
                    $valtozat->setLathato14($lathato14);
                    $valtozat->setLathato15($lathato15);
                    if ($termek->getNemkaphato()) {
                        $valtozat->setElerheto(false);
                        $valtozat->setElerheto2(false);
                        $valtozat->setElerheto3(false);
                        $valtozat->setElerheto4(false);
                        $valtozat->setElerheto5(false);
                        $valtozat->setElerheto6(false);
                        $valtozat->setElerheto7(false);
                        $valtozat->setElerheto8(false);
                        $valtozat->setElerheto9(false);
                        $valtozat->setElerheto10(false);
                        $valtozat->setElerheto11(false);
                        $valtozat->setElerheto12(false);
                        $valtozat->setElerheto13(false);
                        $valtozat->setElerheto14(false);
                        $valtozat->setElerheto15(false);
                    } else {
                        $valtozat->setElerheto($elerheto);
                        $valtozat->setElerheto2($elerheto2);
                        $valtozat->setElerheto3($elerheto3);
                        $valtozat->setElerheto4($elerheto4);
                        $valtozat->setElerheto5($elerheto5);
                        $valtozat->setElerheto6($elerheto6);
                        $valtozat->setElerheto7($elerheto7);
                        $valtozat->setElerheto8($elerheto8);
                        $valtozat->setElerheto9($elerheto9);
                        $valtozat->setElerheto10($elerheto10);
                        $valtozat->setElerheto11($elerheto11);
                        $valtozat->setElerheto12($elerheto12);
                        $valtozat->setElerheto13($elerheto13);
                        $valtozat->setElerheto14($elerheto14);
                        $valtozat->setElerheto15($elerheto15);
                    }
                    //					$valtozat->setBrutto($brutto);
                    $valtozat->setNetto($netto);
                    $valtozat->setTermekfokep($termekfokep);
                    $valtozat->setElorendelheto($elorendelheto);
                    if (count($cikkszamok) > 0) {
                        if (count($cikkszamok) == 1) {
                            $valtozat->setCikkszam($cikkszamok[0]);
                        } elseif (array_key_exists($cikl, $cikkszamok)) {
                            $valtozat->setCikkszam($cikkszamok[$cikl]);
                        }
                    }
                    if (count($idegencikkszamok) > 0) {
                        if (count($idegencikkszamok) == 1) {
                            $valtozat->setIdegencikkszam($idegencikkszamok[0]);
                        } elseif (array_key_exists($cikl, $idegencikkszamok)) {
                            $valtozat->setIdegencikkszam($idegencikkszamok[$cikl]);
                        }
                    }
                    $valtozat->setUnasalaptipus($unasalaptipus);

                    if ($at1 && $ertek1) {
                        $valtozat->setAdatTipus1($at1);
                        $valtozat->setErtek1($ertek1);
                        $valtdb++;
                    }

                    if ($at2 && $ertek2) {
                        $valtozat->setAdatTipus2($at2);
                        $valtozat->setErtek2($ertek2);
                        $valtdb++;
                    }

                    $kep = $this->getEm()->getRepository(TermekKep::class)->find($kepid);
                    if ($kep) {
                        $valtozat->setKep($kep);
                    }

                    if ($valtdb > 0) {
                        $this->getEm()->persist($valtozat);
                    } else {
                        $termek->removeValtozat($valtozat);
                    }
                    $cikl++;
                }
            }
            $this->getEm()->flush();
        }

        $view = $this->createView('termektermekvaltozatkarb.tpl');
        $valtozatok = $termek->getValtozatok();
        $result = '';
        foreach ($valtozatok as $valt) {
            $view->setVar('valtozat', $this->loadVars($valt, $termek, true));
            $result .= $view->getTemplateResult();
        }
        echo $result;
    }

    public function getKeszletByRaktar()
    {
        $valtozatid = $this->params->getIntRequestParam('valtozatid');
        /** @var TermekValtozat $valtozat */
        $valtozat = $this->getRepo()->find($valtozatid);
        if ($valtozat) {
            $view = $this->createView('termekkeszletreszletezo.tpl');
            $view->setVar('lista', \Services\KeszletService::getKeszletByRaktar($valtozat));
            $tpl = $view->getTemplateResult();
        }
        echo json_encode([
            'title' => $valtozat->getNev(),
            'html' => $tpl,
        ]);
    }

}
