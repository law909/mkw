<?php

namespace Controllers;

use Entities\Bankbizonylatfej;
use Entities\Bankbizonylattetel;
use Entities\Bankszamla;
use Entities\BankTranzakcio;
use Entities\Bizonylatfej;
use Entities\Bizonylattipus;
use Entities\Jogcim;
use Entities\Partner;
use Entities\Valutanem;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class banktranzakcioController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(BankTranzakcio::class);
        $this->setKarbFormTplName('banktranzakciokarbform.tpl');
        $this->setKarbTplName('banktranzakciokarb.tpl');
        $this->setListBodyRowTplName('banktranzakciolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new \Entities\BankTranzakcio();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['konyvelesdatumstr'] = $t->getKonyvelesdatumStr();
        $x['erteknapstr'] = $t->getErteknapStr();
        // a tárolt kulcs helyett a bank megjelenítendő neve (a régi, import előtti soroknál üres)
        $bank = (string)$t->getBank();
        $x['banknev'] = self::IMPORTFORMATUMOK[$bank]['nev'] ?? $bank;
        return $x;
    }

    /**
     * @param \Entities\BankTranzakcio $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        $partner = $this->getRepo(Partner::class)->find($this->params->getIntRequestParam('partner'));
        if ($partner) {
            $obj->setPartner($partner);
        } else {
            $obj->setPartner(null);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('banktranzakciolista_tbody.tpl');

        $filter = new FilterDescriptor();

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
        $view = $this->createView('banktranzakciolista.tpl');

        $view->setVar('pagetitle', t('Bank tranzakciók'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Bank tranzakciók'));
        $view->setVar('formaction', '/admin/banktranzakcio/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);

        if (!\mkw\store::isPartnerAutocomplete()) {
            $partnerc = new partnerController();
            $view->setVar('partnerlist', $partnerc->getSelectList(($record ? $record->getPartnerId() : 0)));
        }

        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    /**
     * A támogatott banki kivonat-formátumok. A felhasználó importáláskor választ közülük
     * (banktranzakcioupload.tpl), a kulcs érkezik `formatum` paraméterként.
     *
     * `oszlop`: melyik oszlopban áll az adott mező; `elsosor`: az első adatsor (a fejléc alatt);
     * `datum`: a dátumoszlopok formátuma – az OTP HTML-export szövegesen adja a dátumot,
     * a Raiffeisen viszont Excel-sorszámként.
     *
     * `csv`: pontosvesszős szövegfájl (ERSTE) – a PhpSpreadsheet CSV-olvasójának elválasztója
     * és idézőjele. `irany`: külön előjeloszlop (T/J), az összeg ilyenkor előjel nélkül jön.
     * `azonosito` hiányában a sor tartalmából képzünk kulcsot – lásd {@see rowAzonosito()}.
     */
    const IMPORTFORMATUMOK = [
        'raiffeisen' => [
            'nev' => 'Raiffeisen',
            'elsosor' => 1,
            'datum' => 'excel',
            'oszlop' => [
                'konyvelesdatum' => 'B',
                'erteknap' => 'C',
                'azonosito' => 'D',
                'osszeg' => 'E',
                'kozlemeny1' => 'F',
                'kozlemeny2' => 'G',
                'kozlemeny3' => 'H',
                // az ellenoldali számlaszám (partnerkereséshez) és a bizonylatszámot tartalmazó mező
                'szamlaszam' => 'F',
                'bizonylatszam' => 'H',
                // az ellenoldali (partner) neve – a bizonylatszám nélküli tételek párosításához
                'partnernev' => 'G',
            ],
        ],
        'otp' => [
            'nev' => 'OTP',
            'elsosor' => 17,
            'datum' => 'szoveg',
            'oszlop' => [
                'konyvelesdatum' => 'C',
                'erteknap' => 'D',
                'azonosito' => 'I',
                'osszeg' => 'E',
                'kozlemeny1' => 'F',
                'kozlemeny2' => 'G',
                'kozlemeny3' => 'H',
                'szamlaszam' => 'F',
                'bizonylatszam' => 'H',
                // az ellenoldali (partner) neve – a bizonylatszám nélküli tételek párosításához
                'partnernev' => 'G',
            ],
        ],
        'erste' => [
            'nev' => 'ERSTE',
            'elsosor' => 2,
            'datum' => 'szoveg',
            'csv' => ['delimiter' => ';', 'enclosure' => '"'],
            'oszlop' => [
                'konyvelesdatum' => 'A',
                'erteknap' => 'B',
                'osszeg' => 'F',
                // T = terhelés, J = jóváírás; az F oszlopban az összeg mindig pozitív
                'irany' => 'G',
                // a tranzakció típusa (BEIP, 100B, …) csak tájékoztató
                'kozlemeny1' => 'H',
                'kozlemeny2' => 'D',
                'kozlemeny3' => 'I',
                'szamlaszam' => 'E',
                'bizonylatszam' => 'I',
                'partnernev' => 'D',
            ],
        ],
    ];

    public function viewupload()
    {
        $view = $this->createView('banktranzakcioupload.tpl');
        $formatumok = [];
        foreach (self::IMPORTFORMATUMOK as $kulcs => $f) {
            $formatumok[$kulcs] = $f['nev'];
        }
        $view->setVar('formatumok', $formatumok);
        // a legutóbb használt bank legyen előre kiválasztva
        $view->setVar('valasztottformatum', \mkw\store::getParameter(\mkw\consts::LastBankiFormatum, ''));
        $view->printTemplateResult();
    }

    public function upload()
    {
        $formatumkulcs = $this->params->getStringRequestParam('formatum', 'raiffeisen');
        if (!array_key_exists($formatumkulcs, self::IMPORTFORMATUMOK)) {
            echo json_encode(['msg' => 'Ismeretlen formátum: ' . $formatumkulcs]);
            return;
        }
        $formatum = self::IMPORTFORMATUMOK[$formatumkulcs];
        $oszlop = $formatum['oszlop'];
        \mkw\store::setParameter(\mkw\consts::LastBankiFormatum, $formatumkulcs);
        $negativis = $this->params->getBoolRequestParam('negativis', false);

        $filenev = \mkw\store::moveUploadedFile('toimport', 'banktranzakcio');
        if (!$filenev) {
            echo json_encode(['msg' => t('Hiányzó vagy nem elfogadott típusú fájl.')]);
            return;
        }

        $reader = $this->createReader($filenev, $formatum);
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filenev);
        $sheet = $excel->getActiveSheet();
        $maxrow = (int)$sheet->getHighestRow();

        $repo = $this->getRepo();
        $partnerrepo = $this->getRepo(Partner::class);
        $azonszamlalo = [];

        for ($row = $formatum['elsosor']; $row <= $maxrow; ++$row) {
            $osszeg = $this->importOsszeg($sheet->getCell($oszlop['osszeg'] . $row)->getValue());
            if ($osszeg && isset($oszlop['irany'])) {
                $irany = strtoupper(trim((string)$sheet->getCell($oszlop['irany'] . $row)->getValue()));
                $osszeg = ($irany === 'T') ? -abs($osszeg) : abs($osszeg);
            }
            if ($osszeg && ($osszeg > 0 || $negativis)) {
                $azon = isset($oszlop['azonosito'])
                    ? trim((string)$sheet->getCell($oszlop['azonosito'] . $row)->getValue())
                    : $this->rowAzonosito($sheet, $oszlop, $row, $osszeg, $azonszamlalo);
                // az azonosító csak bankon belül egyedi (az OTP pl. rövid sorszámot ad),
                // ezért a duplikátumszűrés a bankot is nézi
                if ($azon && !$repo->findOneBy(['azonosito' => $azon, 'bank' => $formatumkulcs])) {
                    $konyvelesdatum = $this->importDatum(
                        $sheet->getCell($oszlop['konyvelesdatum'] . $row)->getValue(),
                        $formatum['datum']
                    );
                    $erteknap = $this->importDatum(
                        $sheet->getCell($oszlop['erteknap'] . $row)->getValue(),
                        $formatum['datum']
                    );
                    if (!$konyvelesdatum && !$erteknap) {
                        // fejléc- vagy összesítő sor a dátumoszlopban – nem tranzakció
                        continue;
                    }
                    // a bank néha csak az egyik dátumot adja meg (pl. függő kártyás tételnél
                    // nincs még értéknap) – ilyenkor a másikkal pótoljuk
                    $konyvelesdatum = $konyvelesdatum ? $konyvelesdatum : clone $erteknap;
                    $erteknap = $erteknap ? $erteknap : clone $konyvelesdatum;

                    $o = new BankTranzakcio();
                    $o->setAzonosito($azon);
                    $o->setBank($formatumkulcs);
                    $o->setOsszeg($osszeg);

                    $o->setKozlemeny1((string)$sheet->getCell($oszlop['kozlemeny1'] . $row)->getValue());
                    $o->setKozlemeny2((string)$sheet->getCell($oszlop['kozlemeny2'] . $row)->getValue());
                    $o->setKozlemeny3((string)$sheet->getCell($oszlop['kozlemeny3'] . $row)->getValue());

                    $o->setKonyvelesdatum($konyvelesdatum);
                    $o->setErteknap($erteknap);

                    $szamlaszam = trim((string)$sheet->getCell($oszlop['szamlaszam'] . $row)->getValue());
                    $partner = $szamlaszam ? $partnerrepo->findOneBy(['iban' => $szamlaszam]) : null;
                    if ($partner) {
                        $o->setPartner($partner);
                    }

                    $bizszamarr = $this->keresBizonylatszamok($o);
                    if ($bizszamarr) {
                        $o->setBizonylatszamok(implode(';', $bizszamarr));
                    }

                    $this->getEm()->persist($o);
                    $this->getEm()->flush();
                }
            }
        }
        $excel->disconnectWorksheets();
        \unlink($filenev);
    }

    /**
     * A kivonat olvasója. A CSV-formátumoknál az elválasztót és a kódolást is meg kell adni:
     * a magyar bankok latin-2-ben exportálnak, a PhpSpreadsheet viszont UTF-8-at feltételez.
     *
     * @return \PhpOffice\PhpSpreadsheet\Reader\IReader
     */
    private function createReader($filenev, array $formatum)
    {
        if (!isset($formatum['csv'])) {
            return IOFactory::createReader(IOFactory::identify($filenev));
        }
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader->setDelimiter($formatum['csv']['delimiter']);
        $reader->setEnclosure($formatum['csv']['enclosure']);
        $reader->setInputEncoding(
            mb_check_encoding((string)file_get_contents($filenev), 'UTF-8') ? 'UTF-8' : 'ISO-8859-2'
        );
        return $reader;
    }

    /**
     * Azonosító olyan kivonathoz, amiben nincs tranzakcióazonosító (ERSTE): a sor tartalmából
     * képezzük, hogy az újraimportálás ne duplázzon.
     *
     * A fájlon belül azonos tartalmú sorok (ugyanaznap, ugyanakkora, ugyanattól, ugyanazzal a
     * közleménnyel) valódi külön utalások is lehetnek, ezért kapnak sorszámot – így két
     * ugyanolyan sor két tranzakció marad, ugyanannak a fájlnak az újratöltése viszont nem hoz
     * létre újat.
     */
    private function rowAzonosito($sheet, array $oszlop, $row, $osszeg, array &$szamlalo): string
    {
        $reszek = [$osszeg];
        foreach (['konyvelesdatum', 'erteknap', 'szamlaszam', 'kozlemeny1', 'kozlemeny2', 'kozlemeny3'] as $mezo) {
            $reszek[] = isset($oszlop[$mezo])
                ? trim((string)$sheet->getCell($oszlop[$mezo] . $row)->getValue())
                : '';
        }
        $kulcs = md5(implode('|', $reszek));
        $szamlalo[$kulcs] = ($szamlalo[$kulcs] ?? 0) + 1;
        return $kulcs . '-' . $szamlalo[$kulcs];
    }

    public function parosit()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('inaktiv', '=', false);
        $filter->addSql("(_xx.bizonylatszamok IS NULL) OR (_xx.bizonylatszamok = '')");
        $trs = $this->getRepo()->getAll($filter, ['erteknap' => 'ASC']);
        $talalt = 0;
        /** @var BankTranzakcio $tr */
        foreach ($trs as $tr) {
            $bizszamarr = $this->keresBizonylatszamok($tr);
            if ($bizszamarr) {
                $tr->setBizonylatszamok(implode(';', $bizszamarr));
                $this->getEm()->persist($tr);
                $talalt++;
            }
        }
        $this->getEm()->flush();
        echo json_encode([
            'vizsgalt' => count($trs),
            'talalt' => $talalt,
            'msg' => count($trs) . ' párosítatlan tétel közül ' . $talalt . ' kapott bizonylatszámot.',
        ]);
    }

    /**
     * Egy tranzakcióhoz tartozó bizonylatszám(ok) megkeresése. Ugyanaz fut importáláskor és
     * a "Párosít" gombra – a tranzakció eltárolt mezőiből dolgozik:
     *  - kozlemeny3: a közlemény, amiben a bizonylatszám állhat
     *  - kozlemeny2: az ellenoldali (partner) neve
     *  - partner: az ellenoldali számlaszám alapján már megtalált partner
     *
     * Jóváírásnál vevőszámlát, terhelésnél költségszámlát keres.
     *
     * @return array a megtalált bizonylatszámok
     */
    private function keresBizonylatszamok(BankTranzakcio $o): array
    {
        $szamlatipus = $this->getBizonylattipus('szamla');
        $osszeg = (float)$o->getOsszeg();
        $partner = $o->getPartner();
        $trimmedbizsz = trim((string)$o->getKozlemeny3());
        $bizrepo = $this->getRepo(Bizonylatfej::class);
        // jóváírás → vevőszámla, terhelés → költségszámla
        $celtipus = $osszeg > 0 ? $szamlatipus : $this->getBizonylattipus('koltsegszamla');

        $bizszamarr = [];
        if ($osszeg < 0) {
            $bizszamarr = $this->keresKoltsegszamlaErbizonylatszam($trimmedbizsz);
        } elseif ($biz = $bizrepo->find($trimmedbizsz)) {
            $bizszamarr[] = $biz->getId();
        } elseif (is_numeric($trimmedbizsz)) {
            $convertedB = $szamlatipus->getAzonosito() . $o->getErteknap()->format('Y') . '/' . str_pad($trimmedbizsz, 6, '0', STR_PAD_LEFT);

            /** @var Bizonylatfej $biz */
            $biz = $bizrepo->find($convertedB);
            if ($biz) {
                if (!$partner || ($partner && $partner->getId() == $biz->getPartnerId())) {
                    $bizszamarr[] = $biz->getId();
                }
            }
        } else {
            // '/[Ss]?[Zz]?\d{4}\/\d{1,6}/'
            $regexp = '/' . $szamlatipus->getAzonositoForRegexp() . '\d{4}\/\d{1,6}/';
            $matchcnt = preg_match_all($regexp, str_replace(' ', '', $trimmedbizsz), $bizsz);
            if ($matchcnt) {
                foreach ($bizsz[0] as $b) {
                    $convertedB = strtoupper($b);
                    $szamlatipusAzonosito = $szamlatipus->getAzonosito();

                    if (substr($convertedB, 0, 2) !== $szamlatipusAzonosito) {
                        $convertedB = $szamlatipusAzonosito . $convertedB;
                    }
                    $partsofB = explode('/', $convertedB);
                    $partsofB[1] = sprintf('%06d', (int)$partsofB[1]);
                    $convertedB = implode('/', $partsofB);

                    /** @var Bizonylatfej $biz */
                    $biz = $bizrepo->find($convertedB);
                    if ($biz) {
                        if (!$partner || ($partner && $partner->getId() == $biz->getPartnerId())) {
                            $bizszamarr[] = $biz->getId();
                        }
                    }
                }
            }
        }
        if (!$bizszamarr) {
            $bizszamarr = $this->keresBizonylatPartnerEsOsszeg(
                $partner,
                (string)$o->getKozlemeny2(),
                $osszeg,
                $celtipus
            );
        }
        return $bizszamarr;
    }

    /** @return \Entities\Bizonylattipus */
    private function getBizonylattipus($id)
    {
        return $this->getRepo(Bizonylattipus::class)->find($id);
    }

    /**
     * A tranzakció bankjához tartozó saját bankszámla. Ugyanaz a kulcs azonosítja, mint az
     * importformátumot (Bankszámlák törzs → "Bank (tranzakció import)").
     *
     * Ha egy bankhoz több számla is tartozik (pl. HUF és EUR), a bizonylat valutaneme dönt;
     * ha úgy nincs találat, a bank első számlájával térünk vissza.
     *
     * @return \Entities\Bankszamla|null
     */
    private function keresBankszamla($bank, $valutanem)
    {
        if (!$bank) {
            return null;
        }
        $repo = $this->getRepo(Bankszamla::class);
        if ($valutanem) {
            $bsz = $repo->findOneBy(['bank' => $bank, 'valutanem' => $valutanem]);
            if ($bsz) {
                return $bsz;
            }
        }
        return $repo->findOneBy(['bank' => $bank]);
    }

    /**
     * Terhelés (negatív összeg) párosítása költségszámlához: a szállítói számla száma a
     * költségszámla `erbizonylatszam` mezőjében áll, a banki közlemény pedig tartalmazza
     * (pl. "mbnh25/028433 MBV26/196785").
     *
     * Egy utalással több szállítói számla is kiegyenlíthető, ezért – a vevőszámlás ághoz
     * hasonlóan – minden találatot visszaadunk. A LOCATE sima részstring-keresés, tehát az
     * erbizonylatszamban lévő _ és % nem viselkedik joker karakterként.
     *
     * @return array a megtalált költségszámla-számok
     */
    private function keresKoltsegszamlaErbizonylatszam($kozlemeny): array
    {
        $kozlemeny = trim($kozlemeny);
        if (mb_strlen($kozlemeny) < 4) {
            return [];
        }
        $sql = 'SELECT bf.id FROM bizonylatfej bf'
            . ' WHERE bf.bizonylattipus_id = :ktgtipus'
            . '   AND bf.erbizonylatszam IS NOT NULL'
            . '   AND CHAR_LENGTH(bf.erbizonylatszam) >= 4'
            . '   AND LOCATE(bf.erbizonylatszam, :kozlemeny) > 0';
        $talalatok = $this->getEm()->getConnection()->executeQuery(
            $sql,
            ['ktgtipus' => 'koltsegszamla', 'kozlemeny' => $kozlemeny]
        )->fetchAllAssociative();
        return array_column($talalatok, 'id');
    }

    /**
     * Tartalék párosítás: ha a közleményből nem jött ki bizonylatszám, a partner neve és
     * az összeg alapján keresünk számlát. Minden banknál fut.
     *
     * Csak akkor fogadjuk el, ha PONTOSAN EGY nyitott számla illik rá – egy téves találat
     * rossz bankbizonylatot eredményezne, ezért a bizonytalan eseteket üresen hagyjuk.
     *
     * A nevet a bankok levágják (pl. "SZÁSZ MOTOR KERESKEDELMI ÉS SZOL"), ezért a kivonatból
     * jövő név a teljes név eleje: prefix egyezést nézünk. Ha az ellenoldali számlaszám
     * alapján megvan a partner, akkor a névre nincs is szükség.
     *
     * A "nyitott összeg" a folyószámla egyenlege (a számla sora + a rá könyvelt
     * kiegyenlítések), így egy részben fizetett számla maradványára is talál.
     *
     * @return array a megtalált bizonylatszám egyelemű tömbben, vagy üres tömb
     */
    private function keresBizonylatPartnerEsOsszeg($partner, $nev, $osszeg, Bizonylattipus $szamlatipus): array
    {
        $nev = trim($nev);
        if (!$partner && mb_strlen($nev) < 5) {
            // se partner, se használható név – ennyiből nem párosítunk
            return [];
        }

        $sql = 'SELECT f.hivatkozottbizonylat AS id, SUM(f.brutto * f.irany) AS tartozas'
            . ' FROM folyoszamla f'
            . ' INNER JOIN bizonylatfej bf ON bf.id = f.hivatkozottbizonylat'
            . ' WHERE f.hivatkozottbizonylat IS NOT NULL'
            . '   AND f.rontott = 0'
            . '   AND bf.bizonylattipus_id = :sztipus'
            . '   AND bf.valutanem_id = :valutanem'
            . ($partner ? '   AND bf.partner_id = :partnerid' : '   AND bf.partnernev LIKE :nev')
            . ' GROUP BY f.hivatkozottbizonylat'
            . ' HAVING ABS(tartozas - :osszeg) < 0.01';

        $params = [
            'sztipus' => $szamlatipus->getId(),
            'valutanem' => \mkw\store::getParameter(\mkw\consts::Valutanem),
            'osszeg' => $osszeg,
        ];
        if ($partner) {
            $params['partnerid'] = $partner->getId();
        } else {
            $params['nev'] = addcslashes($nev, '%_\\') . '%';
        }

        $talalatok = $this->getEm()->getConnection()->executeQuery($sql, $params)->fetchAllAssociative();
        if (count($talalatok) === 1) {
            return [$talalatok[0]['id']];
        }
        return [];
    }

    /**
     * Összeg a kivonatból. A HTML-alapú exportokban (OTP) szövegként jön, akár ezres
     * elválasztóval és tizedesvesszővel.
     */
    private function importOsszeg($ertek)
    {
        if (is_numeric($ertek)) {
            return (float)$ertek;
        }
        $ertek = str_replace([' ', "\xc2\xa0", "\t"], '', (string)$ertek);
        $ertek = str_replace(',', '.', $ertek);
        return is_numeric($ertek) ? (float)$ertek : 0.0;
    }

    /**
     * Dátum a kivonatból.
     *  - 'excel': Excel-sorszám (Raiffeisen)
     *  - 'szoveg': "2026.07.24." vagy "2026.07.24. 09:39:00" (OTP)
     *
     * @return \DateTime|null null, ha a cella nem dátum (pl. fejléc- vagy összesítő sor)
     */
    private function importDatum($ertek, $formatum)
    {
        if ($formatum === 'excel') {
            if (!is_numeric($ertek)) {
                return null;
            }
            return Date::excelToDateTimeObject($ertek);
        }
        $matchcnt = preg_match(
            '/(\d{4})\.\s*(\d{1,2})\.\s*(\d{1,2})\.?(?:\s+(\d{1,2}):(\d{2})(?::(\d{2}))?)?/',
            (string)$ertek,
            $m
        );
        if (!$matchcnt) {
            return null;
        }
        $d = new \DateTime();
        $d->setDate((int)$m[1], (int)$m[2], (int)$m[3]);
        $d->setTime((int)($m[4] ?? 0), (int)($m[5] ?? 0), (int)($m[6] ?? 0));
        return $d;
    }

    public function generateBankbizonylat()
    {
        // a tételek jogcíme a beállításokból (Alapértelmezések fül); ha nincs beállítva,
        // marad a régi viselkedés
        $jogcim = $this->getRepo(Jogcim::class)->find(
            \mkw\store::getIntParameter(\mkw\consts::AutoBankbizonylatJogcim, 1)
        );
        $ids = $this->params->getArrayRequestParam('ids');
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bankbizonylatkesz', '=', false);
        $filter->addFilter('inaktiv', '=', false);
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }
        $trs = $this->getRepo()->getAll($filter, ['erteknap' => 'ASC']);
        /** @var BankTranzakcio $tr */
        foreach ($trs as $tr) {
            $mehet = true;
            $szlaszamok = explode(';', $tr->getBizonylatszamok());
            foreach ($szlaszamok as $szlaszam) {
                /** @var Bizonylatfej $szamla */
                $szamla = $this->getRepo(Bizonylatfej::class)->find($szlaszam);
                if (!$szamla) {
                    $mehet = false;
                }
            }
            if ($mehet) {
                $bb = new Bankbizonylatfej();
                $bb->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('bank'));
                $bb->setKelt();
                if ($tr->getPartner()) {
                    $bb->setPartner($tr->getPartner());
                }
                $valutanem = $this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
                // a kivonat bankjához beállított saját bankszámla (Bankszámlák törzs → "Bank
                // (tranzakció import)"). Ez pontosabb, mint a hivatkozott bizonylaté – utóbbi
                // költségszámlánál jellemzően üres is. Ha nincs beállítva, marad a régi működés.
                $sajatbankszamla = $this->keresBankszamla($tr->getBank(), $valutanem);
                if ($sajatbankszamla) {
                    $bb->setBankszamla($sajatbankszamla);
                }
                // jóváírás (a vevő fizet nekünk) → bevétel, terhelés (mi fizetünk a szállítónak)
                // → kiadás. A tételen a bruttó mindig előjel nélküli, az irányt az irany hordozza
                // (a folyószámlára a listener fordított előjellel könyveli).
                $irany = $tr->getOsszeg() < 0 ? -1 : 1;
                $befosszeg = abs($tr->getOsszeg());
                foreach ($szlaszamok as $szlaszam) {
                    /** @var Bizonylatfej $szamla */
                    $szamla = $this->getRepo(Bizonylatfej::class)->find($szlaszam);
                    if ($szamla) {
                        if (!$bb->getPartner()) {
                            $bb->setPartner($szamla->getPartner());
                        }
                        if (!$sajatbankszamla) {
                            $bb->setBankszamla($szamla->getBankszamla());
                        }
                        $bt = new Bankbizonylattetel();
                        $bt->setBizonylatfej($bb);
                        $bt->setPartner($szamla->getPartner());
                        $bt->setDatum($tr->getErteknapStr());
                        $bt->setHivatkozottbizonylat($szlaszam);
                        $bt->setHivatkozottdatum($szamla->getEsedekesseg());
                        $bt->setJogcim($jogcim);
                        $bt->setValutanem($szamla->getValutanem());
                        $bt->setErbizonylatszam($tr->getAzonosito());
                        $bt->setIrany($irany);
                        $needed = min(abs($szamla->getBrutto()), $befosszeg);
                        $bt->setBrutto($needed);
                        $this->getEm()->persist($bt);
                        $befosszeg = $befosszeg - $needed;
                        if ($befosszeg <= 0) {
                            break;
                        }
                    }
                }
                // A valutanemet CSAK a partner beállítása után szabad megadni
                $bb->setValutanem($valutanem);
                $tr->setBankbizonylatkesz(true);
                $this->getEm()->persist($tr);
                $this->getEm()->persist($bb);
                $this->getEm()->flush();
            }
        }
    }
}
