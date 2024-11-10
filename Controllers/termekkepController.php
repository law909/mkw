<?php

namespace Controllers;

use Entities\TermekKep;

class termekkepController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\TermekKep');
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\TermekKep();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['url'] = $t->getUrl();
        $x['urlsmall'] = $t->getUrlSmall();
        $x['urlmedium'] = $t->getUrlMedium();
        $x['urllarge'] = $t->getUrlLarge();
        $x['leiras'] = $t->getLeiras();
        $x['rejtett'] = $t->getRejtett();
        return $x;
    }

    protected function setFields($obj)
    {
        $obj->setLeiras($this->params->getStringRequestParam('leiras'));
        $obj->setUrl($this->params->getStringRequestParam('url'));
        $obj->setRejtett($this->params->getBoolRequestParam('rejtett'));
        return $obj;
    }

    public function getemptyrow()
    {
        $view = $this->createView('termektermekkepkarb.tpl');
        $view->setVar('kep', $this->loadVars(null));
        echo $view->getTemplateResult();
    }

    public function getSelectList($termek, $selid)
    {
        $kepek = $this->getRepo()->getByTermek($termek);
        $keplista = [];
        foreach ($kepek as $kep) {
            $keplista[] = ['id' => $kep->getId(), 'caption' => $kep->getUrl(), 'selected' => $kep->getId() == $selid, 'url' => $kep->getUrlMini()];
        }
        return $keplista;
    }

    public function del()
    {
        $mainpath = \mkw\store::changeDirSeparator(\mkw\store::getConfigValue('mainpath'));
        if ($mainpath) {
            $mainpath = rtrim($mainpath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }
        /** @var TermekKep $kep */
        $kep = $this->getRepo()->find($this->params->getNumRequestParam('id'));
        if ($kep) {
            /* 			unlink($mainpath . $kep->getUrl(''));
              unlink($mainpath . $kep->getUrlMini(''));
              unlink($mainpath . $kep->getUrlSmall(''));
              unlink($mainpath . $kep->getUrlMedium(''));
              unlink($mainpath . $kep->getUrlLarge(''));
             */
            $this->getEm()->remove($kep);
            $this->getEm()->flush();
            $this->deleteMediaFromWP($kep->getWcid());
            $kep->getTermek()->clearWcdate();
            $kep->getTermek()->uploadToWC();
        }
        echo $this->params->getNumRequestParam('id');
    }

    protected function deleteMediaFromWP($media_id)
    {
        $site_url = \mkw\store::getWcUrl();
        $endpoint = $site_url . '/wp-json/wp/v2/media/' . $media_id;
        $headers = [
            'Authorization: Basic ' . base64_encode(\mkw\store::getWpAppName() . ':' . \mkw\store::getWpAppPassword()),
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint . '?force=true');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return $err;
        }

        curl_close($ch);

        $response_data = json_decode($response, true);
        return isset($response_data['deleted']) && $response_data['deleted'] == true;
    }


}
