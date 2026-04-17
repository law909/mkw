<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Arfolyam;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Partner;
use Entities\Raktar;
use Entities\Termek;
use Entities\TermekValtozat;
use mkw\store;

class raktarkeszletnullazoController extends \mkwhelpers\Controller
{

    public function view()
    {
        $view = $this->createView('raktarkeszletnullazo.tpl');
        $view->setVar('pagetitle', t('Raktár készlet nullázás'));

        $raktar = new raktarController($this->params);
        $view->setVar('raktarlist', $raktar->getSelectList());
        $view->setVar('result', null);

        $view->printTemplateResult();
    }

    public function process()
    {
        $view = $this->createView('raktarkeszletnullazo.tpl');
        $view->setVar('pagetitle', t('Raktár készlet nullázás'));

        $raktarid = $this->params->getIntRequestParam('raktar');
        /** @var Raktar $raktar */
        $raktar = $this->getRepo(Raktar::class)->find($raktarid);

        $raktarcontroller = new raktarController($this->params);
        $view->setVar('raktarlist', $raktarcontroller->getSelectList($raktarid));

        if (!$raktar) {
            $view->setVar('result', [
                'success' => false,
                'message' => 'Nincs ilyen raktár.'
            ]);
            $view->printTemplateResult();
            return;
        }

        $em = $this->getEm();
        $conn = $em->getConnection();
        $conn->beginTransaction();
        try {
            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('termek_id', 'termek_id');
            $rsm->addScalarResult('termekvaltozat_id', 'termekvaltozat_id');
            $rsm->addScalarResult('mennyiseg', 'mennyiseg');
            $q = $em->createNativeQuery(
                'SELECT bt.termek_id,bt.termekvaltozat_id, SUM(bt.mennyiseg * bt.irany) AS mennyiseg'
                . ' FROM bizonylattetel bt'
                . ' INNER JOIN bizonylatfej bf ON (bf.id=bt.bizonylatfej_id)'
                . ' WHERE bt.mozgat=1 AND (bt.rontott=0 OR bt.rontott IS NULL) AND bf.raktar_id=:raktarid'
                . ' GROUP BY bt.termek_id,bt.termekvaltozat_id'
                . ' HAVING SUM(bt.mennyiseg * bt.irany) <> 0',
                $rsm
            );
            $q->setParameter('raktarid', $raktar->getId());
            $keszletek = $q->getScalarResult();
            $kellkivet = false;
            $kellbevet = false;
            foreach ($keszletek as $sor) {
                if ($sor['mennyiseg'] > 0) {
                    $kellkivet = true;
                } else {
                    $kellbevet = true;
                }
            }

            $partner = $this->getRepo(Partner::class)->find(\mkw\store::getParameter(\mkw\consts::Tulajpartner));
            if ($kellbevet) {
                $bevet = new Bizonylatfej();
                $bevet->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('bevet'));
                $bevet->setPersistentData();
                $bevet->setPartner($partner);
                $bevet->setSzallitasimod($partner->getSzallitasimod());
                $bevet->setKelt();
                $bevet->setTeljesites();
                $bevet->setEsedekesseg();
                $arf = $this->getEm()->getRepository(Arfolyam::class)->getActualArfolyam($partner->getValutanem(), $bevet->getTeljesites());
                $bevet->setArfolyam($arf->getArfolyam());
                $bevet->setRaktar($raktar);
                $bevet->setBelsomegjegyzes('Raktár készlet nullázás - automatikus bevét');
                $em->persist($bevet);
            }

            if ($kellkivet) {
                $kivet = new Bizonylatfej();
                $kivet->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('kivet'));
                $kivet->setPersistentData();
                $kivet->setPartner($partner);
                $kivet->setSzallitasimod($partner->getSzallitasimod());
                $kivet->setKelt();
                $kivet->setTeljesites();
                $kivet->setEsedekesseg();
                $arf = $this->getEm()->getRepository(Arfolyam::class)->getActualArfolyam($partner->getValutanem(), $kivet->getTeljesites());
                $kivet->setArfolyam($arf->getArfolyam());
                $kivet->setRaktar($raktar);
                $kivet->setBelsomegjegyzes('Raktár készlet nullázás - automatikus kivét');
                $em->persist($kivet);
            }

            $bevetdb = 0;
            $kivetdb = 0;
            foreach ($keszletek as $sor) {
                $mennyiseg = (float)$sor['mennyiseg'];
                /** @var Termek $termek */
                $termek = $em->getRepository(Termek::class)->find($sor['termek_id']);
                /** @var TermekValtozat $valtozat */
                $valtozat = $em->getRepository(TermekValtozat::class)->find($sor['termekvaltozat_id']);
                if (!$termek) {
                    continue;
                }
                if ($mennyiseg > 0) {
                    $tetel = new Bizonylattetel();
                    $tetel->setBizonylatfej($kivet);
                    $tetel->setPersistentData();
                    $tetel->setTermek($termek);
                    $tetel->setTermekvaltozat($valtozat);
                    $tetel->setMennyiseg($mennyiseg);
                    $tetel->fillEgysar();
                    $tetel->calc();
                    $em->persist($tetel);
                    $kivetdb++;
                } else {
                    $tetel = new Bizonylattetel();
                    $tetel->setBizonylatfej($bevet);
                    $tetel->setPersistentData();
                    $tetel->setTermek($termek);
                    $tetel->setTermekvaltozat($valtozat);
                    $tetel->setMennyiseg(abs($mennyiseg));
                    $tetel->fillEgysar();
                    $tetel->calc();
                    $em->persist($tetel);
                    $bevetdb++;
                }
            }

            if (!$bevetdb && !$kivetdb) {
                $conn->rollBack();
                $view->setVar('result', [
                    'success' => true,
                    'message' => 'A kiválasztott raktárban nincs nullázandó termékváltozat készlet.'
                ]);
                $view->printTemplateResult();
                return;
            }

            $em->flush();
            $conn->commit();

            $view->setVar('result', [
                'success' => true,
                'message' => 'Készlet nullázás kész.',
                'bevetid' => $bevet?->getId(),
                'kivetid' => $kivet?->getId(),
                'bevetdb' => $bevetdb,
                'kivetdb' => $kivetdb
            ]);
            $view->printTemplateResult();
        } catch (\Exception $e) {
            $conn->rollBack();
            $view->setVar('result', [
                'success' => false,
                'message' => $e->getMessage()
            ]);
            $view->printTemplateResult();
        }
    }
}
