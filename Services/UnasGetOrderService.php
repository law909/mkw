<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Partner;
use Entities\Termek;
use Entities\TermekValtozat;

/**
 * UNAS megrendelés → `webshopbiz` típusú `Bizonylatfej`.
 *
 * A letöltés SOHA nem változtatja meg a rendelés UNAS-beli állapotát: a `getOrder` hívásba nem
 * kerül `InvoiceAutoSet` (az „Számlázva"-ra tenné a lekért rendeléseket), és a lekérés után nem
 * megy `setOrder`. Ami visszaíródik, azt kizárólag a `Services\UnasSetOrderService` küldi,
 * a bizonylat életciklusából.
 *
 * Lásd docs/unas-megrendeles-integracio.md.
 */
class UnasGetOrderService
{

    public const BIZONYLATTIPUS = 'webshopbiz';

    /** az UNAS tételként küldi a szállítási költséget és a kedvezményt */
    private const SPECIALITEMS = ['shipping-cost', 'handel-cost', 'discount-amount', 'discount-percent'];

    /** rendelésenkénti névzár – ennyit várunk rá */
    private const LOCKWAIT = 5;

    /** a getOrder egy hívásban max. 500-at ad, de a feldolgozás a szűk keresztmetszet */
    private const POLLLIMIT = 100;

    /** ennyi lapot húzunk le egy futásban – a többrendeléses getOrder PREMIUM-on 30/óra */
    private const POLLMAXPAGES = 3;

    /** átfedés a kurzor előtt, hogy egy határra eső rendelés se csússzon ki */
    private const POLLOVERLAP = 300;

    /** biztonsági margó: a most születő rendelést a következő körben hozzuk */
    private const POLLMARGIN = 60;

    /** első futásnál ennyi napra nézünk vissza */
    private const FIRSTRUNDAYS = 7;

    /** a bruttó és a SumPriceGross ekkora eltérése még kerekítés */
    private const TOTALTOLERANCE = 1;

    /** a `Bizonylatfej.unaskey` oszlop hossza */
    private const KEYMAXLENGTH = 50;

    /** @var UnasService */
    private $unas;

    /** @var UnasTorzsadatService */
    private $torzsadat;

    public function __construct(?UnasService $unas = null, ?UnasTorzsadatService $torzsadat = null)
    {
        $this->unas = $unas ?: new UnasService();
        $this->torzsadat = $torzsadat ?: new UnasTorzsadatService();
    }

    // ------------------------------------------------------------------
    // Belépési pontok
    // ------------------------------------------------------------------

    /**
     * Egy rendelés lekérése és importja. Az egyrendeléses getOrder korlátja bőséges
     * (PREMIUM 1000/óra), ezért a kézi újraimport is ezen megy.
     *
     * @return array{statusz: string, unaskey: string, bizonylat: string, hiba: string}
     */
    public function importOrder($key)
    {
        $key = trim((string)$key);
        if ($key === '') {
            return $this->result('hiba', '', null, t('Nincs megadva UNAS rendelésazonosító.'));
        }
        // Key: a szűrés azonosítóra. InvoiceAutoSet-et SOHA nem küldünk – az a rendelés
        // UNAS-beli számlázási státuszát írná át.
        $xml = $this->unas->getApi()->getOrder(['Key' => $key]);
        if (!$xml) {
            return $this->result('hiba', $key, null, $this->unas->getApi()->getLasterrorsAsString());
        }
        $results = $this->importFromXml($xml);
        return $results ? $results[0] : $this->result('hiba', $key, null, t('Az UNAS nem adott vissza rendelést erre az azonosítóra.'));
    }

    /**
     * Hálózat nélkül is hívható: egy elmentett getOrder válasz feldolgozása.
     *
     * @return array[] rendelésenként egy {@see result()}
     * @throws \Exception ha a fájl nem olvasható vagy nem értelmezhető
     */
    public function importFromFile($path)
    {
        if (!is_readable($path)) {
            throw new \Exception(sprintf(t('A fájl nem olvasható: %s'), $path));
        }
        $prev = libxml_use_internal_errors(true);
        libxml_clear_errors();
        // LIBXML_NOENT szándékosan nincs: azzal külső XML-lel XXE-t kapnánk
        $xml = simplexml_load_string(file_get_contents($path), 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NONET);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        if ($xml === false) {
            throw new \Exception(
                sprintf(
                    t('A fájl nem értelmezhető XML (%s).'),
                    $errors ? trim($errors[0]->message) : t('ismeretlen hiba')
                )
            );
        }
        return $this->importFromXml($xml);
    }

    /**
     * @param \SimpleXMLElement $xml a getOrder válasza (`Orders/Order`)
     *
     * @return array[] rendelésenként egy {@see result()}
     */
    public function importFromXml(\SimpleXMLElement $xml)
    {
        $results = [];
        foreach ($this->orderNodes($xml) as $node) {
            $order = $this->parseOrder($node);
            if (($order['key'] ?? '') === '') {
                $results[] = $this->result('hiba', '', null, t('A rendelésben nincs azonosító (Key).'));
                continue;
            }
            // Az azonosítókat NEM csonkítjuk: az `unaskey` az idempotencia-kulcs, két különböző
            // rendelés csonkolva ütközhetne egymással. A külső azonosítóval a getOrder / setOrder
            // szólítja meg a rendelést, tehát az sem csonkítható.
            $tulHosszu = mb_strlen($this->unasAzonosito($order)) > self::KEYMAXLENGTH
                || mb_strlen($order['key']) > self::KEYMAXLENGTH;
            if ($tulHosszu) {
                $results[] = $this->result(
                    'hiba',
                    $order['key'],
                    null,
                    sprintf(
                        t('A rendelés azonosítója hosszabb %s karakternél, így nem tárolható.'),
                        self::KEYMAXLENGTH
                    )
                );
                continue;
            }
            try {
                $result = $this->processOrder($order);
            } catch (\Exception $e) {
                // A poller kurzora ettől nem áll meg, tehát ez a rendelés magától NEM jön újra:
                // a hiba elhárítása után a Key-jel kézzel kell újraimportálni.
                $this->unas->logApiError(
                    sprintf(
                        t(
                            'A(z) %s UNAS rendelés importja nem sikerült: %s Hárítsd el a hibát, majd az '
                            . 'UNAS megrendelések képernyőn ezzel az azonosítóval importáld újra.'
                        ),
                        $order['key'],
                        $e->getMessage()
                    ),
                    'Key: ' . $order['key'],
                    'unasorder'
                );
                $result = $this->result('hiba', $order['key'], null, $e->getMessage());
            }
            // a poller kurzora ebből lép, ha a lapkorlát miatt kellett megállnunk
            $result['datemod'] = $this->timestamp($order['datemod'] ?: $order['date']);
            $results[] = $result;
            if (!\mkw\store::getEm()->isOpen()) {
                // zárt EntityManager mellett a köteg többi eleme is elhasalna
                $results[] = $this->result('hiba', '', null, t('Az EntityManager bezárt, a köteg megszakadt.'));
                break;
            }
        }
        return $results;
    }

