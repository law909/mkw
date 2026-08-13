<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylatstatusz;
use Entities\Bizonylattipus;
use Entities\TermekFa;

class BackorderService extends AbstractBizonylatSzetbontasService
{
    /** a backorderstock beállítás értékei: meddig fogyhat a készlet backorder nélkül */
    const STOCKMINIMUM = 0;
    const STOCKZERO = 1;

    private function isStockToZero(): bool
    {
        return (int)\mkw\store::getParameter(\mkw\consts::BackorderStock, self::STOCKMINIMUM) === self::STOCKZERO;
    }

    private function szabadKeszlet(
        \Entities\Bizonylattetel $tetel,
        Bizonylatfej $biz,
        $nominkeszlet,
        $nominkeszletkat,
        $stocktozero = false
    ): float|bool {
        /** @var \Entities\Termek $t */
        $t = $tetel->getTermek();
        if (!$t || !$t->getMozgat()) {
            return false;
        }
        $v = $tetel->getTermekvaltozat();
        // kategória nélkül a kapcsoló nem jelent semmit – beállított kategória nélküli
        // nominkeszlet nem kapcsolhatja ki a minimumot minden termékre
        $ignoreminkeszlet = $stocktozero
            || ($nominkeszlet && $nominkeszletkat && $t->isInTermekKategoria($nominkeszletkat));
        return ($v ?: $t)->getAvailableStock(
            datum: null,
            raktarid: null,
            kivevebiz: $biz->getId(),
            clamp: true,
            ignoreminkeszlet: $ignoreminkeszlet,
            ignorefoglalas: false
        );
    }

    /**
     * Bizonylat szétbontása készlet szerint. Az eredeti bizonylatot érintetlenül hagyjuk:
     * a teljesíthető és a nem teljesíthető (backorder) tételek két külön, új bizonylatra
     * kerülnek (nem áttesszük, hanem duplikáljuk őket). A szétbontás végén az eredeti
     * bizonylatot lerontjuk (setRontott). Mindkét új bizonylat és a tételeik az eredetire
     * hivatkoznak (parbizonylatfej / parbizonylattetel).
     *
     * Ha nincs mit szétbontani (minden tétel teljesíthető, vagy egyik sem), akkor nem
     * készül új bizonylat: csak az eredeti státuszát állítjuk a megfelelőre.
     */
    public function backOrder($id)
    {
        /** @var Bizonylatfej $regibiz */
        $regibiz = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
        if (!$regibiz) {
            return ['refresh' => 0];
        }
        $nominkeszlet = \mkw\store::getParameter(\mkw\consts::NoMinKeszlet);
        $nominkeszletkat = \mkw\store::getEm()->getRepository(TermekFa::class)->find(
            \mkw\store::getParameter(\mkw\consts::NoMinKeszletTermekkat)
        )?->getKarkod();
        $stocktozero = $this->isStockToZero();
        $teljesitheto = \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find(
            \mkw\store::getParameter(\mkw\consts::BizonylatStatuszTeljesitheto)
        );
        $backorder = \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find(
            \mkw\store::getParameter(\mkw\consts::BizonylatStatuszBackorder)
        );
        \mkw\store::getEm()->beginTransaction();
        try {
            // tervet készítünk: tételenként mennyi teljesíthető és mennyi kerül backorderre
            [$terv, $teljdb, $bodb] = $this->keszletTerv($regibiz, $nominkeszlet, $nominkeszletkat, $stocktozero);

            // csak akkor van értelme szétbontani, ha van teljesíthető ÉS backorder rész is;
            // egyébként nem készül új bizonylat, csak az eredeti státuszát állítjuk
            if ($teljdb == 0 || $bodb == 0) {
                $result = 0;
                // itt csak a státusz változik, a bizonylat tartalma nem: a szállítási költség
                // újraszámítását kikapcsoljuk, nehogy a mentés olyan bizonylatra vigye fel,
                // amelyiken eredetileg nincs (a flag nem perzisztens, alapértéke true)
                $regibiz->setSimpleedit(true);
                $regibiz->setKellszallitasikoltsegetszamolni(false);
                if ($bodb == 0) {
                    $regibiz->setBizonylatstatusz($teljesitheto);
                } elseif ($teljdb == 0) {
                    $regibiz->setBizonylatstatusz($backorder);
                    $result = 1;
                }
                \mkw\store::getEm()->persist($regibiz);
                \mkw\store::getEm()->flush();
                \mkw\store::getEm()->commit();
                return ['refresh' => $result];
            }

            // teljesíthető bizonylat: az eredeti keltével, a teljesíthető mennyiségekkel
            $ujteljbiz = $this->ujBizonylat($regibiz, $teljesitheto);
            foreach ($terv as [$regitetel, $teljmenny, $bomenny]) {
                if ($teljmenny > 0) {
                    $this->masolTetel($regitetel, $ujteljbiz, $teljmenny);
                }
            }
            $this->mentBizonylat($ujteljbiz);

            // backorder bizonylat: mai kelttel (mint korábban az egyetlen új bizonylat)
            $ujbobiz = $this->ujBizonylat($regibiz, $backorder);
            $ujbobiz->setKelt();
            foreach ($terv as [$regitetel, $teljmenny, $bomenny]) {
                if ($bomenny > 0) {
                    $this->masolTetel($regitetel, $ujbobiz, $bomenny);
                }
            }
            $this->mentBizonylat($ujbobiz);

            // a teljes tartalom átkerült az új bizonylatokra, ezért az eredetit lerontjuk
            $this->rontEredeti($regibiz);
            \mkw\store::getEm()->commit();
            return ['refresh' => 1];
        } catch (\Exception $e) {
            \mkw\store::getEm()->rollback();
            throw $e;
        }
    }

