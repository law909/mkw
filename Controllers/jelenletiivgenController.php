<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\Dolgozoszabadsag;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Traits\Munkanap;

/**
 * Aláírásra kész jelenléti ív a dolgozó rögzített munkarendjéből: azok a napok kerülnek rá,
 * amikor dolgoznia kell (munkanapok, ünnepnap nélkül), a távollétek megjelölve.
 *
 * Nem keverendő a {@see jelenletiivController} képernyőjével: az a tényleges be- és kilépéseket
 * tartja nyilván, ez a munkarendből képzett ívet adja.
 */
class jelenletiivgenController extends \mkwhelpers\Controller
{
    use Munkanap;


    public function view()
    {
        $view = $this->createView('jelenletiivgen.tpl');

        $view->setVar('pagetitle', t('Jelenléti ív generálás'));
        $view->setVar('toldatum', date(\mkw\store::$DateFormat, strtotime('first day of this month')));
        $view->setVar('igdatum', date(\mkw\store::$DateFormat, strtotime('last day of this month')));
        $d = new dolgozoController();
        $view->setVar('dolgozolist', $d->getSelectList());

        $view->printTemplateResult();
    }

    public function createLista()
    {
        $adat = $this->getData();
        $report = $this->createView('rep_jelenletiiv.tpl');
        $report->setVar('ivek', $adat['ivek']);
        $report->setVar('tolstr', $adat['tolstr']);
        $report->setVar('igstr', $adat['igstr']);
        $report->printTemplateResult();
    }

    /**
     * Ugyanaz az időszak mátrixban: soronként egy nap, oszloponként egy dolgozó, x ott, ahol
     * aznap dolgozik. Az utolsó oszlop azt mondja meg, hányan dolgoznak aznap.
     */
    public function createMatrix()
    {
        $adat = $this->getData();
        $report = $this->createView('rep_jelenletiivmatrix.tpl');
        $report->setVar('dolgozok', array_column($adat['ivek'], 'dolgozonev'));
        $report->setVar('napok', $this->buildMatrix($adat));
        $report->setVar('tolstr', $adat['tolstr']);
        $report->setVar('igstr', $adat['igstr']);
        $report->printTemplateResult();
    }

    /** @return array soronként ['datum','napnev','jelek','db'] */
    private function buildMatrix(array $adat)
    {
        $napnevek = Dolgozo::getNapok();
        $sorok = [];
        $nap = clone $adat['tol'];
        while ($nap <= $adat['ig']) {
            $kulcs = $nap->format(\mkw\store::$SQLDateFormat);
            $jelek = [];
            $db = 0;
            foreach ($adat['ivek'] as $_iv) {
                $dolgozik = isset($_iv['ledolgozottnapok'][$kulcs]);
                $jelek[] = $dolgozik ? 'x' : '';
                $db += $dolgozik ? 1 : 0;
            }
            $sorok[] = [
                'datum' => $nap->format(\mkw\store::$DateFormat),
                'napnev' => $napnevek[(int)$nap->format('N')],
                'jelek' => $jelek,
                'db' => $db,
            ];
            $nap->modify('+1 day');
        }
        return $sorok;
    }

