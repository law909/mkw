<?php

namespace Services;

use Entities\Bankbizonylatfej;
use Entities\Bankbizonylattetel;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Penztarbizonylatfej;
use Entities\Penztarbizonylattetel;

/**
 * Hire-purchase sale: the customer paid an advance invoice, then the finance company (e.g. Merkantil)
 * got the invoice for the full amount. The customer's money settles part of that invoice, so the
 * advance is reversed, its payments are voided, and each of them is reproduced against the finance
 * company's invoice - a bank payment as a bank document, a cash payment as a cash document.
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

    /** Whether the list shows the button; why it cannot run is told on clicking it (checkEloleg()). */
    public static function canStart(Bizonylatfej $eloleg): bool
    {
        return $eloleg->getBizonylattipus()?->getShowhiteleseladasbutton() && !$eloleg->getRontott();
    }

    /**
     * Everything that depends on the advance only: checked before the dialog opens, and again on running.
     *
     * @return string[] the reasons it cannot run, empty when it can
     */
    public function checkEloleg(Bizonylatfej $eloleg): array
    {
        $tipus = $eloleg->getBizonylattipus();
        if (!self::canStart($eloleg)) {
            return [t('Ezen a bizonylaton hiteles eladás nem indítható.')];
        }
        $hibak = [];
        if ($eloleg->getStorno()) {
            $hibak[] = t('Az előlegszámla stornó bizonylat.');
        }
        if ($eloleg->getStornozott()) {
            $hibak[] = t('Az előlegszámla már stornózva van.');
        }
        if ($tipus->getNyomtatni() && !$eloleg->getNyomtatva()) {
            $hibak[] = t('Az előlegszámla nincs kinyomtatva.');
        }
        // the same condition as the storno buttons: NAV must have accepted the original
        if ($tipus->getNavbekuldendo() && !in_array($eloleg->getNaveredmeny(), ['DONE', 'TESZT'], true)) {
            $hibak[] = t('Az előlegszámla nincs beküldve a NAV-hoz, vagy a NAV még nem fogadta be.');
        }
        if ($hibak) {
            return $hibak;
        }
        if (ElolegService::isOffset($eloleg)) {
            $hibak[] = t('Az előleget már beszámították egy számlán, előbb azt kell rendezni.');
        }
        $tetelek = $this->getPenzmozgasTetelek($eloleg);
        if (!$tetelek) {
            $hibak[] = t('Az előlegszámlához nem tartozik élő bank- vagy pénztárbizonylat.');
        } elseif (abs($eloleg->getEgyenleg() * 1) >= 0.005) {
            $hibak[] = t('Az előlegszámla nincs teljesen kiegyenlítve.');
        }
        foreach ($tetelek as $tetel) {
            $fej = $tetel->getBizonylatfej();
            if ($fej instanceof Penztarbizonylatfej && $this->penztarZarolt($fej)) {
                $hibak[] = t('A pénztárbizonylat a pénztár lezárt időszakába esik') . ': ' . $fej->getId();
            }
        }
        return array_values(array_unique($hibak));
    }

    /**
     * @return array{storno: Bizonylatfej, uj: array<Bankbizonylatfej|Penztarbizonylatfej>, rontott: string[], osszeg: float}
     *
     * @throws \mkwhelpers\Exceptions\UserMessageException
     */
    public function run(Bizonylatfej $eloleg, ?Bizonylatfej $szamla, int $stornotipus): array
    {
        if (!array_key_exists($stornotipus, self::STORNOTIPUSOK)) {
            $this->fail(t('Ismeretlen stornó típus.'));
        }
        $hibak = $this->checkEloleg($eloleg);
        if ($hibak) {
            $this->fail(implode(' ', $hibak));
        }
        $this->checkSzamla($eloleg, $szamla);

        $tetelek = $this->getPenzmozgasTetelek($eloleg);
        $osszeg = 0;
        foreach ($tetelek as $tetel) {
            $irany = $tetel instanceof Bankbizonylattetel ? $tetel->getIrany() : $tetel->getBizonylatfej()->getIrany();
            $osszeg += $tetel->getBrutto() * $irany;
        }
        $osszeg = round(abs($osszeg), 4);
        $nyitott = $szamla->getEgyenleg() * -1 * $szamla->getIrany();
        if ($osszeg - $nyitott >= 0.005) {
            $this->fail(sprintf(t('A számla nyitott összege (%s) kisebb az előleg befizetésénél (%s).'), $nyitott * 1, $osszeg));
        }

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

            $uj = $this->reproducePenzmozgas($eloleg, $szamla, $tetelek);
            $this->em->flush();
            $conn->commit();
        } catch (\Throwable $e) {
            $conn->rollBack();
            \mkw\store::writelog($eloleg->getId() . ': ' . $e->getMessage(), 'hiteleseladas.log');
            throw $e;
        }
        return ['storno' => $storno, 'uj' => $uj, 'rontott' => $rontott, 'osszeg' => $osszeg];
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
        if (!$szamla->getPartner()?->getHitelintezet()) {
            $this->fail(t('A számla partnere nem hitelintézet') . ': ' . $szamla->getId());
        }
        if (!$szamla->getPenztmozgat()) {
            $this->fail(t('A számla nem képez kintlévőséget') . ': ' . $szamla->getId());
        }
        // the payments' direction is copied onto the new bank lines
        if ($szamla->getIrany() != $eloleg->getIrany()) {
            $this->fail(t('A számla iránya eltér az előlegszámláétól.'));
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

    /**
     * One new document per original one, with the same account / cash desk, date, title and amounts,
     * only the partner and the referenced invoice change.
     *
     * @return array<Bankbizonylatfej|Penztarbizonylatfej>
     */
    private function reproducePenzmozgas(Bizonylatfej $eloleg, Bizonylatfej $szamla, array $tetelek): array
    {
        $csoportok = [];
        foreach ($tetelek as $tetel) {
            $regi = $tetel->getBizonylatfej();
            $csoportok[get_class($regi) . '|' . $regi->getId()][] = $tetel;
        }
        $megjegyzes = sprintf('%s előleg befizetése a(z) %s számlára (hiteles eladás).', $eloleg->getId(), $szamla->getId());
        $hivatkozottdatum = $szamla->getEsedekessegStr() ?: $szamla->getKeltStr();
        $ujak = [];
        foreach ($csoportok as $regitetelek) {
            $regi = $regitetelek[0]->getBizonylatfej();
            if ($regi instanceof Bankbizonylatfej) {
                $uj = new Bankbizonylatfej();
                $uj->setBizonylattipus($this->em->getRepository(Bizonylattipus::class)->find('bank'));
                $uj->setBankszamla($regi->getBankszamla());
            } else {
                $uj = new Penztarbizonylatfej();
                // the order matters: PenztarbizonylatfejListener::generateId() needs type, cash desk, direction and date
                $uj->setBizonylattipus($this->em->getRepository(Bizonylattipus::class)->find('penztar'));
                $uj->setIrany($regi->getIrany());
                $uj->setPenztar($regi->getPenztar());
                $uj->setArfolyam($regi->getArfolyam() ?: 1);
            }
            $uj->setKelt(clone $regi->getKelt());
            // setPartner() puts the partner's currency on the header, the original's goes back after it
            $uj->setPartner($szamla->getPartner());
            $uj->setValutanem($regi->getValutanem());
            $uj->setErbizonylatszam($regi->getErbizonylatszam());
            $uj->setMegjegyzes($megjegyzes);
            foreach ($regitetelek as $regitetel) {
                if ($regi instanceof Bankbizonylatfej) {
                    $tetel = new Bankbizonylattetel();
                    $tetel->setBizonylatfej($uj);
                    $tetel->setIrany($regitetel->getIrany());
                    $tetel->setPartner($szamla->getPartner());
                    $tetel->setDatum(clone $regitetel->getDatum());
                    $tetel->setValutanem($regitetel->getValutanem());
                    $tetel->setErbizonylatszam($regitetel->getErbizonylatszam());
                } else {
                    $tetel = new Penztarbizonylattetel();
                    $uj->addBizonylattetel($tetel);
                }
                $tetel->setJogcim($regitetel->getJogcim());
                $tetel->setHivatkozottbizonylat($szamla->getId());
                $tetel->setHivatkozottdatum($hivatkozottdatum);
                $tetel->setBrutto($regitetel->getBrutto());
                $this->em->persist($tetel);
            }
            $this->em->persist($uj);
            $ujak[] = $uj;
        }
        return $ujak;
    }

    private function fail(string $uzenet): never
    {
        throw new \mkwhelpers\Exceptions\UserMessageException($uzenet);
    }
}
