<?php

namespace Services;

use Entities\Bankbizonylatfej;
use Entities\Bankbizonylattetel;
use Entities\Bizonylatfej;
use Entities\Bizonylattipus;
use Entities\Penztarbizonylatfej;
use Entities\Penztarbizonylattetel;

/**
 * A bizonylathoz tartozó pénzmozgások (pénztár- és bankbizonylat) kezelése a bizonylat felől.
 *
 * A rontást a BizonylatfejListener végzi (ott a bizonylat mentésének a részeként fut), ide a
 * stornó került: azt a storno bizonylat mentése UTÁN kell elvégezni, mert az eredeti bizonylat
 * `stornozott` jelölője is csak akkor áll be.
 *
 * A leírás a docs/penzkezeles.md-ben van.
 */
class PenzmozgasService
{

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    public function __construct()
    {
        $this->em = \mkw\store::getEm();
    }

    /**
     * A bizonylathoz tartozó élő pénzmozgások – a felhasználó ezekre kap kérdést.
     *
     * @param \Entities\Bizonylatfej|string $bizonylat
     *
     * @return array a pénztár- és bankbizonylat fejek egy tömbben
     */
    public function getEloPenzmozgas($bizonylat)
    {
        $bizszam = is_object($bizonylat) ? $bizonylat->getId() : $bizonylat;
        if (!$bizszam) {
            return [];
        }

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('pt.hivatkozottbizonylat', '=', $bizszam)
            ->addFilter('rontott', '=', false);
        $lista = $this->em->getRepository(Penztarbizonylatfej::class)->getAllByHivatkozottBizonylat($filter);

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bt.hivatkozottbizonylat', '=', $bizszam)
            ->addFilter('bt.rontott', '=', false)
            ->addFilter('rontott', '=', false);

        return array_merge(
            $lista,
            $this->em->getRepository(Bankbizonylatfej::class)->getAllByHivatkozottBizonylat($filter)
        );
    }

    /**
     * A stornózott bizonylat pénzügyi teljesítésének stornózása: az eredeti pénzmozgásokat
     * megjelöljük stornózottként, a stornó bizonylatnak pedig ellentétes irányú párt képzünk.
     *
     * A pénz maga nem tűnik el sehonnan: az eredeti befizetés megmarad a folyószámlán, a
     * visszafizetés pedig új sorként jelenik meg – a kettő együtt nullázza ki a partner egyenlegét.
     *
     * Ha a stornó bizonylathoz a mentés már képzett pénzmozgást (automatikus pénztárbizonylatot
     * képző bizonylattípus), akkor azt csak megjelöljük, nem képzünk mellé másikat.
     *
     * @param \Entities\Bizonylatfej $eredeti a stornózott bizonylat
     * @param \Entities\Bizonylatfej $stornobiz a stornó bizonylat
     *
     * @return int a képzett (vagy megjelölt) stornó pénzmozgások száma
     */
    public function createStornoPenzmozgas($eredeti, $stornobiz)
    {
        if (!$eredeti || !$stornobiz) {
            return 0;
        }
        $eredetiek = $this->getEloPenzmozgas($eredeti);
        if (!$eredetiek) {
            return 0;
        }

        foreach ($eredetiek as $penzmozgas) {
            $penzmozgas->setStornozott(true);
            $this->em->persist($penzmozgas);
        }

        $mar = $this->getEloPenzmozgas($stornobiz);
        if ($mar) {
            foreach ($mar as $penzmozgas) {
                $penzmozgas->setStorno(true);
                $this->em->persist($penzmozgas);
            }
            return count($mar);
        }

        $db = 0;
        foreach ($eredetiek as $penzmozgas) {
            if ($penzmozgas instanceof Penztarbizonylatfej) {
                $db += $this->mirrorPenztarbizonylat($penzmozgas, $eredeti, $stornobiz) ? 1 : 0;
            } else {
                $db += $this->mirrorBankbizonylat($penzmozgas, $eredeti, $stornobiz) ? 1 : 0;
            }
        }
        return $db;
    }

