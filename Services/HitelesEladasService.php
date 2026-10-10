<?php

namespace Services;

use Entities\Bankbizonylatfej;
use Entities\Bankbizonylattetel;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Jogcim;
use Entities\Penztarbizonylatfej;

/**
 * Hire-purchase sale: the customer paid an advance invoice, then the finance company (e.g. Merkantil)
 * got the invoice for the full amount. The customer's money settles part of that invoice, so the
 * advance is reversed, its payments are voided, and the paid amount is booked as a bank document
 * against the finance company's invoice.
 *
 * Unlike the normal storno, the payment is not mirrored back (PenzmozgasService::createStornoPenzmozgas):
 * no money goes back to the customer, it is moved onto the other invoice.
 */
class HitelesEladasService
{

    public const STORNOTIPUSOK = [
        1 => 'Számlával egy tekintet alá eső okirat',
        2 => 'Érvénytelenítő számla',
    ];

    /** Negated on the storno lines, the same set the storno editor negates (bizonylattetelController). */
    private const NEGALTMEZOK = [
        'Mennyiseg', 'Netto', 'Afaertek', 'Brutto', 'Nettohuf', 'Afaertekhuf', 'Bruttohuf',
        'Gyujtomennyiseg', 'Sordobozmennyiseg',
    ];

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    public function __construct()
    {
        $this->em = \mkw\store::getEm();
    }

    /** The cheap part of the checks, for showing the button on the list. */
    public static function canStart(Bizonylatfej $eloleg): bool
    {
        return $eloleg->getBizonylattipus()?->getShowhiteleseladasbutton()
            && !$eloleg->getRontott()
            && !$eloleg->getStorno()
            && !$eloleg->getStornozott()
            // the same condition as the storno buttons: NAV must have accepted the original
            && (!$eloleg->isNavbekuldendo() || in_array($eloleg->getNaveredmeny(), ['DONE', 'TESZT'], true));
    }

    /**
     * @return array{storno: Bizonylatfej, bank: Bankbizonylatfej, rontott: string[], osszeg: float}
     *
     * @throws \mkwhelpers\Exceptions\UserMessageException
     */
    public function run(Bizonylatfej $eloleg, ?Bizonylatfej $szamla, int $stornotipus): array
    {
        if (!array_key_exists($stornotipus, self::STORNOTIPUSOK)) {
            $this->fail(t('Ismeretlen stornó típus.'));
        }
        if (!self::canStart($eloleg)) {
            $this->fail(t('Ezen a bizonylaton hiteles eladás nem indítható.'));
        }
        if (ElolegService::isOffset($eloleg)) {
            $this->fail(t('Az előleget már beszámították egy számlán, előbb azt kell rendezni.'));
        }
        $this->checkSzamla($eloleg, $szamla);

        $tetelek = $this->getPenzmozgasTetelek($eloleg);
        if (!$tetelek) {
            $this->fail(t('Az előlegszámlához nem tartozik élő bank- vagy pénztárbizonylat.'));
        }
        if (abs($eloleg->getEgyenleg() * 1) >= 0.005) {
            $this->fail(t('Az előlegszámla nincs teljesen kiegyenlítve.'));
        }
        $osszeg = 0;
        foreach ($tetelek as $tetel) {
            $fej = $tetel->getBizonylatfej();
            if ($fej instanceof Penztarbizonylatfej && $this->penztarZarolt($fej)) {
                $this->fail(t('A pénztárbizonylat a pénztár lezárt időszakába esik') . ': ' . $fej->getId());
            }
            $irany = $tetel instanceof Bankbizonylattetel ? $tetel->getIrany() : $tetel->getBizonylatfej()->getIrany();
            $osszeg += $tetel->getBrutto() * $irany;
        }
        $osszeg = round(abs($osszeg), 4);
        $nyitott = $szamla->getEgyenleg() * -1 * $szamla->getIrany();
        if ($osszeg - $nyitott >= 0.005) {
            $this->fail(sprintf(t('A számla nyitott összege (%s) kisebb az előleg befizetésénél (%s).'), $nyitott * 1, $osszeg));
        }
        $bankszamla = $this->getBankszamla($tetelek, $szamla);
        $jogcim = $this->getJogcim($tetelek);

        $conn = $this->em->getConnection();
        $conn->beginTransaction();
        try {
            $rontott = $this->rontPenzmozgas($tetelek);
            $this->em->flush();

            $storno = $this->createStorno($eloleg, $stornotipus);
            $this->em->flush();

            $eloleg->setKellszallitasikoltsegetszamolni(false);
            $eloleg->setStornozott(true);
            $this->em->persist($eloleg);
            $this->em->flush();

            $bank = $this->createBankbizonylat($eloleg, $szamla, $osszeg, $bankszamla, $jogcim);
            $this->em->flush();
            $conn->commit();
        } catch (\Throwable $e) {
            $conn->rollBack();
            \mkw\store::writelog($eloleg->getId() . ': ' . $e->getMessage(), 'hiteleseladas.log');
            throw $e;
        }
        return ['storno' => $storno, 'bank' => $bank, 'rontott' => $rontott, 'osszeg' => $osszeg];
    }

