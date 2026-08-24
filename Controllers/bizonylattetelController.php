<?php

namespace Controllers;

use Entities\Afa;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Partner;
use Entities\Termek;
use Entities\TermekValtozat;
use Entities\Valutanem;
use PhpOffice\PhpSpreadsheet\IOFactory;

class bizonylattetelController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Bizonylattetel::class);
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        $oper = $this->params->getStringRequestParam('oper');
        $termekCtrl = new termekController();
        $vtsz = new vtszController();
        $afa = new afaController();
        $me = new meController();
        if (!$t) {
            $t = new \Entities\Bizonylattetel();
            $this->getEm()->detach($t);
            $x = $this->getEntityFieldsArray($t);
            $x['id'] = \mkw\store::createUID();
            $x['oper'] = 'add';
        } else {
            $x = $this->getEntityFieldsArray($t);
            $x['id'] = $t->getId();
            $x['oper'] = 'edit';
        }
        $x['termek'] = $t->getTermekId();
        $x['termekvaltozat'] = $t->getTermekvaltozatId();
        $x['termeknev_locale'] = $t->getLocalizedFieldValue('termeknev');
        $x['vasarlasdatumstr'] = $t->getVasarlasdatumStr();
        if ($oper === 'storno') {
            $x['netto'] = $t->getNetto() * -1;
            $x['afa'] = $t->getAfaertek() * -1;
            $x['brutto'] = $t->getBrutto() * -1;
            $x['nettohuf'] = $t->getNettohuf() * -1;
            $x['afahuf'] = $t->getAfaertekhuf() * -1;
            $x['bruttohuf'] = $t->getBruttohuf() * -1;
            $x['mennyiseg'] = $t->getMennyiseg() * -1;
        }

        $x['hataridostr'] = $t->getHataridoStr();
        $x['mainurl'] = \mkw\store::getConfigValue('mainurl');

        $term = $t->getTermek();
        $x['kellegyediazonosito'] = $term ? (bool)$term->getKellegyediazonosito() : false;
        // a költségszámla importja minden tételre ezt a gyűjtőterméket teszi, az ár viszont a
        // bejövő számláról jön: termékcserekor nem szabad felülírni (lásd bizonylathelper.js)
        $x['koltsegtermek'] = $term && \mkw\store::getParameter(\mkw\consts::KoltsegTermek) == $term->getId();
        if ($term) {
            $eb = $term->getBruttoAr($t->getTermekvaltozat(), $t->getBizonylatfej()->getPartner());
            $x['eladasibrutto'] = $eb;
            if ($x['bruttoegysar'] != 0) {
                $x['haszonszazalek'] = $eb / $x['bruttoegysar'] * 100 - 100;
            } else {
                $x['haszonszazalek'] = 0;
            }
            $x['kozepeskepurl'] = $term->getKepurlMedium();
            $x['kiskepurl'] = $term->getKepurlSmall();
            $x['minikepurl'] = $term->getKepurlMini();
            $x['kepurl'] = $term->getKepurlLarge();
            $x['slug'] = $term->getSlug();
            $x['link'] = \mkw\store::getRouter()->generate('showtermek', \mkw\store::getConfigValue('mainurl'), ['slug' => $term->getSlug()]);
            $x['kartonurl'] = \mkw\store::getRouter()->generate('admintermekkartonview', false, [], ['id' => $term->getId()]);
        }

        if ($forKarb) {
            $x['valtozatlist'] = $termekCtrl->getValtozatList($t->getTermekId(), $t->getTermekvaltozatId());
            $x['vtszlist'] = $vtsz->getSelectList(($t->getVtsz() ? $t->getVtsz()->getId() : 0));
            $x['afalist'] = $afa->getSelectList(($t->getAfa() ? $t->getAfa()->getId() : 0));
            $x['melist'] = $me->getSelectList($t->getMekodId());
            if (!\mkw\store::isTermekAutocomplete()) {
                $x['termeklist'] = $termekCtrl->getSelectList();
            }
        }
        return $x;
    }

    protected function setFields($obj)
    {
        return $obj;
    }

    public function getemptyrow()
    {
        $biztipus = $this->params->getStringRequestParam('type');
        $view = $this->createView('bizonylattetelkarb.tpl');
        $tetel = $this->loadVars(null, true);
        $view->setVar('tetel', $tetel);
        /** @var \Entities\Bizonylattipus $bt */
        $bt = $this->getRepo(Bizonylattipus::class)->find($biztipus);
        $bt->setTemplateVars($view);
        echo $view->getTemplateResult();
    }

    public function getquickemptyrow()
    {
        $biztipus = $this->params->getStringRequestParam('type');
        $view = $this->createView('bizonylattetelquickkarb.tpl');
        $view->setVar('tetel', $this->loadVars(null, true));
        $bt = $this->getRepo(Bizonylattipus::class)->find($biztipus);
        $bt->setTemplateVars($view);
        echo $view->getTemplateResult();
    }

    public function getar()
    {
        // Nincsenek ársávok
        if (!\mkw\store::isArsavok()) {
            $termek = $this->getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termek'));
            $partner = $this->getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner'));
            $valutanem = $this->getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('valutanem'));
            $valtozat = null;
            if ($this->params->getIntRequestParam('valtozat')) {
                $valtozat = $this->getEm()->getRepository(TermekValtozat::class)->find($this->params->getIntRequestParam('valtozat'));
            }
            if ($termek) {
                $r = [
                    'netto' => $termek->getNettoAr($valtozat),
                    'brutto' => $termek->getBruttoAr($valtozat),
                    'kedvezmeny' => $termek->getKedvezmeny($partner),
                    'enetto' => $termek->getKedvezmenynelkuliNettoAr($valtozat, $partner, $valutanem),
                    'ebrutto' => $termek->getKedvezmenynelkuliBruttoAr($valtozat, $partner, $valutanem)
                ];
                echo json_encode($r);
            } else {
                echo json_encode([
                    'netto' => 0,
                    'brutto' => 0,
                    'kedvezmeny' => 0,
                    'enetto' => 0,
                    'ebrutto' => 0
                ]);
            }
        } // Vannak ársávok
        else {
            /** @var \Entities\Termek $termek */
            $termek = $this->getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('termek'));
            $partner = $this->getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('partner'));
            $valutanem = $this->getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('valutanem'));
            $valtozat = null;
            if ($this->params->getIntRequestParam('valtozat')) {
                $valtozat = $this->getEm()->getRepository(TermekValtozat::class)->find($this->params->getIntRequestParam('valtozat'));
            }
            if ($termek) {
                $r = [
                    'netto' => $termek->getNettoAr($valtozat, $partner, $valutanem),
                    'brutto' => $termek->getBruttoAr($valtozat, $partner, $valutanem),
                    'kedvezmeny' => $termek->getKedvezmeny($partner),
                    'enetto' => $termek->getKedvezmenynelkuliNettoAr($valtozat, $partner, $valutanem),
                    'ebrutto' => $termek->getKedvezmenynelkuliBruttoAr($valtozat, $partner, $valutanem)
                ];
                echo json_encode($r);
            } else {
                echo json_encode([
                    'netto' => 0,
                    'brutto' => 0,
                    'kedvezmeny' => 0,
                    'enetto' => 0,
                    'ebrutto' => 0
                ]);
            }
        }
    }

    public function calcAr($afaid, $arfolyam, $nettoegysar, $enettoegysar, $mennyiseg)
    {
        $afaent = $this->getEm()->getRepository(Afa::class)->find($afaid);
        $bruttoegysar = 0;
        $ebruttoegysar = 0;
        if ($afaent) {
            $bruttoegysar = $afaent->calcBrutto($nettoegysar);
            $ebruttoegysar = $afaent->calcBrutto($enettoegysar);
        }
        $netto = $nettoegysar * $mennyiseg;

        $brutto = 0;
        if ($afaent) {
            $brutto = $afaent->calcBrutto($netto);
        }
        $afa = $brutto - $netto;

        $nettoegysarhuf = $nettoegysar * $arfolyam;
        $bruttoegysarhuf = $bruttoegysar * $arfolyam;
        $enettoegysarhuf = $enettoegysar * $arfolyam;
        $ebruttoegysarhuf = $ebruttoegysar * $arfolyam;
        $nettohuf = $netto * $arfolyam;
        $bruttohuf = $brutto * $arfolyam;
        $afahuf = $afa * $arfolyam;
        return [
            'nettoegysar' => $nettoegysar,
            'bruttoegysar' => $bruttoegysar,
            'enettoegysar' => $enettoegysar,
            'ebruttoegysar' => $ebruttoegysar,
            'netto' => $netto,
            'brutto' => $brutto,
            'afa' => $afa,
            'nettoegysarhuf' => $nettoegysarhuf,
            'bruttoegysarhuf' => $bruttoegysarhuf,
            'enettoegysarhuf' => $enettoegysarhuf,
            'ebruttoegysarhuf' => $ebruttoegysarhuf,
            'nettohuf' => $nettohuf,
            'bruttohuf' => $bruttohuf,
            'afahuf' => $afahuf
        ];
    }

    public function calcarforclient()
    {
        echo json_encode(
            $this->calcAr(
                $this->params->getIntRequestParam('afa'),
                $this->params->getNumRequestParam('arfolyam', 1),
                $this->params->getNumRequestParam('nettoegysar', 0),
                $this->params->getNumRequestParam('enettoegysar', 0),
                $this->params->getNumRequestParam('mennyiseg', 0)
            )
        );
    }

    /**
     * A minkeszletlistaController::exportBizonylat() formátumú xlsx sorai tételnek.
     * Oszlopok: A=termék id, B=változat id, C=cikkszám, D=vonalkód, E=mennyiség.
     */
    public function importXlsx()
    {
        $file = $this->getUploadedFile();
        if (!$file) {
            echo json_encode(['ok' => false, 'error' => t('Nem érkezett feltöltött fájl.')]);
            return;
        }

        try {
            $reader = IOFactory::createReader(IOFactory::identify($file));
            $reader->setReadDataOnly(true);
            $sheet = $reader->load($file)->getActiveSheet();
        } catch (\Exception $e) {
            \unlink($file);
            echo json_encode(['ok' => false, 'error' => t('A fájl nem olvasható táblázatként.')]);
            return;
        }

        $tetelek = [];
        $hibak = [];
        $maxrow = (int)$sheet->getHighestRow();
        for ($row = 1; $row <= $maxrow; ++$row) {
            $termekid = (int)$sheet->getCell('A' . $row)->getValue();
            $valtozatid = (int)$sheet->getCell('B' . $row)->getValue();
            $cikkszam = trim((string)$sheet->getCell('C' . $row)->getValue());
            $vonalkod = trim((string)$sheet->getCell('D' . $row)->getValue());
            $mennyiseg = (float)$sheet->getCell('E' . $row)->getValue();
            if (!$termekid && ($cikkszam === '') && ($vonalkod === '')) {
                continue;
            }

            $talalat = $this->findTermek($termekid, $valtozatid, $cikkszam, $vonalkod);
            if (!$talalat) {
                // a fejlécsor is ide fut be, azt nem jelentjük hibaként
                if ($row > 1) {
                    $hibak[] = sprintf(t('%d. sor: nem azonosítható termék (%s)'), $row, trim($cikkszam . ' ' . $vonalkod));
                }
                continue;
            }
            $tetelek[] = $this->tetelAdat($talalat, $mennyiseg);
        }
        \unlink($file);

        echo json_encode(['ok' => true, 'tetelek' => $tetelek, 'hibak' => $hibak]);
    }

    /** FC-Moto rendelés csv: a fejlécben ezek a mezőnevek érdekelnek */
    private const FCMOTO_MEZOK = ['barcode', 'supplierarticlenumber', 'productquantity'];

    /** fejléc nélküli (régi) fájl oszlopsorrendje: barcode;supplierArticleNumber;productTitle;productQuantity */
    private const FCMOTO_REGIOSZLOPOK = ['barcode' => 0, 'supplierarticlenumber' => 1, 'productquantity' => 3];

    /**
     * FC-Moto rendelés csv sorai tételnek, pontosvesszővel elválasztva.
     *
     * Az oszlopok a fejlécsor mezőneveiből oldódnak fel, mert az FC-Moto bővítette a fájlt
     * (a régi négy oszlop helyett tizenegy, más sorrendben). Fejléc nélküli fájlnál a régi
     * oszlopsorrend érvényes.
     *
     * Az azonosítás elsősorban cikkszám (supplierArticleNumber – nálunk ez a cikkszám),
     * másodsorban vonalkód alapján megy.
     */
    public function importFcMoto()
    {
        $file = $this->getUploadedFile();
        if (!$file) {
            echo json_encode(['ok' => false, 'error' => t('Nem érkezett feltöltött fájl.')]);
            return;
        }

        $handle = fopen($file, 'r');
        if (!$handle) {
            \unlink($file);
            echo json_encode(['ok' => false, 'error' => t('A fájl nem olvasható.')]);
            return;
        }

        $oszlop = null;
        $tetelek = [];
        $hibak = [];
        $row = 0;
        while (($sor = fgetcsv($handle, 0, ';')) !== false) {
            $row++;
            if ($oszlop === null) {
                $oszlop = $this->fcMotoOszlopok($sor);
                if ($oszlop['fejleces']) {
                    continue;
                }
            }
            $vonalkod = trim((string)($sor[$oszlop['barcode']] ?? ''));
            $cikkszam = trim((string)($sor[$oszlop['supplierarticlenumber']] ?? ''));
            if (($vonalkod === '') && ($cikkszam === '')) {
                continue;
            }
            $mennyiseg = (float)str_replace(',', '.', (string)($sor[$oszlop['productquantity']] ?? ''));

            $talalat = $this->findTermek(0, 0, $cikkszam, $vonalkod);
            if (!$talalat) {
                $hibak[] = sprintf(t('%d. sor: nem azonosítható termék (%s)'), $row, trim($cikkszam . ' ' . $vonalkod));
                continue;
            }
            $tetelek[] = $this->tetelAdat($talalat, $mennyiseg);
        }
        fclose($handle);
        \unlink($file);

        echo json_encode(['ok' => true, 'tetelek' => $tetelek, 'hibak' => $hibak]);
    }

    /**
     * Az FC-Moto csv első sorából az oszlopsorszámok. A `fejleces` jelzi, hogy a sort fejlécként
     * el kell dobni – enélkül nem tudnánk megkülönböztetni a fejléc nélküli régi fájltól.
     *
     * @return array{barcode: int, supplierarticlenumber: int, productquantity: int, fejleces: bool}
     */
    private function fcMotoOszlopok(array $elsosor): array
    {
        $nevek = [];
        foreach ($elsosor as $i => $cella) {
            // az első cellán BOM is lehet
            $nev = strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', (string)$cella)));
            if (in_array($nev, self::FCMOTO_MEZOK, true)) {
                $nevek[$nev] = $i;
            }
        }
        if (!isset($nevek['barcode']) && !isset($nevek['supplierarticlenumber'])) {
            return self::FCMOTO_REGIOSZLOPOK + ['fejleces' => false];
        }
        // fejléces fájlnál a hiányzó mező NEM a régi pozícióra esik vissza: ott már más adat áll
        $hianyzo = array_fill_keys(self::FCMOTO_MEZOK, -1);
        return $nevek + $hianyzo + ['fejleces' => true];
    }

    /** Az Oxford (GALAD) számla-munkafüzet oszlopai. A fejlécsor az A oszlopban „Code". */
    private const OXFORD_OSZLOPOK = ['cikkszam' => 'A', 'nev' => 'B', 'mennyiseg' => 'H'];

    /**
     * Oxford számla xlsx tételei. A munkafüzet számlánként egy munkalapot tartalmaz, a lap neve
     * a szállító számlaszáma – ezért két lépés: elsőre a lapok nevét adjuk vissza, a választott
     * lappal újraküldve pedig a tételeket.
     *
     * Sorból akkor lesz tétel, ha az első oszlop (cikkszám) nem üres – a számla eleji fuvar- és
     * jogi szövegek így maradnak ki. A cikkszám a termék vagy a változat cikkszáma; ha nincs meg,
     * a beállított alapértelmezett termék kerül a tételre, a tétel nevébe és cikkszámába a lapon
     * szereplő adatokkal, és a kliens pirossal jelöli.
     */
    public function importOxford()
    {
        $file = $this->getUploadedFile();
        if (!$file) {
            echo json_encode(['ok' => false, 'error' => t('Nem érkezett feltöltött fájl.')]);
            return;
        }

        try {
            $reader = IOFactory::createReader(IOFactory::identify($file));
            $reader->setReadDataOnly(true);
            $excel = $reader->load($file);
        } catch (\Exception $e) {
            \unlink($file);
            echo json_encode(['ok' => false, 'error' => t('A fájl nem olvasható táblázatként.')]);
            return;
        }
        \unlink($file);

        $sheetnev = trim($this->params->getStringRequestParam('sheet'));
        if ($sheetnev === '') {
            $sheets = $excel->getSheetNames();
            $excel->disconnectWorksheets();
            echo json_encode(['ok' => true, 'sheets' => $sheets]);
            return;
        }

        $sheet = $excel->getSheetByName($sheetnev);
        if (!$sheet) {
            $excel->disconnectWorksheets();
            echo json_encode(['ok' => false, 'error' => sprintf(t('Nincs "%s" nevű munkalap a fájlban.'), $sheetnev)]);
            return;
        }

        $oszlop = self::OXFORD_OSZLOPOK;
        $alaptermek = $this->getRepo(Termek::class)->find(\mkw\store::getParameter(\mkw\consts::DefaultTermek));

        $tetelek = [];
        $hibak = [];
        $maxrow = (int)$sheet->getHighestRow();
        for ($row = 1; $row <= $maxrow; ++$row) {
            $cikkszam = trim((string)$sheet->getCell($oszlop['cikkszam'] . $row)->getValue());
            if (($cikkszam === '') || (strcasecmp($cikkszam, 'Code') === 0)) {
                continue;
            }
            $nev = trim((string)$sheet->getCell($oszlop['nev'] . $row)->getValue());
            $mennyiseg = (float)$sheet->getCell($oszlop['mennyiseg'] . $row)->getValue();

            $talalat = $this->findTermek(0, 0, $cikkszam, '');
            if ($talalat) {
                $tetelek[] = $this->tetelAdat($talalat, $mennyiseg);
                continue;
            }
            if (!$alaptermek) {
                $hibak[] = sprintf(
                    t('%d. sor: nincs ilyen cikkszámú termék (%s), és nincs beállítva alapértelmezett termék sem.'),
                    $row,
                    $cikkszam
                );
                continue;
            }
            $adat = $this->tetelAdat([$alaptermek, null], $mennyiseg);
            // a fel nem ismert cikkszám és a szállító megnevezése a tétel nevébe, a cikkszám a
            // tétel cikkszám mezőjébe is – a helykitöltő termék sajátja ott semmit nem mondana
            $adat['value'] = trim($cikkszam . ' ' . $nev);
            $adat['cikkszam'] = $cikkszam;
            $tetelek[] = $adat;
            $hibak[] = sprintf(t('%d. sor: nincs ilyen cikkszámú termék (%s), az alapértelmezett termék került rá.'), $row, $cikkszam);
        }
        $excel->disconnectWorksheets();

        echo json_encode([
            'ok' => true,
            'tetelek' => $tetelek,
            'hibak' => $hibak,
            // a lap neve a szállító számlaszáma
            'erbizonylatszam' => $sheetnev,
        ]);
    }

    /**
     * A feltöltött fájl a storage-ba mentve, vagy null. A hívó törli.
     *
     * @return string|null
     */
    private function getUploadedFile()
    {
        return \mkw\store::moveUploadedFile('toimport', 'tetelimport');
    }

    /**
     * Egy importsor termék+változat azonosítása. Sorrend: id, cikkszám, vonalkód – és mindegyiknél
     * előbb a változat, mert az a pontosabb találat.
     *
     * @return array{0: Termek, 1: TermekValtozat|null}|null
     */
    private function findTermek($termekid, $valtozatid, $cikkszam, $vonalkod)
    {
        if ($termekid) {
            /** @var Termek|null $termek */
            $termek = $this->getRepo(Termek::class)->find($termekid);
            if (!$termek) {
                return null;
            }
            /** @var TermekValtozat|null $valtozat */
            $valtozat = $valtozatid ? $this->getRepo(TermekValtozat::class)->find($valtozatid) : null;
            if ($valtozat && (!$valtozat->getTermek() || ($valtozat->getTermek()->getId() != $termek->getId()))) {
                $valtozat = null;
            }
            return [$termek, $valtozat];
        }

        foreach ([['cikkszam', $cikkszam], ['vonalkod', $vonalkod]] as [$mezo, $ertek]) {
            if ($ertek === '') {
                continue;
            }
            /** @var TermekValtozat|null $valtozat */
            $valtozat = $this->getRepo(TermekValtozat::class)->findOneBy([$mezo => $ertek]);
            if ($valtozat && $valtozat->getTermek()) {
                return [$valtozat->getTermek(), $valtozat];
            }
            /** @var Termek|null $termek */
            $termek = $this->getRepo(Termek::class)->findOneBy([$mezo => $ertek]);
            if ($termek) {
                return [$termek, null];
            }
        }
        return null;
    }

    /**
     * @param array{0: Termek, 1: TermekValtozat|null} $talalat
     */
    private function tetelAdat($talalat, $mennyiseg)
    {
        [$termek, $valtozat] = $talalat;
        $adat = (new termekController())->getBizonylattetelAdat($termek, $valtozat ? $valtozat->getId() : 0);
        // a nem pozitív mennyiséget a kliens a szokásos alapértelmezéssel tölti ki
        $adat['mennyiseg'] = $mennyiseg > 0 ? $mennyiseg : 0;
        return $adat;
    }

    public function valtozathtmllist()
    {
        $tc = new termekController();
        $tomb = [
            'id' => $this->params->getRequestParam('tetelid', 0),
            'valtozatlist' => $tc->getValtozatList(
                $this->params->getRequestParam('id', 0),
                $this->params->getRequestParam('sel', 0),
                $this->params->getIntRequestParam('raktar', 0)
            )
        ];
        $view = $this->createView('bizonylatteteltermekvaltozatselect.tpl');
        $view->setVar('tetel', $tomb);
        echo json_encode([
            'html' => $view->getTemplateResult(),
            'db' => count($tomb['valtozatlist'])
        ]);
    }

    public function quickvaltozathtmllist()
    {
        $termekid = $this->params->getRequestParam('id', 0);
        $termektetelid = $this->params->getRequestParam('tetelid', 0);
        $tc = new termekController();
        $view = $this->createView('bizonylattetelquickvaltozatkarb.tpl');
        $valtozatlist = $tc->getValtozatList($termekid, 0);
        $vlist = [];
        foreach ($valtozatlist as $v) {
            $v['tetelid'] = \mkw\store::createUID();
            $v['termekid'] = $termekid;
            $v['termektetelid'] = $termektetelid;
            $vlist[] = $v;
        }
        $view->setVar('valtozatlist', $vlist);
        echo json_encode([
            'tetelid' => $termektetelid,
            'html' => $view->getTemplateResult()
        ]);
    }

}