    /**
     * Ugyanaz a tartalom xlsx-ben: dolgozónként egy munkalap, hogy nyomtatás nélkül is
     * továbbadható legyen.
     */
    public function export()
    {
        $adat = $this->getData();

        $excel = new Spreadsheet();
        $excel->removeSheetByIndex(0);
        $lapnevek = [];
        foreach ($adat['ivek'] as $_iv) {
            $lap = $excel->createSheet();
            $lap->setTitle($this->getLapnev($_iv['dolgozonev'], $lapnevek));

            $lap->setCellValue('A1', t('Jelenléti ív'));
            $lap->setCellValue('A2', $_iv['dolgozonev'] . ($_iv['munkakornev'] ? ' (' . $_iv['munkakornev'] . ')' : ''));
            $lap->setCellValue('A3', $adat['tolstr'] . ' - ' . $adat['igstr']);
            $lap->setCellValue('C3', $_iv['munkaido']);

            $lap->setCellValue('A5', t('Dátum'))
                ->setCellValue('B5', t('Nap'))
                ->setCellValue('C5', t('Munkakezdés'))
                ->setCellValue('D5', t('Munka vége'))
                ->setCellValue('E5', t('Óra'))
                ->setCellValue('F5', t('Távollét'))
                ->setCellValue('G5', t('Aláírás'));

            $sor = 6;
            foreach ($_iv['napok'] as $_nap) {
                $lap->setCellValue('A' . $sor, $_nap['datum'])
                    ->setCellValue('B' . $sor, $_nap['napnev'])
                    ->setCellValue('C' . $sor, $_nap['kezdes'])
                    ->setCellValue('D' . $sor, $_nap['vege'])
                    ->setCellValue('E' . $sor, $_nap['ora'] ?: '')
                    ->setCellValue('F' . $sor, $_nap['tavollet']);
                $sor++;
            }
            $sor++;
            $lap->setCellValue('A' . $sor, t('Munkanap'))->setCellValue('B' . $sor, count($_iv['napok']));
            $sor++;
            $lap->setCellValue('A' . $sor, t('Ledolgozott'))->setCellValue('B' . $sor, $_iv['ledolgozott']);
            $sor++;
            $lap->setCellValue('A' . $sor, t('Távollét'))->setCellValue('B' . $sor, $_iv['tavollet']);
            $sor++;
            $lap->setCellValue('A' . $sor, t('Ledolgozott óra'))->setCellValue('B' . $sor, $_iv['oraosszesen']);
            $sor += 3;
            $lap->setCellValue('A' . $sor, t('dolgozó aláírása'));
            $lap->setCellValue('D' . $sor, t('munkáltató aláírása'));

            foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $oszlop) {
                $lap->getColumnDimension($oszlop)->setAutoSize(true);
            }
        }
        if (!$excel->getSheetCount()) {
            $excel->createSheet()->setTitle(t('Jelenléti ív'));
        }
        $excel->setActiveSheetIndex(0);

        $filename = uniqid('jelenletiiv-') . '.xlsx';
        $filepath = \mkw\store::storagePath($filename);
        IOFactory::createWriter($excel, 'Xlsx')->save($filepath);

        header('Cache-Control: private');
        header('Content-Type: application/stream');
        header('Content-Length: ' . filesize($filepath));
        header('Content-Disposition: attachment; filename="jelenletiiv.xlsx"');

        readfile($filepath);

