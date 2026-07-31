<?php

namespace Services;

use Entities\Afa;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Fizmod;
use Entities\ME;
use Entities\Orszag;
use Entities\Partner;
use Entities\Raktar;
use Entities\Termek;
use Entities\Valutanem;
use Entities\Vtsz;

/**
 * NAV Online Számla 3.0 InvoiceData adatból – abban a JSON alakban, ahogy a NAV kliens
 * (lásd {@see NAVKoltsegszamlaImportService}) visszaadja – készít "koltsegszamla"
 * bizonylattípusú bizonylatot.
 *
 * A JSON az InvoiceData szerkezetét követi (invoiceNumber, invoiceMain.invoice.invoiceHead,
 * invoiceLines.line, …). Kezelt eltérések: egy tétel vs. tömbnyi tétel, normál
 * (lineAmountsNormal, nettó egységár) vs. egyszerűsített (lineAmountsSimplified, bruttó
 * egységár + vatContent) számla, ÁFA-kulcs / adómentesség / fordított adózás, hiányzó
 * adószám, cím, fizetési mód, teljesítési dátum, árfolyam, egységár.
 *
 * Alapelvek:
 * - A tételek terméke NEM a beérkezett adatból keresett termék, hanem a beállításokban
 *   kiválasztott "költség" termék (\mkw\consts::KoltsegTermek). A tétel neve, ára, ÁFÁ-ja,
 *   mennyiségi egysége és VTSZ-e viszont a bejövő adatból jön.
 * - A partner a számlát kiállító fél: a supplierInfo blokk. Adószám alapján keressük, ha az
 *   nincs, név alapján; ha úgy sincs meg, felvesszük szállítóként.
 * - A kapcsolódó törzsadatokat (mennyiségi egység, VTSZ, ÁFA-kulcs, ország, valutanem), ha
 *   nincsenek nálunk, létrehozzuk.
 *
 * A bizonylatszámot, a fejösszegeket és a folyószámlát a BizonylatfejListener számolja a
 * mentéskor; itt csak a bemeneti adatokat töltjük.
 */
class KoltsegszamlaJSONImportService
{
    private const BIZONYLATTIPUS = 'koltsegszamla';

    /** NAV unitOfMeasureType → nálunk használt mennyiségi egység megnevezés */
    private const ME_NEVEK = [
        'PIECE' => 'db',
        'KILOGRAM' => 'kg',
        'TON' => 'tonna',
        'KWH' => 'kWh',
        'DAY' => 'nap',
        'HOUR' => 'óra',
        'MINUTE' => 'perc',
        'MONTH' => 'hónap',
        'LITER' => 'liter',
        'KILOMETER' => 'km',
        'CUBIC_METER' => 'm3',
        'METER' => 'm',
        'LINEAR_METER' => 'fm',
        'CARTON' => 'karton',
        'PACK' => 'csomag',
    ];

    /**
     * Költségszámla létrehozása a NAV InvoiceData JSON-jából (lásd az inv*.json mintákat).
     *
     * @return Bizonylatfej a létrehozott, perzisztált költségszámla; vagy a már létező, nem
     *                      rontott költségszámla, ha az érkeztetett bizonylatszámmal ilyen van
     *
     * @throws \Exception ha a JSON nem értelmezhető, vagy nincs beállítva a költség termék
     */
    public function createFromJson(string $json): Bizonylatfej
    {
        $adat = json_decode(trim($json), true);
        if (!is_array($adat)) {
            throw new \Exception('A NAV JSON nem értelmezhető.');
        }
        return $this->createFromArray($adat);
    }