    /**
     * @param \Entities\Penztarbizonylatfej $regi
     * @param \Entities\Bizonylatfej $eredeti
     * @param \Entities\Bizonylatfej $stornobiz
     */
    private function mirrorPenztarbizonylat($regi, $eredeti, $stornobiz)
    {
        $penztar = $regi->getPenztar();
        if ($penztar && $this->zartIdoszak($penztar, $stornobiz->getKelt())) {
            \mkw\store::writelog(
                $stornobiz->getId() . ': a pénztár időszaka zárt (' . $penztar->getId() . '), a stornó pénzmozgás elmaradt',
                'autopenztarbizonylat.log'
            );
            return false;
        }

        $tetelek = $this->getHivatkozoTetelek($regi, $eredeti);
        if (!$tetelek) {
            return false;
        }

        $uj = new Penztarbizonylatfej();
        // A sorrend kötött: a PenztarbizonylatfejListener::generateId() a persist()-kor fut,
        // és a bizonylattipus + penztar + irany + kelt mezőket használja.
        $uj->setBizonylattipus($this->em->getRepository(Bizonylattipus::class)->find('penztar'));
        $uj->setIrany($regi->getIrany() * -1);
        $uj->setPenztar($penztar);
        $uj->setKelt($stornobiz->getKeltStr());
        // a setPartner() a partner valutanemét is ráteszi a fejre, ezért utána írjuk vissza a bizonylatét
        $uj->setPartner($regi->getPartner());
        $uj->setValutanem($regi->getValutanem());
        $uj->setArfolyam($regi->getArfolyam() ?: 1);
        $uj->setMegjegyzes($regi->getId() . ' stornó pénzmozgása.');
        $uj->setStorno(true);

        foreach ($tetelek as $regitetel) {
            $tetel = new Penztarbizonylattetel();
            $uj->addBizonylattetel($tetel);
            $tetel->setJogcim($regitetel->getJogcim());
            $tetel->setHivatkozottbizonylat($stornobiz->getId());
            $tetel->setHivatkozottdatum($stornobiz->getEsedekessegStr() ?: $stornobiz->getKeltStr());
            $tetel->setBrutto($regitetel->getBrutto());
            $this->em->persist($tetel);
        }
        $this->em->persist($uj);
        return true;
    }

    /**
     * @param \Entities\Bankbizonylatfej $regi
     * @param \Entities\Bizonylatfej $eredeti
     * @param \Entities\Bizonylatfej $stornobiz
     */
    private function mirrorBankbizonylat($regi, $eredeti, $stornobiz)
    {
        $tetelek = $this->getHivatkozoTetelek($regi, $eredeti);
        if (!$tetelek) {
            return false;
        }

        $uj = new Bankbizonylatfej();
        $uj->setBizonylattipus($this->em->getRepository(Bizonylattipus::class)->find('bank'));
        $uj->setBankszamla($regi->getBankszamla());
        $uj->setKelt($stornobiz->getKeltStr());
        $uj->setPartner($regi->getPartner());
        $uj->setValutanem($regi->getValutanem());
        $uj->setMegjegyzes($regi->getId() . ' stornó pénzmozgása.');
        $uj->setStorno(true);

        foreach ($tetelek as $regitetel) {
            $tetel = new Bankbizonylattetel();
            $tetel->setBizonylatfej($uj);
            $tetel->setIrany($regitetel->getIrany() * -1);
            $tetel->setPartner($regitetel->getPartner());
            $tetel->setDatum($stornobiz->getKeltStr());
            $tetel->setJogcim($regitetel->getJogcim());
            $tetel->setValutanem($regitetel->getValutanem());
            $tetel->setHivatkozottbizonylat($stornobiz->getId());
            $tetel->setHivatkozottdatum($stornobiz->getEsedekessegStr() ?: $stornobiz->getKeltStr());
            $tetel->setBrutto($regitetel->getBrutto());
            $this->em->persist($tetel);
        }
        $this->em->persist($uj);
        return true;
    }

    /**
     * Egy pénzmozgásnak csak azok a tételei tartoznak a bizonylathoz, amelyek rá hivatkoznak –
     * egy bankbizonylat több számlát is kiegyenlíthet.
     *
     * @param \Entities\Bankbizonylatfej|\Entities\Penztarbizonylatfej $penzmozgas
     * @param \Entities\Bizonylatfej $bizonylat
     */
    private function getHivatkozoTetelek($penzmozgas, $bizonylat)
    {
        $tetelek = [];
        foreach ($penzmozgas->getBizonylattetelek() as $tetel) {
            if (!$tetel->getRontott()
                && ((string)$tetel->getHivatkozottbizonylat() === (string)$bizonylat->getId())) {
                $tetelek[] = $tetel;
            }
        }
        return $tetelek;
    }

    /**
     * @param \Entities\Penztar $penztar
     * @param \DateTime|null $kelt
     */
    private function zartIdoszak($penztar, $kelt)
    {
        $zart = \mkw\store::getParameter(\mkw\consts::PenztarZarva . $penztar->getId());
        if (!$zart || !$kelt) {
            return false;
        }
        return $kelt->format(\mkw\store::$SQLDateFormat) <= $zart;
    }
}