        \unlink($filepath);
    }

    /**
     * Munkalap név a dolgozó nevéből: az Excel 31 karakternél levágja, a []:*?/\\ jeleket nem
     * tűri, és két azonos nevű lap sem lehet.
     */
    private function getLapnev($dolgozonev, array &$lapnevek)
    {
        $nev = trim(str_replace(['\\', '/', '?', '*', '[', ']', ':'], ' ', $dolgozonev));
        $nev = mb_substr($nev, 0, 28) ?: t('dolgozó');
        $jelolt = $nev;
        $sorszam = 1;
        while (in_array($jelolt, $lapnevek, true)) {
            $sorszam++;
            $jelolt = $nev . ' ' . $sorszam;
        }
        $lapnevek[] = $jelolt;
        return $jelolt;
    }

    /**
     * @return array ['tol','ig','tolstr','igstr','ivek'] – ívenként a dolgozó és a napjai
     */
    protected function getData()
    {
        $tol = $this->datumParam('tol', 'first day of this month');
        $ig = $this->datumParam('ig', 'last day of this month');
        if ($ig < $tol) {
            $ig = clone $tol;
        }

        $unnepnapok = $this->getUnnepnapok($tol, $ig);
        $ivek = [];
        /** @var Dolgozo $dolgozo */
        foreach ($this->getDolgozok() as $dolgozo) {
            $ivek[] = $this->createIv($dolgozo, $tol, $ig, $unnepnapok);
        }

        return [
            'tol' => $tol,
            'ig' => $ig,
            'tolstr' => $tol->format(\mkw\store::$DateFormat),
            'igstr' => $ig->format(\mkw\store::$DateFormat),
            'ivek' => $ivek,
        ];
    }

    private function createIv(Dolgozo $dolgozo, \DateTime $tol, \DateTime $ig, array $unnepnapok)
    {
        $szabadsagok = $this->getRepo(Dolgozoszabadsag::class)->getByDolgozoAndIdoszak($dolgozo, $tol, $ig);
        $napnevek = Dolgozo::getNapok();
        $kezdes = $dolgozo->getMunkakezdesStr();
        $vege = $dolgozo->getMunkavegeStr();
        $napiora = $this->getNapiOra($dolgozo);

        $napok = [];
        $ledolgozottnapok = [];
        $ledolgozott = 0;
        $tavollet = 0;
        $oraosszesen = 0;
        $nap = clone $tol;
        while ($nap <= $ig) {
            if ($this->isMunkanap($dolgozo, $nap, $unnepnapok)) {
                $tavolletnev = $this->getTavolletNev($szabadsagok, $nap);
                $ora = $tavolletnev ? 0 : $napiora;
                $napok[] = [
                    'datum' => $nap->format(\mkw\store::$DateFormat),
                    'napnev' => $napnevek[(int)$nap->format('N')],
                    'kezdes' => $tavolletnev ? '' : $kezdes,
                    'vege' => $tavolletnev ? '' : $vege,
                    'tavollet' => $tavolletnev,
                    'ora' => $ora,
                    'orastr' => $this->formatOra($ora),
                ];
                $oraosszesen += $ora;
                if ($tavolletnev) {
                    $tavollet++;
                } else {
                    $ledolgozott++;
                    $ledolgozottnapok[$nap->format(\mkw\store::$SQLDateFormat)] = true;
                }
            }
            $nap->modify('+1 day');
        }

        return [
            'dolgozonev' => $dolgozo->getNev(),
            'munkakornev' => $dolgozo->getMunkakorNev(),
            'munkaido' => ($kezdes && $vege) ? $kezdes . ' - ' . $vege : '',
            'napok' => $napok,
            'ledolgozottnapok' => $ledolgozottnapok,
            'ledolgozott' => $ledolgozott,
            'tavollet' => $tavollet,
            'oraosszesen' => $oraosszesen,
            'oraosszesenstr' => $this->formatOra($oraosszesen),
        ];
    }

    /**
     * A dolgozó rögzített munkaidejéből egy munkanap óraszáma. Munkaidő nélkül 0 – ilyenkor az
     * óra oszlop üresen marad, hogy látszódjon: a munkarend nincs kitöltve.
     */
    private function getNapiOra(Dolgozo $dolgozo)
    {
        $kezdes = $dolgozo->getMunkakezdes();
        $vege = $dolgozo->getMunkavege();
        if (!$kezdes || !$vege) {
            return 0;
        }
        $perc = ((int)$vege->format('H') * 60 + (int)$vege->format('i'))
            - ((int)$kezdes->format('H') * 60 + (int)$kezdes->format('i'));
        if ($perc < 0) {
            // éjszakába nyúló műszak
            $perc += 24 * 60;
        }
        return round($perc / 60, 2);
    }

    private function formatOra($ora)
    {
        if (!$ora) {
            return '';
        }
        return rtrim(rtrim(number_format($ora, 2, ',', ''), '0'), ',');
    }

    /**
     * @param \Entities\Dolgozoszabadsag[] $szabadsagok
     *
     * @return string a távollét típusa, vagy üres string, ha aznap dolgozik
     */
    private function getTavolletNev(array $szabadsagok, \DateTime $nap)
    {
        foreach ($szabadsagok as $szabadsag) {
            if ($szabadsag->tartalmazzaNapot($nap)) {
                return t($szabadsag->getTipusNev());
            }
        }
        return '';
    }

    /**
     * @return \Entities\Dolgozo[]
     */
    private function getDolgozok()
    {
        $dolgozoid = $this->params->getIntRequestParam('dolgozo');
        if ($dolgozoid) {
            $dolgozo = $this->getRepo(Dolgozo::class)->find($dolgozoid);
            return $dolgozo ? [$dolgozo] : [];
        }
        $filter = new FilterDescriptor();
        $filter->addFilter('inaktiv', '=', false);
        return $this->getRepo(Dolgozo::class)->getAll($filter, ['nev' => 'ASC']);
    }

    private function datumParam($nev, $alapertelmezett)
    {
        $ertek = $this->params->getStringRequestParam($nev);
        $datum = $ertek ? new \DateTime(\mkw\store::convDate($ertek)) : new \DateTime($alapertelmezett);
        return $datum->setTime(0, 0);
    }

}