    /**
     * Költségszámla létrehozása a már dekódolt InvoiceData tömbből.
     *
     * @throws \Exception
     */
    public function createFromArray(array $adat): Bizonylatfej
    {
        $fejadat = $this->olvasFejadat($adat);
        $tetelek = $this->olvasTetelek($adat, $fejadat);

        $em = \mkw\store::getEm();
        $biztipus = $em->getRepository(Bizonylattipus::class)->find(self::BIZONYLATTIPUS);
        if (!$biztipus) {
            throw new \Exception('Hiányzik a "' . self::BIZONYLATTIPUS . '" bizonylattípus.');
        }

        // ha az érkeztetett (szállítói) bizonylatszámmal már van NEM rontott költségszámla,
        // nem készítünk újat – a meglévőt adjuk vissza
        $letezo = $this->keresLetezoKoltsegszamla($fejadat['invoiceNumber'], $biztipus);
        if ($letezo) {
            return $letezo;
        }

        $koltsegtermek = $this->getKoltsegTermek();

        // A törzsadatokat (partner, valutanem, fizmód, ÁFA, VTSZ, mennyiségi egység) még a
        // bizonylat összeállítása ELŐTT feloldjuk. A hiányzók létrehozása flush-sel jár, az
        // pedig a tételek felvitele közben hibára futna: a már perzisztált tétel ilyenkor a
        // még nem perzisztált bizonylatfejre hivatkozik (arra pedig nincs cascade persist).
        // Külön előny, hogy egy elakadt számla nem rántja vissza a közben létrejött
        // törzsadatokat sem, így azok nem maradnak érvénytelen azonosítóval a memóriában.
        $partner = $this->keresVagyLetrehozPartner($fejadat['supplier']);
        $valutanem = $this->keresVagyLetrehozValutanem($fejadat['currency']);
        $fizmod = $this->keresFizmod($fejadat['paymentMethod']);
        $tetelek = $this->torzsadatokFeloldasa($tetelek);

        $em->beginTransaction();
        try {
            $fej = new Bizonylatfej();
            $fej->setBizonylattipus($biztipus);
            $fej->setPersistentData();
            // a partner beállítása felülírja a partner* mezőket és a partner alapértelmezett
            // valutanemét/fizmódját is – a devizát és a fizmódot utána az adatból állítjuk
            $fej->setPartner($partner);

            $raktar = $em->getRepository(Raktar::class)->find(\mkw\store::getDefaultRaktarId());
            if ($raktar) {
                $fej->setRaktar($raktar);
            }

            $fej->setValutanem($valutanem);
            $fej->setArfolyam($fejadat['exchangeRate']);
            if ($fizmod) {
                $fej->setFizmod($fizmod);
            }

            if ($fejadat['issueDate']) {
                $fej->setKelt($fejadat['issueDate']);
            }
            if ($fejadat['deliveryDate']) {
                $fej->setTeljesites($fejadat['deliveryDate']);
            }
            if ($fejadat['paymentDate']) {
                $fej->setEsedekesseg($fejadat['paymentDate']);
            } else {
                $fej->setEsedekesseg();
            }
            $fej->setErbizonylatszam($fejadat['invoiceNumber']);
            $fej->setKellszallitasikoltsegetszamolni(false);
            $fej->setSzallitasikoltsegbrutto(0);

            $forditott = false;
            foreach ($tetelek as $tetel) {
                if ($this->addTetel($fej, $tetel, $koltsegtermek)) {
                    $forditott = true;
                }
            }
            if ($forditott) {
                $fej->setForditottadozas(true);
            }

            $em->persist($fej);
            $em->flush();
            $em->commit();

            return $fej;
        } catch (\Exception $e) {
            $em->rollback();
            // a fej és a tételei ilyenkor perzisztáltak, de mentetlenek: ha bent maradnának a
            // UnitOfWork-ben, a következő számla flush-e is rajtuk hibázna el
            $this->elenged($fej ?? null);
            throw $e;
        }
    }

