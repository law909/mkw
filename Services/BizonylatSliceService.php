<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;

class BizonylatSliceService
{

    /**
     * Bizonylat szétbontása a tételek termékeinek gyártója szerint: minden gyártó tételei
     * külön bizonylatra kerülnek. Az új bizonylatok fejadatai az eredeti másolatai.
     *
     * A gyártó nélküli tételek (és a termék nélküliek, pl. költségtételek) az eredeti
     * bizonylaton maradnak. Ha minden tételnek van gyártója, akkor az első gyártó tételei
     * maradnak az eredetin, a többi gyártó kap új bizonylatot.
     *
     * @param string $id a szétbontandó bizonylat azonosítója
     *
     * @return array [
     *     'darab' => hány új bizonylat készült,
     *     'bizonylatszamok' => az új bizonylatok azonosítói,
     *     'uzenet' => felhasználónak szóló üzenet
     * ]
     */
    public function sliceByManufacturer($id)
    {
        /** @var Bizonylatfej $regibiz */
        $regibiz = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
        if (!$regibiz) {
            return $this->eredmeny([], 'A bizonylat nem található.');
        }

        [$csoportok, $gyartonevek] = $this->csoportositTetelek($regibiz);

        if (!$csoportok) {
            return $this->eredmeny([], 'Egyik tétel termékének sincs gyártója, nincs mit szétbontani.');
        }
        // ha nincs gyártó nélküli tétel, az első gyártó tételei maradnak az eredeti bizonylaton
        if (count($csoportok) === 1 && $this->mindenTetelCsoportban($regibiz, $csoportok)) {
            return $this->eredmeny([], 'Minden tétel ugyanahhoz a gyártóhoz tartozik, nincs mit szétbontani.');
        }
        if ($this->mindenTetelCsoportban($regibiz, $csoportok)) {
            // az unset (az array_shift-tel szemben) megtartja a gyártó id kulcsokat
            unset($csoportok[array_key_first($csoportok)]);
        }

        \mkw\store::getEm()->beginTransaction();
        try {
            $ujak = [];
            foreach ($csoportok as $gyartoid => $tetelek) {
                $ujbiz = $this->ujBizonylat($regibiz);
                foreach ($tetelek as $regitetel) {
                    $this->atteszTetel($regitetel, $regibiz, $ujbiz);
                }
                $ujbiz->calcOsszesen();
                \mkw\store::getEm()->persist($ujbiz);
                // bizonylatonként külön flush: az azonosítót a prePersist a tábla eddigi
                // legnagyobb bizonylatszámából képzi, ezért a következő csak akkor kaphat
                // helyes számot, ha az előző már bent van
                \mkw\store::getEm()->flush();
                $ujak[] = [
                    'id' => $ujbiz->getId(),
                    'gyarto' => $gyartonevek[$gyartoid] ?? ''
                ];
            }

            $regibiz->calcOsszesen();
            \mkw\store::getEm()->persist($regibiz);
            \mkw\store::getEm()->flush();
            \mkw\store::getEm()->commit();
        } catch (\Exception $e) {
            \mkw\store::getEm()->rollback();
            throw $e;
        }

        return $this->eredmeny($ujak);
    }

    /**
     * A bizonylat tételei gyártónként csoportosítva. A gyártó nélküli (és termék nélküli)
     * tételek nem kerülnek egyik csoportba sem, azok maradnak az eredeti bizonylaton.
     *
     * @return array [ [gyartoid => Bizonylattetel[]], [gyartoid => gyártónév] ]
     */
    private function csoportositTetelek(Bizonylatfej $biz)
    {
        $csoportok = [];
        $gyartonevek = [];
        /** @var Bizonylattetel $tetel */
        foreach ($biz->getBizonylattetelek() as $tetel) {
            $gyarto = $tetel->getTermek()?->getGyarto();
            if (!$gyarto) {
                continue;
            }
            $csoportok[$gyarto->getId()][] = $tetel;
            $gyartonevek[$gyarto->getId()] = $gyarto->getNev();
        }
        return [$csoportok, $gyartonevek];
    }

    /**
     * Igaz, ha a bizonylat minden tétele valamelyik gyártócsoportba tartozik, azaz nincs
     * olyan tétel, ami magától maradna az eredeti bizonylaton.
     */
    private function mindenTetelCsoportban(Bizonylatfej $biz, array $csoportok)
    {
        $csoportositott = 0;
        foreach ($csoportok as $tetelek) {
            $csoportositott += count($tetelek);
        }
        return $csoportositott === count($biz->getBizonylattetelek());
    }

    /**
     * Az eredetivel mindenben megegyező új bizonylatfej (saját azonosítóval).
     */
    private function ujBizonylat(Bizonylatfej $regibiz)
    {
        $ujbiz = new Bizonylatfej();
        $ujbiz->duplicateFrom($regibiz);
        $ujbiz->clearId();
        $ujbiz->clearCreated();
        $ujbiz->clearLastmod();
        // a duplicateFrom szándékosan kihagyja, de a szétbontott bizonylatnak ugyanaz
        // az előzménye, mint az eredetinek
        if ($regibiz->getParbizonylatfej()) {
            $ujbiz->setParbizonylatfej($regibiz->getParbizonylatfej());
        }
        // a duplicateFrom set/get párokat másol, a setter nélküli származtatott mezők
        // (belsőüzletkötő és felhasználó neve, emailje) így kimaradnának – a kapcsolat
        // újra beállításával töltetjük ki őket
        $belsouzletkoto = $regibiz->getBelsouzletkoto();
        if ($belsouzletkoto) {
            $ujbiz->removeBelsouzletkoto();
            $ujbiz->setBelsouzletkoto($belsouzletkoto);
        }
        $felhasznalo = $regibiz->getFelhasznalo();
        if ($felhasznalo) {
            $ujbiz->removeFelhasznalo();
            $ujbiz->setFelhasznalo($felhasznalo);
        }
        return $ujbiz;
    }

    /**
     * Tétel áttétele az új bizonylatra: az eredetivel megegyező másolat kerül az új
     * bizonylatra, a régi tétel pedig törlődik (ahogy a backorder is csinálja).
     */
    private function atteszTetel(Bizonylattetel $regitetel, Bizonylatfej $regibiz, Bizonylatfej $ujbiz)
    {
        $ujtetel = new Bizonylattetel();
        $ujtetel->duplicateFrom($regitetel);
        $ujtetel->clearCreated();
        $ujtetel->clearLastmod();
        // a duplicateFrom kihagyja, pedig a készletmozgatás múlik rajta
        if ($regitetel->getParbizonylattetel()) {
            $ujtetel->setParbizonylattetel($regitetel->getParbizonylattetel());
        }
        $ujbiz->addBizonylattetel($ujtetel);
        $ujtetel->calc();
        \mkw\store::getEm()->persist($ujtetel);

        $regibiz->removeBizonylattetel($regitetel);
        \mkw\store::getEm()->remove($regitetel);
    }

    private function eredmeny(array $ujak, $uzenet = null)
    {
        if (is_null($uzenet)) {
            $reszek = [];
            foreach ($ujak as $uj) {
                $reszek[] = $uj['id'] . ($uj['gyarto'] ? ' (' . $uj['gyarto'] . ')' : '');
            }
            $uzenet = count($ujak) . ' új bizonylat készült: ' . implode(', ', $reszek);
        }
        return [
            'darab' => count($ujak),
            'bizonylatszamok' => array_column($ujak, 'id'),
            'uzenet' => $uzenet
        ];
    }
}
