<?php

namespace Controllers;

use Entities\Bankbizonylatfej;
use Entities\Bankbizonylattetel;
use Entities\Bankszamla;
use Entities\Bizonylatfej;
use Entities\Bizonylattipus;
use Entities\GLSUtanvet;
use Entities\Jogcim;
use Entities\Valutanem;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

/**
 * A GLS utánvét-kimutatás importja, a bank tranzakció import mintájára.
 *
 * A GLS „Actual pcl statuses" munkalapjából csak azok a sorok kerülnek be, amelyeken van
 * **beszedett** utánvét (Q oszlop) – a regisztrált, de be nem szedett összeg még nem pénz.
 * Importáláskor megpróbáljuk kitalálni, melyik bizonylathoz tartozik a befizetés; az
 * eredmény a karbantartón kézzel javítható, és a „Párosít" gomb újra lefuttatja a keresést.
 */
class glsutanvetController extends \mkwhelpers\MattableController
{

    /**
     * A kimutatás oszlopkiosztása. A GLS mindig ugyanezt a lapot adja, ezért – a banki
     * importtal ellentétben – nincs formátumválasztó.
     */
    private const ELSOSOR = 4;
    private const OSZLOP = [
        'csomagszam' => 'A',
        'regisztraltosszeg' => 'B',
        'statusz' => 'C',
        'felvetel' => 'E',
        'statuszdatum' => 'F',
        'ugyfelhivatkozas' => 'H',
        'utanvethivatkozas' => 'I',
        'nev' => 'K',
        'atvevo' => 'L',
        'irszam' => 'M',
        'varos' => 'N',
        'utca' => 'O',
        'orszag' => 'P',
        'osszeg' => 'Q',
    ];

