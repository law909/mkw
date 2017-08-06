<?php

namespace Controllers;

use mkw\store;
use mkwhelpers\FilterDescriptor;

class orarendController extends \mkwhelpers\MattableController {

    public function __construct($params) {
		$this->setEntityName('Entities\Orarend');
		$this->setKarbFormTplName('orarendkarbform.tpl');
		$this->setKarbTplName('orarendkarb.tpl');
		$this->setListBodyRowTplName('orarendlista_tbody_tr.tpl');
		$this->setListBodyRowVarName('_orarend');
		parent::__construct($params);
	}

    /**
     * @param \Entities\Orarend $t
     * @param bool $forKarb
     * @return array
     */
	protected function loadVars($t, $forKarb = false) {
        $dolgozoCtrl = new dolgozoController($this->params);
		$x = array();
		if (!$t) {
			$t = new \Entities\Orarend();
			$this->getEm()->detach($t);
		}
		$x['id'] = $t->getId();
		$x['nev'] = $t->getNev();
		$x['dolgozonev'] = $t->getDolgozoNev();
		$x['jogateremnev'] = $t->getJogateremNev();
		$x['jogaoratipusnev'] = $t->getJogaoratipusNev();
		$x['maxferohely'] = $t->getMaxferohely();
        $x['napnev'] = $t->getNapNev();
        $x['kezdet'] = $t->getKezdetStr();
		$x['veg'] = $t->getVegStr();
		$x['inaktiv'] = $t->getInaktiv();
		return $x;
	}

    /**
     * @param \Entities\Orarend $obj
     * @return mixed
     */
	protected function setFields($obj) {
		$dolgozo = \mkw\store::getEm()->getRepository('Entities\Dolgozo')->find($this->params->getIntRequestParam('dolgozo'));
		if ($dolgozo) {
			$obj->setDolgozo($dolgozo);
		}
		else {
		    $obj->setDolgozo(null);
        }
        $jogaterem = \mkw\store::getEm()->getRepository('Entities\Jogaterem')->find($this->params->getIntRequestParam('jogaterem'));
        if ($jogaterem) {
            $obj->setJogaterem($jogaterem);
        }
        else {
            $obj->setJogaterem(null);
        }
		$jogaoratipus = \mkw\store::getEm()->getRepository('Entities\Jogaoratipus')->find($this->params->getIntRequestParam('jogaoratipus'));
		if ($jogaoratipus) {
			$obj->setJogaoratipus($jogaoratipus);
		}
		else {
			$obj->setJogaoratipus(null);
		}
		$obj->setNev($this->params->getStringRequestParam('nev'));
		$obj->setMaxferohely($this->params->getIntRequestParam('maxferohely'));
		$obj->setNap($this->params->getIntRequestParam('nap'));
		$obj->setKezdet($this->params->getStringRequestParam('kezdet'));
        $obj->setVeg($this->params->getStringRequestParam('veg'));
		$obj->setInaktiv($this->params->getBoolRequestParam('inaktiv'));
//		$obj->doStuffOnPrePersist();
		return $obj;
	}

    protected function afterSave($o) {
        parent::afterSave($o);
    }

	public function getlistbody() {
		$view = $this->createView('orarendlista_tbody.tpl');

		$filter = new \mkwhelpers\FilterDescriptor();
		if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
		}
        $f = $this->params->getNumRequestParam('inaktivfilter',9);
        if ($f != 9) {
            $filter->addFilter('inaktiv', '=', $f);
        }
        if (!is_null($this->params->getRequestParam('napfilter', null))) {
            $filter->addFilter('nap' , '=', $this->params->getIntRequestParam('napfilter'));
        }
        if (!is_null($this->params->getRequestParam('jogaoratipusfilter', null))) {
            $filter->addFilter('jogaoratipus' , '=', $this->params->getIntRequestParam('jogaoratipusfilter'));
        }
        if (!is_null($this->params->getRequestParam('jogateremfilter', null))) {
            $filter->addFilter('jogaterem' , '=', $this->params->getIntRequestParam('jogateremfilter'));
        }
        if (!is_null($this->params->getRequestParam('dolgozofilter', null))) {
            $filter->addFilter('dolgozo' , '=', $this->params->getIntRequestParam('dolgozofilter'));
        }

        $this->initPager($this->getRepo()->getCount($filter));
        $egyedek = $this->getRepo()->getWithJoins(
                $filter, $this->getOrderArray(), $this->getPager()->getOffset(), $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'orarendlista', $view));
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
		$view = $this->createView('orarendlista.tpl');
		$view->setVar('pagetitle', t('Órarend'));
		$view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
		$view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());

        $dc = new dolgozoController($this->params);
        $view->setVar('dolgozolist', $dc->getSelectList(($ora ? $ora->getDolgozoId() : 0)));

        $jtc = new jogateremController($this->params);
        $view->setVar('jogateremlist', $jtc->getSelectList(($ora ? $ora->getJogateremId() : 0)));

        $jotc = new jogaoratipusController($this->params);
        $view->setVar('jogaoratipuslist', $jotc->getSelectList(($ora ? $ora->getJogaoratipusId() : 0)));

        $view->setVar('naplist', store::getDaynameSelectList(($ora ? $ora->getNap() : 0)));

		$view->printTemplateResult();
	}

	protected function _getkarb($tplname) {
		$id = $this->params->getRequestParam('id', 0);
		$oper = $this->params->getRequestParam('oper', '');
		$view = $this->createView($tplname);
		$view->setVar('pagetitle', t('Órarend'));
		$view->setVar('oper', $oper);

		$ora = $this->getRepo()->findWithJoins($id);
		$view->setVar('egyed', $this->loadVars($ora, true));

        $dc = new dolgozoController($this->params);
		$view->setVar('dolgozolist', $dc->getSelectList(($ora ? $ora->getDolgozoId() : 0)));

		$jtc = new jogateremController($this->params);
		$view->setVar('jogateremlist', $jtc->getSelectList(($ora ? $ora->getJogateremId() : 0)));

        $jotc = new jogaoratipusController($this->params);
        $view->setVar('jogaoratipuslist', $jotc->getSelectList(($ora ? $ora->getJogaoratipusId() : 0)));

        $view->setVar('naplist', store::getDaynameSelectList(($ora ? $ora->getNap() : 0)));

        $view->printTemplateResult();
	}

    public function setflag() {
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
            }
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
    }

    public function exportToWordpress() {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('inaktiv', '=', false);
	    $rec = $this->getRepo()->getWithJoins($filter, array('nap' => 'ASC', 'kezdet' => 'ASC', 'nev' => 'ASC'));
	    $orarend = array();
	    /** @var \Entities\Orarend $item */
        foreach ($rec as $item) {
            $orarend[$item->getNap()]['napnev'] = \mkw\store::getDayname($item->getNap());
            $orarend[$item->getNap()]['orak'][] = array(
                'kezdet' => $item->getKezdetStr(),
                'veg' => $item->getVegStr(),
                'oranev' => $item->getNev(),
                'tanar' => $item->getDolgozoNev(),
                'terem' => $item->getJogateremNev(),
                'class' => $item->getJogateremOrarendclass()
            );
	    }
        $view = $this->createView('orarendwordpress.tpl');
        $view->setVar('orarend', $orarend);
        $view->printTemplateResult();
    }

}