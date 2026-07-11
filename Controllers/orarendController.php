<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\JogaBejelentkezes;
use Entities\Jogaoratipus;
use Entities\Jogaterem;
use Entities\Orarend;
use Entities\Orarendhelyettesites;
use Entities\Rendezveny;
use mkw\store;
use mkwhelpers\FilterDescriptor;

class orarendController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Orarend::class);
        $this->setKarbFormTplName('orarendkarbform.tpl');
        $this->setKarbTplName('orarendkarb.tpl');
        $this->setListBodyRowTplName('orarendlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_orarend');
        parent::__construct();
    }

    /**
     * @param \Entities\Orarend $t
     * @param bool $forKarb
     *
     * @return array
     */
    protected function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Orarend();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['dolgozonev'] = $t->getDolgozoNev();
        $x['jogateremnev'] = $t->getJogateremNev();
        $x['jogaoratipusnev'] = $t->getJogaoratipusNev();
        $x['napnev'] = $t->getNapNev();
        $x['kezdet'] = $t->getKezdetStr();
        $x['veg'] = $t->getVegStr();
        return $x;
    }

    /**
     * @param \Entities\Orarend $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj, ['raw' => ['onlineurl']]);

        $dolgozo = \mkw\store::getEm()->getRepository(Dolgozo::class)->find($this->params->getIntRequestParam('dolgozo'));
        if ($dolgozo) {
            $obj->setDolgozo($dolgozo);
        } else {
            $obj->setDolgozo(null);
        }
        $jogaterem = \mkw\store::getEm()->getRepository(Jogaterem::class)->find($this->params->getIntRequestParam('jogaterem'));
        if ($jogaterem) {
            $obj->setJogaterem($jogaterem);
        } else {
            $obj->setJogaterem(null);
        }
        $jogaoratipus = \mkw\store::getEm()->getRepository(Jogaoratipus::class)->find($this->params->getIntRequestParam('jogaoratipus'));
        if ($jogaoratipus) {
            $obj->setJogaoratipus($jogaoratipus);
        } else {
            $obj->setJogaoratipus(null);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('orarendlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        $f = $this->params->getNumRequestParam('inaktivfilter', 9);
        if ($f != 9) {
            $filter->addFilter('inaktiv', '=', $f);
        }
        $f = $this->params->getNumRequestParam('multilangfilter', 9);
        if ($f != 9) {
            $filter->addFilter('multilang', '=', $f);
        }
        if (!is_null($this->params->getRequestParam('napfilter', null))) {
            $filter->addFilter('nap', '=', $this->params->getIntRequestParam('napfilter'));
        }
        if (!is_null($this->params->getRequestParam('jogaoratipusfilter', null))) {
            $filter->addFilter('jogaoratipus', '=', $this->params->getIntRequestParam('jogaoratipusfilter'));
        }
        if (!is_null($this->params->getRequestParam('jogateremfilter', null))) {
            $filter->addFilter('jogaterem', '=', $this->params->getIntRequestParam('jogateremfilter'));
        }
        if (!is_null($this->params->getRequestParam('dolgozofilter', null))) {
            $filter->addFilter('dolgozo', '=', $this->params->getIntRequestParam('dolgozofilter'));
        }

        $this->initPager($this->getRepo()->getCount($filter));
        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'orarendlista', $view));
    }

    public function getselectlist($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid)
            ];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function getListForHelyettesites()
    {
        $datum = new \DateTime(\mkw\store::convDate($this->params->getDateRequestParam('datum')));
        if ($datum) {
            $nap = $datum->format('N');
            $filter = new FilterDescriptor();
            $filter->addFilter('nap', '=', $nap);
            $filter->addFilter('inaktiv', '=', false);
            $rec = $this->getRepo()->getAll($filter, ['nev' => 'asc']);
            $ret = '<select id="OrarendEdit" name="orarend" required="required">';
            /** @var \Entities\Orarend $sor */
            foreach ($rec as $sor) {
                $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNevTanar() . '</option>';
            }
            $ret .= '</select>';
            echo $ret;
        }
    }

    public function viewlist()
    {
        $view = $this->createView('orarendlista.tpl');
        $view->setVar('pagetitle', t('Órarend'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());

        $dc = new dolgozoController();
        $view->setVar('dolgozolist', $dc->getSelectList());

        $jtc = new jogateremController();
        $view->setVar('jogateremlist', $jtc->getSelectList());

        $jotc = new jogaoratipusController();
        $view->setVar('jogaoratipuslist', $jotc->getSelectList());

        $view->setVar('naplist', store::getDaynameSelectList());

        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Órarend'));
        $view->setVar('oper', $oper);

        $ora = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($ora, true));

        $dc = new dolgozoController();
        $view->setVar('dolgozolist', $dc->getSelectList(($ora ? $ora->getDolgozoId() : 0)));

        $jtc = new jogateremController();
        $view->setVar('jogateremlist', $jtc->getSelectList(($ora ? $ora->getJogateremId() : 0)));

        $jotc = new jogaoratipusController();
        $view->setVar('jogaoratipuslist', $jotc->getSelectList(($ora ? $ora->getJogaoratipusId() : 0)));

        $view->setVar('naplist', store::getDaynameSelectList(($ora ? $ora->getNap() : 0)));

        $view->printTemplateResult();
    }

    public function setflag()
    {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag');
        /** @var \Entities\Orarend $obj */
        $obj = $this->getRepo()->find($id);
        if ($obj) {
            switch ($flag) {
                case 'inaktiv':
                    $obj->setInaktiv($kibe);
                    break;
                case 'bejelentkezeskell':
                    $obj->setBejelentkezeskell($kibe);
                    break;
                case 'multilang':
                    $obj->setMultilang($kibe);
                    break;
                case 'lemondhato':
                    $obj->setLemondhato($kibe);
                    break;
                case 'orarendbennincs':
                    $obj->setOrarendbennincs($kibe);
                    break;
                case 'bejelentkezesertesitokell':
                    $obj->setBejelentkezesertesitokell($kibe);
                    break;
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function exportToWordpress()
    {
        $offset = $this->params->getIntRequestParam('o', 0);
        $tanarkod = $this->params->getIntRequestParam('t', 0);
        $csakfelmeres = $this->params->getIntRequestParam('f', 0);
        $startdatum = \mkw\store::startOfWeek();
        if ($offset < 0) {
            $startdatum->sub(new \DateInterval('P' . abs($offset) . 'W'));
        } else {
            $startdatum->add(new \DateInterval('P' . $offset . 'W'));
        }
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('inaktiv', '=', false);
        $filter->addFilter('orarendbennincs', '=', false);
        if ($tanarkod) {
            $filter->addFilter('dolgozo', '=', $tanarkod);
        }
        if ($csakfelmeres) {
            $filter->addFilter('jogaoratipus', '=', \mkw\store::getParameter(\mkw\consts::JogaAllapotfelmeresTipus));
        } else {
            $filter->addFilter('jogaoratipus', '<>', \mkw\store::getParameter(\mkw\consts::JogaAllapotfelmeresTipus));
        }
        $rec = $this->getRepo()->getWithJoins($filter, ['nap' => 'ASC', 'kezdet' => 'ASC', 'nev' => 'ASC']);
        $orarend = [];
        /** @var \Entities\Orarend $item */
        foreach ($rec as $item) {
            $orak = [
                'id' => $item->getId(),
                'kezdet' => $item->getKezdetStr(),
                'veg' => $item->getVegStr(),
                'oranev' => $item->getNev(),
                'oraurl' => $item->getJogaoratipusUrl(),
                'tanar' => $item->getDolgozoNev(),
                'tanarurl' => $item->getDolgozoUrl(),
                'teremclass' => $item->getJogateremOrarendclass(),
                'helyettesito' => '',
                'helyettesitourl' => '',
                'terem' => $item->getJogateremNev(),
                'class' => $item->getJogateremOrarendclass(),
                'delelott' => $item->isDelelottKezdodik(),
                'elmarad' => false,
                'multilang' => $item->getMultilang(),
                'onlineurl' => $item->getOnlineurl(),
                'bejelentkezeskell' => $item->isBejelentkezeskell(),
                'lemondhato' => $item->getLemondhato()
            ];
            $xdatum = clone $startdatum;
            $napdatum = $xdatum->add(new \DateInterval('P' . ($item->getNap() - 1) . 'D'));
            $orak['datum'] = $napdatum->format(\mkw\store::$SQLDateFormat);
            $orak['bejelentkezesdb'] = $this->getRepo(JogaBejelentkezes::class)->getAdottOraCount($napdatum, $item->getId());
            $orak['maxbejelentkezes'] = $item->getMaxferohely();
            $orak['szabadhely'] = $orak['maxbejelentkezes'] - $orak['bejelentkezesdb'];
            if ($orak['maxbejelentkezes'] <= 0) {
                $orak['megvanhely'] = true;
            } else {
                $orak['megvanhely'] = $orak['szabadhely'] > 0;
            }

            $hf = new \mkwhelpers\FilterDescriptor();
            $hf->addFilter('datum', '>=', \mkw\store::startOfWeek($startdatum));
            $hf->addFilter('datum', '<=', \mkw\store::endOfWeek($startdatum));
            $hf->addFilter('orarend', '=', $item->getId());
            $hrec = $this->getRepo(Orarendhelyettesites::class)->getAll($hf, []);
            if ($hrec) {
                $elmarad = false;
                foreach ($hrec as $h) {
                    if ($h->getElmarad()) {
                        $elmarad = true;
                    }
                }
                $orak['elmarad'] = $elmarad;
                $orak['helyettesito'] = $hrec[0]->getHelyettesitoNev();
                $orak['helyettesitourl'] = $hrec[0]->getHelyettesitoUrl();
            }
            $orarend[$item->getNap()]['napnev'] = \mkw\store::getDayname($item->getNap());
            $xdatum = clone $startdatum;
            $orarend[$item->getNap()]['napdatum'] = $xdatum->add(new \DateInterval('P' . ($item->getNap() - 1) . 'D'))->format(\mkw\store::$DateFormat);
            $orarend[$item->getNap()]['orak'][] = $orak;
        }
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('orarendbenszerepel', '=', true);
        $filter->addFilter('ra.orarendbenszerepel', '=', true);
        $rec = $this->getRepo(Rendezveny::class)->getWithJoins($filter, ['kezdodatum' => 'ASC', 'kezdoido' => 'ASC']);
        /** @var \Entities\Rendezveny $item */
        foreach ($rec as $item) {
            $orak = [
                'id' => $item->getId(),
                'kezdet' => $item->getKezdoido(),
                'veg' => '',
                'oranev' => $item->getTeljesNev(),
                'oraurl' => $item->getUrl(),
                'tanar' => $item->getTanar()?->getNev(),
                'tanarurl' => $item->getTanar()?->getUrl(),
                'teremclass' => '',
                'helyettesito' => '',
                'helyettesitourl' => '',
                'terem' => $item->getJogaterem()?->getNev(),
                'class' => $item->getJogaterem()?->getOrarendclass(),
                'delelott' => false,
                'elmarad' => false,
                'multilang' => false,
                'onlineurl' => $item->getOnlineurl(),
                'bejelentkezeskell' => false,
                'datum' => '',
                'bejelentkezesdb' => 0,
                'maxbejelentkezes' => 0,
                'megvanhely' => true,
                'szabadhely' => 0,
                'lemondhato' => false
            ];
            if (!array_key_exists($item->getNap(), $orarend)) {
                $orarend[$item->getNap()]['napnev'] = \mkw\store::getDayname($item->getNap());
                $orarend[$item->getNap()]['napdatum'] = $item->getKezdodatumStr();
            }
            $orarend[$item->getNap()]['orak'][] = $orak;
        }

        foreach ($orarend as $elem) {
            uasort($elem['orak'], function ($a, $b) {
                return strnatcmp($a['kezdet'], $b['kezdet']);
            });
        }

        $view = $this->createView('orarendwordpress.tpl');
        $view->setVar('orarend', $orarend);
        $view->setVar('prevoffset', $offset - 1);
        $view->setVar('nextoffset', $offset + 1);
        $view->setVar('tanarkod', $tanarkod);
        $view->printTemplateResult();
    }

    public function doPrint()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('inaktiv', '=', false);
        $filter->addFilter('alkalmi', '=', false);
        $rec = $this->getRepo()->getWithJoins($filter, ['nap' => 'ASC', 'kezdet' => 'ASC', 'nev' => 'ASC']);
        $orarend = [];
        /** @var \Entities\Orarend $item */
        foreach ($rec as $item) {
            $orarend[$item->getNap()]['napnev'] = \mkw\store::getDayname($item->getNap());
            $orarend[$item->getNap()]['orak'][] = [
                'kezdet' => $item->getKezdetStr(),
                'veg' => $item->getVegStr(),
                'oranev' => $item->getNev(),
                'oraurl' => $item->getJogaoratipusUrl(),
                'tanar' => $item->getDolgozoNev(),
                'tanarurl' => $item->getDolgozoUrl(),
                'terem' => $item->getJogateremNev(),
                'class' => $item->getJogateremOrarendclass(),
                'delelott' => $item->isDelelottKezdodik(),
                'atlagresztvevoszam' => $item->getAtlagresztvevoszam(),
                'multilang' => $item->getMultilang(),
                'online' => $item->getOnlineurl()
            ];
        }
        $view = $this->createView('orarendprint.tpl');
        $view->setVar('orarend', $orarend);
        $view->printTemplateResult();
    }

}