    /**
     * Egy félbemaradt bizonylat (és tételei) leválasztása az EntityManagerről, hogy a
     * beütemezett, de el nem mentett beszúrások ne kerüljenek bele egy későbbi flush-be.
     */
    private function elenged(?Bizonylatfej $fej): void
    {
        if (!$fej) {
            return;
        }
        $em = \mkw\store::getEm();
        if (!$em->isOpen()) {
            return;
        }
        foreach ($fej->getBizonylattetelek() as $tetel) {
            $em->detach($tetel);
        }
        $em->detach($fej);
    }

    /**
     * A bizonylatfej-szintű adatok kiolvasása.
     */
    private function olvasFejadat(array $adat): array
    {
        $head = $this->ag($adat, 'invoiceMain.invoice.invoiceHead');
        $detail = $this->ag($head, 'invoiceDetail');

        $arfolyam = $this->szam($detail, 'exchangeRate');
        // teljesítés: ha nincs, az elszámolási időszak vége, végül a számla kelte
        $teljesites = $this->datum($this->mezo($detail, 'invoiceDeliveryDate'))
            ?: $this->datum($this->mezo($detail, 'invoiceDeliveryPeriodEnd'))
                ?: $this->datum($this->mezo($detail, 'invoiceAccountingDeliveryDate'));
        $kelt = $this->datum($this->mezo($adat, 'invoiceIssueDate'));

        return [
            'invoiceNumber' => $this->mezo($adat, 'invoiceNumber'),
            'issueDate' => $kelt ?: $teljesites,
            'deliveryDate' => $teljesites ?: $kelt,
            'paymentDate' => $this->datum($this->mezo($detail, 'paymentDate')) ?: $kelt,
            'currency' => $this->mezo($detail, 'currencyCode'),
            'exchangeRate' => $arfolyam > 0 ? $arfolyam : 1,
            'paymentMethod' => $this->mezo($detail, 'paymentMethod'),
            'supplier' => $this->olvasSzallito($this->ag($head, 'supplierInfo')),
        ];
    }

    /**
     * A számlakibocsátó (szállító) adatai. Az adószám és a cím hiányozhat (ilyenkor üresen
     * marad), az adószám pedig gyakran csonka (csak törzsszám, vagy törzsszám + ÁFA-kód),
     * ezért a törzsszámot külön is visszaadjuk: a partnerkeresés azzal megy.
     */
    private function olvasSzallito(array $supplier): array
    {
        // az adószám lehet egyszerű szöveg is, ha nem bontva érkezett
        $adoszam = $this->mezo($supplier, 'supplierTaxNumber');
        if ($adoszam === '') {
            $adoszam = implode('-', array_filter([
                $this->mezo($supplier, 'supplierTaxNumber.taxpayerId'),
                $this->mezo($supplier, 'supplierTaxNumber.vatCode'),
                $this->mezo($supplier, 'supplierTaxNumber.countyCode'),
            ], fn($v) => $v !== ''));
        }

        $cim = $this->ag($supplier, 'supplierAddress.simpleAddress');
        if ($cim) {
            $utca = $this->mezo($cim, 'additionalAddressDetail');
        } else {
            $cim = $this->ag($supplier, 'supplierAddress.detailedAddress');
            $utca = implode(' ', array_filter([
                $this->mezo($cim, 'streetName'),
                $this->mezo($cim, 'publicPlaceCategory'),
                $this->mezo($cim, 'number'),
                $this->mezo($cim, 'building'),
                $this->mezo($cim, 'staircase'),
                $this->mezo($cim, 'floor'),
                $this->mezo($cim, 'door'),
            ], fn($v) => $v !== ''));
        }

        return [
            'nev' => $this->mezo($supplier, 'supplierName'),
            'adoszam' => $adoszam,
            'torzsszam' => $this->torzsszam($adoszam),
            'orszagkod' => $this->mezo($cim, 'countryCode'),
            'irszam' => $this->mezo($cim, 'postalCode'),
            'varos' => $this->mezo($cim, 'city'),
            'utca' => $utca,
            // a szállító bankszámlaszáma a partner iban mezőjébe megy
            'bankszamlaszam' => $this->mezo($supplier, 'supplierBankAccountNumber'),
        ];
    }

