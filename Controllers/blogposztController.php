<?php

namespace Controllers;

use mkw\store;
use mkwhelpers\FilterDescriptor;

class blogposztController extends \mkwhelpers\MattableController {

    private $kaphatolett = false;
    private $vanshowarsav = false;

    public function __construct($params) {
		$this->setEntityName('Entities\Blogposzt');
		$this->setKarbFormTplName('blogposztkarbform.tpl');
		$this->setKarbTplName('blogposztkarb.tpl');
		$this->setListBodyRowTplName('blogposztlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_blogposzt');
		parent::__construct($params);
	}

	protected function loadVars($t, $forKarb = false) {
		$x = array();
		if (!$t) {
			$t = new \Entities\Blogposzt();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
        $x['megjelenesdatumstr'] = $t->getMegjelenesdatumStr();
		$x['cim'] = $t->getCim();
		$x['szoveg'] = $t->getSzoveg();
		$x['kivonat'] = $t->getKivonat();
		$x['lathato'] = $t->getLathato();
		$x['termekfa1nev'] = $t->getTermekfa1Nev();
		$x['termekfa2nev'] = $t->getTermekfa2Nev();
		$x['termekfa3nev'] = $t->getTermekfa3Nev();
		$x['termekfa1'] = $t->getTermekfa1Id();
		$x['termekfa2'] = $t->getTermekfa2Id();
		$x['termekfa3'] = $t->getTermekfa3Id();
        $x['slug'] = $t->getSlug();
		$x['kepurl'] = $t->getKepurl();
		$x['kepurlsmall'] = $t->getKepurlSmall();
		$x['kepurlmedium'] = $t->getKepurlMedium();
		$x['kepurllarge'] = $t->getKepurlLarge();
		$x['kepleiras'] = $t->getKepleiras();
		return $x;
	}

    /**
     * @param \Entities\Blogposzt $obj
     * @return mixed
     */
	protected function setFields($obj) {
		$obj->setCim($this->params->getStringRequestParam('cim'));
		$obj->setKivonat($this->params->getStringRequestParam('kivonat'));
		$obj->setSzoveg($this->params->getOriginalStringRequestParam('szoveg'));
		$obj->setLathato($this->params->getBoolRequestParam('lathato'));
		$obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
		$obj->setKepleiras($this->params->getStringRequestParam('kepleiras', ''));
        $obj->setMegjelenesdatum($this->params->getStringRequestParam('megjelenesdatum'));

        $farepo = \mkw\store::getEm()->getRepository('Entities\TermekFa');
		$fa = $farepo->find($this->params->getIntRequestParam('termekfa1'));
		if ($fa) {
			$obj->setTermekfa1($fa);
		}
		else {
			$obj->setTermekfa1(null);
		}
		$fa = $farepo->find($this->params->getIntRequestParam('termekfa2'));
		if ($fa) {
			$obj->setTermekfa2($fa);
		}
		else {
			$obj->setTermekfa2(null);
		}
		$fa = $farepo->find($this->params->getIntRequestParam('termekfa3'));
		if ($fa) {
			$obj->setTermekfa3($fa);
		}
		else {
			$obj->setTermekfa3(null);
		}
		return $obj;
	}

	public function getlistbody() {
		$view = $this->createView('blogposztlista_tbody.tpl');

		$filter = new \mkwhelpers\FilterDescriptor();
        $f = $this->params->getNumRequestParam('lathatofilter',9);
        if ($f != 9) {
            $filter->addFilter('lathato', '=', $f);
        }

        $cf = $this->params->getStringRequestParam('cimfilter');
        if ($cf) {
            $filter->addFilter('cim', 'LIKE', '%' . $cf . '%');
        }

		$fv = $this->params->getArrayRequestParam('fafilter');
		if (!empty($fv)) {
			$ff = new \mkwhelpers\FilterDescriptor();
            $ff->addFilter('id', 'IN', $fv);
			$res = \mkw\store::getEm()->getRepository('Entities\TermekFa')->getAll($ff, array());
			$faszuro = array();
			foreach ($res as $sor) {
				$faszuro[] = $sor->getKarkod() . '%';
			}
            $filter->addFilter(array('_xx.termekfa1karkod', '_xx.termekfa2karkod', '_xx.termekfa3karkod'), 'LIKE', $faszuro);
		}

        $this->initPager($this->getRepo()->getCount($filter));
        $egyedek = $this->getRepo()->getWithJoins(
            $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'blogposztlista', $view));
	}

