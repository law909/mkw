<?php

namespace Services;

use Entities\Afa;
use Entities\Bizonylatstatusz;
use Entities\CsomagTerminal;
use Entities\Fizmod;
use Entities\Orszag;
use Entities\Partner;
use Entities\Partnertipus;
use Entities\Raktar;
use Entities\Szallitasimod;
use Entities\Valutanem;

/**
 * Az UNAS rendelés törzsadatainak feloldása. Azért külön osztály, mert MINDEN feloldásnak a
 * bizonylat összeállítása ELŐTT meg kell történnie: a hiányzó törzsadat létrehozása flush()-sal
 * jár, az pedig tétel-felvitel közben hibára fut (a már perzisztált tétel ilyenkor egy még nem
 * perzisztált bizonylatfejre hivatkozna).
 *
 * Amit létrehozunk: `Partner`, `Afa`. Amit soha nem: `Valutanem`, `Orszag` (az ország vezérli a
 * `Partner::calcAFAOverride()`-ot, egy rosszul felvett ország hibás ÁFÁ-t okoz), valamint
 * `Fizmod` / `Szallitasimod` / `Bizonylatstatusz` – ezeknél a `tipus` eltalálása pénztárbizonylatot
 * és folyószámla sort érint. Ami nem oldható fel, az a beállított alapértékre esik és
 * figyelmeztetést kap ({@see getWarnings()}).
 *
 * Lásd docs/unas-megrendeles-integracio.md.
 */
class UnasTorzsadatService
{

    /** @var string[] */
    private $warnings = [];

    // ------------------------------------------------------------------
    // Leképezések (JSON a parameterek táblában)
    // ------------------------------------------------------------------