    /**
     * A tételsorok kiolvasása. Egy tétel esetén a "line" nem lista, ezt a lista() kezeli.
     *
     * @return array[] tételenként a kiolvasott értékek
     */
    private function olvasTetelek(array $adat, array $fejadat): array
    {
        $tetelek = [];
        foreach ($this->lista($adat, 'invoiceMain.invoice.invoiceLines.line') as $line) {
            $tetelek[] = $this->olvasTetel($line, $fejadat);
        }
        return $tetelek;
    }

    /**
     * Egy tételsor kiolvasása. Az egyszerűsített (lineAmountsSimplified) számlán az egységár
     * bruttó, a normálon nettó – ezt a 'brutto' jelzi. Hiányzó egységárat a sorösszegből és a
     * mennyiségből számolunk vissza.
     */
    private function olvasTetel(array $line, array $fejadat): array
    {
        $mennyiseg = $this->szam($line, 'quantity');
        if ($mennyiseg == 0) {
            $mennyiseg = 1;
        }

        $egyszerusitett = (bool)$this->ag($line, 'lineAmountsSimplified');
        $osszegek = $this->ag($line, $egyszerusitett ? 'lineAmountsSimplified' : 'lineAmountsNormal');
        $afaadat = $this->olvasAfakulcs($this->ag($osszegek, 'lineVatRate'));

        $ar = $this->szam($line, 'unitPrice');
        $arhuf = $this->szam($line, 'unitPriceHUF');
        if ($egyszerusitett) {
            if ($ar == 0) {
                $ar = $this->szam($osszegek, 'lineGrossAmountSimplified') / $mennyiseg;
            }
            if ($arhuf == 0) {
                $arhuf = $this->szam($osszegek, 'lineGrossAmountSimplifiedHUF') / $mennyiseg;
            }
        } else {
            if ($ar == 0) {
                $ar = $this->szam($osszegek, 'lineNetAmountData.lineNetAmount') / $mennyiseg;
            }
            if ($arhuf == 0) {
                $arhuf = $this->szam($osszegek, 'lineNetAmountData.lineNetAmountHUF') / $mennyiseg;
            }
        }
        if ($arhuf == 0) {
            $arhuf = $ar * $fejadat['exchangeRate'];
        }

        return [
            'desc' => $this->mezo($line, 'lineDescription'),
            'qty' => $mennyiseg,
            'unitNav' => $this->mezo($line, 'unitOfMeasure'),
            'unitOwn' => $this->mezo($line, 'unitOfMeasureOwn'),
            'egysar' => $ar,
            'egysarhuf' => $arhuf,
            'brutto' => $egyszerusitett,
            'vtsz' => $this->olvasVtszkod($line),
            'vatPercent' => $afaadat['percent'],
            'vatCase' => $afaadat['case'],
            'reverse' => $afaadat['reverse'],
        ];
    }

    /**
     * A tételek törzsadat-hivatkozásainak (ÁFA-kulcs, VTSZ, mennyiségi egység) feloldása, a
     * hiányzók létrehozásával. Azért külön lépés a tételek felvitele előtt, mert a
     * létrehozás flush-sel jár – lásd a createFromArray() megjegyzését.
     *
     * @param array[] $tetelek
     * @return array[] a tételek az 'afa', 'vtszobj' és 'me' entitásokkal kiegészítve
     */
    private function torzsadatokFeloldasa(array $tetelek): array
    {
        foreach ($tetelek as &$t) {
            $t['afa'] = $this->keresVagyLetrehozAfa($t['vatPercent'], $t['vatCase']);
            // az új VTSZ a tétel ÁFA-kulcsát kapja
            $t['vtszobj'] = $this->keresVagyLetrehozVtsz($t['vtsz'], $t['afa']);
            $t['me'] = $this->keresVagyLetrehozME($t['unitOwn'], $t['unitNav']);
        }
        unset($t);
        return $tetelek;
    }