    /**
     * Tételenkénti szétbontási terv a szabad készlet alapján. Minden tételhez megadja,
     * hogy mennyi teljesíthető azonnal és mennyi kerül backorderre.
     *
     * A költségtételek (szállítási / utánvét / kezelési költség, vásárlási utalvány) kimaradnak
     * a tervből: nem készletezett termékek, ezért teljesíthetőnek látszanának, és önmagukban egy
     * üres, csak költséget tartalmazó "teljesíthető" bizonylatot eredményeznének. Az új
     * bizonylatokra a listener képzi őket a saját tartalmuk alapján.
     *
     * @return array [
     *     [ [Bizonylattetel, teljesíthető mennyiség, backorder mennyiség], ... ],
     *     hány tételnek van teljesíthető része,
     *     hány tételnek van backorder része
     * ]
     */
    private function keszletTerv(Bizonylatfej $regibiz, $nominkeszlet, $nominkeszletkat, $stocktozero = false)
    {
        $terv = [];
        $teljdb = 0;
        $bodb = 0;
        /** @var \Entities\Bizonylattetel $regitetel */
        foreach ($regibiz->getBizonylattetelek() as $regitetel) {
            if ($this->koltsegTetel($regitetel)) {
                continue;
            }
            $menny = $regitetel->getMennyiseg();
            $keszlet = $this->szabadKeszlet($regitetel, $regibiz, $nominkeszlet, $nominkeszletkat, $stocktozero);
            if ($keszlet === false || $keszlet >= $menny) {
                // készletet nem mozgató tétel, vagy a teljes mennyiség teljesíthető
                $teljmenny = $menny;
                $bomenny = 0;
            } elseif ($keszlet <= 0) {
                // semmi sem teljesíthető: a teljes mennyiség backorder
                $teljmenny = 0;
                $bomenny = $menny;
            } else {
                // részben teljesíthető: a szabad készlet teljesíthető, a maradék backorder
                $teljmenny = $keszlet;
                $bomenny = $menny - $keszlet;
            }
            if ($teljmenny > 0) {
                $teljdb++;
            }
            if ($bomenny > 0) {
                $bodb++;
            }
            $terv[] = [$regitetel, $teljmenny, $bomenny];
        }
        return [$terv, $teljdb, $bodb];
    }

    public function getTeljesithetoBackorderLista()
    {
        $ret = [];
        $foglalotipusok = Bizonylattipus::getFoglalIdList();
        $backorder = \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszBackorder));
        if ($backorder && $foglalotipusok) {
            $nominkeszlet = \mkw\store::getParameter(\mkw\consts::NoMinKeszlet);
            $nominkeszletkat = \mkw\store::getEm()->getRepository(TermekFa::class)->find(
                \mkw\store::getParameter(\mkw\consts::NoMinKeszletTermekkat)
            )?->getKarkod();
            $stocktozero = $this->isStockToZero();

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('bizonylatstatusz', '=', $backorder);
            $filter->addFilter('bizonylattipus', '', $foglalotipusok);
            $filter->addFilter('rontott', '=', false);
            $filter->addFilter('hibas', '=', false);
            $fejek = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->getWithTetelek($filter, ['hatarido' => 'ASC']);
            if ($fejek) {
                /** @var \Entities\Bizonylatfej $fej */
                foreach ($fejek as $fej) {
                    $vankeszlet = false;
                    /** @var \Entities\Bizonylattetel $tetel */
                    foreach ($fej->getBizonylattetelek() as $tetel) {
                        if ($this->szabadKeszlet($tetel, $fej, $nominkeszlet, $nominkeszletkat, $stocktozero) > 0) {
                            $vankeszlet = true;
                            break;
                        }
                    }
                    if ($vankeszlet) {
                        $ret[] = [
                            'id' => $fej->getId(),
                            'kelt' => $fej->getKeltStr(),
                            'hatarido' => $fej->getHataridoStr(),
                            'partnernev' => $fej->getPartnernev(),
                            // a listán több bizonylattípus is szerepel, ezért a saját típusa adja az URL-t
                            'printurl' => $fej->getPrintUrl(),
                            'editurl' => $fej->getKarbUrl()
                        ];
                    }
                }
            }
        }
        return $ret;
    }


}