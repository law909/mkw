<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;

/**
 * Számlák képzése bizonylat sablonokból (bizsablon típus) — a bizonylat lista "Számlázás"
 * csoportos művelete.
 *
 * A sablon fej- és tételadatai másolódnak, a fej típusa számla lesz, kelte a mai nap, és a
 * sablonra hivatkozik (parbizonylatfej). A tételek nevéhez fűzhető szöveg (pl. az elszámolt
 * hónap), a mennyiségük pedig felülírható — mindkettő a csoportos művelet modaljáról jön.
 *
 * Az összegeket, a NAV-beküldendő jelölőt és a költségtételeket a BizonylatfejListener képzi
 * a mentéskor, ez a szolgáltatás azokhoz nem nyúl.
 */
class BizsablonSzamlazasService
{
    /** @var string[] a képzés közben fellépő hibák bizonylatonként */
    private $errors = [];

    /**
     * @param string[] $sablonidk üres tömb esetén a rendszeres sablonok
     * @param string $tetelnevtoldat minden tétel nevéhez hozzáfűzött szöveg
     * @param float|null $mennyiseg a számlatételek mennyisége; null esetén a sablon mennyisége marad
     * @param bool $teljesitesazesedekesseg a teljesítés az esedékesség napja legyen-e
     *
     * @return array{szamlaszamok: string[], hibak: string[], uzenet: string}
     */
    public function createSzamlak(array $sablonidk, $tetelnevtoldat = '', $mennyiseg = null, $teljesitesazesedekesseg = true)
    {
        $szamlatipus = $this->getRepo(Bizonylattipus::class)->find('szamla');
        if (!$szamlatipus) {
            return $this->result([], 'Nincs számla bizonylattípus.');
        }

        $sablonok = $this->loadSablonok($sablonidk);
        if (!$sablonok) {
            return $this->result([], 'Nincs számlázható bizonylat sablon.');
        }

        $szamlaszamok = [];
        foreach ($sablonok as $sablon) {
            $szamla = $this->createSzamla($sablon, $szamlatipus, $tetelnevtoldat, $mennyiseg, $teljesitesazesedekesseg);
            if ($szamla) {
                $szamlaszamok[] = $szamla->getId();
            }
        }
        return $this->result($szamlaszamok);
    }

    /**
     * A számlázandó sablonok: a kiválasztottak, kiválasztás híján a rendszeresek. A rontott
     * sablonokból egyik esetben sem képzünk számlát.
     *
     * @return Bizonylatfej[]
     */
    private function loadSablonok(array $sablonidk)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('bizonylattipus', '=', 'bizsablon');
        $filter->addFilter('rontott', '=', false);
        if ($sablonidk) {
            $filter->addFilter('id', 'IN', $sablonidk);
        } else {
            $filter->addFilter('rendszeres', '=', true);
        }
        return $this->getRepo(Bizonylatfej::class)->getAll($filter, ['id' => 'ASC']);
    }

    /**
     * Egy sablon számlája. A bizonylatszámot a prePersist a tábla eddigi legnagyobb számából
     * képzi, ezért számlánként külön flush kell.
     */
    private function createSzamla(
        Bizonylatfej $sablon,
        Bizonylattipus $szamlatipus,
        $tetelnevtoldat,
        $mennyiseg,
        $teljesitesazesedekesseg
    ) {
        $em = \mkw\store::getEm();
        $szamla = new Bizonylatfej();
        $szamla->duplicateFrom($sablon);
        $szamla->clearId();
        $szamla->clearCreated();
        $szamla->clearLastmod();
        $szamla->setBizonylattipus($szamlatipus);
        $szamla->setParbizonylatfej($sablon);
        $szamla->setRendszeres(false);
        $szamla->setNyomtatva(false);
        $szamla->setNaveredmeny(null);
        $szamla->setKelt();
        $szamla->setTeljesites();
        $szamla->calcEsedekesseg();
        if ($teljesitesazesedekesseg) {
            // külön példány: egy közös DateTime-ot a két mező egyszerre módosítana
            $szamla->setTeljesites(clone $szamla->getEsedekesseg());
        }
        $szamla->setPersistentData();

        foreach ($sablon->getBizonylattetelek() as $sablontetel) {
            $this->copyTetel($sablontetel, $szamla, $tetelnevtoldat, $mennyiseg);
        }

        try {
            $szamla->calcOsszesen();
            $em->persist($szamla);
            $em->flush();
        } catch (\Exception $e) {
            $this->errors[] = $sablon->getId() . ': ' . $e->getMessage();
            return null;
        }
        return $szamla;
    }

    /** A sablontétel másolata a számlán, a kért névtoldattal és mennyiséggel. */
    private function copyTetel(Bizonylattetel $sablontetel, Bizonylatfej $szamla, $tetelnevtoldat, $mennyiseg)
    {
        $tetel = new Bizonylattetel();
        $tetel->duplicateFrom($sablontetel);
        $tetel->clearCreated();
        $tetel->clearLastmod();
        if ($tetelnevtoldat !== '') {
            $tetel->setTermeknev(trim($tetel->getTermeknev() . ' ' . $tetelnevtoldat));
        }
        if (!is_null($mennyiseg)) {
            $tetel->setMennyiseg($mennyiseg);
            $tetel->calc();
        }
        $szamla->addBizonylattetel($tetel);
        $tetel->setParbizonylattetel($sablontetel);
        \mkw\store::getEm()->persist($tetel);
    }

    private function result(array $szamlaszamok, $uzenet = null)
    {
        if (is_null($uzenet)) {
            $uzenet = $szamlaszamok
                ? count($szamlaszamok) . ' számla készült: ' . implode(', ', $szamlaszamok)
                : 'Nem készült számla.';
        }
        return [
            'szamlaszamok' => $szamlaszamok,
            'hibak' => $this->errors,
            'uzenet' => $uzenet
        ];
    }

    private function getRepo($entity)
    {
        return \mkw\store::getEm()->getRepository($entity);
    }
}
