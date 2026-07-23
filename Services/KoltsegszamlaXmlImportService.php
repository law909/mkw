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
 * NAV Online Számla 3.0 InvoiceData XML-ből (amilyet a {@see Bizonylatfej::toNAVOnlineXML3_0()}
 * állít elő) készít "koltsegszamla" bizonylattípusú bizonylatot.
 *
 * Alapelvek:
 * - A tételek terméke NEM a beérkezett adatból keresett termék, hanem a beállításokban
 *   kiválasztott "költség" termék (\mkw\consts::KoltsegTermek). A tétel neve, ára, ÁFÁ-ja,
 *   mennyiségi egysége és VTSZ-e viszont az XML-ből jön.
 * - A partner a számlát kiállító fél: az XML supplierInfo blokkja. Adószám alapján keressük,
 *   ha nincs, felvesszük a partnertörzsbe (szállítóként).
 * - A kapcsolódó törzsadatokat (mennyiségi egység, VTSZ, ÁFA-kulcs, ország, valutanem), ha
 *   nincsenek nálunk, létrehozzuk.
 *
 * A bizonylatszámot, a fejösszegeket és a folyószámlát a BizonylatfejListener számolja a
 * mentéskor; itt csak a bemeneti adatokat töltjük.
 */
class KoltsegszamlaXmlImportService
{
    private const BIZONYLATTIPUS = 'koltsegszamla';

    private const NS_DATA_3_0 = 'http://schemas.nav.gov.hu/OSA/3.0/data';

    /**
     * Költségszámla létrehozása egy NAV InvoiceData XML-ből.
     *
     * @param string $xml nyers InvoiceData XML, vagy a toNAVOnlineXML által visszaadott
     *                    "CREATE"/"STORNO" + base64 (a '+' jelet '$'-ra cserélve) csomagolt forma
     *
     * @return Bizonylatfej a létrehozott, perzisztált költségszámla; vagy a már létező, nem
     *                      rontott költségszámla, ha az érkeztetett bizonylatszámmal ilyen van
     *
     * @throws \Exception ha az XML nem értelmezhető, vagy nincs beállítva a költség termék
     */
    public function createFromXml(string $xml): Bizonylatfej
    {
        $sx = $this->parse($this->unwrap($xml));
        $fejadat = $this->olvasFejadat($sx);
        $tetelek = $this->olvasTetelek($sx);

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

        $em->beginTransaction();
        try {
            $partner = $this->keresVagyLetrehozPartner($fejadat['supplier']);

            $fej = new Bizonylatfej();
            $fej->setBizonylattipus($biztipus);
            $fej->setPersistentData();
            // a partner beállítása felülírja a partner* mezőket és a partner alapértelmezett
            // valutanemét/fizmódját is – a devizát és a fizmódot utána az XML-ből állítjuk
            $fej->setPartner($partner);

            $raktar = $em->getRepository(Raktar::class)->find(\mkw\store::getDefaultRaktarId());
            if ($raktar) {
                $fej->setRaktar($raktar);
            }

            $fej->setValutanem($this->keresVagyLetrehozValutanem($fejadat['currency']));
            $fej->setArfolyam($fejadat['exchangeRate']);
            $fizmod = $this->keresFizmod($fejadat['paymentMethod']);
            if ($fizmod) {
                $fej->setFizmod($fizmod);
            }

            $fej->setKelt($fejadat['issueDate']);
            $fej->setTeljesites($fejadat['deliveryDate']);
            if ($fejadat['paymentDate']) {
                $fej->setEsedekesseg($fejadat['paymentDate']);
            }
            // a szállító eredeti (érkeztetett) bizonylatszáma
            $fej->setErbizonylatszam($fejadat['invoiceNumber']);
            // költségszámlán ne számoljon szállítási költséget a listener
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
            throw $e;
        }
    }

