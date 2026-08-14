<?php

namespace Controllers;

use Entities\Penztarbizonylatfej;
use Entities\Penztarbizonylattetel;

class penztarbizonylatfejController extends \mkwhelpers\MattableController
{

    use \Traits\Kiegyenlites;

    public function __construct()
    {
        $this->setEntityName(Penztarbizonylatfej::class);
        $this->setKarbFormTplName('penztarbizonylatfejkarbform.tpl');
        $this->setKarbTplName('penztarbizonylatfejkarb.tpl');
        $this->setListBodyRowTplName('penztarbizonylatfejlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Penztarbizonylatfej();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['irany'] = $t->getIrany();
        $x['bizonylattipusid'] = $t->getBizonylattipusId();
        $x['rontott'] = $t->getRontott();
        $x['erbizonylatszam'] = $t->getErbizonylatszam();
        $x['megjegyzes'] = $t->getMegjegyzes();
        $x['keltstr'] = $t->getKeltStr();
        $x['brutto'] = $t->getBrutto();
        $x['valutanemnev'] = $t->getValutanemnev();
        $x['valutanem'] = $t->getValutanemId();
        $x['arfolyam'] = $t->getArfolyam();
        $x['penztar'] = $t->getPenztar();
        $x['penztarnev'] = $t->getPenztarnev();
        $x['penztarid'] = $t->getPenztarId();
        $x['partnernev'] = $t->getPartnernev();
        $x['partnerkeresztnev'] = $t->getPartnerkeresztnev();
        $x['partnervezeteknev'] = $t->getPartnervezeteknev();
        $x['partneradoszam'] = $t->getPartneradoszam();
        $x['partnereuadoszam'] = $t->getPartnereuadoszam();
        $x['partnerirszam'] = $t->getPartnerirszam();
        $x['partnervaros'] = $t->getPartnervaros();
        $x['partnerutca'] = $t->getPartnerutca();
        $x['partnerhazszam'] = $t->getPartnerhazszam();
        $x['updatedby'] = $t->getUpdatedbyNev();
        $x['createdby'] = $t->getCreatedbyNev();
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();

        $x['nemrossz'] = !$t->getRontott();

        if ($forKarb) {
            $tetelCtrl = new penztarbizonylattetelController();
            foreach ($t->getBizonylattetelek() as $ttetel) {
                $tetel[] = $tetelCtrl->loadVars($ttetel, true);
            }
            $x['tetelek'] = $tetel;
        }
        return $x;
    }

    /**
     * @param \Entities\Penztarbizonylatfej $obj
     *
     * @return \Entities\Penztarbizonylatfej
     */
    protected function setFields($obj)
    {
        $quick = $this->params->getBoolRequestParam('quick');

        $obj->setErbizonylatszam($this->params->getStringRequestParam('erbizonylatszam'));
        $obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
        $obj->setKelt($this->params->getStringRequestParam('kelt'));

        $arfolyam = $this->params->getNumRequestParam('arfolyam');
        if (!$arfolyam) {
            $arfolyam = 1;
        }
        $obj->setArfolyam($arfolyam);

        $partnerkod = $this->params->getIntRequestParam('partner');

        if ($partnerkod == -1) {
            $partneremail = $this->params->getStringRequestParam('partneremail');
            if ($partneremail) {
                $partnerobj = $this->getRepo('Entities\Partner')->findOneBy(['email' => $partneremail]);
                if (!$partnerobj) {
                    $partnerobj = new \Entities\Partner();
                }
            } else {
                $partnerobj = new \Entities\Partner();
            }
            $partnerobj->setEmail($this->params->getStringRequestParam('partneremail'));
            $partnerobj->setTelefon($this->params->getStringRequestParam('partnertelefon'));
            $partnerobj->setVezeteknev($this->params->getStringRequestParam('partnervezeteknev'));
            $partnerobj->setKeresztnev($this->params->getStringRequestParam('partnerkeresztnev'));
            if ($partnerobj->getVezeteknev() || $partnerobj->getKeresztnev()) {
                $partnerobj->setNev($partnerobj->getVezeteknev() . ' ' . $partnerobj->getKeresztnev());
            }
            $partnerobj->setIrszam($this->params->getStringRequestParam('partnerirszam'));
            $partnerobj->setVaros($this->params->getStringRequestParam('partnervaros'));
            $partnerobj->setUtca($this->params->getStringRequestParam('partnerutca'));
            $partnerobj->setHazszam($this->params->getStringRequestParam('partnerhazszam'));
            $this->getEm()->persist($partnerobj);
            $obj->setPartner($partnerobj);
        } else {
            $partnerobj = \mkw\store::getEm()->getRepository('Entities\Partner')->find($partnerkod);
            if ($partnerobj) {
                if ($quick) {
                    $partnerobj->setEmail($this->params->getStringRequestParam('partneremail'));
                    $partnerobj->setTelefon($this->params->getStringRequestParam('partnertelefon'));
                    $partnerobj->setVezeteknev($this->params->getStringRequestParam('partnervezeteknev'));
                    $partnerobj->setKeresztnev($this->params->getStringRequestParam('partnerkeresztnev'));
                    if ($partnerobj->getVezeteknev() || $partnerobj->getKeresztnev()) {
                        $partnerobj->setNev($partnerobj->getVezeteknev() . ' ' . $partnerobj->getKeresztnev());
                    }
                    $partnerobj->setIrszam($this->params->getStringRequestParam('partnerirszam'));
                    $partnerobj->setVaros($this->params->getStringRequestParam('partnervaros'));
                    $partnerobj->setUtca($this->params->getStringRequestParam('partnerutca'));
                    $partnerobj->setHazszam($this->params->getStringRequestParam('partnerhazszam'));
                    $this->getEm()->persist($partnerobj);
                }

                $obj->setPartner($partnerobj);
            }
        }

        $valutanem = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem'));
        if ($valutanem) {
            $obj->setValutanem($valutanem);
        }

        $ck = \mkw\store::getEm()->getRepository('Entities\Penztar')->find($this->params->getIntRequestParam('penztar'));
        if ($ck) {
            $obj->setPenztar($ck);
        }

        $bt = $this->getRepo('Entities\Bizonylattipus')->find('penztar');
        $obj->setBizonylattipus($bt);
        $obj->setIrany($this->params->getIntRequestParam('irany'));

        if ($quick) {
            if (($this->params->getIntRequestParam('teteljogcim') > 0)) {
                $jogcim = $this->getEm()->getRepository('Entities\Jogcim')->find($this->params->getIntRequestParam('teteljogcim'));
                if ($jogcim) {
                    $tetel = new Penztarbizonylattetel();
                    $obj->addBizonylattetel($tetel);

                    $tetel->setJogcim($jogcim);
                    $tetel->setHivatkozottbizonylat($this->params->getStringRequestParam('tetelhivatkozottbizonylat'));
                    $tetel->setHivatkozottdatum($this->params->getStringRequestParam('tetelhivatkozottdatum'));
                    $tetel->setSzoveg($this->params->getStringRequestParam('tetelszoveg'));

                    $tetel->setBrutto($this->params->getFloatRequestParam('tetelosszeg'));

                    $this->getEm()->persist($tetel);
                } else {
                    \mkw\store::writelog(print_r($this->params->asArray(), true), 'nincsjogcim.log');
                }
            }
        } else {
            $tetelids = $this->params->getArrayRequestParam('tetelid');
            foreach ($tetelids as $tetelid) {
                if (($this->params->getIntRequestParam('teteljogcim_' . $tetelid) > 0)) {
                    $oper = $this->params->getStringRequestParam('teteloper_' . $tetelid);
                    $jogcim = $this->getEm()->getRepository('Entities\Jogcim')->find($this->params->getIntRequestParam('teteljogcim_' . $tetelid));
                    if ($jogcim) {
                        switch ($oper) {
                            case $this->addOperation:
                            case $this->inheritOperation:
                                $tetel = new Penztarbizonylattetel();
                                $obj->addBizonylattetel($tetel);

                                $tetel->setJogcim($jogcim);
                                $tetel->setHivatkozottbizonylat($this->params->getStringRequestParam('tetelhivatkozottbizonylat_' . $tetelid));
                                $tetel->setHivatkozottdatum($this->params->getStringRequestParam('tetelhivatkozottdatum_' . $tetelid));
                                $tetel->setSzoveg($this->params->getStringRequestParam('tetelszoveg_' . $tetelid));

                                $tetel->setBrutto($this->params->getFloatRequestParam('tetelosszeg_' . $tetelid));

                                $this->getEm()->persist($tetel);
                                break;
                            case $this->editOperation:
                                /** @var \Entities\Penztarbizonylattetel $tetel */
                                $tetel = $this->getEm()->getRepository('Entities\Penztarbizonylattetel')->find($tetelid);
                                if ($tetel) {
                                    $tetel->setJogcim($jogcim);
                                    $tetel->setHivatkozottbizonylat($this->params->getStringRequestParam('tetelhivatkozottbizonylat_' . $tetelid));
                                    $tetel->setHivatkozottdatum($this->params->getStringRequestParam('tetelhivatkozottdatum_' . $tetelid));
                                    $tetel->setSzoveg($this->params->getStringRequestParam('tetelszoveg_' . $tetelid));

                                    $tetel->setBrutto($this->params->getFloatRequestParam('tetelosszeg_' . $tetelid));

                                    $this->getEm()->persist($tetel);
                                }
                                break;
                        }
                    } else {
                        \mkw\store::writelog(print_r($this->params->asArray(), true), 'nincsjogcim.log');
                    }
                }
            }
        }
        return $obj;
    }

    protected function setVars($view)
    {
        $bt = $this->getRepo('Entities\Bizonylattipus')->find('penztar');
        if ($bt) {
            $bt->setTemplateVars($view);
        }

        $vc = new valutanemController();
        $view->setVar('valutanemlist', $vc->getSelectList());

        $bc = new penztarController();
        $view->setVar('penztarlist', $bc->getSelectList());
    }

    public function getlistbody()
    {
        $view = $this->createView('penztarbizonylatfejlista_tbody.tpl');

        $this->setVars($view);

        $filter = new \mkwhelpers\FilterDescriptor();

        if (!is_null($this->params->getRequestParam('idfilter', null))) {
            $filter->addFilter('id', 'LIKE', '%' . $this->params->getStringRequestParam('idfilter'));
        }

        $f = $this->params->getStringRequestParam('vevonevfilter');
        if ($f) {
            $filter->addFilter('partnernev', 'LIKE', '%' . $f . '%');
        }

        $v = $this->getRepo('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanemfilter'));
        if ($v) {
            $filter->addFilter('valutanem', '=', $v);
        }

        $b = $this->getRepo('Entities\Penztar')->find($this->params->getIntRequestParam('penztarfilter'));
        if ($b) {
            $filter->addFilter('penztar', '=', $b);
        }

        $tol = $this->params->getStringRequestParam('datumtolfilter');
        $ig = $this->params->getStringRequestParam('datumigfilter');
        if ($tol || $ig) {
            if ($tol) {
                $filter->addFilter('kelt', '>=', $tol);
            }
            if ($ig) {
                $filter->addFilter('kelt', '<=', $ig);
            }
        }
        $f = $this->params->getStringRequestParam('erbizonylatszamfilter');
        if ($f) {
            $filter->addFilter('erbizonylatszam', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getIntRequestParam('bizonylatrontottfilter');
        switch ($f) {
            case 1:
                $filter->addFilter('rontott', '=', false);
                break;
            case 2:
                $filter->addFilter('rontott', '=', true);
                break;
        }
        $f = $this->params->getIntRequestParam('iranyfilter');
        switch ($f) {
            case 1:
                $filter->addFilter('irany', '=', 1);
                break;
            case -1:
                $filter->addFilter('irany', '=', -1);
                break;
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('penztarbizonylatfejlista.tpl');

        $this->setVars($view);

        $view->setVar('pagetitle', t('Pénztárbizonylat'));
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('penztarbizonylatfejlista.tpl');

        $this->setVars($view);

        $view->setVar('pagetitle', t('Pénztárbizonylat'));
        if (\mkw\store::isSuperzoneB2B()) {
            $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl(7));
        } else {
            $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        }
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    /**
     * A pénztárbizonylat nyomtatási képe. A sablont a bizonylattípus tplname mezője
     * adja meg (alapértelmezésben biz_penztar.tpl). A renderelést és a lapozott/régi
     * mód eldöntését a BizonylatPrintService végzi, csak a sablonváltozók állnak itt össze.
     *
     * @param string $id
     *
     * @return array{html: string, paged: bool}|null
     */
    public function renderBizonylat($id)
    {
        /** @var \Entities\Penztarbizonylatfej $o */
        $o = $this->getRepo()->find($id);
        if (!$o) {
            return null;
        }

        $bt = $o->getBizonylattipus();
        $tplname = ($bt ? $bt->getTplname() : '') ?: 'biz_penztar.tpl';

        $x = $this->loadVars($o);
        $x['bizonylatnev'] = $bt ? $bt->getNev() : t('Pénztárbizonylat');
        // a biz_base.tpl ezeket a kulcsokat várja; a pénztárbizonylatnak nincs
        // nyomtatva jelzője és nem esik a számlarendelet hatálya alá
        $x['szamlanev'] = $o->getPartnernev();
        $x['nyomtatva'] = false;
        $x['printrendelet'] = false;
        $x['programnev'] = \mkw\store::getParameter(\mkw\consts::ProgramNev);
        $x['tulajnev'] = \mkw\store::getParameter(\mkw\consts::Tulajnev);
        $x['tulajirszam'] = \mkw\store::getParameter(\mkw\consts::Tulajirszam);
        $x['tulajvaros'] = \mkw\store::getParameter(\mkw\consts::Tulajvaros);
        $x['tulajutca'] = \mkw\store::getParameter(\mkw\consts::Tulajutca);
        $x['tulajadoszam'] = \mkw\store::getParameter(\mkw\consts::Tulajadoszam);
        $x['bruttokiirva'] = \mkw\store::Num2Text($o->getBrutto());

        $tetelCtrl = new penztarbizonylattetelController();
        $tetellista = [];
        foreach ($o->getBizonylattetelek() as $tetel) {
            $tetellista[] = $tetelCtrl->loadVars($tetel);
        }
        $x['tetellista'] = $tetellista;

        $vars = ['egyed' => $x];
        if ($bt) {
            $vars = array_merge($bt->getTemplateVars(), $vars);
        }
        return (new \Services\BizonylatPrintService())->renderTemplate($tplname, $vars);
    }

    public function doPrint()
    {
        $id = $this->params->getStringRequestParam('id');
        $r = $this->renderBizonylat($id);
        if ($r) {
            (new \Services\BizonylatPrintService())->outputResult($r, $id);
        }
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Pénztárbizonylat'));
        $view->setVar('oper', $oper);
        $view->setVar('formaction', '/admin/penztarbizonylatfej/save');
        $record = $this->getRepo()->findWithJoins($id);
        $egyed = $this->loadVars($record, true);
        $partnerid = $record ? $record->getPartnerId() : 0;

        // a bizonylatlista "Kiegyenlít" gombjáról érkezve a form a kiegyenlítendő
        // bizonylat adataival indul
        $kiegy = $record ? null : $this->kiegyenlitendo();
        if ($kiegy) {
            $partnerid = $kiegy['partnerid'];
            $egyed['irany'] = $kiegy['irany'];
            $egyed['keltstr'] = $kiegy['keltstr'];
            $egyed['tetelek'] = [$this->kiegyenlitesTetel($kiegy)];
        }
        $view->setVar('egyed', $egyed);

        $bt = $this->getRepo('Entities\Bizonylattipus')->find('penztar');
        $bt->setTemplateVars($view);

        $partner = new partnerController();
        $view->setVar('partnerlist', $partner->getSelectList($partnerid));

        $valutanem = new valutanemController();
        if (!$record || !$record->getValutanemId()) {
            $valutaid = \mkw\store::getParameter(\mkw\consts::Valutanem, 0);
        } else {
            $valutaid = $record->getValutanemId();
        }
        $view->setVar('valutanemlist', $valutanem->getSelectList($valutaid));

        $penztar = new penztarController();
        $penztarid = false;
        if ($record && $record->getPenztarId()) {
            $penztarid = $record->getPenztarId();
        }
        $view->setVar('penztarlist', $penztar->getSelectList($penztarid));

        return $view->getTemplateResult();
    }

    /**
     * A kiegyenlítés egyetlen, előre kitöltött tételsora. A penztarbizonylattetelController
     * új sort ad (saját UID + oper=add), abba írjuk bele a bizonylat adatait.
     *
     * @param array $kiegy
     *
     * @return array
     */
    private function kiegyenlitesTetel($kiegy)
    {
        $tetelCtrl = new penztarbizonylattetelController();
        $jogcimCtrl = new jogcimController();

        $tetel = $tetelCtrl->loadVars(null, true);
        $tetel['jogcim'] = $kiegy['jogcimid'];
        $tetel['jogcimlist'] = $jogcimCtrl->getSelectList($kiegy['jogcimid']);
        $tetel['hivatkozottbizonylat'] = $kiegy['bizonylat'];
        $tetel['hivatkozottdatumstr'] = $kiegy['esedekessegstr'];
        $tetel['brutto'] = $kiegy['osszeg'];
        return $tetel;
    }

    public function ront()
    {
        $id = $this->params->getStringRequestParam('id');
        if ($id) {
            $bf = $this->getRepo()->find($id);
            if ($bf) {
                $bf->setRontott(true);
                $this->getEm()->persist($bf);
                $this->getEm()->flush();
            }
        }
    }

    public function zarasView()
    {
        $view = $this->createView('penztarzaras.tpl');
        $view->setVar('pagetitle', t('Pénztár zárás'));
        $view->setVar('datum', date(\mkw\store::$DateFormat));

        $penztar = new penztarController();
        $view->setVar('penztarlist', $penztar->getSelectList());
        $view->printTemplateResult();
    }

    public function zar()
    {
        $penztarid = $this->params->getIntRequestParam('penztar');
        if ($this->getRepo('Entities\Penztar')->find($penztarid)) {
            $datum = date_create_from_format(\mkw\store::$JavascriptDateFormat, $this->params->getStringRequestParam('datum'));
            if ($datum) {
                \mkw\store::setParameter(\mkw\consts::PenztarZarva . $penztarid, $datum->format(\mkw\store::$SQLDateFormat));
            }
        }
    }

    public function checkZartIdoszak()
    {
        if (\mkw\store::getAdminSession()->loggedinuser['jog'] == 999) {
            $res = ['response' => 'ok'];
        } else {
            $res = ['response' => 'error'];
            $penztarid = $this->params->getIntRequestParam('penztar');
            if ($this->getRepo('Entities\Penztar')->find($penztarid)) {
                $datum = date_create_from_format(\mkw\store::$JavascriptDateFormat, $this->params->getStringRequestParam('datum'));
                $zart = date_create_from_format(\mkw\store::$SQLDateFormat, \mkw\store::getParameter(\mkw\consts::PenztarZarva . $penztarid));
                if ($datum && $zart) {
                    $diff = $datum->diff($zart);
                    if ($diff && $diff->days > 0 && $diff->invert === 1) {
                        $res['response'] = 'ok';
                    }
                } elseif (!$zart) {
                    $res['response'] = 'ok';
                }
            }
        }
        echo json_encode($res);
    }

}