    /**
     * Az UNAS státuszai és fizetési/szállítási módjai boltonként szabadon konfigurálhatók,
     * ezért nem oszlop, hanem térkép: {UNAS azonosító: MKW azonosító}.
     *
     * @return array<string,string>
     */
    public static function getMap($par)
    {
        $raw = trim((string)\mkw\store::getParameter($par, ''));
        if ($raw === '') {
            return [];
        }
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    public static function saveMap($par, array $map)
    {
        $clean = [];
        foreach ($map as $key => $value) {
            $key = trim((string)$key);
            $value = trim((string)$value);
            if ($key !== '' && $value !== '' && $value !== '0') {
                $clean[$key] = $value;
            }
        }
        \mkw\store::setParameter($par, $clean ? json_encode($clean, JSON_UNESCAPED_UNICODE) : '');
    }

    private static function mapped($par, $unasid)
    {
        $unasid = trim((string)$unasid);
        if ($unasid === '') {
            return null;
        }
        $map = self::getMap($par);
        return isset($map[$unasid]) ? $map[$unasid] : null;
    }

    // ------------------------------------------------------------------
    // Figyelmeztetések
    // ------------------------------------------------------------------

    /** @return string[] */
    public function getWarnings()
    {
        return $this->warnings;
    }

    public function clearWarnings()
    {
        $this->warnings = [];
    }

    public function warn($message)
    {
        $this->warnings[] = $message;
    }

    // ------------------------------------------------------------------
    // Egyszerű feloldások
    // ------------------------------------------------------------------

    /**
     * @return Valutanem
     * @throws \Exception ismeretlen valutanemnél – kitalálni nem lehet, az árfolyam is tőle függ
     */
    public function resolveValutanem($code)
    {
        $code = trim((string)$code);
        if ($code === '') {
            $valutanem = $this->repo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
            if (!$valutanem) {
                throw new \Exception(t('A rendelés nem tartalmaz valutanemet, és nincs beállítva alapértelmezett sem.'));
            }
            return $valutanem;
        }
        $valutanem = $this->repo(Valutanem::class)->findOneBy(['nev' => $code]);
        if (!$valutanem) {
            throw new \Exception(sprintf(
                t('A(z) %s valutanem nincs felvéve a törzsben. Vedd fel, majd importáld újra a rendelést.'),
                $code
            ));
        }
        return $valutanem;
    }

    /**
     * @return Orszag
     * @throws \Exception ismeretlen országnál – az ország dönti el az ÁFA felülbírálást
     */
    public function resolveOrszag($countryCode, $countryName)
    {
        $code = trim((string)$countryCode);
        if ($code !== '') {
            $orszag = $this->repo(Orszag::class)->findOneBy(['iso3166' => $code]);
            if ($orszag) {
                return $orszag;
            }
        }
        $name = trim((string)$countryName);
        if ($name !== '') {
            $orszag = $this->repo(Orszag::class)->findOneBy(['nev' => $name]);
            if ($orszag) {
                return $orszag;
            }
        }
        if ($code === '' && $name === '') {
            $orszag = $this->repo(Orszag::class)->find(\mkw\store::getParameter(\mkw\consts::Orszag));
            if ($orszag) {
                return $orszag;
            }
        }
        throw new \Exception(sprintf(
            t('A(z) "%s" (%s) ország nincs felvéve a törzsben. Vedd fel, majd importáld újra a rendelést.'),
            $name,
            $code
        ));
    }

    /**
     * A százalékos kulcs egyértelmű, ezért ezt létrehozzuk – az a2aController::createAFA() mintája.
     * Flush-ol, ezért csak a bizonylat összeállítása előtt hívható.
     *
     * @return Afa|null
     */
    public function resolveAfa($vat)
    {
        $vat = trim((string)$vat);
        if ($vat === '') {
            return null;
        }
        // az UNAS "27" és "27%" alakban is adhatja
        $vat = str_replace([',', ' ', '%'], ['.', '', ''], $vat);
        if (!is_numeric($vat)) {
            $this->warn(sprintf(t('Értelmezhetetlen ÁFA kulcs az UNAS rendelésben: %s'), $vat));
            return null;
        }
        $ertek = (float)$vat;
        $afa = $this->repo(Afa::class)->findOneBy(['ertek' => $ertek]);
        if (!$afa) {
            $afa = new Afa();
            $afa->setNev(rtrim(rtrim(number_format($ertek, 2, '.', ''), '0'), '.') . ' %');
            $afa->setErtek($ertek);
            \mkw\store::getEm()->persist($afa);
            \mkw\store::getEm()->flush($afa);
        }
        return $afa;
    }

    /** @return Raktar|null */
    public function resolveRaktar()
    {
        $id = \mkw\store::getParameter(\mkw\consts::UnasRaktar) ?: \mkw\store::getParameter(\mkw\consts::Raktar);
        return $id ? $this->repo(Raktar::class)->find($id) : null;
    }

    /** @return Partnertipus|null */
    public function resolvePartnertipus()
    {
        $id = \mkw\store::getParameter(\mkw\consts::UnasPartnertipus);
        return $id ? $this->repo(Partnertipus::class)->find($id) : null;
    }

    /**
     * `Payment.Id` → leképezés → `Payment.Name` → beállított alapérték.
     *
     * @param array $payment a rendelés `Payment` blokkja
     *
     * @return Fizmod|null
     */
    public function resolveFizmod(array $payment)
    {
        $repo = $this->repo(Fizmod::class);

        $mapped = self::mapped(\mkw\consts::UnasFizmodMap, $payment['id'] ?? '');
        if ($mapped) {
            $fizmod = $repo->find($mapped);
            if ($fizmod) {
                return $fizmod;
            }
        }

        $name = trim((string)($payment['name'] ?? ''));
        if ($name !== '') {
            $fizmod = $repo->findOneBy(['nev' => $name]);
            if ($fizmod) {
                return $fizmod;
            }
        }

        $fizmod = $repo->find(\mkw\store::getParameter(\mkw\consts::Fizmod));
        $this->warn(sprintf(
            t('Az UNAS "%s" (id: %s) fizetési módja nincs leképezve, a bizonylatra a(z) "%s" került. Állítsd be az UNAS rendelések képernyőn.'),
            $name,
            $payment['id'] ?? '',
            $fizmod ? $fizmod->getNev() : '-'
        ));
        return $fizmod;
    }

    /**
     * `Shipping.Id` → leképezés → `Shipping.Name` → beállított alapérték.
     *
     * @param array $shipping a rendelés `Shipping` blokkja
     *
     * @return Szallitasimod|null
     */
    public function resolveSzallitasimod(array $shipping)
    {
        $repo = $this->repo(Szallitasimod::class);

        $mapped = self::mapped(\mkw\consts::UnasSzallmodMap, $shipping['id'] ?? '');
        if ($mapped) {
            $szallitasimod = $repo->find($mapped);
            if ($szallitasimod) {
                return $szallitasimod;
            }
        }

        $name = trim((string)($shipping['name'] ?? ''));
        if ($name !== '') {
            $szallitasimod = $repo->findOneBy(['nev' => $name]);
            if ($szallitasimod) {
                return $szallitasimod;
            }
        }

        $this->warn(sprintf(
            t('Az UNAS "%s" (id: %s) szállítási módja nincs leképezve, a bizonylatra nem került szállítási mód. Állítsd be az UNAS rendelések képernyőn.'),
            $name,
            $shipping['id'] ?? ''
        ));
        return null;
    }

    /**
     * `StatusID` → leképezés → kifizetett rendelés státusza → `StatusType` szerinti négy tartalék
     * → „függőben". A kifizetettség erősebb a `StatusType`-nál, de a leképezésnél nem: ha a bolt
     * kezelője összerendelte a státuszokat, az a szándéka.
     *
     * @param array $order a feldolgozott rendelés
     *
     * @return Bizonylatstatusz|null
     */
    public function resolveBizonylatstatusz(array $order)
    {
        $repo = $this->repo(Bizonylatstatusz::class);
        $status = $order['status'] ?? [];

        $mapped = self::mapped(\mkw\consts::UnasStatuszMap, $status['id'] ?? '');
        if ($mapped) {
            $statusz = $repo->find($mapped);
            if ($statusz) {
                return $statusz;
            }
        }

        if (($order['payment']['status'] ?? '') === 'paid') {
            $statusz = $repo->find(\mkw\store::getParameter(\mkw\consts::UnasFizetveStatusz));
            if ($statusz) {
                return $statusz;
            }
        }

        $fallbacks = [
            'open_normal' => \mkw\consts::UnasStatuszOpenNormal,
            'open_prepare' => \mkw\consts::UnasStatuszOpenPrepare,
            'close_ok' => \mkw\consts::UnasStatuszCloseOk,
            'close_fault' => \mkw\consts::UnasStatuszCloseFault,
        ];
        $type = trim((string)($status['type'] ?? ''));
        if (isset($fallbacks[$type])) {
            $statusz = $repo->find(\mkw\store::getParameter($fallbacks[$type]));
            if ($statusz) {
                return $statusz;
            }
        }

        $statusz = $repo->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
        $this->warn(sprintf(
            t('Az UNAS "%s" (id: %s, típus: %s) rendelésstátusza nincs leképezve, a bizonylat a(z) "%s" státuszt kapta.'),
            $status['name'] ?? '',
            $status['id'] ?? '',
            $type,
            $statusz ? $statusz->getNev() : '-'
        ));
        return $statusz;
    }

    /**
     * A leképezés fordított iránya a visszaíráshoz: MKW státusz azonosító → UNAS státusz azonosító.
     *
     * @return string|null
     */
    public static function reverseStatusz($bizonylatstatuszid)
    {
        $bizonylatstatuszid = trim((string)$bizonylatstatuszid);
        if ($bizonylatstatuszid === '') {
            return null;
        }
        foreach (self::getMap(\mkw\consts::UnasStatuszMap) as $unasid => $mkwid) {
            if (trim((string)$mkwid) === $bizonylatstatuszid) {
                return (string)$unasid;
            }
        }
        return null;
    }

    /**
     * Az UNAS csomagpont-azonosítója a szállítási mód `terminaltipus`-a szerinti terminál.
     *
     * @return CsomagTerminal|null
     */
    public function resolveCsomagterminal($szallitasimod, $deliveryPointId)
    {
        $deliveryPointId = trim((string)$deliveryPointId);
        $tipus = $szallitasimod ? trim((string)$szallitasimod->getTerminaltipus()) : '';
        if ($deliveryPointId === '' || $tipus === '') {
            return null;
        }
        $terminal = $this->repo(CsomagTerminal::class)->findOneBy([
            'idegenid' => $deliveryPointId,
            'tipus' => $tipus,
        ]);
        if (!$terminal) {
            $this->warn(sprintf(
                t('A(z) %s azonosítójú %s csomagpont nincs a törzsben, a bizonylatra nem került csomagpont.'),
                $deliveryPointId,
                $tipus
            ));
        }
        return $terminal;
    }

    // ------------------------------------------------------------------
    // Partner
    // ------------------------------------------------------------------

    /**
     * Keresés email alapján (először a regisztrált, aztán a vendég partnerek között), különben
     * új vendégpartner. Meglévő partnernél CSAK az üres mezőket töltjük (a NAV importer szabálya):
     * a rendeléskori cím amúgy is a bizonylatfejre kerül, így egy régi vevő egyszeri eltérő címe
     * nem rontja el a törzsadatot.
     *
     * Flush-ol, ha újat hoz létre.
     *
     * @param array $order a feldolgozott rendelés
     *
     * @return Partner
     * @throws \Exception ismeretlen országnál
     */
    public function resolvePartner(array $order)
    {
        $invoice = $order['customer']['invoice'] ?? [];
        $shipping = $order['customer']['shipping'] ?? [];
        $email = mb_substr(trim((string)($order['customer']['email'] ?? '')), 0, 100);

        // az ország feloldása a partner mentése ELŐTT kell: onnan jön a szamlatipus és a vatstatus
        $orszag = $this->resolveOrszag($invoice['countrycode'] ?? '', $invoice['country'] ?? '');
        $szallorszag = null;
        if (($shipping['countrycode'] ?? '') !== '' || ($shipping['country'] ?? '') !== '') {
            $szallorszag = $this->resolveOrszag($shipping['countrycode'] ?? '', $shipping['country'] ?? '');
        }

        $partner = $this->findPartner($email);
        $uj = !$partner;
        if ($uj) {
            $partner = new Partner();
            $partner->setVendeg(true);
            $partner->setPartnertipus($this->resolvePartnertipus());
            $partner->setEmail($email);
        }

        $this->fillPartner($partner, $order, $orszag, $szallorszag, $uj);

        if ($uj) {
            \mkw\store::getEm()->persist($partner);
            \mkw\store::getEm()->flush($partner);
        }
        return $partner;
    }

    /** @return Partner|null */
    private function findPartner($email)
    {
        if ($email === '') {
            return null;
        }
        /** @var \Entities\PartnerRepository $repo */
        $repo = $this->repo(Partner::class);
        $found = $repo->findNemVendegByEmail($email);
        if (!$found) {
            $found = $repo->findByEmail($email);
        }
        return $found ? $found[0] : null;
    }

    /**
     * @param Partner $partner
     * @param bool $uj új partnernél minden mezőt írunk, meglévőnél csak az üreseket
     */
    private function fillPartner($partner, array $order, $orszag, $szallorszag, $uj)
    {
        $invoice = $order['customer']['invoice'] ?? [];
        $shipping = $order['customer']['shipping'] ?? [];
        $contact = $order['customer']['contact'] ?? [];

        [$vezeteknev, $keresztnev] = $this->splitNev($invoice['nev'] ?? '');
        $this->fill($partner, 'Nev', $invoice['nev'] ?? '', 255, $uj);
        $this->fill($partner, 'Vezeteknev', $vezeteknev, 255, $uj);
        $this->fill($partner, 'Keresztnev', $keresztnev, 255, $uj);
        $this->fill($partner, 'Irszam', $invoice['zip'] ?? '', 10, $uj);
        $this->fill($partner, 'Varos', $invoice['city'] ?? '', 40, $uj);
        $this->fill($partner, 'Utca', $this->utca($invoice), 60, $uj);
        $this->fill($partner, 'Hazszam', $invoice['streetnumber'] ?? '', 40, $uj);
        $this->fill($partner, 'Telefon', $contact['phone'] ?? ($contact['mobile'] ?? ''), 60, $uj);
        $this->fill($partner, 'Mobil', $contact['mobile'] ?? '', 60, $uj);

        $this->fill($partner, 'Szallnev', $shipping['nev'] ?? '', 255, $uj);
        $this->fill($partner, 'Szallirszam', $shipping['zip'] ?? '', 10, $uj);
        $this->fill($partner, 'Szallvaros', $shipping['city'] ?? '', 40, $uj);
        $this->fill($partner, 'Szallutca', $this->utca($shipping), 60, $uj);
        $this->fill($partner, 'Szallhazszam', $shipping['streetnumber'] ?? '', 40, $uj);

        if ($uj || !$partner->getOrszag()) {
            $partner->setOrszag($orszag);
        }
        if ($szallorszag && ($uj || !$partner->getSzallorszag())) {
            $partner->setSzallorszag($szallorszag);
        }

        $szamlatipus = $this->calcSzamlatipus($orszag);
        if ($uj) {
            $partner->setSzamlatipus($szamlatipus);
            $partner->setVatstatus($this->calcVatstatus($invoice, $orszag));
        }

        // az adószám oda kerül, ahova az ország szerint tartozik (a checkout mintája)
        $adoszam = trim((string)($invoice['taxnumber'] ?? ''));
        $euadoszam = trim((string)($invoice['eutaxnumber'] ?? ''));
        if ($szamlatipus === 0) {
            $this->fill($partner, 'Adoszam', $adoszam, 13, $uj);
        } elseif ($szamlatipus === 1) {
            $this->fill($partner, 'Euadoszam', $euadoszam ?: $adoszam, 30, $uj);
        } else {
            $this->fill($partner, 'Thirdadoszam', $euadoszam ?: $adoszam, 50, $uj);
        }
    }

    /** 0 = magyar, 1 = EU-n belüli, 2 = EU-n kívüli (PartnerRepository::getSzamlatipusList) */
    public function calcSzamlatipus($orszag)
    {
        if (\mkw\store::isMagyarorszag($orszag)) {
            return 0;
        }
        return $orszag && $orszag->getEu() ? 1 : 2;
    }

    /**
     * 1 = belföldi adóalany, 2 = magánszemély, 3 = egyéb
     * (PartnerRepository::getVatstatusList, a mugenraceCheckoutController leképezése).
     *
     * Elsősorban az ADÓSZÁMBÓL dolgozunk, nem a `CustomerType`-ból: az UNAS dokumentációja szerint
     * az utóbbi enum (`private` | `company` | `other_customer_without_tax_number`), de a
     * kiküldése „áruházi beállítástól is függ" – a mi boltunk 78 rendelésből egyszer sem küldte,
     * miközben 30-ban ott volt az adószám. Ha egy bolt mégis küldi, figyelembe vesszük.
     */
    private function calcVatstatus(array $invoice, $orszag)
    {
        $tipus = trim((string)($invoice['customertype'] ?? ''));
        if ($tipus === 'other_customer_without_tax_number') {
            return 3;
        }
        $vanAdoszam = trim((string)($invoice['taxnumber'] ?? '')) !== ''
            || trim((string)($invoice['eutaxnumber'] ?? '')) !== '';
        if ($tipus !== 'company' && !$vanAdoszam) {
            return 2;
        }
        return \mkw\store::isMagyarorszag($orszag) ? 1 : 3;
    }

    /** Az UNAS a `Street`-et darabolva is adja; ha nincs darabolva, a teljes utcát használjuk. */
    private function utca(array $address)
    {
        $streetName = trim((string)($address['streetname'] ?? ''));
        $streetType = trim((string)($address['streettype'] ?? ''));
        if ($streetName !== '') {
            return trim($streetName . ' ' . $streetType);
        }
        return trim((string)($address['street'] ?? ''));
    }

    /** Egy szóközös névnél az UTOLSÓ szóköznél bontunk – a Vatera importer szabálya. */
    private function splitNev($nev)
    {
        $nev = trim(preg_replace('/\s+/u', ' ', (string)$nev));
        $pos = mb_strrpos($nev, ' ');
        if ($pos === false) {
            return [$nev, ''];
        }
        return [mb_substr($nev, 0, $pos), mb_substr($nev, $pos + 1)];
    }

    private function fill($entity, $field, $value, $maxLength, $uj)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return;
        }
        if (!$uj && trim((string)$entity->{'get' . $field}()) !== '') {
            return;
        }
        $entity->{'set' . $field}(mb_substr($value, 0, $maxLength));
    }

    private function repo($entityClass)
    {
        return \mkw\store::getEm()->getRepository($entityClass);
    }

}
