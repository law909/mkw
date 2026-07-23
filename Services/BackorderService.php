<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylatstatusz;
use Entities\TermekFa;

class BackorderService extends AbstractBizonylatSzetbontasService
{
    private function szabadKeszlet(
        \Entities\Bizonylattetel $tetel,
        Bizonylatfej $biz,
        $nominkeszlet,
        $nominkeszletkat
    ): float|bool {
        /** @var \Entities\Termek $t */
        $t = $tetel->getTermek();
        if (!$t || !$t->getMozgat()) {
            return false;
        }
        $v = $tetel->getTermekvaltozat();
        if ($nominkeszlet && $t->isInTermekKategoria($nominkeszletkat)) {
            if ($v) {
                $keszlet = $v->getKeszlet() - $v->getFoglaltMennyiseg($biz->getId());
            } else {
                $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg($biz->getId());
            }
        } elseif ($v) {
            $keszlet = $v->getKeszlet() - $v->getFoglaltMennyiseg($biz->getId()) - $v->calcMinboltikeszlet();
        } else {
            $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg($biz->getId()) - $t->getMinboltikeszlet();
        }
        return max($keszlet, 0);
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
        $teljesitheto = \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find(
            \mkw\store::getParameter(\mkw\consts::BizonylatStatuszTeljesitheto)
        );
        $backorder = \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find(
            \mkw\store::getParameter(\mkw\consts::BizonylatStatuszBackorder)
        );
        \mkw\store::getEm()->beginTransaction();
        try {
            // tervet készítünk: tételenként mennyi teljesíthető és mennyi kerül backorderre
            [$terv, $teljdb, $bodb] = $this->keszletTerv($regibiz, $nominkeszlet, $nominkeszletkat);

            // csak akkor van értelme szétbontani, ha van teljesíthető ÉS backorder rész is;
            // egyébként nem készül új bizonylat, csak az eredeti státuszát állítjuk
            if ($teljdb == 0 || $bodb == 0) {
                $result = 0;
                if ($bodb == 0) {
                    $regibiz->setBizonylatstatusz($teljesitheto);
                    foreach ($regibiz->getBizonylattetelek() as $regitetel) {
                        $regitetel->calc();
                        \mkw\store::getEm()->persist($regitetel);
                    }
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
     * @return array [
     *     [ [Bizonylattetel, teljesíthető mennyiség, backorder mennyiség], ... ],
     *     hány tételnek van teljesíthető része,
     *     hány tételnek van backorder része
     * ]
     */
    private function keszletTerv(Bizonylatfej $regibiz, $nominkeszlet, $nominkeszletkat)
    {
        $terv = [];
        $teljdb = 0;
        $bodb = 0;
        /** @var \Entities\Bizonylattetel $regitetel */
        foreach ($regibiz->getBizonylattetelek() as $regitetel) {
            $menny = $regitetel->getMennyiseg();
            $keszlet = $this->szabadKeszlet($regitetel, $regibiz, $nominkeszlet, $nominkeszletkat);
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
        $backorder = \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszBackorder));
        if ($backorder) {
            $nominkeszlet = \mkw\store::getParameter(\mkw\consts::NoMinKeszlet);
            $nominkeszletkat = \mkw\store::getEm()->getRepository(TermekFa::class)->find(
                \mkw\store::getParameter(\mkw\consts::NoMinKeszletTermekkat)
            )?->getKarkod();

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('bizonylatstatusz', '=', $backorder);
            $filter->addFilter('bizonylattipus', '', ['megrendeles', 'webshopbiz']);
            $filter->addFilter('rontott', '=', false);
            $filter->addFilter('hibas', '=', false);
            $fejek = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->getWithTetelek($filter, ['hatarido' => 'ASC']);
            if ($fejek) {
                /** @var \Entities\Bizonylatfej $fej */
                foreach ($fejek as $fej) {
                    $vankeszlet = false;
                    /** @var \Entities\Bizonylattetel $tetel */
                    foreach ($fej->getBizonylattetelek() as $tetel) {
                        if ($this->szabadKeszlet($tetel, $fej, $nominkeszlet, $nominkeszletkat) > 0) {
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
                            'printurl' => \mkw\store::getRouter()->generate('adminmegrendelesfejprint', false, [], [
                                'id' => $fej->getId()
                            ]),
                            'editurl' => \mkw\store::getRouter()->generate('adminmegrendelesfejviewkarb', false, [], [
                                'id' => $fej->getId(),
                                'oper' => 'edit'
                            ])
                        ];
                    }
                }
            }
        }
        return $ret;
    }


}