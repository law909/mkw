<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Unasoutbox;

/**
 * A bizonylat életciklusából származó változások visszaírása az UNAS-ba (`setOrder`).
 *
 * A sorokat a `Listeners\UnasOutboxListener` írja `onFlush`-ban, a küldés viszont NEM ott
 * történik: egy HTTP hívás a flush közepén blokkol, hibázhat és bezárja az EntityManagert.
 * A sort a cron (vagy egy admin gomb) húzza le.
 *
 * Lásd docs/unas-megrendeles-integracio.md.
 */
class UnasSetOrderService
{

    /** ennyi próbálkozás után a sor véglegesen hibás */
    public const MAXPROBALKOZAS = 5;

    /**
     * Amit az UNAS-ba számlaként jelentünk. A `Bizonylattipus`-on nincs „ez számla" jelző, a
     * `navbekuldendo` pedig más kérdésre válaszol, ezért azonosítólista.
     */
    public const SZAMLATIPUSOK = ['szamla', 'esetiszamla', 'keziszamla'];

    /** @var UnasService */
    private $unas;

    public function __construct(?UnasService $unas = null)
    {
        $this->unas = $unas ?: new UnasService();
    }

    // ------------------------------------------------------------------
    // Sorbatétel
    // ------------------------------------------------------------------

    /**
     * A listener hívja, a UnitOfWork-ön belül – ezért NEM persist-el és nem flush-ol, a
     * visszaadott entitást a hívó veszi fel a changesetbe.
     *
     * @param Bizonylatfej $fej
     *
     * @return Unasoutbox
     */
    public static function build($fej, $tipus)
    {
        $sor = new Unasoutbox();
        $sor->setCreated(new \DateTime());
        $sor->setBizonylatfej($fej);
        $sor->setUnaskey($fej->getUnaskey());
        $sor->setUnaskulsokey($fej->getUnaskulsokey());
        $sor->setTipus($tipus);
        $sor->setAllapot(Unasoutbox::ALLAPOTFUGGO);
        return $sor;
    }

    /**
     * Kézi sorbatétel (admin gomb, kötegelt újraküldés). Flush-ol.
     *
     * @param Bizonylatfej $fej
     *
     * @return Unasoutbox|null
     */
    public function enqueue($fej, $tipus)
    {
        if (!$fej || !trim((string)$fej->getUnaskey()) || !self::isEnabled($tipus)) {
            return null;
        }
        $em = \mkw\store::getEm();
        /** @var \Entities\UnasoutboxRepository $repo */
        $repo = $em->getRepository(Unasoutbox::class);
        $letezo = $repo->findPending($fej->getUnaskey(), $tipus);
        if ($letezo) {
            return $letezo;
        }
        $sor = self::build($fej, $tipus);
        $em->persist($sor);
        $em->flush($sor);
        return $sor;
    }

    /** A visszaírás típusonként kapcsolható – a bolt kezelője dönti el, mit engedünk az UNAS-ba. */
    public static function isEnabled($tipus)
    {
        $par = [
            Unasoutbox::TIPUSSTATUSZ => \mkw\consts::UnasVisszairasStatusz,
            Unasoutbox::TIPUSSZAMLA => \mkw\consts::UnasVisszairasSzamla,
            Unasoutbox::TIPUSCSOMAG => \mkw\consts::UnasVisszairasCsomag,
        ];
        if (!isset($par[$tipus])) {
            return false;
        }
        return (bool)\mkw\store::getParameter($par[$tipus]);
    }

    // ------------------------------------------------------------------
    // Küldés
    // ------------------------------------------------------------------