    /**
     * Inkrementális lehúzás a `TimeModStart` kurzorral. A kurzort csak infrastruktúra-hiba
     * (API hiba, zárt EntityManager) állítja meg – EGY rendelés bukása nem, különben egy hiányzó
     * törzsadat örökre megállítaná a lehúzást.
     *
     * @param bool $csakLetoltes csak lekérjük a rendeléseket és a nyers XML-t elmentjük a
     *                           `storage/logs` alá – NEM importálunk, és a kurzor sem lép,
     *                           tehát a következő igazi lehúzás ugyanezeket hozza majd
     *
     * @return array{feldolgozva: int, uj: int, letezo: int, hiba: int, lapok: int,
     *               kurzor: int, csakletoltes: bool, talalt: int, fajlok: array, eredmenyek: array[]}
     */
    public function pollOrders($csakLetoltes = false)
    {
        $cursor = (int)\mkw\store::getParameter(\mkw\consts::UnasImportCursor, 0);
        if ($cursor <= 0) {
            $cursor = time() - self::FIRSTRUNDAYS * 86400;
        }
        $until = time() - self::POLLMARGIN;
        $summary = [
            'feldolgozva' => 0,
            'uj' => 0,
            'letezo' => 0,
            'hiba' => 0,
            'lapok' => 0,
            'kurzor' => $cursor,
            'csakletoltes' => (bool)$csakLetoltes,
            'talalt' => 0,
            'fajlok' => [],
            'eredmenyek' => []
        ];
        if ($until <= $cursor - self::POLLOVERLAP) {
            return $summary;
        }

        $api = $this->unas->getApi();
        $vege = false;
        $megszakadt = false;
        $utolsoDateMod = 0;
        for ($page = 0; $page < self::POLLMAXPAGES; $page++) {
            $xml = $api->getOrder([
                'TimeModStart' => $cursor - self::POLLOVERLAP,
                'TimeModEnd' => $until,
                'Order' => 'order_time_asc',
                'LimitNum' => self::POLLLIMIT,
                'LimitStart' => $page * self::POLLLIMIT,
            ]);
            if (!$xml) {
                $summary['hiba']++;
                $summary['eredmenyek'][] = $this->result('hiba', '', null, $api->getLasterrorsAsString());
                return $summary;
            }
            $summary['lapok']++;

            if ($csakLetoltes) {
                // a nyers választ az UnasAPI::parseResponse() már kimentette a storage/logs alá
                $fajl = $api->getLastDumpFile();
                if ($fajl) {
                    $summary['fajlok'][] = $fajl;
                }
                $db = count($this->orderNodes($xml));
                $summary['talalt'] += $db;
                if ($db < self::POLLLIMIT) {
                    break;
                }
                continue;
            }

            $results = $this->importFromXml($xml);
            $vege = count($results) < self::POLLLIMIT;
            foreach ($results as $result) {
                $summary['eredmenyek'][] = $result;
                $summary['feldolgozva']++;
                if ($result['statusz'] === 'uj') {
                    $summary['uj']++;
                } elseif ($result['statusz'] === 'hiba') {
                    $summary['hiba']++;
                } else {
                    $summary['letezo']++;
                }
                if ($result['statusz'] !== 'hiba' && !empty($result['datemod'])) {
                    $utolsoDateMod = max($utolsoDateMod, (int)$result['datemod']);
                }
            }
            if (!\mkw\store::getEm()->isOpen()) {
                $megszakadt = true;
                break;
            }
            if ($vege) {
                break;
            }
        }

        // A kurzort infrastruktúra-hiba állítja meg (API hiba: fentebb visszatérünk; zárt
        // EntityManager: itt), EGY rendelés bukása NEM. Különben egy hiányzó törzsadat miatt
        // elakadó rendelés örökre megállítaná a lehúzást, és minden körben újranaplózná magát.
        // A bukott rendelés Apierrorlog sorban a `Key`-jével szerepel, onnan kézzel újraimportálható.
        if ($csakLetoltes) {
            // semmit nem importáltunk, tehát a kurzor NEM léphet: a következő igazi lehúzásnak
            // ugyanezt az ablakot kell újra végigmennie
            return $summary;
        }
        if (!$megszakadt) {
            // Ha a lapkorlát miatt álltunk meg, a kurzor csak a feldolgozott rendelésekig lép:
            // `$until`-ra ugorva a maradék némán kimaradna. Így a következő futás onnan folytatja.
            $summary['kurzor'] = $vege ? $until : ($utolsoDateMod ?: $cursor);
            \mkw\store::setParameter(\mkw\consts::UnasImportCursor, $summary['kurzor']);
        }
        return $summary;
    }

    // ------------------------------------------------------------------
    // Egy rendelés
    // ------------------------------------------------------------------