    /**
     * A "CREATE"/"STORNO" + base64 csomagolás lefejtése, ha az input úgy érkezett. Nyers XML
     * esetén változatlanul visszaadja.
     */
    private function unwrap(string $xml): string
    {
        $xml = trim($xml);
        if ($xml === '' || $xml[0] === '<') {
            return $xml;
        }
        $prefix = '';
        foreach (['CREATE', 'STORNO', 'MODIFY'] as $p) {
            if (str_starts_with($xml, $p)) {
                $prefix = $p;
                break;
            }
        }
        $b64 = substr($xml, strlen($prefix));
        // a producer a base64 '+' jeleit '$'-ra cseréli (Delphi miatt) – visszaalakítjuk
        $b64 = str_replace('$', '+', $b64);
        $decoded = base64_decode($b64, true);
        return ($decoded !== false && $decoded !== '') ? $decoded : $xml;
    }

    private function parse(string $xmlstr): \SimpleXMLElement
    {
        $prev = libxml_use_internal_errors(true);
        $sx = simplexml_load_string($xmlstr);
        libxml_use_internal_errors($prev);
        if ($sx === false) {
            throw new \Exception('A NAV XML nem értelmezhető.');
        }
        $ns = $sx->getDocNamespaces(true);
        if (isset($ns['']) && $ns[''] !== self::NS_DATA_3_0) {
            throw new \Exception('Nem támogatott NAV séma (' . $ns[''] . '). Csak a 3.0 verzió támogatott.');
        }
        return $sx;
    }

    /**
     * A bizonylatfej-szintű adatok kiolvasása. A base-névterű (base:) elemeket a
     * children('base', true) ágon érjük el, a többit sima property-hozzáféréssel.
     */
    private function olvasFejadat(\SimpleXMLElement $sx): array
    {
        $head = $sx->invoiceMain->invoice->invoiceHead;
        $supplier = $head->supplierInfo;

        $taxbase = $supplier->supplierTaxNumber->children('base', true);
        $adoszam = implode('-', array_filter([
            trim((string)$taxbase->taxpayerId),
            trim((string)$taxbase->vatCode),
            trim((string)$taxbase->countyCode),
        ], fn($v) => $v !== ''));

        $addr = $supplier->supplierAddress->children('base', true)->simpleAddress;

        $detail = $head->invoiceDetail;
        $exchangeRate = (float)$detail->exchangeRate;

        return [
            'invoiceNumber' => trim((string)$sx->invoiceNumber),
            'issueDate' => $this->datum((string)$sx->invoiceIssueDate),
            'deliveryDate' => $this->datum((string)$detail->invoiceDeliveryDate),
            'paymentDate' => $this->datum((string)$detail->paymentDate),
            'currency' => trim((string)$detail->currencyCode),
            'exchangeRate' => $exchangeRate > 0 ? $exchangeRate : 1,
            'paymentMethod' => trim((string)$detail->paymentMethod),
            'supplier' => [
                'nev' => trim((string)$supplier->supplierName),
                'adoszam' => $adoszam,
                'orszagkod' => trim((string)$addr->countryCode),
                'irszam' => trim((string)$addr->postalCode),
                'varos' => trim((string)$addr->city),
                'utca' => trim((string)$addr->additionalAddressDetail),
            ],
        ];
    }

    /**
     * A tételsorok kiolvasása.
     *
     * @return array[] tételenként a nyers XML-értékek
     */
    private function olvasTetelek(\SimpleXMLElement $sx): array
    {
        $tetelek = [];
        $lines = $sx->invoiceMain->invoice->invoiceLines;
        foreach ($lines->line as $line) {
            $amounts = $line->lineAmountsNormal;

            // VTSZ productCode (ha van)
            $vtsz = '';
            if (isset($line->productCodes)) {
                foreach ($line->productCodes->productCode as $pc) {
                    if ((string)$pc->productCodeCategory === 'VTSZ') {
                        $vtsz = trim((string)$pc->productCodeValue);
                    }
                }
            }

            // ÁFA: százalék, adómentesség (case) vagy fordított adózás
            $vatPercent = 0.0;
            $vatCase = '';
            $reverse = false;
            $vr = $amounts->lineVatRate;
            if (isset($vr->vatPercentage)) {
                $vatPercent = round((float)$vr->vatPercentage * 100, 2);
            } elseif (isset($vr->vatExemption)) {
                $vatCase = trim((string)$vr->vatExemption->case);
            } elseif (isset($vr->vatDomesticReverseCharge)) {
                $reverse = true;
            }

            $tetelek[] = [
                'desc' => trim((string)$line->lineDescription),
                'qty' => (float)$line->quantity,
                'unitNav' => trim((string)$line->unitOfMeasure),
                'unitOwn' => trim((string)$line->unitOfMeasureOwn),
                'unitPrice' => (float)$line->unitPrice,
                'unitPriceHUF' => (float)$line->unitPriceHUF,
                'vtsz' => $vtsz,
                'vatPercent' => $vatPercent,
                'vatCase' => $vatCase,
                'reverse' => $reverse,
            ];
        }
        return $tetelek;
    }

