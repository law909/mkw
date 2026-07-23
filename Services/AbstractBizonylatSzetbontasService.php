<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;

/**
 * Közös alap a bizonylatot szétbontó szolgáltatásokhoz (gyártó szerinti szeletelés –
 * {@see BizonylatSliceService}, illetve készlet szerinti backorder – {@see BackorderService}).
 *
 * A közös művelet: az eredeti bizonylatból származtatott, új bizonylato(ka)t készítünk –
 * a fej és a tételek duplikátumok, amelyek az eredetire hivatkoznak (parbizonylatfej /
 * parbizonylattetel) –, majd a szétbontás végén az eredeti bizonylatot lerontjuk.
 */
abstract class AbstractBizonylatSzetbontasService
{
    /**
     * Az eredetivel megegyező, az eredetire hivatkozó (parbizonylatfej) új bizonylatfej,
     * saját azonosítóval. Ha kap státuszt, azt is beállítja rajta (egyébként a
     * duplicateFrom által másolt eredeti státusz marad).
     */
    protected function ujBizonylat(Bizonylatfej $regibiz, $statusz = null)
    {
        $ujbiz = new Bizonylatfej();
        $ujbiz->duplicateFrom($regibiz);
        $ujbiz->clearId();
        $ujbiz->clearCreated();
        $ujbiz->clearLastmod();
        if ($statusz) {
            $ujbiz->setBizonylatstatusz($statusz);
        }
        // az új bizonylat előzménye maga az eredeti bizonylat (a duplicateFrom a
        // parbizonylatfej-hivatkozást szándékosan kihagyja)
        $ujbiz->setParbizonylatfej($regibiz);
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
     * mutató hivatkozások, származási láncok) érintetlen marad az eredeti bizonylaton. Az
     * új tétel az eredeti tételből származik (parbizonylattetel).
     *
     * A duplicateFrom kihagyja a bizonylatfej- és a parbizonylattetel-hivatkozást: előbbit
     * az addBizonylattetel köti be, utóbbit itt állítjuk be. Ha kap mennyiséget és az eltér
     * az eredetitől (részleges átvétel), a tételt az új mennyiséggel újraszámoljuk;
     * egyébként a duplikátum értékeit érintetlenül hagyjuk.
     */
    protected function masolTetel(Bizonylattetel $regitetel, Bizonylatfej $ujbiz, $mennyiseg = null)
    {
        $ujtetel = new Bizonylattetel();
        $ujtetel->duplicateFrom($regitetel);
        $ujtetel->clearCreated();
        $ujtetel->clearLastmod();
        if ($mennyiseg !== null && $mennyiseg != $regitetel->getMennyiseg()) {
            $ujtetel->setMennyiseg($mennyiseg);
            $ujtetel->calc();
        }
        $ujbiz->addBizonylattetel($ujtetel);
        $ujtetel->setParbizonylattetel($regitetel);
        \mkw\store::getEm()->persist($ujtetel);
    }

    /**
     * Új bizonylat véglegesítése: fejösszegek számítása, persist és flush. Bizonylatonként
     * külön flush kell, mert a bizonylatszámot a prePersist a tábla eddigi legnagyobb
     * bizonylatszámából képzi – a következő csak akkor kaphat helyes számot, ha az előző
     * már bent van.
     */
    protected function mentBizonylat(Bizonylatfej $ujbiz)
    {
        $ujbiz->calcOsszesen();
        \mkw\store::getEm()->persist($ujbiz);
        \mkw\store::getEm()->flush();
    }

    /**
     * Az eredeti bizonylat lerontása a szétbontás végén: a bizonylatfej és — a setRontott
     * révén — a tételei is rontottá válnak. A ront() vezérlőakcióval egyezően a szállítási
     * költség újraszámítását is kikapcsoljuk.
     */
    protected function rontEredeti(Bizonylatfej $regibiz)
    {
        $regibiz->setKellszallitasikoltsegetszamolni(false);
        $regibiz->setRontott(true);
        \mkw\store::getEm()->persist($regibiz);
        \mkw\store::getEm()->flush();
    }
}