    /**
     * @return array{osszes: int, kesz: int, hiba: int, kihagyva: int, fek: bool}
     */
    public function drainOutbox($limit = 50)
    {
        $summary = ['osszes' => 0, 'kesz' => 0, 'hiba' => 0, 'kihagyva' => 0, 'fek' => false];
        if (!UnasService::isEnabled()) {
            return $summary;
        }
        $em = \mkw\store::getEm();
        /** @var \Entities\UnasoutboxRepository $repo */
        $repo = $em->getRepository(Unasoutbox::class);
        $sorok = $repo->getPending($limit);
        $api = $this->unas->getApi();

        // rendelésenként külön hívás: a setOrder 100-at is elvinne egyszerre, de akkor egy hibás
        // rendelés miatt nem tudnánk, melyik sor ment át és melyik nem
        foreach ($sorok as $sor) {
            $summary['osszes']++;
            $payload = $this->buildPayload($sor);
            if (!$payload) {
                // a bizonylat közben eltűnt, vagy a típus visszaírása ki van kapcsolva
                $this->siker($sor);
                $summary['kihagyva']++;
            } else {
                $xml = $api->setOrder(['Order' => [$payload]]);
                $hiba = $xml ? $this->orderError($xml) : $api->getLasterrorsAsString();
                if ($hiba === '') {
                    $this->siker($sor);
                    $summary['kesz']++;
                } elseif ($this->nemKuldtukEl($api)) {
                    // A rate limiter / karbantartási ablak meg sem próbálta elküldeni, tehát ez
                    // nem próbálkozás: különben egy szűk óra 5 kör alatt minden sort elhasználna.
                    $summary['osszes']--;
                    $summary['fek'] = true;
                    break;
                } else {
                    $this->hiba($sor, $hiba);
                    $summary['hiba']++;
                }
            }
            if (!$em->isOpen()) {
                return $summary;
            }
            $em->flush();
        }
        return $summary;
    }

    /**
     * A `setOrder` válaszában rendelésenként is jöhet hiba. Az `UnasAPI::extractErrors()`
     * szándékosan csak a gyökér szintjét nézi, ezért a beágyazott elutasítást itt kell elkapni –
     * különben egy visszautasított státuszmódosítást sikernek vennénk.
     *
     * @return string üres, ha rendben
     */
    private function orderError(\SimpleXMLElement $xml)
    {
        $nodes = $xml->getName() === 'Order' ? [$xml] : ($xml->Order ?? []);
        foreach ($nodes as $node) {
            if (isset($node->Error)) {
                $code = isset($node->Error->Code) ? trim((string)$node->Error->Code) : '';
                $message = isset($node->Error->Message) ? trim((string)$node->Error->Message) : trim((string)$node->Error);
                return trim($code . ' ' . $message) ?: 'ORDERERROR';
            }
            $statusz = isset($node->Status) ? strtolower(trim((string)$node->Status)) : '';
            if ($statusz !== '' && $statusz !== 'ok' && $statusz !== 'success') {
                return 'Az UNAS elutasította a rendelés módosítását: ' . (string)$node->Status;
            }
        }
        return '';
    }