    /**
     * Egy tétel hozzáadása a bizonylathoz. A fix költség termékből indulunk, majd a nevet,
     * ÁFÁ-t, VTSZ-t, mennyiségi egységet és az árakat az XML-ből felülírjuk.
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

        $afa = $this->keresVagyLetrehozAfa($t['vatPercent'], $t['vatCase']);

        // a VTSZ-t az ÁFA elé állítjuk, mert setVtsz() felülírhatja a tétel ÁFÁ-ját
        $vtsz = $this->keresVagyLetrehozVtsz($t['vtsz'], $afa);
        if ($vtsz) {
            $tetel->setVtsz($vtsz);
        }
        $tetel->setAfa($afa);

        $tetel->setMekod($this->keresVagyLetrehozME($t['unitOwn'], $t['unitNav']));

        $tetel->setTermeknev($t['desc']);
        $tetel->setTermeknevL1($t['desc']);

        $tetel->setMennyiseg($t['qty']);
        $tetel->setNettoegysar($t['unitPrice']);         // bruttoegysar az ÁFÁ-ból automatikus
        $tetel->setNettoegysarhuf($t['unitPriceHUF']);
        $tetel->setBruttoegysarhuf($afa->calcBrutto($t['unitPriceHUF']));
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

    private function getKoltsegTermek(): Termek
    {
        $id = \mkw\store::getParameter(\mkw\consts::KoltsegTermek);
        $termek = $id ? \mkw\store::getEm()->getRepository(Termek::class)->find($id) : null;
        if (!$termek) {
            throw new \Exception('Nincs beállítva (vagy nem található) a költség termék a beállításokban.');
        }
        return $termek;
    }

    /**
     * Partner keresése adószám alapján; ha nincs, felvétele a partnertörzsbe szállítóként.
     */
    private function keresVagyLetrehozPartner(array $s): Partner
    {
        $em = \mkw\store::getEm();
        $partner = null;
        if ($s['adoszam'] !== '') {
            $partner = $em->getRepository(Partner::class)->findOneBy(['adoszam' => $s['adoszam']]);
        }
        if (!$partner) {
            $partner = new Partner();
            $partner->setNev($s['nev']);
            $partner->setAdoszam($s['adoszam']);
            $partner->setIrszam($s['irszam']);
            $partner->setVaros($s['varos']);
            $partner->setUtca($s['utca']);
            $orszag = $this->keresVagyLetrehozOrszag($s['orszagkod']);
            if ($orszag) {
                $partner->setOrszag($orszag);
            }
            $partner->setSzallito(true);
            $em->persist($partner);
            $em->flush();
        }
        return $partner;
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
     * Mennyiségi egység keresése a saját (szöveges) megnevezés alapján, hiány esetén
     * létrehozása. A NAV típust (unitOfMeasure) az új egység navtipus mezőjébe tesszük,
     * kivéve az 'OWN'-t (az a szöveges saját egységet jelenti).
     */
    private function keresVagyLetrehozME(string $ownText, string $navType): ME
    {
        $ownText = trim($ownText);
        $navType = trim($navType);
        if ($ownText === '') {
            $ownText = ($navType !== '' && $navType !== 'OWN') ? $navType : 'db';
        }
        $em = \mkw\store::getEm();
        $me = $em->getRepository(ME::class)->findOneBy(['nev' => $ownText]);
        if (!$me) {
            $me = new ME();
            $me->setNev($ownText);
            if ($navType !== '' && $navType !== 'OWN') {
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
}
