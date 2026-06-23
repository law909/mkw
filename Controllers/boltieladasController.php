<?php

namespace Controllers;

use Entities\Afa;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Fizmod;
use Entities\Partner;
use Entities\Raktar;
use Entities\Termek;
use Entities\TermekValtozat;
use Entities\Valutanem;

class boltieladasController extends \mkwhelpers\Controller
{
    const BIZTIPUS = 'boltieladas';

    private function getBoltivevo()
    {
        $id = \mkw\store::getParameter(\mkw\consts::Boltivevo);
        if (!$id) {
            return null;
        }
        return $this->getRepo(Partner::class)->find($id);
    }

    private function getDefaultValutanem()
    {
        return $this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
    }

    private function getDefaultRaktar()
    {
        return $this->getRepo(Raktar::class)->find(\mkw\store::getParameter(\mkw\consts::Raktar));
    }

    public function getkarb()
    {
        $view = $this->createView('boltieladaskarb.tpl');

        $partner = $this->getBoltivevo();
        $view->setVar('boltivevonev', $partner ? $partner->getNev() : '');
        $view->setVar('boltivevohiba', $partner ? '' : t('Nincs beállítva bolti vevő a beállításokban!'));

        $fmc = new fizmodController();
        $view->setVar('fizmodlist', $fmc->getSelectList());

        echo $view->getTemplateResult();
    }

    public function findtermek()
    {
        $kod = trim($this->params->getStringRequestParam('vonalkod'));
        if ($kod === '') {
            return;
        }

        /** @var Termek|null $termek */
        $termek = null;
        /** @var TermekValtozat|null $valtozat */
        $valtozat = null;

        $valtozat = $this->getRepo(TermekValtozat::class)->findOneBy(['vonalkod' => $kod]);
        if (!$valtozat) {
            $valtozat = $this->getRepo(TermekValtozat::class)->findOneBy(['cikkszam' => $kod]);
        }
        if ($valtozat) {
            $termek = $valtozat->getTermek();
        } else {
            // 3) termék vonalkód, 4) termék cikkszám
            $termek = $this->getRepo(Termek::class)->findOneBy(['vonalkod' => $kod]);
            if (!$termek) {
                $termek = $this->getRepo(Termek::class)->findOneBy(['cikkszam' => $kod]);
            }
        }

        if (!$termek) {
            return;
        }

        $partner = $this->getBoltivevo();
        $valutanem = $this->getDefaultValutanem();

        /** @var Afa|null $afa */
        $afa = $termek->getAfa();
        $afakulcs = $afa ? $afa->getErtek() : 0;

        $enetto = $termek->getKedvezmenynelkuliNettoAr($valtozat, $partner, $valutanem);
        $netto = $termek->getNettoAr($valtozat, $partner, $valutanem);
        $kedvezmeny = $termek->getKedvezmeny($partner);
        $brutto = $afa ? $afa->calcBrutto($netto) : $netto;

        $nev = $termek->getKiirtnev() ?: $termek->getNev();
        if ($valtozat && $valtozat->getNev() && trim($valtozat->getNev(), ' -')) {
            $nev .= ' (' . $valtozat->getNev() . ')';
        }
        $cikkszam = ($valtozat && $valtozat->getCikkszam()) ? $valtozat->getCikkszam() : $termek->getCikkszam();

        $view = $this->createView('boltieladastetel.tpl');
        $view->setVar('termekid', $termek->getId());
        $view->setVar('valtozatid', $valtozat ? $valtozat->getId() : 0);
        $view->setVar('afaid', $afa ? $afa->getId() : 0);
        $view->setVar('afakulcs', $afakulcs);
        $view->setVar('nev', $nev);
        $view->setVar('cikkszam', $cikkszam);
        $view->setVar('enettoegysar', number_format((float)$enetto, 2, '.', ''));
        $view->setVar('nettoegysar', number_format((float)$netto, 2, '.', ''));
        $view->setVar('bruttoegysar', number_format((float)$brutto, 2, '.', ''));
        $view->setVar('kedvezmeny', number_format((float)$kedvezmeny, 2, '.', ''));
        echo $view->getTemplateResult();
    }