    /**
     * VTSZ kód a productCodes közül (a NAV-nál a kód lehet saját értékben is). A többi
     * kategóriát (SZJ, OWN, EAN, …) nem tudjuk hova tenni, azokat kihagyjuk.
     */
    private function olvasVtszkod(array $line): string
    {
        foreach ($this->lista($line, 'productCodes.productCode') as $pc) {
            if ($this->mezo($pc, 'productCodeCategory') === 'VTSZ') {
                $kod = $this->mezo($pc, 'productCodeValue');
                return $kod !== '' ? $kod : $this->mezo($pc, 'productCodeOwnValue');
            }
        }
        return '';
    }

    /**
     * ÁFA-kulcs a lineVatRate ágból: százalék, bruttóból számolt ÁFA-tartalom (egyszerűsített
     * számla), adómentesség/hatályon kívüliség (case) vagy fordított adózás.
     *
     * @return array{percent: float, case: string, reverse: bool}
     */
    private function olvasAfakulcs(array $vatRate): array
    {
        $afaadat = ['percent' => 0.0, 'case' => '', 'reverse' => false];

        if ($this->mezo($vatRate, 'vatPercentage') !== '') {
            $afaadat['percent'] = round($this->szam($vatRate, 'vatPercentage') * 100, 2);
        } elseif ($this->mezo($vatRate, 'vatContent') !== '') {
            // vatContent a bruttóra vetített ÁFA-tartalom (pl. 0.2126 → 27%)
            $tartalom = $this->szam($vatRate, 'vatContent');
            if ($tartalom > 0 && $tartalom < 1) {
                $afaadat['percent'] = round($tartalom / (1 - $tartalom) * 100, 2);
            }
        } elseif ($this->ag($vatRate, 'vatExemption')) {
            $afaadat['case'] = $this->mezo($vatRate, 'vatExemption.case');
        } elseif ($this->ag($vatRate, 'vatOutOfScope')) {
            $afaadat['case'] = $this->mezo($vatRate, 'vatOutOfScope.case');
        } elseif (array_key_exists('vatDomesticReverseCharge', $vatRate)) {
            $afaadat['reverse'] = true;
        }

        return $afaadat;
    }

    /**
     * Egy tétel hozzáadása a bizonylathoz. A fix költség termékből indulunk, majd a nevet,
     * ÁFÁ-t, VTSZ-t, mennyiségi egységet és az árakat a bejövő adatból felülírjuk.
     *
     * A törzsadatok itt már fel vannak oldva (lásd torzsadatokFeloldasa()): ebben a
     * metódusban nem szabad flush-t kiváltó műveletet végezni.
     *
     * @return bool true, ha a tétel fordított adózású (a fejen jelezni kell)
     */
    private function addTetel(Bizonylatfej $fej, array $t, Termek $koltsegtermek): bool
    {
        $em = \mkw\store::getEm();

        $tetel = new Bizonylattetel();
        $fej->addBizonylattetel($tetel);   // előbb a fejre kötjük (irany/valutanem/arfolyam)
        $tetel->setPersistentData();
        $tetel->setTermek($koltsegtermek); // innen jön a default név/ÁFA/VTSZ/ME – ezt írjuk felül

        // a VTSZ-t az ÁFA elé állítjuk, mert setVtsz() felülírhatja a tétel ÁFÁ-ját
        if ($t['vtszobj']) {
            $tetel->setVtsz($t['vtszobj']);
        }
        $tetel->setAfa($t['afa']);

        $tetel->setMekod($t['me']);

        if ($t['desc'] !== '') {
            $tetel->setTermeknev($t['desc']);
            $tetel->setTermeknevL1($t['desc']);
        }

        $tetel->setMennyiseg($t['qty']);
        // a nettó/bruttó egységár párját az ÁFA-kulcsból a setter számolja
        if ($t['brutto']) {
            $tetel->setBruttoegysar($t['egysar']);
            $tetel->setBruttoegysarhuf($t['egysarhuf']);
        } else {
            $tetel->setNettoegysar($t['egysar']);
            $tetel->setNettoegysarhuf($t['egysarhuf']);
        }
        $tetel->calc();

        $em->persist($tetel);

        return $t['reverse'];
    }