    private function checkSzamla(Bizonylatfej $eloleg, ?Bizonylatfej $szamla): void
    {
        if (!$szamla) {
            $this->fail(t('Nincs ilyen számla.'));
        }
        if (!in_array($szamla->getBizonylattipusId(), Bizonylattipus::SZAMLATIPUSOK, true)) {
            $this->fail(t('A megadott bizonylat nem számla') . ': ' . $szamla->getId());
        }
        if ($szamla->getRontott() || $szamla->getStorno() || $szamla->getStornozott()) {
            $this->fail(t('A számla rontott vagy stornózott') . ': ' . $szamla->getId());
        }
        if (!$szamla->getPenztmozgat()) {
            $this->fail(t('A számla nem képez kintlévőséget') . ': ' . $szamla->getId());
        }
        if ($szamla->getValutanemId() != $eloleg->getValutanemId()) {
            $this->fail(t('A számla valutaneme eltér az előlegszámláétól.'));
        }
    }

    /**
     * The live bank and cash lines referring to the advance. Only these: a bank document may settle
     * several invoices.
     *
     * @return array<Bankbizonylattetel|\Entities\Penztarbizonylattetel>
     */
    private function getPenzmozgasTetelek(Bizonylatfej $eloleg): array
    {
        $ret = [];
        foreach ((new PenzmozgasService())->getEloPenzmozgas($eloleg) as $fej) {
            foreach ($fej->getBizonylattetelek() as $tetel) {
                if (!$tetel->getRontott() && (string)$tetel->getHivatkozottbizonylat() === (string)$eloleg->getId()) {
                    $ret[] = $tetel;
                }
            }
        }
        return $ret;
    }

    /**
     * Same rule as the voiding on the document (BizonylatfejListener::rontPenzmozgasTetelek()): the
     * own lines, and the header when no live line is left on it.
     *
     * @return string[] the touched bank and cash document ids
     */
    private function rontPenzmozgas(array $tetelek): array
    {
        $fejek = [];
        foreach ($tetelek as $tetel) {
            $fej = $tetel->getBizonylatfej();
            $tetel->setRontott(true);
            $this->em->persist($tetel);
            $fejek[$fej->getId()] = $fej;
        }
        foreach ($fejek as $fej) {
            $maradelo = false;
            foreach ($fej->getBizonylattetelek() as $tetel) {
                $maradelo = $maradelo || !$tetel->getRontott();
            }
            if (!$maradelo) {
                $fej->setRontott(true);
            }
            $this->em->persist($fej);
        }
        return array_keys($fejek);
    }

    private function penztarZarolt(Penztarbizonylatfej $fej): bool
    {
        $penztar = $fej->getPenztar();
        $zart = $penztar ? \mkw\store::getParameter(\mkw\consts::PenztarZarva . $penztar->getId()) : null;
        return $zart && $fej->getKelt() && $fej->getKelt()->format(\mkw\store::$SQLDateFormat) <= $zart;
    }

