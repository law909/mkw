<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\Jogaterem;
use Entities\MPTNGYSzakmaianyag;
use Entities\MPTNGYSzakmaianyagtipus;
use Entities\MPTNGYTema;
use Entities\MPTNGYTemakor;
use Entities\Partner;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class mptngyszakmaianyagController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(MPTNGYSzakmaianyag::class);
        $this->setKarbFormTplName('mptngyszakmaianyagkarbform.tpl');
        $this->setKarbTplName('mptngyszakmaianyagkarb.tpl');
        $this->setListBodyRowTplName('mptngyszakmaianyaglista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    /**
     * @param MPTNGYSzakmaianyag $t
     * @param $forKarb
     *
     * @return array
     */
    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\MPTNGYSzakmaianyag();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();
        $x['tulajdonos'] = $t->getTulajdonos()?->getId();
        $x['tulajdonosnev'] = $t->getTulajdonos()?->getNev();
        $x['opponens'] = $t->getOpponens()?->getId();
        $x['opponensnev'] = $t->getOpponens()?->getNev();
        $x['szerzo1'] = $t->getSzerzo1()?->getId();
        $x['szerzo1nev'] = $t->getSzerzo1()?->getNev();
        $x['szerzo2'] = $t->getSzerzo2()?->getId();
        $x['szerzo2nev'] = $t->getSzerzo2()?->getNev();
        $x['szerzo3'] = $t->getSzerzo3()?->getId();
        $x['szerzo3nev'] = $t->getSzerzo3()?->getNev();
        $x['szerzo4'] = $t->getSzerzo4()?->getId();
        $x['szerzo4nev'] = $t->getSzerzo4()?->getNev();
        $x['szerzo5'] = $t->getSzerzo5()?->getId();
        $x['szerzo5nev'] = $t->getSzerzo5()?->getNev();
        $x['szerzo6'] = $t->getSzerzo6()?->getId();
        $x['szerzo6nev'] = $t->getSzerzo6()?->getNev();
        $x['szerzo7'] = $t->getSzerzo7()?->getId();
        $x['szerzo7nev'] = $t->getSzerzo7()?->getNev();
        $x['szerzo8'] = $t->getSzerzo8()?->getId();
        $x['szerzo8nev'] = $t->getSzerzo8()?->getNev();
        $x['szerzo9'] = $t->getSzerzo9()?->getId();
        $x['szerzo9nev'] = $t->getSzerzo9()?->getNev();
        $x['szerzo10'] = $t->getSzerzo10()?->getId();
        $x['szerzo10nev'] = $t->getSzerzo10()?->getNev();
        $x['beszelgetopartner'] = $t->getBeszelgetopartner()?->getId();
        $x['beszelgetopartnernev'] = $t->getBeszelgetopartner()?->getNev();
        $x['kezdodatumstr'] = $t->getKezdodatumStr();
        $x['tipus'] = $t->getTipus()?->getId();
        $x['tipusnev'] = $t->getTipus()?->getNev();
        $x['eloadas1'] = $t->getEloadas1()?->getId();
        $x['eloadas1cim'] = $t->getEloadas1() ? '1. ' . $t->getEloadas1()?->getCim() : '';
        $x['eloadas2'] = $t->getEloadas2()?->getId();
        $x['eloadas2cim'] = $t->getEloadas2() ? '2. ' . $t->getEloadas2()?->getCim() : '';
        $x['eloadas3'] = $t->getEloadas3()?->getId();
        $x['eloadas3cim'] = $t->getEloadas3() ? '3. ' . $t->getEloadas3()?->getCim() : '';
        $x['eloadas4'] = $t->getEloadas4()?->getId();
        $x['eloadas4cim'] = $t->getEloadas4() ? '4. ' . $t->getEloadas4()?->getCim() : '';
        $x['eloadas5'] = $t->getEloadas5()?->getId();
        $x['eloadas5cim'] = $t->getEloadas5() ? '5. ' . $t->getEloadas5()?->getCim() : '';
        $x['biralo1'] = $t->getBiralo1()?->getId();
        $x['biralo1nev'] = $t->getBiralo1()?->getNev();
        $x['biralo2'] = $t->getBiralo2()?->getId();
        $x['biralo2nev'] = $t->getBiralo2()?->getNev();
        $x['biralo3'] = $t->getBiralo3()?->getId();
        $x['biralo3nev'] = $t->getBiralo3()?->getNev();
        $x['allszerzoregistered'] = $t->isAllSzerzoRegistered();
        $x['szerzo1registered'] = $t->isSzerzoRegistered(1);
        $x['szerzo2registered'] = $t->isSzerzoRegistered(2);
        $x['szerzo3registered'] = $t->isSzerzoRegistered(3);
        $x['szerzo4registered'] = $t->isSzerzoRegistered(4);
        $x['szerzo5registered'] = $t->isSzerzoRegistered(5);
        $x['szerzo6registered'] = $t->isSzerzoRegistered(6);
        $x['szerzo7registered'] = $t->isSzerzoRegistered(7);
        $x['szerzo8registered'] = $t->isSzerzoRegistered(8);
        $x['szerzo9registered'] = $t->isSzerzoRegistered(9);
        $x['szerzo10registered'] = $t->isSzerzoRegistered(10);
        $x['beszelgetopartnerregistered'] = $t->isSzerzoRegistered(0);
        $x['opponensregistered'] = $t->isSzerzoRegistered(-1);
        $x['szimpozium'] = ($t->getTipus()?->getId() == \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumTipus));
        $x['konyvbemutato'] = ($t->getTipus()?->getId() == \mkw\store::getParameter(\mkw\consts::MPTNGYKonyvbemutatoTipus));
        $x['temakor1'] = $t->getTemakor1()?->getId();
        $x['temakor1nev'] = $t->getTemakor1()?->getNev();
        $x['temakor2'] = $t->getTemakor2()?->getId();
        $x['temakor2nev'] = $t->getTemakor2()?->getNev();
        $x['temakor3'] = $t->getTemakor3()?->getId();
        $x['temakor3nev'] = $t->getTemakor3()?->getNev();

        $x['biralo1pont'] = $t->calcB1pont();
        $x['biralo2pont'] = $t->calcB2pont();
        $x['biralo3pont'] = $t->calcB3pont();
        $x['osszespont'] = $t->calcPont();

        $x['terem'] = $t->getTerem()?->getId();
        $x['teremnev'] = $t->getTerem()?->getNev();

        $x['tema'] = $t->getTema()?->getId();
        $x['temanev'] = $t->getTema()?->getNev();
        return $x;
    }

    /**
     * @param \Entities\MPTNGYSzakmaianyag $obj
     *
     * @return \Entities\MPTNGYSzakmaianyag
     */
    public function setFields($obj, $oper, $pub = false)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);
        if (!$pub) {
            $obj->setBiralatkesz($this->params->getBoolRequestParam('biralatkesz'));
            $obj->setKonferencianszerepelhet($this->params->getBoolRequestParam('konferencianszerepelhet'));
            $obj->setVegleges($this->params->getBoolRequestParam('vegleges'));
            $obj->setB1biralatkesz($this->params->getBoolRequestParam('b1biralatkesz'));
            $obj->setB2biralatkesz($this->params->getBoolRequestParam('b2biralatkesz'));
            $obj->setB3biralatkesz($this->params->getBoolRequestParam('b3biralatkesz'));
            $obj->setSzerzo1email($this->params->getStringRequestParam('szerzo1email'));
            $obj->setSzerzo2email($this->params->getStringRequestParam('szerzo2email'));
            $obj->setSzerzo3email($this->params->getStringRequestParam('szerzo3email'));
            $obj->setSzerzo4email($this->params->getStringRequestParam('szerzo4email'));
            $obj->setSzerzo5email($this->params->getStringRequestParam('szerzo5email'));
            $obj->setSzerzo6email($this->params->getStringRequestParam('szerzo6email'));
            $obj->setSzerzo7email($this->params->getStringRequestParam('szerzo7email'));
            $obj->setSzerzo8email($this->params->getStringRequestParam('szerzo8email'));
            $obj->setSzerzo9email($this->params->getStringRequestParam('szerzo9email'));
            $obj->setSzerzo10email($this->params->getStringRequestParam('szerzo10email'));
            $obj->setOpponensemail($this->params->getStringRequestParam('opponensemail'));
            $obj->setBeszelgetopartneremail($this->params->getStringRequestParam('beszelgetopartneremail'));
        } elseif ($this->params->getBoolRequestParam('vegleges')) {
            $obj->setVegleges($this->params->getBoolRequestParam('vegleges'));
        }

        $tipus = \mkw\store::getEm()->getRepository(MPTNGYSzakmaianyagtipus::class)->find($this->params->getIntRequestParam('tipus'));
        if ($tipus) {
            $obj->setTipus($tipus);
        } else {
            $obj->removeTipus();
        }

        if (!$pub) {
            $terem = \mkw\store::getEm()->getRepository(Jogaterem::class)->find($this->params->getIntRequestParam('terem'));
            if ($terem) {
                $obj->setTerem($terem);
            } else {
                $obj->removeTerem();
            }

            $tema = \mkw\store::getEm()->getRepository(MPTNGYTema::class)->find($this->params->getIntRequestParam('tema'));
            if ($tema) {
                $obj->setTema($tema);
            } else {
                $obj->removeTema();
            }

            $tulaj = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('tulajdonos'));
            if ($tulaj) {
                $obj->setTulajdonos($tulaj);
            } else {
                $obj->removeTulajdonos();
            }

            $opponens = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('opponens'));
            if ($opponens) {
                $obj->setOpponens($opponens);
            } else {
                $obj->removeOpponens();
            }
            $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo1'));
            if ($szerzo) {
                $obj->setSzerzo1($szerzo);
            } else {
                $obj->removeSzerzo1();
            }
            $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo2'));
            if ($szerzo) {
                $obj->setSzerzo2($szerzo);
            } else {
                $obj->removeSzerzo2();
            }
            $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo3'));
            if ($szerzo) {
                $obj->setSzerzo3($szerzo);
            } else {
                $obj->removeSzerzo3();
            }
            $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo4'));
            if ($szerzo) {
                $obj->setSzerzo4($szerzo);
            } else {
                $obj->removeSzerzo4();
            }
            $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo5'));
            if ($szerzo) {
                $obj->setSzerzo5($szerzo);
            } else {
                $obj->removeSzerzo5();
            }
            $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo6'));
            if ($szerzo) {
                $obj->setSzerzo6($szerzo);
            } else {
                $obj->removeSzerzo6();
            }
            $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo7'));
            if ($szerzo) {
                $obj->setSzerzo7($szerzo);
            } else {
                $obj->removeSzerzo7();
            }
            $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo8'));
            if ($szerzo) {
                $obj->setSzerzo8($szerzo);
            } else {
                $obj->removeSzerzo8();
            }
            $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo9'));
            if ($szerzo) {
                $obj->setSzerzo9($szerzo);
            } else {
                $obj->removeSzerzo9();
            }
            $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo10'));
            if ($szerzo) {
                $obj->setSzerzo10($szerzo);
            } else {
                $obj->removeSzerzo10();
            }

            $bp = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('beszelgetopartner'));
            if ($bp) {
                $obj->setBeszelgetopartner($bp);
            } else {
                $obj->removeBeszelgetopartner();
            }

            $biralo = \mkw\store::getEm()->getRepository(\Entities\Dolgozo::class)->find($this->params->getIntRequestParam('biralo1'));
            if ($biralo) {
                if (
                    ($biralo->getEmail() != $obj->getSzerzo1email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo2email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo3email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo4email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo5email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo6email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo7email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo8email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo9email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo10email()) &&
                    ($biralo->getEmail() != $obj->getOpponensemail()) &&
                    ($biralo->getEmail() != $obj->getBeszelgetopartneremail())
                ) {
                    $obj->setBiralo1($biralo);
                }
            } else {
                $obj->removeBiralo1();
            }
            $biralo = \mkw\store::getEm()->getRepository(\Entities\Dolgozo::class)->find($this->params->getIntRequestParam('biralo2'));
            if ($biralo) {
                if (
                    ($biralo->getEmail() != $obj->getSzerzo1email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo2email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo3email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo4email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo5email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo6email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo7email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo8email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo9email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo10email()) &&
                    ($biralo->getEmail() != $obj->getOpponensemail()) &&
                    ($biralo->getEmail() != $obj->getBeszelgetopartneremail())
                ) {
                    $obj->setBiralo2($biralo);
                }
            } else {
                $obj->removeBiralo2();
            }
            $biralo = \mkw\store::getEm()->getRepository(\Entities\Dolgozo::class)->find($this->params->getIntRequestParam('biralo3'));
            if ($biralo) {
                if (
                    ($biralo->getEmail() != $obj->getSzerzo1email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo2email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo3email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo4email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo5email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo6email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo7email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo8email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo9email()) &&
                    ($biralo->getEmail() != $obj->getSzerzo10email()) &&
                    ($biralo->getEmail() != $obj->getOpponensemail()) &&
                    ($biralo->getEmail() != $obj->getBeszelgetopartneremail())
                ) {
                    $obj->setBiralo3($biralo);
                }
            } else {
                $obj->removeBiralo3();
            }
        }

        $eloadas = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->find($this->params->getIntRequestParam('eloadas1'));
        if ($eloadas) {
            $obj->setEloadas1($eloadas);
        } else {
            $obj->removeEloadas1();
        }
        $eloadas = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->find($this->params->getIntRequestParam('eloadas2'));
        if ($eloadas) {
            $obj->setEloadas2($eloadas);
        } else {
            $obj->removeEloadas2();
        }
        $eloadas = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->find($this->params->getIntRequestParam('eloadas3'));
        if ($eloadas) {
            $obj->setEloadas3($eloadas);
        } else {
            $obj->removeEloadas3();
        }
        $eloadas = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->find($this->params->getIntRequestParam('eloadas4'));
        if ($eloadas) {
            $obj->setEloadas4($eloadas);
        } else {
            $obj->removeEloadas4();
        }
        $eloadas = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->find($this->params->getIntRequestParam('eloadas5'));
        if ($eloadas) {
            $obj->setEloadas5($eloadas);
        } else {
            $obj->removeEloadas5();
        }

        $temakor = \mkw\store::getEm()->getRepository(MPTNGYTemakor::class)->find($this->params->getIntRequestParam('temakor1'));
        if ($temakor) {
            $obj->setTemakor1($temakor);
        } else {
            $obj->removeTemakor1();
        }

        $temakor = \mkw\store::getEm()->getRepository(MPTNGYTemakor::class)->find($this->params->getIntRequestParam('temakor2'));
        if ($temakor) {
            $obj->setTemakor2($temakor);
        } else {
            $obj->removeTemakor2();
        }

        $temakor = \mkw\store::getEm()->getRepository(MPTNGYTemakor::class)->find($this->params->getIntRequestParam('temakor3'));
        if ($temakor) {
            $obj->setTemakor3($temakor);
        } else {
            $obj->removeTemakor3();
        }

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('mptngyszakmaianyaglista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('idfilter', null))) {
            $filter->addFilter('id', '=', $this->params->getIntRequestParam('idfilter'));
        }

        if (!is_null($this->params->getRequestParam('cimfilter', null))) {
            $fv = $this->params->getStringRequestParam('cimfilter');
            $filter->addFilter('cim', 'LIKE', '%' . $fv . '%');
        }

        if (!is_null($this->params->getRequestParam('tulajdonosfilter', null))) {
            $filter->addFilter('tulajdonos', '=', $this->params->getIntRequestParam('tulajdonosfilter'));
        }

        if (!is_null($this->params->getRequestParam('elsoszerzofilter', null))) {
            $filter->addFilter('szerzo1', '=', $this->params->getIntRequestParam('elsoszerzofilter'));
        }

        if (!is_null($this->params->getRequestParam('szerzofilter', null))) {
            $filter->addFilter(['szerzo2', 'szerzo3', 'szerzo4', 'szerzo5', 'szerzo6', 'szerzo7', 'szerzo8', 'szerzo9', 'szerzo10'],
                '=',
                $this->params->getIntRequestParam('szerzofilter'));
        }

        if (!is_null($this->params->getRequestParam('opponensfilter', null))) {
            $filter->addFilter('opponens', '=', $this->params->getIntRequestParam('opponensfilter'));
        }

        $f = $this->params->getNumRequestParam('bekuldvefilter', 9);
        if ($f != 9) {
            $filter->addFilter('vegleges', '=', $f);
        }

        $f = $this->params->getNumRequestParam('biralatkeszfilter', 9);
        if ($f != 9) {
            $filter->addFilter('biralatkesz', '=', $f);
        }

        $f = $this->params->getNumRequestParam('konferencianszerepelhetfilter', 9);
        if ($f != 9) {
            $filter->addFilter('konferencianszerepelhet', '=', $f);
        }

        $f = $this->params->getNumRequestParam('elsobiralokellfilter', 9);
        switch ($f) {
            case 1:
                $filter->addSql('((_xx.biralo1 IS NOT NULL) AND (_xx.biralo1<>0))');
                break;
            case 0:
                $filter->addSql('((_xx.biralo1 IS NULL) OR (_xx.biralo1=0))');
                break;
        }

        $f = $this->params->getNumRequestParam('masodikbiralokellfilter', 9);
        switch ($f) {
            case 1:
                $filter->addSql('((_xx.biralo2 IS NOT NULL) AND (_xx.biralo2<>0))');
                break;
            case 0:
                $filter->addSql('((_xx.biralo2 IS NULL) OR (_xx.biralo2=0))');
                break;
        }

        $f = $this->params->getNumRequestParam('pluszbiralokellfilter', 9);
        if ($f != 9) {
            $filter->addFilter('pluszbiralokell', '=', $f);
        }

        if (!is_null($this->params->getRequestParam('temakor1filter', null))) {
            $filter->addSql(
                '((_xx.temakor1=' . $this->params->getIntRequestParam('temakor1filter') . ') OR ' .
                '(_xx.temakor2=' . $this->params->getIntRequestParam('temakor1filter') . ') OR ' .
                '(_xx.temakor3=' . $this->params->getIntRequestParam('temakor1filter') . '))'
            );
        }

        if (!is_null($this->params->getRequestParam('tipusfilter', null))) {
            $filter->addFilter('tipus', '=', $this->params->getIntRequestParam('tipusfilter'));
        }

        if (!is_null($this->params->getRequestParam('teremfilter', null))) {
            $filter->addFilter('terem', '=', $this->params->getIntRequestParam('teremfilter'));
        }

        if (!is_null($this->params->getRequestParam('temafilter', null))) {
            $filter->addFilter('tema', '=', $this->params->getIntRequestParam('temafilter'));
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('mptngyszakmaianyaglista.tpl');

        $view->setVar('pagetitle', t('Szakmai anyagok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());

        $pc = new partnerController();
        $view->setVar('tulajdonoslist', $pc->getSelectList());
        $view->setVar('elsoszerzolist', $pc->getSelectList());
        $view->setVar('szerzolist', $pc->getSelectList());
        $view->setVar('opponenslist', $pc->getSelectList());
        $tk = new mptngytemakorController();
        $view->setVar('temakor1list', $tk->getSelectList());
        $tc = new mptngyszakmaianyagtipusController();
        $view->setVar('tipuslist', $tc->getSelectList());
        $jt = new jogateremController();
        $view->setVar('teremlist', $jt->getSelectList());
        $tx = new mptngytemaController();
        $view->setVar('temalist', $tx->getSelectList());

        $view->printTemplateResult();
    }

    public function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Szakmai anyag'));
        $view->setVar('oper', $oper);

        /** @var MPTNGYSzakmaianyag $anyag */
        $anyag = $this->getRepo()->findWithJoins($id);
        // loadVars utan nem abc sorrendben adja vissza
        $tc = new mptngyszakmaianyagtipusController();
        $view->setVar('tipuslist', $tc->getSelectList($anyag?->getTipus()?->getId()));

        $jt = new jogateremController();
        $view->setVar('teremlist', $jt->getSelectList($anyag?->getTerem()?->getId()));

        $xt = new mptngytemaController();
        $view->setVar('temalist', $xt->getSelectList($anyag?->getTema()?->getId()));

        $pc = new partnerController();
        $view->setVar('tulajdonoslist', $pc->getSelectList($anyag?->getTulajdonos()?->getId()));
        $view->setVar('opponenslist', $pc->getSelectList($anyag?->getOpponens()?->getId()));
        $view->setVar('szerzo1list', $pc->getSelectList($anyag?->getSzerzo1()?->getId()));
        $view->setVar('szerzo2list', $pc->getSelectList($anyag?->getSzerzo2()?->getId()));
        $view->setVar('szerzo3list', $pc->getSelectList($anyag?->getSzerzo3()?->getId()));
        $view->setVar('szerzo4list', $pc->getSelectList($anyag?->getSzerzo4()?->getId()));
        $view->setVar('szerzo5list', $pc->getSelectList($anyag?->getSzerzo5()?->getId()));
        $view->setVar('szerzo6list', $pc->getSelectList($anyag?->getSzerzo6()?->getId()));
        $view->setVar('szerzo7list', $pc->getSelectList($anyag?->getSzerzo7()?->getId()));
        $view->setVar('szerzo8list', $pc->getSelectList($anyag?->getSzerzo8()?->getId()));
        $view->setVar('szerzo9list', $pc->getSelectList($anyag?->getSzerzo9()?->getId()));
        $view->setVar('szerzo10list', $pc->getSelectList($anyag?->getSzerzo10()?->getId()));
        $view->setVar('beszelgetopartnerlist', $pc->getSelectList($anyag?->getBeszelgetopartner()?->getId()));

        $ac = new mptngyszakmaianyagController();
        $view->setVar('eloadas1list', $ac->getSelectList($anyag?->getEloadas1()?->getId()));
        $view->setVar('eloadas2list', $ac->getSelectList($anyag?->getEloadas2()?->getId()));
        $view->setVar('eloadas3list', $ac->getSelectList($anyag?->getEloadas3()?->getId()));
        $view->setVar('eloadas4list', $ac->getSelectList($anyag?->getEloadas4()?->getId()));
        $view->setVar('eloadas5list', $ac->getSelectList($anyag?->getEloadas5()?->getId()));

        $bc = new dolgozoController();
        // biralo temakore kozt szerepel temakor1,2,3
        $biraloids = [];
        $tkor = $this->getRepo(MPTNGYTemakor::class)->find($anyag?->getTemakor1()?->getId());
        if ($tkor) {
            foreach ($tkor->getDolgozok() as $dolg) {
                $biraloids[$dolg->getId()] = $dolg->getId();
            }
        }
        $tkor = $this->getRepo(MPTNGYTemakor::class)->find($anyag?->getTemakor2()?->getId());
        if ($tkor) {
            foreach ($tkor->getDolgozok() as $dolg) {
                $biraloids[$dolg->getId()] = $dolg->getId();
            }
        }
        $tkor = $this->getRepo(MPTNGYTemakor::class)->find($anyag?->getTemakor3()?->getId());
        if ($tkor) {
            foreach ($tkor->getDolgozok() as $dolg) {
                $biraloids[$dolg->getId()] = $dolg->getId();
            }
        }
        // biralo nem erte el a max vallaltat
        $vbiraloids = [];
        $birafilter = new FilterDescriptor();
        foreach ($biraloids as $bid) {
            /** @var Dolgozo $bira */
            $bira = $this->getRepo(Dolgozo::class)->find($bid);
            $birafilter->clear();
            $birafilter->addSql("((_xx.biralo1 = $bid) OR (_xx.biralo2 = $bid) OR (_xx.biralo3 = $bid))");
            $birafilter->addFilter('id', '<>', $anyag->getId());
            $cnt = $this->getRepo(MPTNGYSzakmaianyag::class)->getCount($birafilter);
            if ($anyag?->getBiralo1()?->getId() == $bid || $anyag?->getBiralo2()?->getId() == $bid || $anyag?->getBiralo3()?->getId() == $bid) {
                $cnt--;
            }
            if ($bira->getMptngymaxdb() > $cnt) {
                $vbiraloids[$bid] = $bid;
            }
        }
        $birafilter->clear();
        if ($vbiraloids) {
            $birafilter->addFilter('_xx.id', 'IN', $vbiraloids);
        } else {
            $birafilter->addSql('(1=0)');
        }
        $view->setVar('biralo1list', $bc->getSelectList($anyag?->getBiralo1()?->getId(), true, $birafilter));
        $view->setVar('biralo2list', $bc->getSelectList($anyag?->getBiralo2()?->getId(), true, $birafilter));
        $view->setVar('biralo3list', $bc->getSelectList($anyag?->getBiralo3()?->getId(), true, $birafilter));

        $tk = new mptngytemakorController();
        $view->setVar('temakor1list', $tk->getSelectList($anyag?->getTemakor1()?->getId()));
        $view->setVar('temakor2list', $tk->getSelectList($anyag?->getTemakor2()?->getId()));
        $view->setVar('temakor3list', $tk->getSelectList($anyag?->getTemakor3()?->getId()));

        $view->setVar('datumlist', \mkw\store::getMPTNGYDateList($anyag?->getKezdodatum()));

        $view->setVar('szempont1nev', \mkw\store::getParameter(\mkw\consts::MPTNGYSzempont1));
        $view->setVar('szempont2nev', \mkw\store::getParameter(\mkw\consts::MPTNGYSzempont2));
        $view->setVar('szempont3nev', \mkw\store::getParameter(\mkw\consts::MPTNGYSzempont3));
        $view->setVar('szempont4nev', \mkw\store::getParameter(\mkw\consts::MPTNGYSzempont4));
        $view->setVar('szempont5nev', \mkw\store::getParameter(\mkw\consts::MPTNGYSzempont5));

        $view->setVar('egyed', $this->loadVars($anyag, true));
        $view->printTemplateResult();
    }

    /**
     * @param $selid
     * @param FilterDescriptor | array $filter
     *
     * @return array
     */
    public function getSelectList($selid = null, $filter = [])
    {
        $f = new FilterDescriptor();
        $rec = $this->getRepo()->getAll($f, ['cim' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getCim(),
                'selected' => ($sor->getId() == $selid)
            ];
        }
        return $res;
    }

    public function showSzakmaianyagok()
    {
        if ($this->getRepo(Partner::class)->checkloggedin()) {
            $v = $this->createMainView('anyaglist.tpl');
            $v->printTemplateResult();
        } else {
            $pc = new partnerController();
            $pc->showLoginForm();
        }
    }

    public function getAnyagList()
    {
        $res = [];
        $partner = $this->getRepo(Partner::class)->getLoggedInUser();
        if ($partner) {
            $filter = new FilterDescriptor();
            $filter->addSql(
                '(_xx.tulajdonos=' . $partner->getId() . ') OR ' .
                '(_xx.opponens=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo1=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo2=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo3=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo4=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo5=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo6=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo7=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo8=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo9=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo10=' . $partner->getId() . ') OR ' .
                '(_xx.beszelgetopartner=' . $partner->getId() . ')'
            );

            $anyagok = $this->getRepo()->getAll($filter);
            foreach ($anyagok as $anyag) {
                $x = $this->loadVars($anyag);
                $x['editable'] = $anyag->getTulajdonosId() === $partner->getId();
                $res[] = $x;
            }
        }
        echo json_encode($res);
    }

    public function getSajatAnyagList()
    {
        $res = [];
        $partner = $this->getRepo(Partner::class)->getLoggedInUser();
        if ($partner) {
            $filter = new FilterDescriptor();
            $filter->addFilter('tulajdonos', '=', $partner);
            $filter->addFilter('tipus', '=', \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumEloadasTipus));
            $filter->addFilter('vegleges', '=', true);

            $anyagok = $this->getRepo()->getAll($filter);
            /** @var MPTNGYSzakmaianyag $anyag */
            foreach ($anyagok as $anyag) {
                $x = $this->loadVars($anyag);
                $x['editable'] = $anyag->getTulajdonos()?->getId() === $partner->getId();
                $res[] = $x;
            }
        }
        echo json_encode($res);
    }

    public function getBiralandoAnyagList()
    {
        $res = [];
        $dolg = $this->getRepo(Dolgozo::class)->getLoggedInUser();
        if ($dolg) {
            $did = $dolg->getId();
            $filter = new FilterDescriptor();
            $filter->addSql("((_xx.biralo1 = $did) OR (_xx.biralo2 = $did) OR (_xx.biralo3 = $did))");
            $filter->addFilter('vegleges', '=', true);
            $anyagok = $this->getRepo()->getAll($filter);
            /** @var MPTNGYSzakmaianyag $anyag */
            foreach ($anyagok as $anyag) {
                $x = $this->loadVars($anyag);
                switch (true) {
                    case $anyag->getBiralo1()?->getId() == $did:
                        $x['biralosorszam'] = 1;
                        break;
                    case $anyag->getBiralo2()?->getId() == $did:
                        $x['biralosorszam'] = 2;
                        break;
                    case $anyag->getBiralo3()?->getId() == $did:
                        $x['biralosorszam'] = 3;
                        break;
                    default:
                        $x['biralosorszam'] = 0;
                        break;
                }
                $res[] = $x;
            }
        }
        echo json_encode($res);
    }

    public function getDatumList()
    {
        echo json_encode(\mkw\store::getMPTNGYDateList());
    }

    protected function setSzerzoByEmail($obj, $num)
    {
        $email = $this->params->getStringRequestParam("szerzo{$num}email");
        $g = "getSzerzo{$num}email";
        $go = "getSzerzo{$num}";
        $s = "setSzerzo{$num}";
        $se = "setSzerzo{$num}email";
        /* Beszelgetopartner */
        if ($num === 0) {
            $email = $this->params->getStringRequestParam('beszelgetopartneremail');
            $g = 'getBeszelgetopartneremail';
            $go = 'getBeszelgetopartner';
            $s = 'setBeszelgetopartner';
            $se = 'setBeszelgetopartneremail';
        }
        /* Opponens */
        if ($num === -1) {
            $email = $this->params->getStringRequestParam('opponensemail');
            $g = 'getOpponensemail';
            $go = 'getOpponens';
            $s = 'setOpponens';
            $se = 'setOpponensemail';
        }

        $obj->$se($email);
        $obj->$s($this->getRepo(Partner::class)->findOneBy(['email' => $email]));
        return $obj;
    }

    public function pubSave()
    {
        $ret = ['success' => false];
        $partner = $this->getRepo(Partner::class)->getLoggedInUser();
        $anyagid = $this->params->getIntRequestParam('id');
        if ($partner) {
            if ($this->params->getBoolRequestParam('vegleges')) {
                $pc = new mptngypartnerController();
                $ell = $pc->getPartnerInfoForCheck($partner, $anyagid);
            } else {
                $ell['success'] = true;
            }

            if ($ell) {
                if ($ell['success']) {
                    $ret['success'] = true;
                    $anyag = null;
                    $oper = '';
                    if ($anyagid) {
                        $anyag = $this->getRepo()->find($anyagid);
                        $oper = 'edit';
                    }
                    if (!$anyag) {
                        $anyag = new MPTNGYSzakmaianyag();
                        $oper = 'add';
                    }
                    $anyag = $this->setFields($anyag, $oper, true);
                    $anyag = $this->setSzerzoByEmail($anyag, 0); // beszelgetopartner
                    $anyag = $this->setSzerzoByEmail($anyag, -1); // opponens
                    $anyag = $this->setSzerzoByEmail($anyag, 1);
                    $anyag = $this->setSzerzoByEmail($anyag, 2);
                    $anyag = $this->setSzerzoByEmail($anyag, 3);
                    $anyag = $this->setSzerzoByEmail($anyag, 4);
                    $anyag = $this->setSzerzoByEmail($anyag, 5);
                    $anyag = $this->setSzerzoByEmail($anyag, 6);
                    $anyag = $this->setSzerzoByEmail($anyag, 7);
                    $anyag = $this->setSzerzoByEmail($anyag, 8);
                    $anyag = $this->setSzerzoByEmail($anyag, 9);
                    $anyag = $this->setSzerzoByEmail($anyag, 10);
                    $anyag->setTulajdonos($partner);
                    $this->getEm()->persist($anyag);
                    $this->getEm()->flush();
                } else {
                    $msg = [];
                    if (!$ell['elsoszerzo']) {
                        $ret['fields']['szerzo1email'] = [
                            'valid' => false,
                            'error' => t('Maximum egy anyagnak lehet az első szerzője')
                        ];
                    }
                    if (!$ell['szimpoziumelnok']) {
                        $ret['fields']['tulajdonosnev'] = [
                            'valid' => false,
                            'error' => t('Maximum egy szimpóziumnak lehet az elnöke')
                        ];
                    }
                    if (!$ell['opponens']) {
                        $ret['fields']['opponensemail'] = [
                            'valid' => false,
                            'error' => t('Maximum egy szimpóziumnak lehet az opponense')
                        ];
                    }
                    if (!$ell['szerzo2']) {
                        $ret['fields']['szerzo2email'] = [
                            'valid' => false,
                            'error' => t('Maximum öt anyagnak lehet a szerzője')
                        ];
                    }
                    if (!$ell['szerzo3']) {
                        $ret['fields']['szerzo3email'] = [
                            'valid' => false,
                            'error' => t('Maximum öt anyagnak lehet a szerzője')
                        ];
                    }
                    if (!$ell['szerzo4']) {
                        $ret['fields']['szerzo4email'] = [
                            'valid' => false,
                            'error' => t('Maximum öt anyagnak lehet a szerzője')
                        ];
                    }
                    if (!$ell['szerzo5']) {
                        $ret['fields']['szerzo5email'] = [
                            'valid' => false,
                            'error' => t('Maximum öt anyagnak lehet a szerzője')
                        ];
                    }
                    if (!$ell['szerzo6']) {
                        $ret['fields']['szerzo6email'] = [
                            'valid' => false,
                            'error' => t('Maximum öt anyagnak lehet a szerzője')
                        ];
                    }
                    if (!$ell['szerzo7']) {
                        $ret['fields']['szerzo7email'] = [
                            'valid' => false,
                            'error' => t('Maximum öt anyagnak lehet a szerzője')
                        ];
                    }
                    if (!$ell['szerzo8']) {
                        $ret['fields']['szerzo8email'] = [
                            'valid' => false,
                            'error' => t('Maximum öt anyagnak lehet a szerzője')
                        ];
                    }
                    if (!$ell['szerzo9']) {
                        $ret['fields']['szerzo9email'] = [
                            'valid' => false,
                            'error' => t('Maximum öt anyagnak lehet a szerzője')
                        ];
                    }
                    if (!$ell['szerzo10']) {
                        $ret['fields']['szerzo10email'] = [
                            'valid' => false,
                            'error' => t('Maximum öt anyagnak lehet a szerzője')
                        ];
                    }
                    if (!$ell['opponensszerzo']) {
                        $ret['fields']['opponensemail'] = [
                            'valid' => false,
                            'error' => t('Nem lehet opponense olyan előadásnak, aminek a szerzője')
                        ];
                    }
                }
            }
        }
        echo json_encode($ret);
    }

    public function biralatSave()
    {
        $ret = ['success' => false];
        /** @var Dolgozo $biralo */
        $biralo = $this->getRepo(Dolgozo::class)->getLoggedInUser();
        if ($biralo) {
            $id = $this->params->getIntRequestParam('id');
            /** @var MPTNGYSzakmaianyag $anyag */
            $anyag = $this->getRepo()->find($id);
            if ($anyag) {
                $szempont1 = $this->params->getIntRequestParam('szempont1');
                $szempont2 = $this->params->getIntRequestParam('szempont2');
                $szempont3 = $this->params->getIntRequestParam('szempont3');
                $szempont4 = $this->params->getIntRequestParam('szempont4');
                $szempont5 = $this->params->getIntRequestParam('szempont5');
                $szoveges = $this->params->getStringRequestParam('szovegesertekeles');
                switch (true) {
                    case $anyag->getBiralo1()?->getId() == $biralo->getId():
                        $bn = 'B1';
                        break;
                    case $anyag->getBiralo2()?->getId() == $biralo->getId():
                        $bn = 'B2';
                        break;
                    case $anyag->getBiralo3()?->getId() == $biralo->getId():
                        $bn = 'B3';
                        break;
                }
                $fn = "set{$bn}szempont1";
                $anyag->{$fn}($szempont1);
                $fn = "set{$bn}szempont2";
                $anyag->{$fn}($szempont2);
                $fn = "set{$bn}szempont3";
                $anyag->{$fn}($szempont3);
                $fn = "set{$bn}szempont4";
                $anyag->{$fn}($szempont4);
                $fn = "set{$bn}szempont5";
                $anyag->{$fn}($szempont5);
                $fn = "set{$bn}szovegesertekeles";
                $anyag->{$fn}($szoveges);
                $fn = "set{$bn}biralatkesz";
                $anyag->{$fn}($this->params->getBoolRequestParam('bbiralatkesz'));
                $this->getEm()->persist($anyag);
                $this->getEm()->flush();
                $ret['success'] = true;
            }
        }
        echo json_encode($ret);
    }

    public function recalcBiralat()
    {
        $anyagok = $this->getRepo(MPTNGYSzakmaianyag::class)->getAll();
        /** @var MPTNGYSzakmaianyag $anyag */
        foreach ($anyagok as $anyag) {
            $anyag->setKonferencianszerepelhet($anyag->calcKonferencianszerepelhet());
            $anyag->setPluszbiralokell($anyag->calcPluszBiraloKell());
            $anyag->setBiralatkesz($anyag->calcBiralatkesz());
            \mkw\store::getEm()->persist($anyag);
            \mkw\store::getEm()->flush();
        }
        echo 'Ready.';
    }

    public function exportKivonatkotet()
    {
        $this->doExportKivonatkotet(false);
    }

    public function exportKivonatkotetAll()
    {
        $this->doExportKivonatkotet(true);
    }

    protected function doExportKivonatkotet($all = false)
    {
        function x($o, $sor)
        {
            return \mkw\store::getExcelCoordinate($o, $sor);
        }

        $sql = "(SELECT
            t.nev AS tipus,
            a.id,
            a.cim,
            a.tartalom,
            p.nev AS szerzonev,
            a.szerzo1email,
            p.mpt_munkahelynev,
            '' AS szimpocim, '' AS elnok, '' AS elnok_munkahely, '' AS opponens, '' AS opponens_munkahely,'' AS szimpotartalom, NULL AS szimpoid,
            p2.nev AS szerzo2nev, a.szerzo2email, p2.mpt_munkahelynev AS szerzo2munkahely,
            p3.nev AS szerzo3nev, a.szerzo3email, p3.mpt_munkahelynev AS szerzo3munkahely,
            p4.nev AS szerzo4nev, a.szerzo4email, p4.mpt_munkahelynev AS szerzo4munkahely,
            p5.nev AS szerzo5nev, a.szerzo5email, p5.mpt_munkahelynev AS szerzo5munkahely,
            p6.nev AS szerzo6nev, a.szerzo6email, p6.mpt_munkahelynev AS szerzo6munkahely,
            p7.nev AS szerzo7nev, a.szerzo7email, p7.mpt_munkahelynev AS szerzo7munkahely,
            p8.nev AS szerzo8nev, a.szerzo8email, p8.mpt_munkahelynev AS szerzo8munkahely,
            p9.nev AS szerzo9nev, a.szerzo9email, p9.mpt_munkahelynev AS szerzo9munkahely,
            p10.nev AS szerzo10nev, a.szerzo10email, p10.mpt_munkahelynev AS szerzo10munkahely,
            a.egyebszerzok
            FROM mptngyszakmaianyag a
            JOIN mptngyszakmaianyagtipus t      ON a.tipus_id = t.id
            JOIN partner p                      ON a.szerzo1_id = p.id
            LEFT JOIN partner p2                ON a.szerzo2_id = p2.id
            LEFT JOIN partner p3                ON a.szerzo3_id = p3.id
            LEFT JOIN partner p4                ON a.szerzo4_id = p4.id
            LEFT JOIN partner p5                ON a.szerzo5_id = p5.id
            LEFT JOIN partner p6                ON a.szerzo6_id = p6.id
            LEFT JOIN partner p7                ON a.szerzo7_id = p7.id
            LEFT JOIN partner p8                ON a.szerzo8_id = p8.id
            LEFT JOIN partner p9                ON a.szerzo9_id = p9.id
            LEFT JOIN partner p10               ON a.szerzo10_id = p10.id";
        if ($all) {
            $sql .= " WHERE a.vegleges = 1
              AND a.tipus_id IN (1, 2, 4, 6)";
        } else {
            $sql .= " WHERE a.konferencianszerepelhet = 1
              AND a.biralatkesz = 1
              AND a.vegleges = 1
              AND a.tipus_id IN (1, 2, 4, 6)";
        }

        $sql .= " ORDER BY t.nev, a.id
            ) UNION (
            SELECT
                t.nev AS tipus,
                a.id,
                a.cim,
                a.tartalom,
                p.nev AS szerzonev,
                a.szerzo1email,
                p.mpt_munkahelynev,
                sz.cim AS szimpocim,
                tulaj.nev AS elnok,tulaj.mpt_munkahelynev AS elnok_munkahely,
                oppo.nev AS opponens,oppo.mpt_munkahelynev AS opponens_munkahely,
                sz.tartalom AS szimpotartalom,
                sz.id AS szimpoid,
                p2.nev AS szerzo2nev, a.szerzo2email, p2.mpt_munkahelynev AS szerzo2munkahely,
                p3.nev AS szerzo3nev, a.szerzo3email, p3.mpt_munkahelynev AS szerzo3munkahely,
                p4.nev AS szerzo4nev, a.szerzo4email, p4.mpt_munkahelynev AS szerzo4munkahely,
                p5.nev AS szerzo5nev, a.szerzo5email, p5.mpt_munkahelynev AS szerzo5munkahely,
                p6.nev AS szerzo6nev, a.szerzo6email, p6.mpt_munkahelynev AS szerzo6munkahely,
                p7.nev AS szerzo7nev, a.szerzo7email, p7.mpt_munkahelynev AS szerzo7munkahely,
                p8.nev AS szerzo8nev, a.szerzo8email, p8.mpt_munkahelynev AS szerzo8munkahely,
                p9.nev AS szerzo9nev, a.szerzo9email, p9.mpt_munkahelynev AS szerzo9munkahely,
                p10.nev AS szerzo10nev, a.szerzo10email, p10.mpt_munkahelynev AS szerzo10munkahely,
                a.egyebszerzok
            FROM mptngyszakmaianyag a
            JOIN mptngyszakmaianyagtipus t      ON a.tipus_id = t.id
            JOIN partner p                      ON a.szerzo1_id = p.id
            JOIN mptngyszakmaianyag sz          ON (
                sz.eloadas1_id = a.id OR
                sz.eloadas2_id = a.id OR
                sz.eloadas3_id = a.id OR
                sz.eloadas4_id = a.id OR
                sz.eloadas5_id = a.id
            )
            JOIN partner oppo                   ON sz.opponensemail = oppo.email
            JOIN partner tulaj                  ON sz.tulajdonos_id = tulaj.id
            LEFT JOIN partner p2                ON a.szerzo2_id = p2.id
            LEFT JOIN partner p3                ON a.szerzo3_id = p3.id
            LEFT JOIN partner p4                ON a.szerzo4_id = p4.id
            LEFT JOIN partner p5                ON a.szerzo5_id = p5.id
            LEFT JOIN partner p6                ON a.szerzo6_id = p6.id
            LEFT JOIN partner p7                ON a.szerzo7_id = p7.id
            LEFT JOIN partner p8                ON a.szerzo8_id = p8.id
            LEFT JOIN partner p9                ON a.szerzo9_id = p9.id
            LEFT JOIN partner p10               ON a.szerzo10_id = p10.id";
        if ($all) {
            $sql .= " WHERE a.vegleges = 1
              AND a.tipus_id = 5";
        } else {
            $sql .= " WHERE a.konferencianszerepelhet = 1
              AND a.biralatkesz = 1
              AND a.vegleges = 1
              AND a.tipus_id = 5";
        }
        $sql .= " ORDER BY sz.cim, a.id);";

        $conn = $this->getEm()->getConnection();
        $res = $conn->fetchAllAssociative($sql);

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        if ($res) {
            $o = 0;
            foreach (array_keys($res[0]) as $header) {
                $sheet->setCellValue(x($o++, 1), $header);
            }

            $sor = 2;
            foreach ($res as $item) {
                $o = 0;
                foreach ($item as $val) {
                    $sheet->setCellValue(x($o++, $sor), $val);
                }
                $sor++;
            }
        }

        if ($all) {
            $fname = 'veglegesanyag';
        } else {
            $fname = 'kivonatkotet';
        }
        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $filepath = \mkw\store::storagePath(uniqid($fname) . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $fname . ".xlsx");

        readfile($filepath);

        \unlink($filepath);
    }

    public function exportProgramfuzethez()
    {
        function x($o, $sor)
        {
            return \mkw\store::getExcelCoordinate($o, $sor);
        }

        $sql = "(
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'szerző 1' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.szerzo1_id=p.id) AND (a.konferencianszerepelhet=1)
            union 
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'szerző 2' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.szerzo2_id=p.id) AND (a.konferencianszerepelhet=1)
            union
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'szerző 3' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.szerzo3_id=p.id) AND (a.konferencianszerepelhet=1)
            union
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'szerző 4' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.szerzo4_id=p.id) AND (a.konferencianszerepelhet=1)
            UNION
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'szerző 5' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.szerzo5_id=p.id) AND (a.konferencianszerepelhet=1)
            UNION
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'szerző 6' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.szerzo6_id=p.id) AND (a.konferencianszerepelhet=1)
            union
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'szerző 7' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.szerzo7_id=p.id) AND (a.konferencianszerepelhet=1)
            UNION
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'szerző 8' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.szerzo8_id=p.id) AND (a.konferencianszerepelhet=1)
            union
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'szerző 9' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.szerzo9_id=p.id) AND (a.konferencianszerepelhet=1)
            UNION
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'szerző 10' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.szerzo10_id=p.id) AND (a.konferencianszerepelhet=1)
            UNION
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'beszélgető partner' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.beszelgetopartner_id=p.id) AND (a.konferencianszerepelhet=1)
            union
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'elnök' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.tulajdonos_id=p.id) AND (a.tipus_id=4) AND (a.konferencianszerepelhet=1)
            union
            SELECT p.nev,p.email,p.mpt_munkahelynev,a.id,a.cim,t.nev AS tipus,'opponens' AS t,IF(a.tipus_id=5,(SELECT id FROM mptngyszakmaianyag ma WHERE (ma.eloadas1_id=a.id) OR (ma.eloadas2_id=a.id) OR (ma.eloadas3_id=a.id) OR (ma.eloadas4_id=a.id) OR (ma.eloadas5_id=a.id)),null) AS szimpozium_id
            FROM partner p,mptngyszakmaianyag a
            LEFT OUTER JOIN mptngyszakmaianyagtipus t ON (a.`tipus_id`=t.id)
            WHERE (a.opponens_id=p.id) AND (a.tipus_id=4) AND (a.konferencianszerepelhet=1)
            ) 
            order by id;";

        $conn = $this->getEm()->getConnection();
        $res = $conn->fetchAllAssociative($sql);

        $excel = new Spreadsheet();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        if ($res) {
            $o = 0;
            foreach (array_keys($res[0]) as $header) {
                $sheet->setCellValue(x($o++, 1), $header);
            }

            $sor = 2;
            foreach ($res as $item) {
                $o = 0;
                foreach ($item as $val) {
                    $sheet->setCellValue(x($o++, $sor), $val);
                }
                $sor++;
            }
        }

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $filepath = \mkw\store::storagePath(uniqid('programfuzet') . '.xlsx');
        $writer->save($filepath);

        $fileSize = filesize($filepath);

        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=programfuzet.xlsx");

        readfile($filepath);

        \unlink($filepath);
    }

}
