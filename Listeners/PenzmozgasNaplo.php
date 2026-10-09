<?php

namespace Listeners;

use Entities\Bizonylatnaplo;

/**
 * A bank- és a pénztárbizonylat naplója a Bizonylatnaplo táblába: létrehozás, a fej
 * lényeges mezőinek változása, és a tételek felvétele, módosítása, törlése.
 *
 * A két listener onFlush-a hívja, MIELŐTT az összeget és a folyószámlát újraszámolná: így
 * csak az látszik, amit a felhasználó (vagy egy másik listener, pl. a bizonylat rontása) átírt.
 */
class PenzmozgasNaplo
{
    private const FEJMEZOK = [
        'kelt' => 'Kelt',
        'partner' => 'Partner',
        'bankszamla' => 'Bankszámla',
        'penztar' => 'Pénztár',
        'valutanem' => 'Valutanem',
        'erbizonylatszam' => 'Er. biz. szám',
        'megjegyzes' => 'Megjegyzés',
        'rontott' => 'Rontott',
        'storno' => 'Stornó',
        'stornozott' => 'Stornózott',
    ];

    private const TETELMEZOK = [
        'brutto' => 'Összeg',
        'hivatkozottbizonylat' => 'Hivatkozott bizonylat',
        'hivatkozottdatum' => 'Hivatkozott dátum',
        'datum' => 'Dátum',
        'jogcim' => 'Jogcím',
        'partner' => 'Partner',
        'szoveg' => 'Szöveg',
        'rontott' => 'Rontott',
    ];

    private $em;
    private $uow;
    private $fejClass;
    private $tetelClass;
    private $naplomd;
    private $dolgozo;

    public function __construct($em, string $fejClass, string $tetelClass)
    {
        $this->em = $em;
        $this->uow = $em->getUnitOfWork();
        $this->fejClass = $fejClass;
        $this->tetelClass = $tetelClass;
        $this->naplomd = $em->getClassMetadata(Bizonylatnaplo::class);
    }

    public function log(): void
    {
        $this->dolgozo = \mkw\store::getLoggedInDolgozo();
        // a fej rontása a tételeket is rontja: elég egyszer, a fejnél naplózni
        $rontottFejek = [];

        foreach ($this->uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof $this->fejClass) {
                $this->write($entity, Bizonylatnaplo::ESEMENY_LETREHOZAS, t('Létrehozás'));
            }
        }

        foreach ($this->uow->getScheduledEntityUpdates() as $entity) {
            if (!($entity instanceof $this->fejClass)) {
                continue;
            }
            foreach ($this->changes($entity, self::FEJMEZOK) as $mezo => [$regi, $uj]) {
                $this->write($entity, Bizonylatnaplo::ESEMENY_MEZOVALTOZAS, t(self::FEJMEZOK[$mezo]), $mezo, $regi, $uj);
                if ($mezo === 'rontott' && $entity->getRontott()) {
                    $rontottFejek[spl_object_id($entity)] = true;
                }
            }
        }

        foreach ($this->uow->getScheduledEntityInsertions() as $tetel) {
            if (($tetel instanceof $this->tetelClass) && ($fej = $this->liveFej($tetel))) {
                $this->write($fej, Bizonylatnaplo::ESEMENY_MEZOVALTOZAS, t('Tétel felvéve'), 'tetel', '', $this->tetelLeiras($tetel));
            }
        }

        foreach ($this->uow->getScheduledEntityUpdates() as $tetel) {
            if (!($tetel instanceof $this->tetelClass) || !($fej = $this->liveFej($tetel))) {
                continue;
            }
            foreach ($this->changes($tetel, self::TETELMEZOK) as $mezo => [$regi, $uj]) {
                if ($mezo === 'rontott' && isset($rontottFejek[spl_object_id($fej)])) {
                    continue;
                }
                $this->write(
                    $fej,
                    Bizonylatnaplo::ESEMENY_MEZOVALTOZAS,
                    t('Tétel') . ' (' . $this->tetelLeiras($tetel) . '): ' . t(self::TETELMEZOK[$mezo]),
                    $mezo,
                    $regi,
                    $uj
                );
            }
        }

