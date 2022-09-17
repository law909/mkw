<?php

namespace Controllers;

use mkw\store;
use mkwhelpers\FilterDescriptor;
use Entities;

class orarendhelyettesitesController extends \mkwhelpers\MattableController {

    public function __construct($params) {
		$this->setEntityName('Entities\Orarendhelyettesites');
		$this->setKarbFormTplName('orarendhelyettesiteskarbform.tpl');
		$this->setKarbTplName('orarendhelyettesiteskarb.tpl');
		$this->setListBodyRowTplName('orarendhelyettesiteslista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_helyettesites');
		parent::__construct($params);
	}

    /**
     * @param \Entities\Orarendhelyettesites $t
     * @param bool $forKarb
     * @return array
     */
	protected function loadVars($t, $forKarb = false) {
		$x = array();
		if (!$t) {
			$t = new \Entities\Orarendhelyettesites();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
        $x['helyettesitonev'] = $t->getHelyettesitoNev();
        $x['oranev'] = $t->getOrarendNev();
        $x['datum'] = $t->getDatumStr();
		$x['inaktiv'] = $t->getInaktiv();
		$x['elmarad'] = $t->getElmarad();
		return $x;
	}

    /**
     * @param \Entities\Orarendhelyettesites $obj
     * @return mixed
     */
	protected function setFields($obj) {
	    switch ($this->params->getStringRequestParam('oper')) {
	        case 'edit':
                $helyettesito = \mkw\store::getEm()->getRepository('Entities\Dolgozo')->find($this->params->getIntRequestParam('helyettesito'));
                if ($helyettesito) {
                    $obj->setHelyettesito($helyettesito);
                }
                else {
                    $obj->setHelyettesito(null);
                }
                $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
                $oldelmarad = $obj->getElmarad();
                $obj->setElmarad($this->params->getBoolRequestParam('elmarad'));
                if ($obj->getElmarad() && $oldelmarad !== $obj->getElmarad()) {
                    $this->sendErtesitoEmail($obj);
                }
                break;
            default:
                $orarend = \mkw\store::getEm()->getRepository('Entities\Orarend')->find($this->params->getIntRequestParam('orarend'));
                if ($orarend) {
                    $obj->setOrarend($orarend);
                }
                $helyettesito = \mkw\store::getEm()->getRepository('Entities\Dolgozo')->find($this->params->getIntRequestParam('helyettesito'));
                if ($helyettesito) {
                    $obj->setHelyettesito($helyettesito);
                }
                else {
                    $obj->setHelyettesito(null);
                }
                $obj->setDatum($this->params->getStringRequestParam('datum'));
                $obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
                $obj->setElmarad($this->params->getBoolRequestParam('elmarad'));
                if ($obj->getElmarad()) {
                    $this->sendErtesitoEmail($obj);
                }
        }
//		$obj->doStuffOnPrePersist();
		return $obj;
	}

	protected function sendErtesitoEmail($obj) {
        $ora = $this->getRepo(Entities\Orarend::class)->find($obj->getOrarendId());
        if ($ora) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('orarend', '=', $obj->getOrarendId());
            $filter->addFilter('datum', '=', $obj->getDatum()->format(\mkw\store::$SQLDateFormat));
            $resztvevok = $this->getRepo(Entities\JogaBejelentkezes::class)->getAll($filter, ['partnernev' => 'ASC']);

            /** @var \Entities\JogaBejelentkezes $resztvevo */
            foreach ($resztvevok as $resztvevo) {
                $email = $resztvevo->getPartneremail();
                $emailtpl = $this->getRepo('\Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaElmaradasErtesitoSablon));
                if ($email && $emailtpl) {
                    $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                    $body = \mkw\store::getTemplateFactory()->createMainView(
                        'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
                    );
                    $body->setVar('oranev', $ora->getJogaoratipusNev());
                    $body->setVar('tanarnev', $ora->getDolgozoNev());
                    $body->setVar('idopont', $ora->getKezdetStr());
                    if ($resztvevo) {
                        $body->setVar('partnerkeresztnev', $resztvevo->getPartnerKeresztnev());
                        $body->setVar('partnervezeteknev', $resztvevo->getPartnerVezeteknev());
                    }
                    $body->setVar('datum', $obj->getDatumStr());

                    $mailer = \mkw\store::getMailer();

                    $mailer->addTo($email);
                    $mailer->setSubject($subject->getTemplateResult());
                    $mailer->setMessage($body->getTemplateResult());

                    $mailer->send();
                }
            }
        }
    }
    /**
     * @param \Entities\Orarendhelyettesites $o
     * @return mixed
     */
    protected function afterSave($o, $parancs = null) {
        parent::afterSave($o, $parancs);
        $dolgozo = $o->getOrarend()->getDolgozo();
        if ($parancs !== $this->delOperation && ($o->getElmarad() || $o->getHelyettesitoId()) && $dolgozo->isOraelmaradaskonyvelonek()) {
            $email = \mkw\store::getParameter(\mkw\consts::KonyveloEmail);
            $emailtpl = $this->getRepo('Entities\Emailtemplate')->find(\mkw\store::getParameter(\mkw\consts::JogaElmaradasKonyvelonekSablon));
            if ($email && $emailtpl) {

                $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
                $body = \mkw\store::getTemplateFactory()->createMainView('string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg())));
                $body->setVar('tanarnev', $o->getOrarend()->getDolgozoNev());
                $body->setVar('datum', $o->getDatumStr());

                $mailer = \mkw\store::getMailer();

                $mailer->addTo($email);
                $mailer->setSubject($subject->getTemplateResult());
                $mailer->setMessage($body->getTemplateResult());

                $mailer->send();

            }
        }
    }

	public function getlistbody() {
		$view = $this->createView('orarendhelyettesiteslista_tbody.tpl');

		$filter = new \mkwhelpers\FilterDescriptor();
        $f = $this->params->getNumRequestParam('inaktivfilter',9);
        if ($f != 9) {
            $filter->addFilter('inaktiv', '=', $f);
        }
        $f = $this->params->getNumRequestParam('elmaradfilter',9);
        if ($f != 9) {
            $filter->addFilter('elmarad', '=', $f);
        }
        if (!is_null($this->params->getRequestParam('helyettesitofilter', null))) {
            $filter->addFilter('helyettesito' , '=', $this->params->getIntRequestParam('helyettesitofilter'));
        }

        $this->initPager($this->getRepo()->getCount($filter));
        $egyedek = $this->getRepo()->getWithJoins(
                $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'orarendhelyettesiteslista', $view));
	}

	public function getselectlist($selid) {
		$rec = $this->getRepo()->getAllForSelectList(array(), array('nev' => 'ASC'));
		$res = array();
		foreach ($rec as $sor) {
			$res[] = array(
				'id' => $sor['id'],
				'caption' => $sor['nev'],
				'selected' => ($sor['id'] == $selid)
			);
		}
		return $res;
	}

	public function htmllist() {
		$rec = $this->getRepo()->getAllForSelectList(array(), array('nev' => 'asc'));
		$ret = '<select>';
		foreach ($rec as $sor) {
			$ret.='<option value="' . $sor['id'] . '">' . $sor['nev'] . '</option>';
		}
		$ret.='</select>';
		echo $ret;
	}

	public function viewlist() {
		$view = $this->createView('orarendhelyettesiteslista.tpl');
		$view->setVar('pagetitle', t('Helyettesítés'));
		$view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());

        $dc = new dolgozoController($this->params);
        $view->setVar('helyettesitolist', $dc->getSelectList());

        $jtc = new orarendController($this->params);
        $view->setVar('orarendlist', $jtc->getSelectList());

		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id = $this->params->getRequestParam('id', 0);
		$oper = $this->params->getRequestParam('oper', '');
		$view = $this->createView($tplname);
		$view->setVar('pagetitle', t('Helyettesítés'));
		$view->setVar('oper', $oper);

		$ora = $this->getRepo()->findWithJoins($id);
		$view->setVar('egyed', $this->loadVars($ora, true));

        $dc = new dolgozoController($this->params);
        $view->setVar('helyettesitolist', $dc->getSelectList(($ora ? $ora->getHelyettesitoId() : 0)));

        $view->printTemplateResult();
	}

}