    public function __construct()
    {
        $this->setEntityName(GLSUtanvet::class);
        $this->setKarbFormTplName('glsutanvetkarbform.tpl');
        $this->setKarbTplName('glsutanvetkarb.tpl');
        $this->setListBodyRowTplName('glsutanvetlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new GLSUtanvet();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['felvetelstr'] = $t->getFelvetelStr();
        $x['statuszdatumstr'] = $t->getStatuszdatumStr();
        $x['cim'] = $t->getCim();
        return $x;
    }

    /**
     * @param GLSUtanvet $obj
     */
    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function getlistbody()
    {
        $view = $this->createView('glsutanvetlista_tbody.tpl');

        $filter = new FilterDescriptor();
        $csomagszam = $this->params->getStringRequestParam('csomagszamfilter');
        if ($csomagszam) {
            $filter->addFilter('csomagszam', 'LIKE', '%' . $csomagszam . '%');
        }
        $nev = $this->params->getStringRequestParam('nevfilter');
        if ($nev) {
            $filter->addFilter('nev', 'LIKE', '%' . $nev . '%');
        }
        // a párosítatlan tételek kiszűrése – ezekkel kell kezdeni a munkát
        if ($this->params->getIntRequestParam('parositatlanfilter')) {
            $filter->addSql("(_xx.bizonylatszamok IS NULL) OR (_xx.bizonylatszamok = '')");
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('glsutanvetlista.tpl');

        $view->setVar('pagetitle', t('GLS utánvétek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('GLS utánvét'));
        $view->setVar('formaction', '/admin/glsutanvet/save');
        $view->setVar('oper', $this->params->getRequestParam('oper', ''));
        $view->setVar('egyed', $this->loadVars($this->getRepo()->find($id), true));
        return $view->getTemplateResult();
    }

    public function viewupload()
    {
        $this->createView('glsutanvetupload.tpl')->printTemplateResult();
    }

    public function upload()
    {
        header('Content-Type: application/json; charset=utf-8');

        $filenev = \mkw\store::moveUploadedFile('toimport', 'glsutanvet', ['xls', 'xlsx']);
        if (!$filenev) {
            echo json_encode(['msg' => t('Csak .xls vagy .xlsx fájl tölthető fel.')]);
            return;
        }

        try {
            $reader = IOFactory::createReader(IOFactory::identify($filenev));
            $reader->setReadDataOnly(true);
            $excel = $reader->load($filenev);
        } catch (\Exception $e) {
            \unlink($filenev);
            echo json_encode(['msg' => t('A fájl nem olvasható táblázatként') . ': ' . $e->getMessage()]);
            return;
        }
        $sheet = $excel->getActiveSheet();
        $maxrow = (int)$sheet->getHighestRow();

        $repo = $this->getRepo();
        $sorok = 0;
        $beolvasott = 0;
        $ujdb = 0;
        $parositott = 0;

        for ($row = self::ELSOSOR; $row <= $maxrow; ++$row) {
            $csomagszam = trim((string)$sheet->getCell(self::OSZLOP['csomagszam'] . $row)->getValue());
            if (!$csomagszam) {
                continue;
            }
            $sorok++;
            // csak a ténylegesen beszedett utánvét érdekes
            $osszeg = $this->importOsszeg($sheet->getCell(self::OSZLOP['osszeg'] . $row)->getValue());
            if (!$osszeg) {
                continue;
            }
            $beolvasott++;
            if ($repo->findOneBy(['csomagszam' => $csomagszam])) {
                continue;
            }

            $o = new GLSUtanvet();
            $o->setCsomagszam($csomagszam);
            $o->setOsszeg($osszeg);
            $o->setRegisztraltosszeg(
                $this->importOsszeg($sheet->getCell(self::OSZLOP['regisztraltosszeg'] . $row)->getValue())
            );
            foreach (['statusz', 'ugyfelhivatkozas', 'utanvethivatkozas', 'nev', 'atvevo', 'irszam', 'varos', 'utca', 'orszag'] as $mezo) {
                $o->{'set' . ucfirst($mezo)}(trim((string)$sheet->getCell(self::OSZLOP[$mezo] . $row)->getValue()));
            }
            $o->setFelvetel($this->importDatum($sheet->getCell(self::OSZLOP['felvetel'] . $row)->getValue()));
            $o->setStatuszdatum($this->importDatum($sheet->getCell(self::OSZLOP['statuszdatum'] . $row)->getValue()));

            $bizszamarr = $this->keresBizonylatszamok($o);
            if ($bizszamarr) {
                $o->setBizonylatszamok(implode(';', $bizszamarr));
                $parositott++;
            }

            $this->getEm()->persist($o);
            $this->getEm()->flush();
            $ujdb++;
        }
        $excel->disconnectWorksheets();
        \unlink($filenev);

        echo json_encode([
            'msg' => $sorok . ' csomag a kimutatásban, ebből ' . $beolvasott . ' soron van beszedett utánvét; '
                . $ujdb . ' új tétel keletkezett, ebből ' . $parositott . ' kapott bizonylatszámot.',
        ]);
    }

    /**
     * A bizonylatszám nélküli tételeken újra lefuttatja a keresést – pl. ha az importálás óta
     * elkészült a számla. Ugyanaz a gomb, mint a bank tranzakcióknál.
     */
    public function parosit()
    {
        $filter = new FilterDescriptor();
        $filter->addFilter('inaktiv', '=', false);
        $filter->addSql("(_xx.bizonylatszamok IS NULL) OR (_xx.bizonylatszamok = '')");
        $tetelek = $this->getRepo()->getAll($filter, ['statuszdatum' => 'ASC']);
        $talalt = 0;
        /** @var GLSUtanvet $tetel */
        foreach ($tetelek as $tetel) {
            $bizszamarr = $this->keresBizonylatszamok($tetel);
            if ($bizszamarr) {
                $tetel->setBizonylatszamok(implode(';', $bizszamarr));
                $this->getEm()->persist($tetel);
                $talalt++;
            }
        }
        $this->getEm()->flush();
        echo json_encode([
            'msg' => count($tetelek) . ' párosítatlan tétel közül ' . $talalt . ' kapott bizonylatszámot.',
        ]);
    }

    /**
     * A párosított tételekből bankbizonylat: az utánvétet a futárszolgálat utalja, tehát a
     * pénz a „Utánvét bankszámla" beállításban megadott saját számlánkra érkezik. Egy tételből
     * egy bankbizonylat lesz, a tételei a párosított bizonylatszámok – a
     * `banktranzakcioController::generateBankbizonylat()` mintájára, de mindig bevétel.
     *
     * Csak azok a tételek jönnek szóba, amelyek párosítva vannak, nem inaktívak, és még nincs
     * bankbizonylatuk; a csoportos műveletnél a kipipált soroké.
     */
    public function generateBankbizonylat()
    {
        $jogcim = $this->getRepo(Jogcim::class)->find(
            \mkw\store::getIntParameter(\mkw\consts::AutoBankbizonylatJogcim, 1)
        );
        $utanvetbankszamla = $this->getRepo(Bankszamla::class)->find(
            \mkw\store::getIntParameter(\mkw\consts::UtanvetBankszamla, 0)
        );
        $valutanem = $this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));

        $filter = new FilterDescriptor();
        $filter->addFilter('bankbizonylatkesz', '=', false);
        $filter->addFilter('inaktiv', '=', false);
        $filter->addSql("(_xx.bizonylatszamok IS NOT NULL) AND (_xx.bizonylatszamok <> '')");
        $ids = $this->params->getArrayRequestParam('ids');
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }

        $keszult = 0;
        $kimaradt = 0;
        /** @var GLSUtanvet $tetel */
        foreach ($this->getRepo()->getAll($filter, ['statuszdatum' => 'ASC']) as $tetel) {
            $szlaszamok = explode(';', $tetel->getBizonylatszamok());
            $szamlak = [];
            foreach ($szlaszamok as $szlaszam) {
                $szamla = $this->getRepo(Bizonylatfej::class)->find(trim($szlaszam));
                if (!$szamla) {
                    // hiányzó bizonylatszámnál nem tippelünk, a tétel marad párosítottnak
                    $szamlak = [];
                    break;
                }
                $szamlak[] = $szamla;
            }
            if (!$szamlak) {
                $kimaradt++;
                continue;
            }

            $bb = new Bankbizonylatfej();
            $bb->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('bank'));
            $bb->setKelt();
            if ($utanvetbankszamla) {
                $bb->setBankszamla($utanvetbankszamla);
            }
            $befosszeg = abs((float)$tetel->getOsszeg());
            /** @var Bizonylatfej $szamla */
            foreach ($szamlak as $szamla) {
                if (!$bb->getPartner()) {
                    $bb->setPartner($szamla->getPartner());
                }
                if (!$utanvetbankszamla) {
                    $bb->setBankszamla($szamla->getBankszamla());
                }
                $bt = new Bankbizonylattetel();
                $bt->setBizonylatfej($bb);
                $bt->setPartner($szamla->getPartner());
                $bt->setDatum($tetel->getStatuszdatumStr());
                $bt->setHivatkozottbizonylat($szamla->getId());
                $bt->setHivatkozottdatum($szamla->getEsedekesseg());
                $bt->setJogcim($jogcim);
                $bt->setValutanem($szamla->getValutanem());
                $bt->setErbizonylatszam($tetel->getCsomagszam());
                // az utánvét mindig befizetés
                $bt->setIrany(1);
                $needed = min(abs($szamla->getBrutto()), $befosszeg);
                $bt->setBrutto($needed);
                $this->getEm()->persist($bt);
                $befosszeg = $befosszeg - $needed;
                if ($befosszeg <= 0) {
                    break;
                }
            }
            // A valutanemet CSAK a partner beállítása után szabad megadni
            $bb->setValutanem($valutanem);
            $tetel->setBankbizonylatkesz(true);
            $this->getEm()->persist($tetel);
            $this->getEm()->persist($bb);
            $this->getEm()->flush();
            $keszult++;
        }

        echo json_encode([
            'msg' => $keszult . ' bankbizonylat készült'
                . ($kimaradt ? ', ' . $kimaradt . ' tétel kimaradt (nincs meg a hivatkozott bizonylat).' : '.'),
        ]);
    }