    /**
     * A hívás el sem indult (rate limit fék, karbantartási ablak, hiányzó beállítás). Ilyenkor a
     * sor próbálkozásszáma nem nőhet, és a kötegnek is le kell állnia.
     */
    private function nemKuldtukEl($api)
    {
        foreach ($api->getLasterrors() as $error) {
            if (in_array($error['code'], ['RATELIMIT', 'MAINTENANCE', 'NOTCONFIGURED'], true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * A `setOrder` `modify` blokkja. A rendelés státuszát csak akkor írjuk át, ha van rá
     * leképezés – vaktában küldött státusznévvel az UNAS-ban rossz állapotba kerülne a rendelés.
     *
     * @param Unasoutbox $sor
     *
     * @return array|null
     */
    private function buildPayload($sor)
    {
        if (!self::isEnabled($sor->getTipus())) {
            return null;
        }
        $fej = $sor->getBizonylatfej();
        if (!$fej) {
            return null;
        }

        $payload = [
            'Action' => 'modify',
            // a setOrder csak a `Key`-t fogadja el, ami third-party rendelésnél a piactér azonosítója
            'Key' => $sor->getUnasApikey(),
        ];
        switch ($sor->getTipus()) {
            case Unasoutbox::TIPUSSTATUSZ:
                $unasStatusz = UnasTorzsadatService::reverseStatusz($fej->getBizonylatstatuszId());
                if ($unasStatusz === null) {
                    \mkw\store::writelog(
                        'A(z) ' . $fej->getId() . ' bizonylat "' . $fej->getBizonylatstatusznev()
                        . '" státuszához nincs UNAS leképezés, a visszaírás kimarad.',
                        'unas_api_error.txt'
                    );
                    return null;
                }
                $payload['Status'] = $unasStatusz;
                $payload['StatusEmail'] = \mkw\store::getParameter(\mkw\consts::UnasStatuszEmail) ? 'yes' : 'no';
                break;

            case Unasoutbox::TIPUSSZAMLA:
                $szamla = $this->findSzamla($fej);
                if (!$szamla) {
                    return null;
                }
                $payload['Invoice'] = [
                    // 2 = számlázva (getOrder InvoiceStatus)
                    'Status' => '2',
                    'Number' => $szamla->getId(),
                ];
                break;

            case Unasoutbox::TIPUSCSOMAG:
                $packageNumber = trim((string)$fej->getFuvarlevelszam());
                if ($packageNumber === '') {
                    return null;
                }
                $payload['Shipping'] = ['PackageNumber' => $packageNumber];
                $trackingUrl = trim((string)$fej->getTraceurl());
                if ($trackingUrl !== '') {
                    $payload['Shipping']['TrackingUrl'] = $trackingUrl;
                }
                break;

            default:
                return null;
        }
        return $payload;
    }

    /**
     * A bizonylatból (vagy a belőle képzett bizonylatokból) származó élő számla. A láncot bejárjuk:
     * a megrendelés → szállítólevél → számla útnál a számla nem a megrendelés közvetlen gyereke.
     *
     * @param Bizonylatfej $fej
     *
     * @return Bizonylatfej|null
     */
    private function findSzamla($fej)
    {
        $sor = [$fej];
        $latott = [$fej->getId() => true];
        while ($sor) {
            $aktualis = array_shift($sor);
            foreach ($aktualis->getSzulobizonylatfejek() as $gyerek) {
                if (isset($latott[$gyerek->getId()])) {
                    continue;
                }
                $latott[$gyerek->getId()] = true;
                if ($gyerek->getStorno() || $gyerek->getRontott() || $gyerek->getStornozott()) {
                    continue;
                }
                if (in_array($gyerek->getBizonylattipusId(), self::SZAMLATIPUSOK, true)) {
                    return $gyerek;
                }
                $sor[] = $gyerek;
            }
        }
        return null;
    }

    /**
     * Az UNAS rendelés bizonylata a `parbizonylatfej` láncon fölfelé. A számla nem feltétlenül a
     * megrendelés közvetlen gyereke (megrendelés → szállítólevél → számla).
     *
     * @param Bizonylatfej $fej
     *
     * @return Bizonylatfej|null
     */
    public static function findUnasFej($fej)
    {
        $latott = [];
        while ($fej) {
            if (trim((string)$fej->getUnaskey()) !== '') {
                return $fej;
            }
            // körvédelem: a lánc adathiba miatt önmagába is visszamutathat
            if (isset($latott[$fej->getId()])) {
                return null;
            }
            $latott[$fej->getId()] = true;
            $fej = $fej->getParbizonylatfej();
        }
        return null;
    }

    /** @param Unasoutbox $sor */
    private function siker($sor)
    {
        $sor->setAllapot(Unasoutbox::ALLAPOTKESZ);
        $sor->setFeldolgozva(new \DateTime());
        $sor->setUtolsohiba(null);
        \mkw\store::getEm()->persist($sor);
    }

    /** @param Unasoutbox $sor */
    private function hiba($sor, $message)
    {
        $sor->setProbalkozas($sor->getProbalkozas() + 1);
        $sor->setUtolsohiba($message);
        if ($sor->getProbalkozas() >= self::MAXPROBALKOZAS) {
            $sor->setAllapot(Unasoutbox::ALLAPOTHIBA);
            $sor->setFeldolgozva(new \DateTime());
            $this->unas->logApiError(
                sprintf(
                    t('A(z) %s UNAS rendelés %s visszaírása %s próbálkozás után sem sikerült: %s'),
                    $sor->getUnaskey(),
                    $sor->getTipus(),
                    self::MAXPROBALKOZAS,
                    $message
                ),
                'Key: ' . $sor->getUnaskey(),
                'unasorder'
            );
        }
        \mkw\store::getEm()->persist($sor);
    }

    /** Az admin „Újrafuttat" gombja: a hibás sor visszakerül a függők közé. */
    public function retry($id)
    {
        $em = \mkw\store::getEm();
        $sor = $em->getRepository(Unasoutbox::class)->find($id);
        if (!$sor) {
            return false;
        }
        $sor->setAllapot(Unasoutbox::ALLAPOTFUGGO);
        $sor->setProbalkozas(0);
        $sor->setUtolsohiba(null);
        $sor->setFeldolgozva(null);
        $em->persist($sor);
        $em->flush();
        return true;
    }

}