    /**
     * Létező, NEM rontott költségszámla keresése az érkeztetett (szállítói) bizonylatszám
     * alapján. Rontott bizonylat nem számít találatnak, így rontás után ugyanaz a szállítói
     * számla ismét importálható. Üres bizonylatszámnál nincs duplikátum-vizsgálat.
     */
    private function keresLetezoKoltsegszamla(string $ernum, Bizonylattipus $biztipus): ?Bizonylatfej
    {
        $ernum = trim($ernum);
        if ($ernum === '') {
            return null;
        }
        return \mkw\store::getEm()->getRepository(Bizonylatfej::class)->findOneBy([
            'bizonylattipus' => $biztipus,
            'erbizonylatszam' => $ernum,
            'rontott' => false,
        ]);
    }

    /**
     * A beállításokban kiválasztott költség termék. Kívülről is hívható, hogy az importálás
     * indítása előtt ellenőrizhető legyen a beállítás.
     *
     * @throws \Exception ha nincs beállítva vagy nem található
     */
    public function getKoltsegTermek(): Termek
    {
        $id = \mkw\store::getParameter(\mkw\consts::KoltsegTermek);
        $termek = $id ? \mkw\store::getEm()->getRepository(Termek::class)->find($id) : null;
        if (!$termek) {
            throw new \Exception('Nincs beállítva a költség termék a beállításokban.');
        }
        return $termek;
    }

    /**
     * Partner keresése adószám, annak hiányában (ilyen is érkezik) név alapján; ha nincs meg,
     * felvétele a partnertörzsbe szállítóként. A szállító bankszámlaszáma az iban mezőbe kerül;
     * meglévő partnernél csak akkor írjuk be, ha még üres (nem írunk felül törzsadatot).
     */
    private function keresVagyLetrehozPartner(array $s): Partner
    {
        $em = \mkw\store::getEm();
        $partner = $this->keresPartnertAdoszammal($s['adoszam'], $s['torzsszam']);
        if (!$partner && $s['nev'] !== '') {
            $partner = $em->getRepository(Partner::class)->findOneBy(['nev' => $s['nev']]);
        }
        if ($partner) {
            if ($s['bankszamlaszam'] !== '' && trim((string)$partner->getIban()) === '') {
                $partner->setIban($s['bankszamlaszam']);
                $em->flush();
            }
            return $partner;
        }

        $partner = new Partner();
        $partner->setNev($s['nev']);
        $partner->setAdoszam($s['adoszam']);
        $partner->setIrszam($s['irszam']);
        $partner->setVaros($s['varos']);
        $partner->setUtca($s['utca']);
        $partner->setIban($s['bankszamlaszam']);
        $orszag = $this->keresVagyLetrehozOrszag($s['orszagkod']);
        if ($orszag) {
            $partner->setOrszag($orszag);
        }
        $partner->setSzallito(true);
        $partner->setVatstatus(1);
        $em->persist($partner);
        $em->flush();

        return $partner;
    }