	public function getSelectList($selid = null) {
		$rec = $this->getRepo()->getAllForSelectList(array(), array('megjelenesdatum' => 'DESC', 'cim' => 'ASC'));
		$res = array();
		foreach ($rec as $sor) {
			$res[] = array(
				'id' => $sor['id'],
				'caption' => $sor['cim'],
				'selected' => ($sor['id'] == $selid)
			);
		}
		return $res;
	}

	public function htmllist() {
		$rec = $this->getRepo()->getAllForSelectList(array(), array('megjelenesdatum' => 'DESC', 'cim' => 'ASC'));
		$ret = '<select>';
		foreach ($rec as $sor) {
			$ret.='<option value="' . $sor['id'] . '">' . $sor['cim'] . '</option>';
		}
		$ret.='</select>';
		echo $ret;
	}

	public function viewlist() {
		$view = $this->createView('blogposztlista.tpl');
		$view->setVar('pagetitle', t('Blogposztok'));
		$view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id = $this->params->getRequestParam('id', 0);
		$oper = $this->params->getRequestParam('oper', '');
		$view = $this->createView($tplname);
		$view->setVar('pagetitle', t('Blogposzt'));
		$view->setVar('oper', $oper);

		$bp = $this->getRepo()->findWithJoins($id);

		$view->setVar('egyed', $this->loadVars($bp, true));

        $view->printTemplateResult();
	}

	public function setflag() {
		$id = $this->params->getIntRequestParam('id');
		$kibe = $this->params->getBoolRequestParam('kibe');
		$flag = $this->params->getStringRequestParam('flag');
		/** @var \Entities\Blogposzt $obj */
		$obj = $this->getRepo()->find($id);
		if ($obj) {
			switch ($flag) {
				case 'lathato':
					$obj->setLathato($kibe);
					break;
			}
			$this->getEm()->persist($obj);
			$this->getEm()->flush();
		}
	}

	public function feed() {
		$feedview = $this->getTemplateFactory()->createMainView('feed.tpl');
		$view = $this->getTemplateFactory()->createMainView('termekfeed.tpl');
		$feedview->setVar('title', \mkw\store::getParameter(\mkw\consts::Feedtermektitle, t('Termékeink')));
		$feedview->setVar('link', \mkw\store::getRouter()->generate('termekfeed', true));
		$d = new \DateTime();
		$feedview->setVar('pubdate', $d->format('D, d M Y H:i:s'));
		$feedview->setVar('lastbuilddate', $d->format('D, d M Y H:i:s'));
		$feedview->setVar('description', \mkw\store::getParameter(\mkw\consts::Feedtermekdescription, ''));
		$entries = array();
		$termekek = $this->getRepo()->getFeedTermek();
		foreach ($termekek as $termek) {
			$view->setVar('kepurl', $termek->getKepUrlSmall());
			$view->setVar('szoveg', $termek->getRovidLeiras());
			$view->setVar('url', \mkw\store::getRouter()->generate('showtermek', true, array('slug' => $termek->getSlug())));
			$entries[] = array(
				'title' => $termek->getNev(),
				'link' => \mkw\store::getRouter()->generate('showtermek', true, array('slug' => $termek->getSlug())),
				'guid' => \mkw\store::getRouter()->generate('showtermek', true, array('slug' => $termek->getSlug())),
				'description' => $view->getTemplateResult(),
				'pubdate' => $d->format('D, d M Y H:i:s')
			);
		}
		$feedview->setVar('entries', $entries);
		header('Content-type: text/xml');
		$feedview->printTemplateResult(false);
	}
}