    /**
     * A befizetéshez tartozó bizonylat megkeresése. Lépcsők, a biztosabbtól a bizonytalanabb felé:
     *
     *  1. **fuvarlevélszám** – a GLS csomagszáma; a `Services\GLSService` pontosan ezt írja a
     *     bizonylat `fuvarlevelszam` mezőjébe a címke kérésekor, tehát ez a legerősebb kötés;
     *  2. **hivatkozás** – a kimutatás hivatkozás-mezőiben gyakran maga a bizonylatszám áll;
     *     csak pontos egyezést fogadunk el;
     *  3. **név + összeg + cím** – csak akkor fogadjuk el, ha pontosan egy bizonylat illik rá.
     *
     * A megtalált bizonylat gyakran nem mozgat pénzt (megrendelés, szállítólevél), a befizetés
     * viszont a belőle képzett számlához tartozik – ezért a végén még lekérdezzük a pénzt
     * mozgató leszármazottat.
     *
     * @return string[] a megtalált bizonylatszám egyelemű tömbben, vagy üres tömb
     */
    private function keresBizonylatszamok(GLSUtanvet $o): array
    {
        $jeloltek = $this->keresFuvarlevelszam($o->getCsomagszam());
        if (!$jeloltek) {
            $jeloltek = $this->keresHivatkozas($o);
        }
        if (!$jeloltek) {
            $jeloltek = $this->keresNevOsszegCim($o);
        }
        $bizszam = $this->valasztPenztMozgato($jeloltek);
        return $bizszam === '' ? [] : [$bizszam];
    }

