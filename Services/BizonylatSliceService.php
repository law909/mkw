<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;

class BizonylatSliceService
{

    /**
     * A gyártó nélküli (és termék nélküli, pl. költség-) tételek közös csoportkulcsa.
     * A gyártó azonosítók pozitív auto-increment értékek, így a 0 nem ütközik velük.
     */
    private const NINCS_GYARTO = 0;

    /**
     * Bizonylat szétbontása a tételek termékeinek gyártója szerint: minden gyártó tételei
     * külön, új bizonylatra kerülnek. A tételeket nem áttesszük, hanem új (duplikált)
     * tételeket készítünk róluk, így az eredeti bizonylat a tételeivel együtt érintetlenül
     * megmarad. Az új bizonylatok fejadatai az eredeti másolatai.
     *
     * A gyártó nélküli tételek egy közös új bizonylatra kerülnek — minden tétel új
     * bizonylatra kerül. A szétbontás végén az eredeti bizonylatot lerontjuk
     * (setRontott), mivel a teljes tartalma átkerült az új bizonylatokra.
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
        // ha már készült belőle másik bizonylat, a tételei egy származtatott bizonylat
        // előzményei (parbizonylattetel) — ilyenkor nem bontható szét és nem is rontható le
        if (\mkw\store::getEm()->getRepository(Bizonylatfej::class)->vanSzarmaztatottBizonylat($regibiz)) {
            return $this->eredmeny([], 'A bizonylatból már készült másik bizonylat, ezért nem bontható szét.');
        }

        [$csoportok, $gyartonevek] = $this->csoportositTetelek($regibiz);

        // csak akkor van értelme szétbontani, ha legalább két különböző csoport van;
        // egyetlen csoportnál az új bizonylat az eredeti pontos mása lenne
        if (count($csoportok) < 2) {
            return $this->eredmeny([], 'Minden tétel egy csoportba tartozik, nincs mit szétbontani.');
        }

        \mkw\store::getEm()->beginTransaction();
        try {
            $ujak = [];
            foreach ($csoportok as $gyartoid => $tetelek) {
                $ujbiz = $this->ujBizonylat($regibiz);
                foreach ($tetelek as $regitetel) {
                    $this->masolTetel($regitetel, $ujbiz);
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

            // a teljes tartalom átkerült az új bizonylatokra, ezért az eredetit lerontjuk
            $this->rontEredeti($regibiz);
            \mkw\store::getEm()->commit();
        } catch (\Exception $e) {
            \mkw\store::getEm()->rollback();
            throw $e;
        }

        return $this->eredmeny($ujak);
    }

    /**
     * A bizonylat tételei gyártónként csoportosítva. A gyártó nélküli (és termék nélküli)
     * tételek a NINCS_GYARTO kulcs alatt egy közös csoportba kerülnek, hogy minden tétel
     * új bizonylatra kerülhessen.
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
            $kulcs = $gyarto ? $gyarto->getId() : self::NINCS_GYARTO;
            $csoportok[$kulcs][] = $tetel;
            $gyartonevek[$kulcs] = $gyarto ? $gyarto->getNev() : '';
        }
        return [$csoportok, $gyartonevek];
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
     * Tétel átmásolása az új bizonylatra: az eredeti tételt nem mozgatjuk, hanem egy vele
     * megegyező, friss tételt hozunk létre az új bizonylaton. Az eredeti tétel (és a rá
     * mutató hivatkozások, származási láncok) érintetlen marad az eredeti bizonylaton.
     * Az új tétel önálló: a duplicateFrom kihagyja a bizonylatfej- és az előzmény-
     * (parbizonylattetel) hivatkozást, előbbit az addBizonylattetel köti be.
     */
    private function masolTetel(Bizonylattetel $regitetel, Bizonylatfej $ujbiz)
    {
        $ujtetel = new Bizonylattetel();
        $ujtetel->duplicateFrom($regitetel);
        $ujtetel->clearCreated();
        $ujtetel->clearLastmod();
        $ujbiz->addBizonylattetel($ujtetel);
        \mkw\store::getEm()->persist($ujtetel);
    }

    /**
     * Az eredeti bizonylat lerontása a szétbontás végén: a bizonylatfej és — a setRontott
     * révén — a tételei is rontottá válnak. A ront() vezérlőakcióval egyezően a szállítási
     * költség újraszámítását is kikapcsoljuk.
     */
    private function rontEredeti(Bizonylatfej $regibiz)
    {
        $regibiz->setKellszallitasikoltsegetszamolni(false);
        $regibiz->setRontott(true);
        \mkw\store::getEm()->persist($regibiz);
        \mkw\store::getEm()->flush();
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