    /**
     * Partner keresése adószám alapján. A bejövő adószám gyakran csonka (pl. csak a 8 jegyű
     * törzsszám, vagy törzsszám + ÁFA-kód), a törzsben viszont a teljes adószám áll, ezért a
     * törzsszámra keresünk rá az adószám és a csoportos adószám elején. Csonkolatlan
     * (nem 8 jegyű) esetben marad a pontos egyezés.
     */
    private function keresPartnertAdoszammal(string $adoszam, string $torzsszam): ?Partner
    {
        $repo = \mkw\store::getEm()->getRepository(Partner::class);
        if ($torzsszam === '') {
            return $adoszam !== '' ? $repo->findOneBy(['adoszam' => $adoszam]) : null;
        }
        $qb = $repo->createQueryBuilder('p');
        return $qb
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->like('p.adoszam', ':minta'),
                    $qb->expr()->like('p.csoportosadoszam', ':minta')
                )
            )
            ->setParameter('minta', $torzsszam . '%')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Az adószám 8 jegyű törzsszáma, ha kiolvasható belőle.
     */
    private function torzsszam(string $adoszam): string
    {
        $szamok = preg_replace('/\D/', '', $adoszam);
        return strlen($szamok) >= 8 ? substr($szamok, 0, 8) : '';
    }

    private function keresVagyLetrehozOrszag(string $iso): ?Orszag
    {
        $iso = trim($iso);
        if ($iso === '') {
            return null;
        }
        $em = \mkw\store::getEm();
        $orszag = $em->getRepository(Orszag::class)->findOneBy(['iso3166' => $iso]);
        if (!$orszag) {
            $orszag = new Orszag();
            $orszag->setIso3166($iso);
            $orszag->setNev($iso);
            $em->persist($orszag);
            $em->flush();
        }
        return $orszag;
    }

    private function keresVagyLetrehozValutanem(string $code): ?Valutanem
    {
        $em = \mkw\store::getEm();
        $code = trim($code);
        if ($code !== '') {
            $v = $em->getRepository(Valutanem::class)->findOneBy(['nev' => $code]);
            if ($v) {
                return $v;
            }
            $v = new Valutanem();
            $v->setNev($code);
            $em->persist($v);
            $em->flush();
            return $v;
        }
        return $em->getRepository(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
    }

    /**
     * Fizmód keresése a NAV paymentMethod (navtipus) alapján. Ha nincs egyértelmű találat,
     * null-t ad – ilyenkor a partnertől örökölt fizmód marad a bizonylaton.
     */
    private function keresFizmod(string $paymentMethod): ?Fizmod
    {
        $paymentMethod = trim($paymentMethod);
        if ($paymentMethod === '') {
            return null;
        }
        return \mkw\store::getEm()->getRepository(Fizmod::class)->findOneBy(['navtipus' => $paymentMethod]);
    }

    /**
     * ÁFA-kulcs keresése (adómentesnél a NAV case-szel), hiány esetén létrehozása.
     */
    private function keresVagyLetrehozAfa(float $percent, string $navcase): Afa
    {
        $em = \mkw\store::getEm();
        if ($percent == 0 && $navcase !== '') {
            $afa = $em->getRepository(Afa::class)->findOneBy(['ertek' => 0, 'navcase' => $navcase]);
            if (!$afa) {
                $afa = new Afa();
                $afa->setNev($navcase);
                $afa->setErtek(0);
                $afa->setNavcase($navcase);
                $em->persist($afa);
                $em->flush();
            }
            return $afa;
        }
        $afa = $em->getRepository(Afa::class)->findOneBy(['ertek' => $percent]);
        if (!$afa) {
            $afa = new Afa();
            $afa->setNev(rtrim(rtrim(number_format($percent, 2, '.', ''), '0'), '.') . '%');
            $afa->setErtek($percent);
            $em->persist($afa);
            $em->flush();
        }
        return $afa;
    }

    /**
     * VTSZ keresése kód alapján, hiány esetén létrehozása (a tétel ÁFÁ-jával). Üres kódnál
     * null-t ad (nincs VTSZ a soron).
     */
    private function keresVagyLetrehozVtsz(string $szam, Afa $afa): ?Vtsz
    {
        $szam = trim($szam);
        if ($szam === '') {
            return null;
        }
        $em = \mkw\store::getEm();
        $vtsz = $em->getRepository(Vtsz::class)->findOneBy(['szam' => $szam]);
        if (!$vtsz) {
            $vtsz = new Vtsz();
            $vtsz->setSzam($szam);
            $vtsz->setAfa($afa);
            $em->persist($vtsz);
            $em->flush();
        }
        return $vtsz;
    }

    /**
     * Mennyiségi egység keresése: előbb a NAV típus (navtipus), majd a szöveges megnevezés
     * alapján, hiány esetén létrehozása. Ha nem jött saját (szöveges) egység – ez a helyzet a
     * NAV-tól kapott számláknál –, a NAV kód magyar megfelelőjét használjuk (PIECE → db).
     */
    private function keresVagyLetrehozME(string $ownText, string $navType): ME
    {
        $ownText = trim($ownText);
        $navType = strtoupper(trim($navType));
        if ($navType === 'OWN') {   // az 'OWN' a szöveges saját egységet jelenti
            $navType = '';
        }
        $em = \mkw\store::getEm();

        if ($ownText === '' && $navType !== '') {
            $me = $em->getRepository(ME::class)->findOneBy(['navtipus' => $navType]);
            if ($me) {
                return $me;
            }
            $ownText = self::ME_NEVEK[$navType] ?? $navType;
        }
        if ($ownText === '') {
            $ownText = 'db';
        }

        $me = $em->getRepository(ME::class)->findOneBy(['nev' => $ownText]);
        if (!$me) {
            $me = new ME();
            $me->setNev($ownText);
            if ($navType !== '') {
                $me->setNavtipus($navType);
            }
            $em->persist($me);
            $em->flush();
        }
        return $me;
    }

    /**
     * NAV SQL-dátum (Y-m-d) → DateTime. A Bizonylatfej dátum-setterei DateTime-ot közvetlenül
     * elfogadnak, így elkerüljük a lokalizált formátum-konverziót.
     */
    private function datum(string $s): ?\DateTime
    {
        $s = trim($s);
        if ($s === '') {
            return null;
        }
        try {
            return new \DateTime($s);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Részfa a dekódolt JSON-ból, pontokkal megadott útvonalon. Ha nincs meg (vagy nem
     * tömb), üres tömböt ad.
     */
    private function ag(array $adat, string $ut): array
    {
        $node = $adat;
        foreach (explode('.', $ut) as $kulcs) {
            if (!is_array($node) || !isset($node[$kulcs])) {
                return [];
            }
            $node = $node[$kulcs];
        }
        return is_array($node) ? $node : [];
    }

    /**
     * Szöveges érték a dekódolt JSON-ból, pontokkal megadott útvonalon. Hiányzó vagy nem
     * skalár értéknél üres sztring.
     */
    private function mezo(array $adat, string $ut): string
    {
        $node = $adat;
        foreach (explode('.', $ut) as $kulcs) {
            if (!is_array($node) || !isset($node[$kulcs])) {
                return '';
            }
            $node = $node[$kulcs];
        }
        return is_scalar($node) ? trim((string)$node) : '';
    }

    /**
     * Számérték a dekódolt JSON-ból.
     */
    private function szam(array $adat, string $ut): float
    {
        $s = $this->mezo($adat, $ut);
        return $s === '' ? 0.0 : (float)str_replace(',', '.', $s);
    }

    /**
     * Ismétlődő elem (pl. line, productCode) egységesen listaként: egy előfordulásnál a
     * JSON-ban maga az elem áll, többnél lista. A nem tömb elemeket eldobjuk.
     *
     * @return array[]
     */
    private function lista(array $adat, string $ut): array
    {
        $node = $this->ag($adat, $ut);
        if (!$node) {
            return [];
        }
        $lista = array_is_list($node) ? $node : [$node];
        return array_values(array_filter($lista, 'is_array'));
    }
}