    /**
     * A csomagszám a bizonylat fuvarlevélszám mezőjében. A mezőben több szám is állhat
     * (több csomag egy bizonylathoz), ezért a részstring-találat is jó – a LOCATE sima
     * részstring-keresés, joker karakter nélkül.
     *
     * Több találat normális: a képzett bizonylat átveszi az előd fuvarlevélszámát, tehát egy
     * megrendelés és a belőle készült számla egyaránt illik. A választást a
     * valasztPenztMozgato() végzi.
     *
     * @return string[]
     */
    private function keresFuvarlevelszam($csomagszam): array
    {
        $csomagszam = trim((string)$csomagszam);
        if (mb_strlen($csomagszam) < 6) {
            return [];
        }
        return $this->getEm()->getConnection()->executeQuery(
            'SELECT bf.id FROM bizonylatfej bf'
            . ' WHERE bf.rontott = 0 AND bf.stornozott = 0 AND bf.fuvarlevelszam IS NOT NULL'
            . '   AND LOCATE(:csomagszam, bf.fuvarlevelszam) > 0',
            ['csomagszam' => $csomagszam]
        )->fetchFirstColumn();
    }

    /**
     * A GLS hivatkozás-mezői gyakran magát a bizonylatszámot viszik (a csomagfeladáskor mi
     * töltjük ki őket) – pl. „MR2025/002079". Csak PONTOS bizonylatszám-egyezést fogadunk el,
     * nem tippelünk: ha a mező webshopos rendelésszám, ez az ág egyszerűen nem talál semmit.
     *
     * @return string[]
     */
    private function keresHivatkozas(GLSUtanvet $o): array
    {
        $jeloltek = [];
        foreach ([$o->getUgyfelhivatkozas(), $o->getUtanvethivatkozas()] as $hivatkozas) {
            $hivatkozas = trim((string)$hivatkozas);
            if ($hivatkozas === '' || !str_contains($hivatkozas, '/')) {
                continue;
            }
            /** @var Bizonylatfej|null $biz */
            $biz = $this->getRepo(Bizonylatfej::class)->find($hivatkozas);
            if ($biz && !$biz->getRontott() && !$biz->getStornozott()) {
                $jeloltek[] = $biz->getId();
            }
        }
        return $jeloltek;
    }