        foreach ($this->uow->getScheduledEntityDeletions() as $tetel) {
            if (($tetel instanceof $this->tetelClass) && ($fej = $this->liveFej($tetel))) {
                $this->write($fej, Bizonylatnaplo::ESEMENY_MEZOVALTOZAS, t('Tétel törölve'), 'tetel', $this->tetelLeiras($tetel), '');
            }
        }
    }

    /**
     * The tracked fields that really changed, as display values; a DateTime re-set to the same day is no change.
     *
     * @return array mezo => [regi, uj]
     */
    private function changes($entity, array $mezok): array
    {
        $result = [];
        foreach ($this->uow->getEntityChangeSet($entity) as $mezo => [$regi, $uj]) {
            if (!isset($mezok[$mezo])) {
                continue;
            }
            $regi = $this->ertek($mezo, $regi);
            $uj = $this->ertek($mezo, $uj);
            if ($regi !== $uj) {
                $result[$mezo] = [$regi, $uj];
            }
        }
        return $result;
    }

    /** The head of a line, unless the head itself is being deleted. On deletion the line is already detached from it. */
    private function liveFej($tetel)
    {
        $fej = $tetel->getBizonylatfej();
        if (!$fej) {
            $fej = $this->uow->getOriginalEntityData($tetel)['bizonylatfej'] ?? null;
        }
        if (!($fej instanceof $this->fejClass) || $this->uow->isScheduledForDelete($fej)) {
            return null;
        }
        return $fej;
    }

    private function tetelLeiras($tetel): string
    {
        $parts = [];
        if ($tetel->getHivatkozottbizonylat()) {
            $parts[] = $tetel->getHivatkozottbizonylat();
        }
        $parts[] = $this->ertek('brutto', $tetel->getBrutto());
        if ($tetel->getJogcimnev()) {
            $parts[] = $tetel->getJogcimnev();
        }
        return implode(', ', $parts);
    }

    private function ertek(string $mezo, $ertek): string
    {
        if ($ertek === null || $ertek === '') {
            return '';
        }
        if (is_bool($ertek)) {
            return $ertek ? t('igen') : t('nem');
        }
        if ($ertek instanceof \DateTimeInterface) {
            return $ertek->format(\mkw\store::$DateFormat);
        }
        if (is_object($ertek)) {
            if (method_exists($ertek, 'getNev')) {
                return (string)$ertek->getNev();
            }
            if (method_exists($ertek, 'getSzamlaszam')) {
                return (string)$ertek->getSzamlaszam();
            }
            return method_exists($ertek, 'getId') ? (string)$ertek->getId() : '';
        }
        if ($mezo === 'brutto' && is_numeric($ertek)) {
            return number_format((float)$ertek, 2, ',', ' ');
        }
        return mb_substr((string)$ertek, 0, 255);
    }

    private function write($fej, $esemeny, $esemenynev, $mezo = '', $regiertek = '', $ujertek = ''): void
    {
        $naplo = new Bizonylatnaplo();
        $naplo->setPenzmozgas($fej);
        $naplo->setCreated(new \DateTime());
        $naplo->setDolgozo($this->dolgozo);
        // a setDolgozo csak akkor tölti a nevet, ha van Dolgozo rekord (a SYSADMIN-nak nincs)
        $naplo->setDolgozonev(\mkw\store::getLoggedInDolgozoNev());
        $naplo->setEsemeny($esemeny);
        $naplo->setEsemenynev(mb_substr($esemenynev, 0, 255));
        $naplo->setMezo($mezo);
        $naplo->setRegiertek($regiertek);
        $naplo->setUjertek($ujertek);

        $this->em->persist($naplo);
        $this->uow->computeChangeSet($this->naplomd, $naplo);
    }

}