    /**
     * A teljes bizonylat rögzítése. A partner a bolti vevő, a típus "Bolti eladás", a fejben
     * csak a fizetési mód jön a kliensről, a tételek pedig párhuzamos tömbökben.
     */
    public function save()
    {
        $partner = $this->getBoltivevo();
        if (!$partner) {
            echo json_encode(['ok' => false, 'error' => t('Nincs beállítva bolti vevő a setupban!')]);
            return;
        }

        $termekids = $this->params->getArrayRequestParam('termekid');
        if (!$termekids) {
            echo json_encode(['ok' => false, 'error' => t('Nincs tétel a bizonylaton!')]);
            return;
        }
        $valtozatids = $this->params->getArrayRequestParam('valtozatid');
        $mennyisegek = $this->params->getArrayRequestParam('mennyiseg');
        $kedvezmenyek = $this->params->getArrayRequestParam('kedvezmeny');
        $enettok = $this->params->getArrayRequestParam('enettoegysar');
        $nettok = $this->params->getArrayRequestParam('nettoegysar');
        $bruttok = $this->params->getArrayRequestParam('bruttoegysar');
        $afaids = $this->params->getArrayRequestParam('afaid');

        $biztipus = $this->getRepo(Bizonylattipus::class)->find(self::BIZTIPUS);
        if (!$biztipus) {
            echo json_encode(['ok' => false, 'error' => t('Hiányzik a "Bolti eladás" bizonylattípus!')]);
            return;
        }
        $valutanem = $this->getDefaultValutanem();
        $raktar = $this->getDefaultRaktar();
        $fizmod = $this->getRepo(Fizmod::class)->find($this->params->getIntRequestParam('fizmod'));

        $this->getEm()->beginTransaction();
        try {
            $fej = new Bizonylatfej();
            $fej->setPersistentData();
            $fej->setBizonylattipus($biztipus);
            $fej->setKelt('');
            $fej->setTeljesites('');
            $fej->setEsedekesseg('');
            $fej->setHatarido('');
            $fej->setArfolyam(1);
            $fej->setPartner($partner);
            if ($fizmod) {
                $fej->setFizmod($fizmod);
            }
            if ($valutanem) {
                $fej->setValutanem($valutanem);
                $fej->setBankszamla($valutanem->getBankszamla());
            }
            if ($raktar) {
                $fej->setRaktar($raktar);
            }

            $vantetel = false;
            foreach ($termekids as $i => $termekid) {
                $termek = $this->getRepo(Termek::class)->find((int)$termekid);
                if (!$termek) {
                    continue;
                }
                $valtozat = null;
                if (!empty($valtozatids[$i])) {
                    $valtozat = $this->getRepo(TermekValtozat::class)->find((int)$valtozatids[$i]);
                }

                $t = new Bizonylattetel();
                $fej->addBizonylattetel($t);
                $t->setPersistentData();
                $t->setTermek($termek);
                $t->setTermekvaltozat($valtozat);
                if (!empty($afaids[$i])) {
                    $t->setAfa((int)$afaids[$i]);
                }
                $t->setMennyiseg(isset($mennyisegek[$i]) ? (float)str_replace(',', '.', $mennyisegek[$i]) : 1);
                $t->setKedvezmeny(isset($kedvezmenyek[$i]) ? (float)str_replace(',', '.', $kedvezmenyek[$i]) : 0);
                if (isset($enettok[$i])) {
                    $t->setEnettoegysar((float)str_replace(',', '.', $enettok[$i]));
                    $t->setEnettoegysarhuf((float)str_replace(',', '.', $enettok[$i]));
                }
                if (isset($nettok[$i])) {
                    $t->setNettoegysar((float)str_replace(',', '.', $nettok[$i]));
                }
                if (isset($bruttok[$i])) {
                    $t->setBruttoegysar((float)str_replace(',', '.', $bruttok[$i]));
                }
                $t->setNettoegysarhuf($t->getNettoegysar());
                $t->setBruttoegysarhuf($t->getBruttoegysar());
                $t->calc();
                $this->getEm()->persist($t);
                $vantetel = true;
            }

            if (!$vantetel) {
                $this->getEm()->rollback();
                echo json_encode(['ok' => false, 'error' => t('Nincs érvényes tétel a bizonylaton!')]);
                return;
            }

            $fej->calcOsszesen();
            $this->getEm()->persist($fej);
            $this->getEm()->flush();
            $this->getEm()->commit();
        } catch (\Exception $e) {
            $this->getEm()->rollback();
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
            return;
        }

        echo json_encode(['ok' => true, 'id' => $fej->getId()]);
    }
}