    /**
     * Rendelésenkénti névzár + idempotencia. A zár azért kell, mert a cron és egy kézi import
     * ugyanarra a rendelésre egyszerre is futhat; a `Bizonylatfej.unaskey` egyedi indexe a
     * második védvonal.
     *
     * @return array {@see result()}
     * @throws \Exception
     */
    private function processOrder(array $order)
    {
        $azonosito = $this->unasAzonosito($order);
        $conn = \mkw\store::getEm()->getConnection();
        $lock = 'unas_order_' . $azonosito;
        if ((int)$conn->fetchOne('SELECT GET_LOCK(?, ?)', [$lock, self::LOCKWAIT]) !== 1) {
            return $this->result('kihagyva', $this->unasAzonosito($order), null, t('A rendelés éppen feldolgozás alatt van.'));
        }
        try {
            $fej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)
                ->findOneBy(['unaskey' => $azonosito]);
            if ($fej) {
                return $this->refreshOrder($fej, $order);
            }
            return $this->createFromOrder($order);
        } finally {
            $conn->executeStatement('SELECT RELEASE_LOCK(?)', [$lock]);
        }
    }

    /**
     * Új bizonylat. A törzsadatok feloldása a tranzakció ELŐTT van: azok létrehozása flush-sal
     * jár, ami tétel-felvitel közben hibára futna.
     *
     * @return array {@see result()}
     * @throws \Exception
     */
    private function createFromOrder(array $order)
    {
        $em = \mkw\store::getEm();
        $this->torzsadat->clearWarnings();

        $biztipus = $em->getRepository(Bizonylattipus::class)->find(self::BIZONYLATTIPUS);
        if (!$biztipus) {
            throw new \Exception(sprintf(t('Nincs "%s" bizonylattípus.'), self::BIZONYLATTIPUS));
        }

        $partner = $this->torzsadat->resolvePartner($order);
        $valutanem = $this->torzsadat->resolveValutanem($order['currency']);
        $fizmod = $this->torzsadat->resolveFizmod($order['payment']);
        $szallitasimod = $this->torzsadat->resolveSzallitasimod($order['shipping']);
        $bizonylatstatusz = $this->torzsadat->resolveBizonylatstatusz($order);
        $raktar = $this->torzsadat->resolveRaktar();
        $terminal = $this->torzsadat->resolveCsomagterminal(
            $szallitasimod,
            $order['customer']['shipping']['deliverypointid'] ?? ''
        );
        $items = $this->resolveItems($order, $szallitasimod);

        $fej = null;
        $em->beginTransaction();
        try {
            $fej = new Bizonylatfej();
            // az importból érkező változás nem generálhat visszaírást az UNAS-ba
            $fej->unasSkipWriteback = true;
            $fej->setPersistentData();
            $fej->setBizonylattipus($biztipus);

            $kelt = $this->orderDate($order);
            $fej->setKelt($kelt);
            $fej->setTeljesites($kelt);

            // a setPartner() felülírja a partner* mezőket, az üzletkötőt, a fizmódot és a
            // valutanemet is – minden más ezután jön
            $fej->setPartner($partner);
            $this->fillCimek($fej, $order);

            $fej->setFizmod($fizmod);
            if ($szallitasimod) {
                $fej->setSzallitasimod($szallitasimod);
            }
            if ($raktar) {
                $fej->setRaktar($raktar);
            }
            $fej->setEsedekesseg(\mkw\store::calcEsedekesseg($fej->getKelt(), $fizmod, $partner));

            // MINDEN tétel előtt: a Bizonylattetel::setPersistentData() innen veszi az árfolyamot
            $fej->setValutanem($valutanem);
            $arfolyam = $em->getRepository(\Entities\Arfolyam::class)
                ->getActualArfolyam($valutanem, $fej->getTeljesites());
            $fej->setArfolyam($arfolyam->getArfolyam());
            $fej->setBankszamla($valutanem->getBankszamla());

            $fej->setBizonylatstatusz($bizonylatstatusz);
            $fej->setUnaskey($this->unasAzonosito($order));
            $fej->setUnaskulsokey($this->unasKulsoAzonosito($order));
            // a felhasználó az UNAS-beli azonosítót keresi, nem a piactér hash-ét
            $fej->setErbizonylatszam(mb_substr($this->unasAzonosito($order), 0, 30));
            // ugyanaz a mező, amit a webshop saját checkoutja is tölt
            $fej->setReferrer($order['referer']);
            $fej->setWebshopmessage($order['comments']['customer']);
            $fej->setCouriermessage($order['comments']['customer_shipping']);
            if ($order['lang'] !== '') {
                $fej->setBizonylatnyelv(\mkw\store::translateToLongLocaleName($order['lang']));
            }
            if ($terminal) {
                $fej->setCsomagterminal($terminal);
            }
            $this->fillCsomagadat($fej, $order);

            // az UNAS tételként küldi a költségeket, ezért a listener ne képezzen újat
            $fej->setKellszallitasikoltsegetszamolni(false);
            $fej->setSzallitasikoltsegbrutto(0);

            foreach ($items as $item) {
                $this->addTetel($fej, $item);
            }

            $fej->setBelsomegjegyzes($this->buildBelsomegjegyzes($order, $items));
            $fej->calcOsszesen();

            $em->persist($fej);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            // a fej és a tételei ilyenkor perzisztáltak, de mentetlenek: bent hagyva a következő
            // rendelés flush-e is rajtuk hibázna el
            $this->detach($fej);
            throw $e;
        }

        $this->logWarnings($order, $fej);
        $this->checkTotal($fej, $order);
        return $this->result('uj', $this->unasAzonosito($order), $fej, '');
    }

    /**
     * Már importált rendelés: CSAK a státusz és a csomagadatok frissülnek. A `simpleedit` itt
     * helyénvaló – nem akarjuk a költségsorok és a folyószámla újraépítését egy státuszváltásból.
     *
     * @param Bizonylatfej $fej
     *
     * @return array {@see result()}
     * @throws \Exception
     */
    private function refreshOrder($fej, array $order)
    {
        $em = \mkw\store::getEm();
        $this->torzsadat->clearWarnings();

        if ($this->rebuildEngedve($fej)) {
            return $this->rebuildOrder($fej, $order);
        }

        $statusz = $this->torzsadat->resolveBizonylatstatusz($order);
        $valtozott = false;

        if ($statusz && $fej->getBizonylatstatuszId() != $statusz->getId()) {
            $fej->setBizonylatstatusz($statusz);
            $valtozott = true;
        }
        if ($this->fillCsomagadat($fej, $order)) {
            $valtozott = true;
        }
        $kulso = $this->unasKulsoAzonosito($order);
        if ($kulso !== null && !$fej->getUnaskulsokey()) {
            $fej->setUnaskulsokey($kulso);
            $valtozott = true;
        }

        if (!$valtozott) {
            // az átfedéses kurzor miatt ugyanaz a rendelés többször is sorra kerül – változás
            // nélkül nem naplózunk, különben a hibapostafiók megtelne ismétlődő sorokkal
            return $this->result('letezo', $this->unasAzonosito($order), $fej, '');
        }
        // a nem perzisztált jelzőket csak a mentés előtt tesszük rá, hogy egy változás nélküli
        // körben ne maradjon `simpleedit` a memóriában lévő bizonylaton
        $fej->unasSkipWriteback = true;
        $fej->setSimpleedit(true);
        $fej->setKellszallitasikoltsegetszamolni(false);
        $em->persist($fej);
        $em->flush();
        $this->logWarnings($order, $fej);
        $this->checkTotal($fej, $order);
        return $this->result('frissitve', $this->unasAzonosito($order), $fej, '');
    }

    /**
     * A tételek felülírása csak akkor engedett, ha kifejezetten be van kapcsolva, a bizonylat
     * még a „függőben" státuszcsoportban van (különben a készletmozgás és a folyószámla már
     * lekönyvelt), és még nem készült róla számla.
     */
    private function rebuildEngedve($fej)
    {
        if (!\mkw\store::getParameter(\mkw\consts::UnasModositasEngedve)) {
            return false;
        }
        if (count($fej->getSzulobizonylatfejek())) {
            return false;
        }
        $fuggoben = \mkw\store::getEm()->getRepository(\Entities\Bizonylatstatusz::class)
            ->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszFuggoben));
        if (!$fuggoben) {
            return false;
        }
        // Ugyanaz a státusz mindig megfelel. A csoport-egyezés csak akkor számít, ha a csoport
        // ki van töltve: a `Bizonylatstatusz.csoport` nullable, üresen hagyva a "NULL == NULL"
        // minden státuszt átengedne, és egy lezárt bizonylat tételeit is felülírnánk.
        if ($fej->getBizonylatstatuszId() == $fuggoben->getId()) {
            return true;
        }
        $csoport = trim((string)$fuggoben->getCsoport());
        return $csoport !== '' && trim((string)$fej->getBizonylatstatuszcsoport()) === $csoport;
    }

    /**
     * @param Bizonylatfej $fej
     *
     * @return array {@see result()}
     * @throws \Exception
     */
    private function rebuildOrder($fej, array $order)
    {
        $em = \mkw\store::getEm();

        $szallitasimod = $this->torzsadat->resolveSzallitasimod($order['shipping']);
        $statusz = $this->torzsadat->resolveBizonylatstatusz($order);
        $items = $this->resolveItems($order, $szallitasimod);

        $em->beginTransaction();
        try {
            $fej->unasSkipWriteback = true;
            $fej->setKellszallitasikoltsegetszamolni(false);
            $fej->setSzallitasikoltsegbrutto(0);

            foreach ($fej->getBizonylattetelek()->toArray() as $tetel) {
                $fej->removeBizonylattetel($tetel);
                $em->remove($tetel);
            }
            foreach ($items as $item) {
                $this->addTetel($fej, $item);
            }
            if ($statusz) {
                $fej->setBizonylatstatusz($statusz);
            }
            if ($szallitasimod) {
                $fej->setSzallitasimod($szallitasimod);
            }
            $this->fillCsomagadat($fej, $order);
            $fej->setBelsomegjegyzes($this->buildBelsomegjegyzes($order, $items));
            $fej->calcOsszesen();

            $em->persist($fej);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }

        $this->logWarnings($order, $fej);
        $this->checkTotal($fej, $order);
        return $this->result('ujraepitve', $this->unasAzonosito($order), $fej, '');
    }

    // ------------------------------------------------------------------
    // Fej
    // ------------------------------------------------------------------

    /**
     * A rendeléskori cím a mérvadó, nem a törzsadat – ezért a setPartner() UTÁN felülírjuk
     * a fej számlázási és szállítási mezőit.
     *
     * @param Bizonylatfej $fej
     */
    private function fillCimek($fej, array $order)
    {
        $invoice = $order['customer']['invoice'] ?? [];
        $shipping = $order['customer']['shipping'] ?? [];
        $contact = $order['customer']['contact'] ?? [];

        $this->setIfNotEmpty($fej, 'Partnernev', $invoice['nev'] ?? '', 255);
        $this->setIfNotEmpty($fej, 'Partnerirszam', $invoice['zip'] ?? '', 10);
        $this->setIfNotEmpty($fej, 'Partnervaros', $invoice['city'] ?? '', 40);
        $this->setIfNotEmpty($fej, 'Partnerutca', $this->utca($invoice), 60);
        $this->setIfNotEmpty($fej, 'Partnerhazszam', $invoice['streetnumber'] ?? '', 40);
        $this->setIfNotEmpty($fej, 'Partneremail', $order['customer']['email'] ?? '', 100);
        $this->setIfNotEmpty($fej, 'Partnertelefon', $contact['phone'] ?? ($contact['mobile'] ?? ''), 40);

        $szallnev = trim((string)($shipping['recipientname'] ?? '')) ?: trim((string)($shipping['nev'] ?? ''));
        $this->setIfNotEmpty($fej, 'Szallnev', $szallnev, 255);
        $this->setIfNotEmpty($fej, 'Szallirszam', $shipping['zip'] ?? '', 10);
        $this->setIfNotEmpty($fej, 'Szallvaros', $shipping['city'] ?? '', 40);
        $this->setIfNotEmpty($fej, 'Szallutca', $this->utca($shipping), 60);
        $this->setIfNotEmpty($fej, 'Szallhazszam', $shipping['streetnumber'] ?? '', 40);

        // a szállítási ország az ÁFA felülbírálást vezérli, ezért csak feloldott országot írunk
        if (($shipping['countrycode'] ?? '') !== '' || ($shipping['country'] ?? '') !== '') {
            $fej->setPartnerszallorszag(
                $this->torzsadat->resolveOrszag($shipping['countrycode'] ?? '', $shipping['country'] ?? '')
            );
        }
        $orszag = $this->torzsadat->resolveOrszag($invoice['countrycode'] ?? '', $invoice['country'] ?? '');
        $fej->setPartnerorszag($orszag);

        // A számlatípus a RENDELÉS országából jön, nem a törzsből: meglévő vevőnél a törzsadat
        // a régi országot tükrözi, a Partner::calcAFAOverride() viszont ezt a hármast olvassa.
        $szamlatipus = $this->torzsadat->calcSzamlatipus($orszag);
        $fej->setPartnerSzamlatipus($szamlatipus);
        $adoszam = trim((string)($invoice['taxnumber'] ?? ''));
        $euadoszam = trim((string)($invoice['eutaxnumber'] ?? ''));
        if ($szamlatipus === 0) {
            $this->setIfNotEmpty($fej, 'Partneradoszam', $adoszam, 13);
        } elseif ($szamlatipus === 1) {
            $this->setIfNotEmpty($fej, 'Partnereuadoszam', $euadoszam ?: $adoszam, 30);
        } else {
            $this->setIfNotEmpty($fej, 'Partnerthirdadoszam', $euadoszam ?: $adoszam, 50);
        }
    }

    /**
     * Csomagszám és követési URL – csak ha nálunk MÉG ÜRES. Különben a saját fuvarozó-integrációnk
     * által rögzített számot írnánk felül azzal, amit épp mi töltöttünk fel az UNAS-ba.
     *
     * @param Bizonylatfej $fej
     *
     * @return bool változott-e
     */
    private function fillCsomagadat($fej, array $order)
    {
        $valtozott = false;
        $packageNumber = trim((string)($order['shipping']['packagenumber'] ?? ''));
        if ($packageNumber !== '' && !trim((string)$fej->getFuvarlevelszam())) {
            $fej->setFuvarlevelszam($packageNumber);
            $valtozott = true;
        }
        $trackingUrl = trim((string)($order['shipping']['trackingurl'] ?? ''));
        if ($trackingUrl !== '' && !trim((string)$fej->getTraceurl())) {
            $fej->setTraceurl(mb_substr($trackingUrl, 0, 255));
            $valtozott = true;
        }
        return $valtozott;
    }

    /**
     * Ami a bizonylat mezőibe nem fér bele, de a kereséshez és az utólagos ellenőrzéshez kell.
     * A `Coupon` is ide megy: a `kupon` mezőt nem állítjuk be, mert az a
     * `BizonylatfejListener::createVasarlasiUtalvany()`-t indítaná el.
     */
    private function buildBelsomegjegyzes(array $order, array $items)
    {
        $lines = ['UNAS azonosító: ' . $this->unasAzonosito($order)];
        $kulso = $this->unasKulsoAzonosito($order);
        if ($kulso !== null) {
            $lines[] = 'Külső (piactéri) azonosító: ' . $kulso;
        }
        if ($order['id'] !== '') {
            $lines[] = 'UNAS Id: ' . $order['id'];
        }
        if ($order['coupon'] !== '') {
            $lines[] = 'UNAS kupon: ' . $order['coupon'];
        }
        if (($order['comments']['admin'] ?? '') !== '') {
            $lines[] = 'UNAS admin megjegyzés: ' . $order['comments']['admin'];
        }
        if (($order['invoice']['number'] ?? '') !== '') {
            $lines[] = 'UNAS számla: ' . $order['invoice']['number'] . ' ' . ($order['invoice']['url'] ?? '');
        }
        if (($order['payment']['status'] ?? '') !== '') {
            $lines[] = 'UNAS fizetés: ' . $order['payment']['name'] . ' (' . $order['payment']['status'] . ')';
        }
        $ismeretlen = [];
        foreach ($items as $item) {
            if ($item['fallback']) {
                $ismeretlen[] = trim(($item['sku'] !== '' ? $item['sku'] : $item['id']) . ' ' . $item['name']);
            }
        }
        if ($ismeretlen) {
            $lines[] = t('Nem beazonosított cikkszámok:') . ' ' . implode('; ', $ismeretlen);
        }
        return implode("\n", $lines);
    }

    // ------------------------------------------------------------------
    // Tételek
    // ------------------------------------------------------------------

    /**
     * Minden tétel törzsadatának feloldása a bizonylat ELŐTT (ÁFA létrehozása flush-sal jár).
     * A visszaadott elemek már entitásokat hordoznak.
     *
     * @return array[]
     */
    private function resolveItems(array $order, $szallitasimod)
    {
        $items = [];
        foreach ($order['items'] as $item) {
            $resolved = $item;
            $resolved['afa'] = $this->torzsadat->resolveAfa($item['vat']);
            $resolved['fallback'] = false;
            $resolved['termek'] = null;
            $resolved['valtozat'] = null;
            $resolved['nevoverride'] = '';

            $special = $this->specialTermek($item['id'], $order, $szallitasimod);
            if ($special) {
                $resolved['termek'] = $special['termek'];
                $resolved['nevoverride'] = $special['nev'] ?: $item['name'];
                $resolved['negativ'] = $special['negativ'];
                if (!$resolved['termek']) {
                    // A költség- vagy kedvezménytermék nincs beállítva. A tétel az alapértelmezettre
                    // kerül, hogy az összeg ne tűnjön el a bizonylatról – de erről szólni kell.
                    $resolved['termek'] = $this->defaultTermek();
                    $resolved['fallback'] = true;
                    $this->torzsadat->warn(sprintf(
                        t('A(z) "%s" UNAS tételhez nincs beállítva termék (Beállítások → UNAS fül), '
                            . 'ezért az alapértelmezett termékre került.'),
                        $item['id']
                    ));
                }
                $items[] = $resolved;
                continue;
            }
            $resolved['negativ'] = false;

            $match = $this->matchTermek($item);
            $resolved['termek'] = $match['termek'];
            $resolved['valtozat'] = $match['valtozat'];
            if (!$resolved['termek']) {
                $resolved['termek'] = $this->defaultTermek();
                $resolved['fallback'] = true;
                $resolved['nevoverride'] = $item['name'];
            }
            $items[] = $resolved;
        }
        return $items;
    }

    /**
     * `shipping-cost`, `handel-cost`, `discount-amount`, `discount-percent`.
     *
     * A kezelési költség oda kerül, amit a `Szallitasimod::getTermek()` ad – a
     * `BizonylatfejListener::createKezelesiKoltseg()` NINCS a
     * `isKellszallitasikoltsegetszamolni()` mögé zárva, tehát más termékre tenni azt jelentené,
     * hogy két kezelési díj kerül a bizonylatra.
     *
     * @return array{termek: Termek|null, nev: string, negativ: bool}|null
     */
    private function specialTermek($itemId, array $order, $szallitasimod)
    {
        if (!in_array($itemId, self::SPECIALITEMS, true)) {
            return null;
        }
        $repo = \mkw\store::getEm()->getRepository(Termek::class);
        switch ($itemId) {
            case 'shipping-cost':
                // saját UNAS-os termék, ha be van állítva; egyébként a globális szállítási költség termék
                $termekid = \mkw\store::getParameter(\mkw\consts::UnasSzallitasiKtgTermek)
                    ?: \mkw\store::getParameter(\mkw\consts::SzallitasiKtgTermek);
                return [
                    'termek' => $repo->find($termekid),
                    'nev' => trim((string)($order['shipping']['name'] ?? '')),
                    'negativ' => false,
                ];
            case 'handel-cost':
                $termek = $szallitasimod ? $szallitasimod->getTermek() : null;
                if (!$termek) {
                    $termek = $repo->find(\mkw\store::getParameter(\mkw\consts::UnasKezelesiKtgTermek));
                }
                return ['termek' => $termek, 'nev' => '', 'negativ' => false];
            default:
                return [
                    'termek' => $repo->find(\mkw\store::getParameter(\mkw\consts::UnasKedvezmenyTermek)),
                    'nev' => '',
                    'negativ' => true,
                ];
        }
    }

    /**
     * 1. `Items.Item.Id` → `Termek.unasid`, majd `TermekValtozat.unasid` (ezeket az 1. fázisú
     *    termékimport töltötte fel); változatos terméknél a `Variant.Value`-k pipe-pal
     *    összefűzött kulcsa → `TermekValtozat.unasvaltozat`
     * 2. tartalék: `Sku` → változat vonalkód/cikkszám, majd termék vonalkód/cikkszám
     *
     * A 2. lépcső találatát VISSZAÍRJUK a törzsbe, így a következő rendelésnél már az 1. talál.
     * Az azonosító oda kerül, ahol a találat volt: ha az UNAS termék nálunk egy VÁLTOZAT, akkor a
     * változatra, egyébként a termékre. Termékre feloldott találatnál a változatra tenni súlyos
     * hiba volna: onnantól a `TermekValtozat.unasid` lépcső minden rendelésnél ugyanazt a
     * változatot adná vissza, a tényleges méret/szín helyett.
     *
     * @return array{termek: Termek|null, valtozat: TermekValtozat|null}
     */
    private function matchTermek(array $item)
    {
        $em = \mkw\store::getEm();
        $unasid = trim((string)$item['id']);
        $sku = trim((string)$item['sku']);
        $variantKey = $this->variantKey($item);

        if ($unasid !== '') {
            $termek = $em->getRepository(Termek::class)->findOneBy(['unasid' => $unasid]);
            if ($termek) {
                return [
                    'termek' => $termek,
                    'valtozat' => $this->matchValtozat($termek, $variantKey, $sku),
                ];
            }
            // az UNAS termék nálunk EGY változat
            $valtozat = $em->getRepository(TermekValtozat::class)->findOneBy(['unasid' => $unasid]);
            if ($valtozat && $valtozat->getTermek()) {
                return ['termek' => $valtozat->getTermek(), 'valtozat' => $valtozat];
            }
        }

        if ($sku === '') {
            return ['termek' => null, 'valtozat' => null];
        }
        $valtozatRepo = $em->getRepository(TermekValtozat::class);
        $valtozat = $valtozatRepo->findOneBy(['vonalkod' => $sku]) ?: $valtozatRepo->findOneBy(['cikkszam' => $sku]);
        if ($valtozat && $valtozat->getTermek()) {
            $this->writeBackUnasid($valtozat, $unasid);
            $this->writeBackVariantKey($valtozat, $variantKey);
            return ['termek' => $valtozat->getTermek(), 'valtozat' => $valtozat];
        }
        /** @var \Entities\TermekRepository $termekRepo */
        $termekRepo = $em->getRepository(Termek::class);
        $termek = $termekRepo->getBoltieladasTermekPontos($sku);
        if ($termek) {
            $this->writeBackUnasid($termek, $unasid);
            return ['termek' => $termek, 'valtozat' => $this->matchValtozat($termek, $variantKey, $sku)];
        }
        return ['termek' => null, 'valtozat' => null];
    }

    /**
     * Változat a termékén belül: előbb az UNAS változat-kulcsa, tartalékként a tétel `Sku`-ja.
     * A `Sku` sokszor a változat cikkszáma – ilyenkor a kulcsot vissza is írjuk, hogy legközelebb
     * az első lépcső találjon.
     *
     * @return TermekValtozat|null
     */
    private function matchValtozat(Termek $termek, $variantKey, $sku)
    {
        $repo = \mkw\store::getEm()->getRepository(TermekValtozat::class);
        if ($variantKey !== '') {
            $valtozat = $repo->findOneBy(['termek' => $termek, 'unasvaltozat' => $variantKey]);
            if ($valtozat) {
                return $valtozat;
            }
        }
        $sku = trim((string)$sku);
        if ($sku !== '') {
            $valtozat = $repo->findOneBy(['termek' => $termek, 'vonalkod' => $sku])
                ?: $repo->findOneBy(['termek' => $termek, 'cikkszam' => $sku]);
            if ($valtozat) {
                $this->writeBackVariantKey($valtozat, $variantKey);
                return $valtozat;
            }
        }
        if ($variantKey !== '') {
            \mkw\store::writelog(
                'UNAS rendelés: a(z) ' . $termek->getId() . ' termékhez nincs "' . $variantKey . '" változat',
                'unas_api_error.txt'
            );
        }
        return null;
    }

    /**
     * Az UNAS változat-kulcsa: az értékek az UNAS tulajdonság-sorrendjében, pipe-pal.
     * Ugyanaz az alak, amit a termékimport a `TermekValtozat.unasvaltozat`-ba írt.
     */
    private function variantKey(array $item)
    {
        $values = [];
        foreach ($item['variants'] as $variant) {
            $value = trim((string)$variant['value']);
            if ($value !== '') {
                $values[] = $value;
            }
        }
        return $values ? mb_substr(implode('|', $values), 0, 255) : '';
    }

    /** A törzs magától karbantartja magát: a cikkszámmal feloldott találat megkapja az UNAS azonosítót. */
    private function writeBackUnasid($entity, $unasid)
    {
        if (!$entity || $unasid === '' || trim((string)$entity->getUnasid()) !== '') {
            return;
        }
        $entity->setUnasid(mb_substr($unasid, 0, 50));
    }

    private function writeBackVariantKey(TermekValtozat $valtozat, $variantKey)
    {
        if ($variantKey === '' || trim((string)$valtozat->getUnasvaltozat()) !== '') {
            return;
        }
        $valtozat->setUnasvaltozat($variantKey);
    }

    /** @return Termek|null */
    private function defaultTermek()
    {
        $repo = \mkw\store::getEm()->getRepository(Termek::class);
        $id = \mkw\store::getParameter(\mkw\consts::UnasDefaultTermek)
            ?: \mkw\store::getParameter(\mkw\consts::DefaultTermek);
        return $id ? $repo->find($id) : null;
    }

    /**
     * @param Bizonylatfej $fej
     * @param array $item a {@see resolveItems()} egy eleme
     */
    private function addTetel($fej, array $item)
    {
        $tetel = new Bizonylattetel();
        $fej->addBizonylattetel($tetel);
        // irány / valutanem / árfolyam a fejből – ezért kell a helyes fej-állapot MÁR most
        $tetel->setPersistentData();
        $tetel->setTermek($item['termek']);
        if ($item['valtozat']) {
            $tetel->setTermekvaltozat($item['valtozat']);
        }
        $this->applyAfa($tetel, $fej, $item);

        $netto = (float)$item['pricenet'];
        $brutto = (float)$item['pricegross'];
        if ($item['negativ']) {
            $netto = -abs($netto);
            $brutto = -abs($brutto);
        }

        $tetel->setMennyiseg((float)$item['quantity']);
        $tetel->setNettoegysar($netto);
        // KÖTELEZŐEN a setNettoegysar UTÁN: az felülírja a bruttót az ÁFA kulcsból
        $tetel->setBruttoegysar($brutto);
        $tetel->setNettoegysarhuf($tetel->getNettoegysar() * $tetel->getArfolyam());
        $tetel->setBruttoegysarhuf($tetel->getBruttoegysar() * $tetel->getArfolyam());

        if ($item['nevoverride'] !== '') {
            // a setTermek() UTÁN, hogy a nyomtatott bizonylaton az UNAS-os név látszódjon
            $tetel->setTermeknev(mb_substr($item['nevoverride'], 0, 255));
        }
        // A `termeknev` NOT NULL, és üresen marad, ha se termék, se UNAS-os név nem volt
        // (hiányos beállítás). Enélkül a beszúrás hasalna el az egész rendeléssel.
        if (trim((string)$tetel->getTermeknev()) === '') {
            $tetel->setTermeknev(mb_substr($item['id'] !== '' ? $item['id'] : t('Ismeretlen tétel'), 0, 255));
        }
        if ($item['fallback']) {
            $tetel->setUnasnev(mb_substr($item['name'], 0, 255));
            $tetel->setUnascikkszam(mb_substr($item['sku'] !== '' ? $item['sku'] : $item['id'], 0, 100));
        }
        // az UNAS `Unit` szándékosan nem íródik: a `me` sztringet átírva a `mekod` (ME entitás)
        // a termékből maradna, és a NAV bevallás abból dolgozik

        $tetel->calc();
        \mkw\store::getEm()->persist($tetel);
    }

    /**
     * A `setTermek()` már beállította az ÁFÁ-t, EU-s / harmadik országos vevőnél a
     * `Partner::calcAFAOverride()` szerint. Felülbírálás nélkül az UNAS kulcsát kényszerítjük rá
     * (hogy az összegek egyezzenek); felülbírálással az MKW-s kulcs marad – az a NAV-konform –,
     * és mivel így a bruttó eltérhet, figyelmeztetést írunk.
     *
     * @param Bizonylattetel $tetel
     * @param Bizonylatfej $fej
     */
    private function applyAfa($tetel, $fej, array $item)
    {
        if (!$item['afa']) {
            return;
        }
        $override = Partner::calcAFAOverride(
            $fej->getPartnerszallorszag(),
            $fej->getPartnerorszag(),
            $fej->getPartnerSzamlatipus(),
            $fej->getPartnereuadoszam()
        );
        if (!$override) {
            $tetel->setAfa($item['afa']);
            return;
        }
        if ((float)$override->getErtek() !== (float)$item['afa']->getErtek()) {
            $this->torzsadat->warn(
                sprintf(
                    t(
                        'ÁFA felülbírálás: a(z) "%s" tétel %s%% helyett %s%%-ot kapott a vevő országa szerint, '
                        . 'ezért a bizonylat végösszege eltérhet a rendelésétől.'
                    ),
                    $item['name'],
                    $item['afa']->getErtek(),
                    $override->getErtek()
                )
            );
        }
    }

    // ------------------------------------------------------------------
    // Ellenőrzés, naplózás
    // ------------------------------------------------------------------

    /**
     * A bizonylat bruttója és az UNAS `SumPriceGross`-a. Eltérés esetén nem hibázunk – a
     * bizonylat elkészült –, de a felhasználónak szólunk, mert az összeg mindenkinek fontos.
     *
     * @param Bizonylatfej $fej
     */
    private function checkTotal($fej, array $order)
    {
        $unasTotal = (float)$order['sumpricegross'];
        if (!$unasTotal) {
            return;
        }
        $diff = abs((float)$fej->getBrutto() - $unasTotal);
        if ($diff <= self::TOTALTOLERANCE) {
            return;
        }
        $this->unas->logApiError(
            sprintf(
                t('A(z) %s bizonylat bruttója (%s) eltér az UNAS rendelés végösszegétől (%s). Ellenőrizd a tételeket.'),
                $fej->getId(),
                (float)$fej->getBrutto(),
                $unasTotal
            ),
            'Key: ' . $order['key'],
            'unasorder'
        );
    }

    /**
     * A törzsadat-feloldás figyelmeztetései + a fel nem oldott cikkszámok. Egy rendelés egy
     * Apierrorlog sor: a hibapostafiók így nem telik meg tételenkénti bejegyzésekkel.
     *
     * @param Bizonylatfej|null $fej
     */
    private function logWarnings(array $order, $fej)
    {
        $messages = $this->torzsadat->getWarnings();
        if (!$messages) {
            return;
        }
        $this->unas->logApiError(
            ($fej ? $fej->getId() . ': ' : '') . implode(' ', $messages),
            'Key: ' . $order['key'],
            'unasorder'
        );
    }

    // ------------------------------------------------------------------
    // XML → tömb
    // ------------------------------------------------------------------

    /** @return \SimpleXMLElement[] */
    private function orderNodes(\SimpleXMLElement $xml)
    {
        if ($xml->getName() === 'Order') {
            return [$xml];
        }
        if (isset($xml->Order)) {
            return iterator_to_array($xml->Order, false);
        }
        return [];
    }

    /**
     * A rendelés nyers XML-je → normalizált tömb. Azért tömb, hogy a leképezés SimpleXML-mentes és
     * fájlból is tesztelhető legyen.
     *
     * **Ez az EGYETLEN hely, ami az UNAS XML mezőneveit ismeri.** A SimpleXML kis/nagybetű-érzékeny:
     * egy elrontott név némán üres sztringet ad, nem hibát – ezért az itteni nevek pontosan az
     * UNAS adatszerkezet-dokumentációját követik (`StatusID`, `EUTaxNumber`, `DeliveryPointID`, …).
     * A kimenő tömb kulcsai viszont kisbetűsek, és a feldolgozó rétegek már csak azokat használják.
     */
    private function parseOrder(\SimpleXMLElement $node)
    {
        $customer = $node->Customer ?? null;
        $addresses = $customer && isset($customer->Addresses) ? $customer->Addresses : null;

        return [
            'key' => $this->text($node, 'Key'),
            'internalkey' => $this->text($node, 'InternalKey'),
            'id' => $this->text($node, 'Id'),
            'date' => $this->text($node, 'Date'),
            'datemod' => $this->text($node, 'DateMod'),
            'lang' => $this->text($node, 'Lang'),
            'currency' => $this->text($node, 'Currency'),
            'coupon' => $this->text($node, 'Coupon'),
            'weight' => $this->text($node, 'Weight'),
            'referer' => $this->text($node, 'Referer'),
            'sumpricegross' => $this->text($node, 'SumPriceGross'),
            'comments' => $this->parseComments($node->Comments ?? null),
            'status' => [
                'id' => $this->text($node, 'StatusID'),
                // a doksiban `Status` a státusz neve, `StatusID` az azonosítója – `StatusName` nincs
                'name' => $this->text($node, 'Status'),
                'type' => $this->text($node, 'StatusType'),
            ],
            'payment' => [
                'id' => $node->Payment ? $this->text($node->Payment, 'Id') : '',
                'name' => $node->Payment ? $this->text($node->Payment, 'Name') : '',
                'type' => $node->Payment ? $this->text($node->Payment, 'Type') : '',
                'status' => $node->Payment ? $this->text($node->Payment, 'Status') : '',
                'paid' => $node->Payment ? $this->text($node->Payment, 'Paid') : '',
            ],
            'shipping' => [
                'id' => $node->Shipping ? $this->text($node->Shipping, 'Id') : '',
                'name' => $node->Shipping ? $this->text($node->Shipping, 'Name') : '',
                'packagenumber' => $node->Shipping ? $this->text($node->Shipping, 'PackageNumber') : '',
                'trackingurl' => $node->Shipping ? $this->text($node->Shipping, 'TrackingUrl') : '',
                'foreignid' => $node->Shipping ? $this->text($node->Shipping, 'ForeignID') : '',
            ],
            'invoice' => [
                'status' => $node->Invoice ? $this->text($node->Invoice, 'Status') : '',
                'number' => $node->Invoice ? $this->text($node->Invoice, 'Number') : '',
                'url' => $node->Invoice ? $this->text($node->Invoice, 'Url') : '',
            ],
            'customer' => [
                'id' => $customer ? $this->text($customer, 'Id') : '',
                'email' => $customer ? $this->text($customer, 'Email') : '',
                'username' => $customer ? $this->text($customer, 'Username') : '',
                'contact' => [
                    'name' => $customer && $customer->Contact ? $this->text($customer->Contact, 'Name') : '',
                    'phone' => $customer && $customer->Contact ? $this->text($customer->Contact, 'Phone') : '',
                    'mobile' => $customer && $customer->Contact ? $this->text($customer->Contact, 'Mobile') : '',
                ],
                'invoice' => $this->parseAddress($addresses ? ($addresses->Invoice ?? null) : null),
                'shipping' => $this->parseAddress($addresses ? ($addresses->Shipping ?? null) : null),
            ],
            'items' => $this->parseItems($node->Items ?? null),
        ];
    }

    /**
     * `Comments/Comment[]`, típus szerint szétosztva – NEM `Comment/Text`, ahogy a terv írta.
     * A `customer` a vásárló üzenete a boltnak, a `customer_shipping` a futárnak szól, az `admin`
     * pedig a bolt belső jegyzete (a vásárló nem látja).
     *
     * @return array{customer: string, customer_shipping: string, admin: string}
     */
    private function parseComments($node)
    {
        $result = ['customer' => '', 'customer_shipping' => '', 'admin' => ''];
        if (!$node || !isset($node->Comment)) {
            return $result;
        }
        foreach ($node->Comment as $comment) {
            $tipus = trim((string)($comment->Type ?? ''));
            $szoveg = trim((string)($comment->Text ?? ''));
            if ($szoveg === '' || !array_key_exists($tipus, $result)) {
                continue;
            }
            // ugyanabból a típusból több is jöhet
            $result[$tipus] = trim($result[$tipus] . "\n" . $szoveg);
        }
        return $result;
    }

    private function parseAddress($node)
    {
        if (!$node) {
            return [];
        }
        return [
            'nev' => $this->text($node, 'Name'),
            'recipientname' => $this->text($node, 'RecipientName'),
            'zip' => $this->text($node, 'ZIP'),
            'city' => $this->text($node, 'City'),
            'street' => $this->text($node, 'Street'),
            'streetname' => $this->text($node, 'StreetName'),
            'streettype' => $this->text($node, 'StreetType'),
            'streetnumber' => $this->text($node, 'StreetNumber'),
            'county' => $this->text($node, 'County'),
            'country' => $this->text($node, 'Country'),
            'countrycode' => $this->text($node, 'CountryCode'),
            'taxnumber' => $this->text($node, 'TaxNumber'),
            'eutaxnumber' => $this->text($node, 'EUTaxNumber'),
            'customertype' => $this->text($node, 'CustomerType'),
            'deliverypointid' => $this->text($node, 'DeliveryPointID'),
            'deliverypointgroup' => $this->text($node, 'DeliveryPointGroup'),
        ];
    }

    private function parseItems($node)
    {
        $items = [];
        if (!$node || !isset($node->Item)) {
            return $items;
        }
        foreach ($node->Item as $item) {
            $variants = [];
            if (isset($item->Variants->Variant)) {
                foreach ($item->Variants->Variant as $variant) {
                    $variants[] = [
                        'id' => $this->text($variant, 'Id'),
                        'name' => $this->text($variant, 'Name'),
                        'value' => $this->text($variant, 'Value'),
                    ];
                }
            }
            $items[] = [
                'id' => $this->text($item, 'Id'),
                'sku' => $this->text($item, 'Sku'),
                'name' => $this->text($item, 'Name'),
                'unit' => $this->text($item, 'Unit'),
                'quantity' => $this->text($item, 'Quantity') !== '' ? $this->text($item, 'Quantity') : '1',
                'pricenet' => $this->text($item, 'PriceNet'),
                'pricegross' => $this->text($item, 'PriceGross'),
                'vat' => $this->text($item, 'Vat'),
                'status' => $this->text($item, 'Status'),
                'variants' => $variants,
            ];
        }
        return $items;
    }

    private function text($node, $field)
    {
        if (!$node || !isset($node->{$field})) {
            return '';
        }
        return trim((string)$node->{$field});
    }

    /**
     * A rendelés UNAS-beli azonosítója. Third-party rendelésnél (Árukereső, eMAG, Unas API) a
     * `Key` a PIACTÉR azonosítója és az `InternalKey` az UNAS-beli – normál rendelésnél viszont
     * a `Key` maga az UNAS azonosító. Így az `unaskey` mindig ugyanazt jelenti.
     */
    private function unasAzonosito(array $order)
    {
        return trim((string)$order['internalkey']) !== '' ? $order['internalkey'] : $order['key'];
    }

    /**
     * A piactér azonosítója, ha van – vagyis a `Key`, amikor az nem az UNAS-beli kulcs. A getOrder
     * és a setOrder KIZÁRÓLAG a `Key`-t fogadja el szűrőként, ezért ezt is el kell tárolni.
     *
     * @return string|null null, ha a `Key` maga az UNAS azonosító
     */
    private function unasKulsoAzonosito(array $order)
    {
        $kulso = trim((string)$order['key']);
        return ($kulso !== '' && $kulso !== $this->unasAzonosito($order)) ? $kulso : null;
    }

    private function orderDate(array $order)
    {
        $ts = $this->timestamp($order['date']);
        return $ts ? (new \DateTime())->setTimestamp($ts) : new \DateTime();
    }

    /** Az UNAS unix időbélyeget és `Y.m.d H:i:s` alakot is ad. */
    private function timestamp($raw)
    {
        $raw = trim((string)$raw);
        if ($raw === '') {
            return 0;
        }
        if (ctype_digit($raw)) {
            return (int)$raw;
        }
        return (int)strtotime(str_replace('.', '-', $raw));
    }

    private function utca(array $address)
    {
        $streetName = trim((string)($address['streetname'] ?? ''));
        $streetType = trim((string)($address['streettype'] ?? ''));
        if ($streetName !== '') {
            return trim($streetName . ' ' . $streetType);
        }
        return trim((string)($address['street'] ?? ''));
    }

    private function setIfNotEmpty($entity, $field, $value, $maxLength)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return;
        }
        $entity->{'set' . $field}(mb_substr($value, 0, $maxLength));
    }

    private function detach($fej)
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
     * @param string $statusz uj | frissitve | ujraepitve | letezo | kihagyva | hiba
     * @param Bizonylatfej|null $fej
     */
    private function result($statusz, $unaskey, $fej, $hiba)
    {
        return [
            'statusz' => $statusz,
            'unaskey' => (string)$unaskey,
            'bizonylat' => $fej ? $fej->getId() : '',
            'hiba' => (string)$hiba,
        ];
    }

}