    /**
     * Tartalék párosítás: címzett neve + a beszedett összeg + irányítószám. Mind a szállítási,
     * mind a partneradatokra nézünk, mert utánvétnél a kettő eltérhet. A nevet előtag-egyezéssel
     * keressük (a GLS levághatja), az összegnek viszont pontosan stimmelnie kell.
     *
     * @return string[]
     */
    private function keresNevOsszegCim(GLSUtanvet $o): array
    {
        $nev = trim((string)$o->getNev());
        $irszam = trim((string)$o->getIrszam());
        if (mb_strlen($nev) < 5) {
            return [];
        }
        $sql = 'SELECT bf.id FROM bizonylatfej bf'
            . ' WHERE bf.rontott = 0 AND bf.stornozott = 0'
            . '   AND ABS(bf.brutto - :osszeg) < 0.01'
            . '   AND ((bf.szallnev LIKE :nev) OR (bf.partnernev LIKE :nev))';
        $params = ['osszeg' => (float)$o->getOsszeg(), 'nev' => addcslashes($nev, '%_\\') . '%'];
        if ($irszam !== '') {
            $sql .= ' AND ((bf.szallirszam = :irszam) OR (bf.partnerirszam = :irszam))';
            $params['irszam'] = $irszam;
        }
        return $this->getEm()->getConnection()->executeQuery($sql, $params)->fetchFirstColumn();
    }

    /**
     * A jelöltek közül a pénzt mozgató bizonylat. Megrendelésre vagy szállítólevélre nem lehet
     * befizetést könyvelni, a hozzá tartozó pénz a belőle képzett számlán van – ezért a
     * jelöltek leszármazottait is végigjárjuk (szélességi bejárás, mert megrendelés →
     * szállítólevél → számla lánc is előfordul).
     *
     * Csak akkor válaszolunk, ha PONTOSAN EGY pénzt mozgató bizonylat jött ki – egy téves
     * találat rossz bizonylatra könyvelné a pénzt, ezért a bizonytalan eseteket üresen hagyjuk.
     * Ha egyik jelöltnek sincs pénzt mozgató ága, de a jelölt maga egyértelmű, azt adjuk vissza:
     * jobb egy pontos, de még nem számlázott hivatkozás, mint a semmi.
     *
     * @param string[] $jeloltek
     */
    private function valasztPenztMozgato(array $jeloltek): string
    {
        $jeloltek = array_values(array_unique(array_filter($jeloltek)));
        if (!$jeloltek) {
            return '';
        }
        $bizrepo = $this->getRepo(Bizonylatfej::class);
        $penzmozgatok = [];
        foreach ($jeloltek as $jelolt) {
            /** @var Bizonylatfej|null $biz */
            $biz = $bizrepo->find($jelolt);
            if (!$biz) {
                continue;
            }
            $sor = [$biz];
            $latott = [$biz->getId() => true];
            while ($sor) {
                /** @var Bizonylatfej $akt */
                $akt = array_shift($sor);
                if ($this->mozgatPenzt($akt)) {
                    $penzmozgatok[$akt->getId()] = true;
                    continue;
                }
                /** @var Bizonylatfej $gyerek */
                foreach ($akt->getSzulobizonylatfejek() ?? [] as $gyerek) {
                    if (isset($latott[$gyerek->getId()]) || $gyerek->getRontott() || $gyerek->getStornozott()) {
                        continue;
                    }
                    $latott[$gyerek->getId()] = true;
                    $sor[] = $gyerek;
                }
            }
        }
        if (count($penzmozgatok) === 1) {
            return (string)array_key_first($penzmozgatok);
        }
        if (!$penzmozgatok && count($jeloltek) === 1) {
            return (string)$jeloltek[0];
        }
        return '';
    }

    /** @param Bizonylatfej $biz */
    private function mozgatPenzt($biz): bool
    {
        return (bool)$biz->getPenztmozgat() && (bool)$biz->getBizonylattipus()?->getPenztmozgat();
    }

    private function importOsszeg($ertek)
    {
        if (is_numeric($ertek)) {
            return (float)$ertek;
        }
        $ertek = str_replace([' ', "\xc2\xa0", "\t", ','], ['', '', '', '.'], (string)$ertek);
        return is_numeric($ertek) ? (float)$ertek : 0.0;
    }

    /** A GLS a dátumokat Excel-sorszámként adja. */
    private function importDatum($ertek)
    {
        return is_numeric($ertek) ? Date::excelToDateTimeObject($ertek) : null;
    }

}
