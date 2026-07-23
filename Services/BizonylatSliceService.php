<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;

class BizonylatSliceService extends AbstractBizonylatSzetbontasService
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
     * megmarad. Az új bizonylatok fejadatai az eredeti másolatai, és mind a fejük
     * (parbizonylatfej), mind a tételeik (parbizonylattetel) az eredetire hivatkoznak.
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
                $this->mentBizonylat($ujbiz);
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
