<?php

namespace Controllers;

use Entities\MNRLanding;
use Entities\Termek;
use Entities\TermekFa;
use Entities\TermekValtozat;
use mkw\consts;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class mainController extends \mkwhelpers\Controller
{

    private $view;

    protected function createTermekfaParams()
    {
        return [
            'elemperpage' => $this->params->getIntRequestParam('elemperpage', 20),
            'pageno' => $this->params->getIntRequestParam('pageno', 1),
            'order' => $this->params->getStringRequestParam('order', 'ardesc'),
            'filter' => $this->params->getStringRequestParam('filter', ''),
            'klikkeltcimkekatid' => $this->params->getIntRequestParam('cimkekatid'),
            'arfilter' => $this->params->getStringRequestParam('arfilter', ''),
            'keresett' => $this->params->getStringRequestParam('keresett', ''),
            'vt' => $this->params->getIntRequestParam('vt', 1)
        ];
    }

    public function showOff()
    {
        $this->view = $this->getTemplateFactory()->createMainView('off.tpl');
        \mkw\store::fillTemplate($this->view);
        $this->view->printTemplateResult(false);
    }

    public function show404($head = null)
    {
        $this->view = $this->getTemplateFactory()->createMainView('404.tpl');
        \mkw\store::fillTemplate($this->view);
        $tc = new termekController($this->params);
        $this->view->setVar('ajanlotttermekek', $tc->getAjanlottLista());
        $this->view->setVar('seodescription', t('Sajnos nem találjuk.'));
        $this->view->setVar('pagetitle', t('Sajnos nem találjuk.'));
        if ($head) {
            header($head);
        }
        $this->view->printTemplateResult(false);
    }

    public function view()
    {
        $toPrint = true;

        $this->view = $this->getTemplateFactory()->createMainView('main.tpl');
        \mkw\store::fillTemplate($this->view);
        $hc = new hirController($this->params);
        $tc = new termekController($this->params);
        $khc = new korhintaController($this->params);
        $tfc = new termekfaController($this->params);
        $tcc = new termekcimkeController($this->params);
        $tec = new termekertekelesController($this->params);
        $this->view->setVar('pagetitle', \mkw\store::getParameter(\mkw\consts::Oldalcim));
        $this->view->setVar('seodescription', \mkw\store::getParameter(\mkw\consts::Seodescription));
        switch (true) {
            case \mkw\store::isMindentkapni():
                $this->view->setVar('hirek', $hc->gethirlist());
                $this->view->setVar('ajanlotttermekek', $tc->getAjanlottLista());
                $this->view->setVar('legnepszerubbtermekek', $tc->getLegnepszerubbLista(\mkw\store::getParameter(\mkw\consts::Fooldalnepszerutermekdb, 5)));
                $this->view->setVar('legujabbtermekek', $tc->getLegujabbLista());
                $this->view->setVar('korhintalista', $khc->getLista());
                $this->view->setVar('topkategorialista', $tfc->getformenu(\mkw\store::getSetupValue('topkategoriamenunum'), 0));
                $this->view->setVar('kiemeltmarkalista', $tcc->getKiemeltList());
                $this->view->setVar('newertekeleslista', $tec->getNewList());
                $this->view->setVar('akciostermekek', $tc->getAkciosLista(\mkw\store::getParameter(\mkw\consts::Fooldalakciostermekdb, 6)));
                break;

            case \mkw\store::isMugenrace():
                $this->view->setVar('hirek', $hc->gethirlist());
                $this->view->setVar('ajanlotttermekek', $tc->getAjanlottLista());
                $this->view->setVar('legnepszerubbtermekek', $tc->getLegnepszerubbLista(\mkw\store::getParameter(\mkw\consts::Fooldalnepszerutermekdb, 5)));
                $this->view->setVar('legujabbtermekek', $tc->getLegujabbLista());
                $this->view->setVar('korhintalista', $khc->getLista());
                $this->view->setVar('topkategorialista', $tfc->getformenu(\mkw\store::getSetupValue('topkategoriamenunum'), 0));
                $this->view->setVar('kiemeltmarkalista', $tcc->getKiemeltList());
                $this->view->setVar('akciostermekek', $tc->getAkciosLista(\mkw\store::getParameter(\mkw\consts::Fooldalakciostermekdb, 6)));
                break;

            case \mkw\store::isMugenrace2021():
                $rs = $this->getRepo(MNRLanding::class)->getAll([], ['id' => 'ASC']);
                $r = $rs[0];
                $this->view->setVar('mnrlanding', $r->toPublic());
                break;

            case \mkw\store::isMPTNGY():
                $toPrint = false;
                header('Location: ' . \mkw\store::getRouter()->generate('mptngyszakmaianyagok'));
                break;
        }
        if ($toPrint) {
            $this->view->printTemplateResult(true);
        }
    }

    public function termekfa()
    {
        $tf = new termekfaController($this->params);
        $com = $this->params->getStringParam('slug');
        /** @var TermekFa $ag */
        $ag = $tf->getRepo()->findOneBySlug($com);
        if ($ag && !$ag->getInaktiv()) {
            if (count($ag->getChildren()) > 0) {
                $this->view = $this->getTemplateFactory()->createMainView('katlista.tpl');
                $t = $tf->getkatlista($ag);
            } else {
                $this->view = $this->getTemplateFactory()->createMainView('termeklista.tpl');
                $t = $tf->gettermeklistaforparent($ag, 'termekfa');
            }
            foreach ($t as $k => $v) {
                $this->view->setVar($k, $v);
            }
            \mkw\store::fillTemplate($this->view);
            $this->view->setVar('sketchfabmodelid', $ag->getSketchfabmodelid());
            $this->view->setVar('kepurl', $ag->getKepurlLarge());
            $this->view->setVar('pagetitle', $ag->getShowOldalcim());
            $this->view->setVar('seodescription', $ag->getShowSeodescription());
            $this->view->setVar('blogposztdb', \mkw\store::getParameter(\mkw\consts::BlogposztKategoriadb, 3));
            $this->view->printTemplateResult(true);
        } else {
            \mkw\store::redirectTo404($com, $this->params);
        }
    }

    public function marka()
    {
        $com = $this->params->getStringParam('slug');
        $tf = new termekfaController($this->params);
        $tc = new termekcimkeController($this->params);
        $c = $tc->getRepo()->findOneBySlug($com);
        if ($c) {
            $this->view = $this->getTemplateFactory()->createMainView('termeklista.tpl');
            $t = $tf->gettermeklistaforparent(null, 'marka');
            foreach ($t as $k => $v) {
                $this->view->setVar($k, $v);
            }
            \mkw\store::fillTemplate($this->view);

            $mpt = \mkw\store::getParameter(\mkw\consts::Markaoldalcim);
            if ($mpt) {
                $mpt = str_replace('[markanev]', $c->getNev(), $mpt);
                $mpt = str_replace('[global]', \mkw\store::getParameter(\mkw\consts::Oldalcim), $mpt);
            } else {
                $mpt = \mkw\store::getParameter(\mkw\consts::Oldalcim);
            }
            $this->view->setVar('pagetitle', $mpt);

            $msd = \mkw\store::getParameter(\mkw\consts::Markaseodescription);
            if ($msd) {
                $msd = str_replace('[markanev]', $c->getNev(), $msd);
                $msd = str_replace('[global]', \mkw\store::getParameter(\mkw\consts::Seodescription), $msd);
            } else {
                $msd = \mkw\store::getParameter(\mkw\consts::Seodescription);
            }
            $this->view->setVar('seodescription', $msd);

            $this->view->printTemplateResult(true);
        } else {
            \mkw\store::redirectTo404($com, $this->params);
        }
    }

    public function szuro()
    {
        $tf = new termekfaController($this->params);
        $this->view = $this->getTemplateFactory()->createMainView('termeklista.tpl');
        $t = $tf->gettermeklistaforparent(null, 'szuro');
        foreach ($t as $k => $v) {
            $this->view->setVar($k, $v);
        }
        \mkw\store::fillTemplate($this->view);
        $this->view->printTemplateResult(true);
    }

    public function kereses()
    {
        $term = trim($this->params->getStringRequestParam('term'));
        if ($term) {
            $r = \mkw\store::getEm()->getRepository('\Entities\Termek');
            $res = $r->getNevek($term);
            echo json_encode($res);
        } else {
            $keresoszo = trim($this->params->getStringRequestParam('keresett'));
            if ($keresoszo != '') {
                $log = new \Entities\Keresoszolog($keresoszo);
                \mkw\store::getEm()->persist($log);
                \mkw\store::getEm()->flush();

                $tf = new termekfaController($this->params);
                $t = $tf->gettermeklistaforparent(null, 'kereses');

                $this->view = $this->getTemplateFactory()->createMainView('termeklista.tpl');
                foreach ($t as $k => $v) {
                    $this->view->setVar($k, $v);
                }
                \mkw\store::fillTemplate($this->view);
                $this->view->setVar('seodescription', t('A keresett kifejezés: ') . $keresoszo);
                $this->view->setVar('pagetitle', t('A keresett kifejezés: ') . $keresoszo);
                $this->view->printTemplateResult(true);
            } else {
                $this->view = $this->getTemplateFactory()->createMainView('nincstalalat.tpl');
                $tc = new termekController($this->params);
                $this->view->setVar('ajanlotttermekek', $tc->getAjanlottLista());
                \mkw\store::fillTemplate($this->view);
                $this->view->setVar('seodescription', t('Keressen valamit.'));
                $this->view->setVar('pagetitle', t('Keressen valamit.'));
                $this->view->printTemplateResult(true);
            }
        }
    }

    public function termek()
    {
        switch (true) {
            case \mkw\store::isMindentkapni():
            case \mkw\store::isMugenrace():
                $com = $this->params->getStringParam('slug');
                $tc = new termekController($this->params);
                $filter = new FilterDescriptor();
                $filter->addFilter('slug', '=', $com);
                /** @var Termek $termek */
                $termek = $tc->getRepo()->getWithJoins($filter);
                if (is_array($termek)) {
                    $termek = $termek[0];
                }
                //$termek = $tc->getRepo()->findOneBySlug($com);
                if ($termek && !$termek->getInaktiv() && $termek->getXLathato() && !$termek->getFuggoben()) {
                    $this->view = $this->getTemplateFactory()->createMainView('termeklap.tpl');
                    \mkw\store::fillTemplate($this->view);
                    $this->view->setVar('pagetitle', $termek->getShowOldalcim());
                    $this->view->setVar('seodescription', $termek->getShowSeodescription());
                    $this->view->setVar(
                        'legnepszerubbtermekek',
                        $tc->getLegnepszerubbLista(\mkw\store::getParameter(\mkw\consts::Termeklapnepszerutermekdb, 5))
                    );
                    $this->view->setVar('hozzavasarolttermekek', $tc->getHozzavasaroltLista($termek));
                    $this->view->setVar('blogposztdb', \mkw\store::getParameter(\mkw\consts::BlogposztTermeklapdb, 3));

                    $marka = $termek->getMarka();
                    if ($marka) {
                        $markaszuro = $marka->getTermekFilter();
                    }

                    $t = $tc->getTermekLap($termek);
                    foreach ($t as $k => $v) {
                        $this->view->setVar($k, $v);
                    }
                    $statlap = $this->getRepo('Entities\Statlap')->find(\mkw\store::getParameter(\mkw\consts::SzallitasiFeltetelSablon, 0));
                    if ($statlap) {
                        $this->view->setVar('szallitasifeltetelsablon', $statlap->getSzoveg());
                    }
                    $this->view->printTemplateResult(true);
                } else {
                    \mkw\store::redirectTo404($com, $this->params);
                }
                break;

            case \mkw\store::isSuperzoneB2B():
                $com = $this->params->getStringParam('slug');
                $tc = new termekController($this->params);
                /** @var Termek $termek */
                $termek = $tc->getRepo()->findOneBySlug($com);
                if ($termek && !$termek->getInaktiv() && $termek->getXLathato() && !$termek->getFuggoben()) {
                    $this->view = $this->getTemplateFactory()->createMainView('termeklapszin.tpl');
                    \mkw\store::fillTemplate($this->view);
                    $this->view->setVar('pagetitle', $termek->getShowOldalcim());
                    $this->view->setVar('seodescription', $termek->getShowSeodescription());
                    $t = [];
                    $vtt = [];
                    $t['caption'] = $termek->getNev();
                    $t['cikkszam'] = $termek->getCikkszam();
                    $valtozatok = $termek->getValtozatok();
                    $ma = new \DateTime();
                    /** @var TermekValtozat $valt */
                    foreach ($valtozatok as $valt) {
                        if ($valt->getXElerheto() && $valt->getXLathato()) {
                            if ($valt->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                                $vtt[$valt->getErtek1()]['id'] = $valt->getErtek1();
                                $vtt[$valt->getErtek1()]['caption'] = $valt->getErtek1();
                                $vtt[$valt->getErtek1()]['kepurlmini'] = $valt->getKepurlMini();
                                $vtt[$valt->getErtek1()]['kepurlsmall'] = $valt->getKepurlSmall();
                                $vtt[$valt->getErtek1()]['kepurlmedium'] = $valt->getKepurlMedium();
                                $vtt[$valt->getErtek1()]['kepurllarge'] = $valt->getKepurlLarge();
                                \mkw\store::writelog(
                                    $valt->getErtek1() . ' ' . $valt->getErtek2() . ':' .
                                    $valt->getKeszlet() . ' - ' . $valt->getFoglaltMennyiseg() . ' - ' . $valt->calcMinboltikeszlet() . '=' .
                                    $valt->getKeszlet() - $valt->getFoglaltMennyiseg() - $valt->calcMinboltikeszlet()
                                );
                                $vtt[$valt->getErtek1()]['keszlet'] += $valt->getKeszlet() - $valt->getFoglaltMennyiseg() - $valt->calcMinboltikeszlet();
                                $vtt[$valt->getErtek1()]['bejon'] = $vtt[$valt->getErtek1()]['bejon'] || (($valt->getBeerkezesdatumStr(
                                    )) && ($valt->getBeerkezesdatum() >= $ma) ? true : false);
                                $vtt[$valt->getErtek1()]['link'] = \mkw\store::getRouter()->generate(
                                    'showtermekm',
                                    false,
                                    ['slug' => $com],
                                    ['szin' => urlencode($valt->getErtek1())]
                                );
                            }
                            if ($valt->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) {
                                $vtt[$valt->getErtek2()]['id'] = $valt->getErtek2();
                                $vtt[$valt->getErtek2()]['caption'] = $valt->getErtek2();
                                $vtt[$valt->getErtek2()]['kepurlmini'] = $valt->getKepurlMini();
                                $vtt[$valt->getErtek2()]['kepurlsmall'] = $valt->getKepurlSmall();
                                $vtt[$valt->getErtek2()]['kepurlmedium'] = $valt->getKepurlMedium();
                                $vtt[$valt->getErtek2()]['kepurllarge'] = $valt->getKepurlLarge();
                                $vtt[$valt->getErtek2()]['keszlet'] += $valt->getKeszlet() - $valt->getFoglaltMennyiseg() - $valt->calcMinboltikeszlet();
                                $vtt[$valt->getErtek2()]['bejon'] = $vtt[$valt->getErtek2()]['bejon'] || (($valt->getBeerkezesdatumStr(
                                    )) && ($valt->getBeerkezesdatum() >= $ma) ? true : false);
                                $vtt[$valt->getErtek2()]['link'] = \mkw\store::getRouter()->generate(
                                    'showtermekm',
                                    false,
                                    ['slug' => $com],
                                    ['szin' => urlencode($valt->getErtek2())]
                                );
                            }
                        }
                    }
                    $t['valtozatok'] = $vtt;
                    $this->view->setVar('termek', $t);
                    $this->view->printTemplateResult(true);
                } else {
                    \mkw\store::redirectTo404($com, $this->params);
                }
                break;
            case \mkw\store::isMugenrace2021():
                $com = $this->params->getStringParam('slug');
                $tc = new termekController($this->params);
                $filter = new FilterDescriptor();
                $filter->addFilter('slug', '=', $com);
                /** @var Termek $termek */
                $termek = $tc->getRepo()->getWithJoins($filter);
                if (is_array($termek)) {
                    $termek = $termek[0];
                }
                //$termek = $tc->getRepo()->findOneBySlug($com);
                if ($termek && !$termek->getInaktiv() && $termek->getXLathato() && !$termek->getFuggoben()) {
                    $this->view = $this->getTemplateFactory()->createMainView('termeklap.tpl');
                    \mkw\store::fillTemplate($this->view);
                    $this->view->setVar('pagetitle', $termek->getShowOldalcim());
                    $this->view->setVar('seodescription', $termek->getShowSeodescription());

                    $tfs = $this->getRepo(TermekFa::class)->getForFilter(\mkw\store::getWebshopNum());
                    if ($tfs) {
                        $this->view->setVar('categoryfilter', $tfs);
                    }
                    $t = $tc->getTermekLap($termek);
                    foreach ($t as $k => $v) {
                        $this->view->setVar($k, $v);
                    }
                    $statlap = $this->getRepo('Entities\Statlap')->find(\mkw\store::getParameter(\mkw\consts::SzallitasiFeltetelSablon, 0));
                    if ($statlap) {
                        $this->view->setVar('szallitasifeltetelsablon', $statlap->getSzoveg());
                    }
                    $this->view->printTemplateResult(true);
                } else {
                    \mkw\store::redirectTo404($com, $this->params);
                }
                break;
        }
    }

    // superzone színhez tartozó méretek
    public function termekm()
    {
        $com = $this->params->getStringParam('slug');
        $szin = $this->params->getStringRequestParam('szin');
        $tc = new termekController($this->params);
        /** @var \Entities\Termek $termek */
        $termek = $tc->getRepo()->findOneBySlug($com);
        if ($termek && !$termek->getInaktiv() && $termek->getXLathato() && !$termek->getFuggoben()) {
            $this->view = $this->getTemplateFactory()->createMainView('termeklapmeret.tpl');
            \mkw\store::fillTemplate($this->view);
            $this->view->setVar('pagetitle', $termek->getShowOldalcim());
            $this->view->setVar('seodescription', $termek->getShowSeodescription());
            $t = [];
            $vtt = [];
            $t['id'] = $termek->getId();
            $t['caption'] = $termek->getNev();
            $t['cikkszam'] = $termek->getCikkszam();
            $t['leiras'] = $termek->getLeiras();
            $t['szin'] = $szin;
            $partner = \mkw\store::getLoggedInUser();
            if ($partner) {
                $this->view->setVar('showkeszlet', $partner->isMennyisegetlathat());
                $this->view->setVar('nemrendelhet', $partner->isXNemrendelhet());
            } else {
                $this->view->setVar('showkeszlet', false);
                $this->view->setVar('nemrendelhet', false);
            }
            $valutanem = $termek->getArValutanem(null, $partner);
            if ($valutanem) {
                $t['valutanemnev'] = $valutanem->getNev();
            } else {
                $t['valutanemnev'] = 'X';
            }
            if ($partner && $partner->getSzamlatipus()) {
                $t['ar'] = $termek->getNettoAr(null, $partner);
                $t['eredetiar'] = $termek->getKedvezmenynelkuliNettoAr(null, $partner, $valutanem);
            } else {
                $t['ar'] = $termek->getBruttoAr(null, $partner);
                $t['eredetiar'] = $termek->getKedvezmenynelkuliBruttoAr(null, $partner, $valutanem);
            }
            $t['kedvezmeny'] = $termek->getKedvezmeny($partner);
            $valtozatok = $termek->getValtozatok();
            $ma = new \DateTime();
            /** @var TermekValtozat $valt */
            foreach ($valtozatok as $valt) {
                if ($valt->getXElerheto() && $valt->getXLathato()) {
                    $valtkeszlet = $valt->getKeszlet() - $valt->getFoglaltMennyiseg() - $valt->calcMinboltikeszlet();
                    if (($valt->getAdatTipus1Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) && ($valt->getErtek1() == $szin)) {
                        $t['kepurllarge'] = $valt->getKepurlLarge();
                        $t['kepurlmedium'] = $valt->getKepurlMedium();
                        $vtt[] = [
                            'id' => $valt->getId(),
                            'caption' => $valt->getErtek2(),
                            'keszlet' => $valtkeszlet,
                            'beerkezesdatumstr' => $valt->getBeerkezesdatumStr(),
                            'bejon' => (($valtkeszlet <= 0) && ($valt->getBeerkezesdatumStr()) && ($valt->getBeerkezesdatum() >= $ma) ? true : false)
                        ];
                    }
                    if (($valt->getAdatTipus2Id() == \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin)) && ($valt->getErtek2() == $szin)) {
                        $vtt[] = [
                            'id' => $valt->getId(),
                            'caption' => $valt->getErtek1(),
                            'keszlet' => $valtkeszlet,
                            'beerkezesdatumstr' => $valt->getBeerkezesdatumStr(),
                            'bejon' => (($valtkeszlet <= 0) && ($valt->getBeerkezesdatumStr()) && ($valt->getBeerkezesdatum() >= $ma) ? true : false)
                        ];
                    }
                }
            }
            $t['valtozatok'] = $vtt;
            $this->view->setVar('termek', $t);
            $this->view->printTemplateResult(true);
        } else {
            \mkw\store::redirectTo404($com, $this->params);
        }
    }

    public function valtozatar()
    {
        $termekid = $this->params->getIntRequestParam('t');
        $valtozatid = $this->params->getIntRequestParam('vid');
        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->find($termekid);
        $valtozat = \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->find($valtozatid);
        $ret = [];

        $ret['szallitasiido'] = $termek->calcSzallitasiido($valtozat);
        $ret['minszallitasiido'] = intdiv($ret['szallitasiido'], 2);
        $ret['price'] = number_format($termek->getBruttoAr($valtozat, \mkw\store::getLoggedInUser()), 0, ',', ' ') . ' Ft';
        $ret['kepurlmedium'] = $valtozat->getKepurlMedium();
        $ret['kepurllarge'] = $valtozat->getKepurlLarge();
        $ret['kepurlsmall'] = $valtozat->getKepurlSmall();

        $ret['kepek'] = \mkw\store::getEm()->getRepository('Entities\Termek')->getKepekKiveve($termek, $valtozat);

        echo json_encode($ret);
    }

    public function valtozat()
    {
        $termekkod = $this->params->getIntRequestParam('t');
        $tipusid = $this->params->getIntRequestParam('ti');
        $valtozatertek = $this->params->getRequestParam('v');
        $masiktipusid = $this->params->getIntRequestParam('mti');
        $masikselected = $this->params->getRequestParam('sel');
        $ret = [];

        /** @var \Entities\Termek $termek */
        $termek = \mkw\store::getEm()->getRepository('Entities\Termek')->find($termekkod);

        if ($masiktipusid) {
            $t = [$tipusid, $masiktipusid];
            $e = [$valtozatertek, $masikselected];
        } else {
            $t = [$tipusid];
            $e = [$valtozatertek];
        }
        /** @var \Entities\TermekValtozat $termekvaltozat */
        $termekvaltozat = \mkw\store::getEm()->getRepository('Entities\TermekValtozat')->getByProperties($termek->getId(), $t, $e);

        $ret['szallitasiido'] = $termek->calcSzallitasiido($termekvaltozat);
        $ret['minszallitasiido'] = intdiv($ret['szallitasiido'], 2);
        $ret['price'] = number_format(
                $termek->getBruttoAr(
                    $termekvaltozat,
                    \mkw\store::getLoggedInUser(),
                    \mkw\store::getMainSession()->valutanem,
                    \mkw\store::getParameter(\mkw\consts::Webshop2Price)
                ),
                0,
                ',',
                ' '
            ) . ' ' . \mkw\store::getMainSession()->valutanemnev;
        if ($termekvaltozat) {
            $ret['kepurlmedium'] = $termekvaltozat->getKepurlMedium();
            $ret['kepurllarge'] = $termekvaltozat->getKepurlLarge();
            $ret['kepurlsmall'] = $termekvaltozat->getKepurlSmall();
            $ret['kepurlorig'] = $termekvaltozat->getKepurl();
        }
        $ret['kepek'] = \mkw\store::getEm()->getRepository('Entities\Termek')->getKepekKiveve($termek, $termekvaltozat);
        $ret['imagepath'] = \mkw\store::getConfigValue('main.imagepath', '');

        $valtozatok = $termek->getValtozatok();
        foreach ($valtozatok as $valtozat) {
            if ($valtozat->getXElerheto()) {
                if ($valtozat->getAdatTipus1Id() == $tipusid && ($valtozat->getErtek1() == $valtozatertek || !$valtozatertek)) {
                    $ret['adat'][$valtozat->getErtek2()] = ['value' => $valtozat->getErtek2(), 'sel' => $masikselected == $valtozat->getErtek2()];
                } elseif ($valtozat->getAdatTipus2Id() == $tipusid && ($valtozat->getErtek2() == $valtozatertek || !$valtozatertek)) {
                    $ret['adat'][$valtozat->getErtek1()] = ['value' => $valtozat->getErtek1(), 'sel' => $masikselected == $valtozat->getErtek1()];
                }
            }
        }
        echo json_encode($ret);
    }

    public function kapcsolat()
    {
        $com = $this->params->getStringParam('todo');
        switch ($com) {
            case 'ment':
                $hibas = false;
                $hibak = [];
                $nev = $this->params->getStringRequestParam('nev');
                $email1 = $this->params->getStringRequestParam('email1');
                $email2 = $this->params->getStringRequestParam('email2');
                $telefon = $this->params->getStringRequestParam('telefon');
                $rendelesszam = $this->params->getStringRequestParam('rendelesszam');
                $tema = \mkw\store::getEm()->getRepository('Entities\Kapcsolatfelveteltema')->find($this->params->getStringRequestParam('tema'));
                if ($tema) {
                    $temanev = $tema->getNev();
                } else {
                    $temanev = 'Imseretlen';
                }
                $szoveg = $this->params->getStringRequestParam('szoveg');
                if (!\Zend_Validate::is($email1, 'EmailAddress') || !\Zend_Validate::is($email2, 'EmailAddress')) {
                    $hibas = true;
                    $hibak['email'] = t('Rossz az email');
                }
                if ($email1 !== $email2) {
                    $hibas = true;
                    $hibak['email'] = t('Nem egyezik a két emailcím');
                }
                if ($nev == '') {
                    $hibas = true;
                    $hibak['nev'] = t('Üres a név');
                }
                if ($tema == '') {
                    $hibas = true;
                    $hibak['tema'] = t('Nincs megadva téma');
                }
                if (!$hibas) {
                    $mailer = \mkw\store::getMailer();
                    $mailer->setTo(\mkw\store::getParameter(\mkw\consts::EmailReplyTo));
                    $mailer->setSubject('Kapcsolatfelvétel, ' . $rendelesszam . ' ' . $nev);
                    $mailer->setMessage(
                        'Rendelésszám: ' . $rendelesszam . '<br>' .
                        'Név: ' . $nev . '<br>' .
                        'Email: ' . $email1 . '<br>' .
                        'Telefon: ' . $telefon . '<br>' .
                        'Téma: ' . $temanev . '<br>' .
                        'Szöveg: ' . $szoveg . '<br>'
                    );
                    $mailer->setReplyTo($email1);
                    $mailer->send();
                    $view = $this->getTemplateFactory()->createMainView('kapcsolatkosz.tpl');
                    \mkw\store::fillTemplate($view);
                } else {
                    $kftc = new kapcsolatfelveteltemaController($this->params);
                    $view = $this->getTemplateFactory()->createMainView('kapcsolat.tpl');
                    $view->setVar('nev', $nev);
                    $view->setVar('email1', $email1);
                    $view->setVar('email2', $email2);
                    $view->setVar('telefon', $telefon);
                    $view->setVar('rendelesszam', $rendelesszam);
                    $view->setVar('temalista', $kftc->getSelectList($tema));
                    $view->setVar('szoveg', $szoveg);
                    $view->setVar('hibak', $hibak);
                }
                $view->printTemplateResult(false);
                break;
            default :
                $kftc = new kapcsolatfelveteltemaController($this->params);
                $this->view = $this->getTemplateFactory()->createMainView('kapcsolat.tpl');
                \mkw\store::fillTemplate($this->view);
                $this->view->setVar('pagetitle', 'Kapcsolatfelvétel a webáruház ügyfélszolgálatával - ' . \mkw\store::getParameter('oldalcim'));
                $this->view->setVar('temalista', $kftc->getSelectList(0));
                $this->view->printTemplateResult(true);
                break;
        }
    }

    public function setOrszag($orszagkod = null)
    {
        if (!$orszagkod) {
            $orszagkod = $this->params->getIntRequestParam('orszag');
        }
        /** @var \Entities\Orszag $orszag */
        $orszag = $this->getEm()->getRepository('Entities\Orszag')->find($orszagkod);
        if ($orszag && $orszag->getValutanem()) {
            \mkw\store::getMainSession()->orszag = (int)$orszagkod;
            \mkw\store::getMainSession()->valutanem = $orszag->getValutanemId();
            \mkw\store::getMainSession()->valutanemnev = $orszag->getValutanemNev();
            $kc = new kosarController($this->params);
            $kc->recalcPrices();
        }
    }

    public function clearOrszag()
    {
        \mkw\store::getMainSession()->orszag = null;
        \mkw\store::getMainSession()->valutanem = null;
        \mkw\store::getMainSession()->valutanemnev = null;
    }

    public function setLocale()
    {
        $locale = $this->params->getStringRequestParam('locale');
        \mkw\store::setMainLocale($locale);
        $kc = new kosarController($this->params);
        $kc->recalcPrices();
    }
}