    /** What the storno editor would save for the advance, without the editor. */
    private function createStorno(Bizonylatfej $eloleg, int $stornotipus): Bizonylatfej
    {
        $storno = new Bizonylatfej();
        $storno->duplicateFrom($eloleg);
        $storno->clearId();
        $storno->clearCreated();
        $storno->clearLastmod();
        $storno->setParbizonylatfej($eloleg);
        // derived name/email fields have no setter, re-setting the relation fills them
        $belsouzletkoto = $eloleg->getBelsouzletkoto();
        if ($belsouzletkoto) {
            $storno->removeBelsouzletkoto();
            $storno->setBelsouzletkoto($belsouzletkoto);
        }
        $felhasznalo = $eloleg->getFelhasznalo();
        if ($felhasznalo) {
            $storno->removeFelhasznalo();
            $storno->setFelhasznalo($felhasznalo);
        }
        $storno->setPersistentData();
        $storno->setKelt();
        $storno->setStorno(true);
        $storno->setStornotipus($stornotipus);
        $storno->setBizonylatnev(self::STORNOTIPUSOK[$stornotipus]);
        $storno->setSysmegjegyzes($eloleg->getId() . ' stornó bizonylata.');
        $storno->setMegjegyzes($eloleg->getId() . ' stornó bizonylata (hiteles eladás)');
        $storno->setNaveredmeny(null);
        $storno->setNyomtatva(false);
        $storno->setKellszallitasikoltsegetszamolni(false);
        // the payment is moved to the other invoice, nothing is paid back
        $storno->setNincsautopenztarbizonylat(true);

        foreach ($eloleg->getBizonylattetelek() as $regi) {
            $tetel = new Bizonylattetel();
            $tetel->duplicateFrom($regi);
            $tetel->clearCreated();
            $tetel->clearLastmod();
            $storno->addBizonylattetel($tetel);
            $tetel->setParbizonylattetel($regi);
            $tetel->setStorno(true);
            $tetel->setStornoMozgat(false);
            foreach (self::NEGALTMEZOK as $mezo) {
                $tetel->{'set' . $mezo}($tetel->{'get' . $mezo}() * -1);
            }
            $this->em->persist($tetel);
        }
        $storno->calcOsszesen();
        $this->em->persist($storno);
        return $storno;
    }

    private function createBankbizonylat(Bizonylatfej $eloleg, Bizonylatfej $szamla, float $osszeg, $bankszamla, Jogcim $jogcim): Bankbizonylatfej
    {
        $ma = date(\mkw\store::$DateFormat);
        $bank = new Bankbizonylatfej();
        $bank->setBizonylattipus($this->em->getRepository(Bizonylattipus::class)->find('bank'));
        $bank->setBankszamla($bankszamla);
        $bank->setKelt($ma);
        // setPartner() puts the partner's currency on the header, the invoice's goes back after it
        $bank->setPartner($szamla->getPartner());
        $bank->setValutanem($szamla->getValutanem());
        $bank->setMegjegyzes(sprintf('%s előleg befizetése a(z) %s számlára (hiteles eladás).', $eloleg->getId(), $szamla->getId()));

        $tetel = new Bankbizonylattetel();
        $tetel->setBizonylatfej($bank);
        $tetel->setIrany($szamla->getIrany() * -1);
        $tetel->setPartner($szamla->getPartner());
        $tetel->setDatum($ma);
        $tetel->setJogcim($jogcim);
        $tetel->setValutanem($szamla->getValutanem());
        $tetel->setHivatkozottbizonylat($szamla->getId());
        $tetel->setHivatkozottdatum($szamla->getEsedekessegStr() ?: $szamla->getKeltStr());
        $tetel->setBrutto($osszeg);
        $this->em->persist($tetel);
        $this->em->persist($bank);
        return $bank;
    }

    /** The original bank payment's account, else the invoice's, else the currency's. */
    private function getBankszamla(array $tetelek, Bizonylatfej $szamla)
    {
        foreach ($tetelek as $tetel) {
            if ($tetel instanceof Bankbizonylattetel && $tetel->getBizonylatfej()->getBankszamla()) {
                return $tetel->getBizonylatfej()->getBankszamla();
            }
        }
        $bankszamla = $szamla->getBankszamla() ?: $szamla->getValutanem()?->getBankszamla();
        if (!$bankszamla) {
            $this->fail(t('Nem állapítható meg, melyik bankszámlára kerüljön a bankbizonylat.'));
        }
        return $bankszamla;
    }

    /** The automatic bank document's title, else the original payment's. */
    private function getJogcim(array $tetelek): Jogcim
    {
        $jogcim = $this->em->getRepository(Jogcim::class)->find((int)\mkw\store::getParameter(\mkw\consts::AutoBankbizonylatJogcim));
        if ($jogcim) {
            return $jogcim;
        }
        foreach ($tetelek as $tetel) {
            if ($tetel->getJogcim()) {
                return $tetel->getJogcim();
            }
        }
        $this->fail(t('Nincs beállítva automatikus bankbizonylat jogcím.'));
    }

    private function fail(string $uzenet): never
    {
        throw new \mkwhelpers\Exceptions\UserMessageException($uzenet);
    }
}
