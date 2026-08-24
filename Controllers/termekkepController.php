<?php

namespace Controllers;

use Entities\TermekKep;

class termekkepController extends \mkwhelpers\MattableController
{

    /**
     * A termék főképének álazonosítója a képválasztókban. A főkép a termék `kepurl` mezője,
     * nem `termekkep` sor, ezért nincs valódi id-je; negatív, hogy soha ne ütközzön eggyel.
     */
    public const FOKEP_ID = -1;

    public function __construct()
    {
        $this->setEntityName(TermekKep::class);
//		$this->setKarbFormTplName('?howto?karbform.tpl');
//		$this->setKarbTplName('?howto?karb.tpl');
//		$this->setListBodyRowTplName('?howto?lista_tbody_tr.tpl');
//		$this->setListBodyRowVarName('_egyed');
        parent::__construct();
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
        $x = $this->getEntityFieldsArray($t, $x);
        $x['urlsmall'] = $t->getUrlSmall();
        $x['urlmedium'] = $t->getUrlMedium();
        $x['urllarge'] = $t->getUrlLarge();
        $x['urlmini'] = $t->getUrlMini();
        $x['url400'] = $t->getUrl400();
        $x['url2000'] = $t->getUrl2000();
        return $x;
    }

    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function getemptyrow()
    {
        $view = $this->createView('termektermekkepkarb.tpl');
        $view->setVar('kep', $this->loadVars(null));
        echo $view->getTemplateResult();
    }

    /**
     * A médiatárban kijelölt képek felvétele a termék képei közé. Az azonos url-ű kép nem
     * duplikálódik – a médiatár többször is megnyitható ugyanarra a mappára.
     */
    public function addFromMediatar()
    {
        header('Content-Type: application/json; charset=utf-8');
        /** @var \Entities\Termek|null $termek */
        $termek = $this->getRepo(\Entities\Termek::class)->find($this->params->getIntRequestParam('termek'));
        if (!$termek) {
            echo json_encode(['ok' => false, 'error' => t('Nincs ilyen termék.')]);
            return;
        }

        // az url-t a tárolás elején lévő '/' és a kódolás is megkülönböztetheti, ezért
        // a médiatár összehasonlító változataival nézzük, mi van már fent
        $megvan = [];
        foreach ($this->getRepo()->getByTermek($termek->getId()) as $kep) {
            $megvan[$kep->getUrl('/')] = true;
        }

        $added = 0;
        $skipped = 0;
        foreach ($this->getUrls() as $url) {
            $vanmar = false;
            foreach (\Services\MediatarService::urlVariants($url) as $valtozat) {
                if (isset($megvan['/' . ltrim($valtozat, '/')])) {
                    $vanmar = true;
                    break;
                }
            }
            if ($vanmar) {
                $skipped++;
                continue;
            }
            $kep = new TermekKep();
            $kep->setTermek($termek);
            $kep->setUrl($url);
            $this->getEm()->persist($kep);
            $megvan['/' . ltrim($url, '/')] = true;
            $added++;
        }
        $this->getEm()->flush();

        echo json_encode(['ok' => true, 'added' => $added, 'skipped' => $skipped]);
    }

    /**
     * A termék képsorai újrarajzolva – a médiatárból való felvétel után ezzel frissül a
     * karbantartó Képek lapja, a form többi, még nem mentett adatának elvesztése nélkül.
     */
    public function getrows()
    {
        /** @var \Entities\Termek|null $termek */
        $termek = $this->getRepo(\Entities\Termek::class)->find($this->params->getIntRequestParam('termek'));
        if (!$termek) {
            return;
        }
        foreach ($this->getRepo()->getByTermek($termek->getId()) as $kep) {
            $view = $this->createView('termektermekkepkarb.tpl');
            $view->setVar('kep', $this->loadVars($kep));
            echo $view->getTemplateResult();
        }
    }

    /**
     * A kérésben érkező kép-url-ek. Nyersen olvassuk: a getStringRequestParam az &-et
     * &amp;-re rontaná egy fájlnévben.
     *
     * @return string[]
     */
    private function getUrls()
    {
        $all = $this->params->asArray();
        $v = $all['requestparams']['urls'] ?? [];
        if (!is_array($v)) {
            $v = [$v];
        }
        $out = [];
        foreach ($v as $url) {
            if (is_string($url) && trim($url) !== '') {
                $out[] = trim($url);
            }
        }
        return $out;
    }

    /**
     * A termékhez választható képek.
     *
     * `$fokeppel` esetén az első elem a termék főképe: az nem `termekkep` sor, hanem a termék
     * `kepurl` mezője, ezért kap egy negatív álazonosítót – enélkül a csak főképpel feltöltött
     * terméknél nem lenne miből választani. Csak ott kapcsoljuk be, ahol a hivatkozás üresen is
     * elmenthető (szín képek); a változat képe valódi `termekkep` sorra mutat.
     */
    public function getSelectList($termek, $selid, $fokeppel = false)
    {
        $kepek = $this->getRepo()->getByTermek($termek);
        $keplista = [];
        $selids = is_array($selid) ? $selid : [$selid];
        if ($fokeppel && $termek && trim((string)$termek->getKepurl()) !== '') {
            $keplista[] = [
                'id' => self::FOKEP_ID,
                'caption' => $termek->getKepurl(),
                'fokep' => true,
                'selected' => array_key_exists(self::FOKEP_ID, $selids),
                'sorrend' => $selids[self::FOKEP_ID] ?? null,
                'url' => $termek->getKepurlMini(),
            ];
        }
        foreach ($kepek as $kep) {
            $keplista[] = [
                'id' => $kep->getId(),
                'caption' => $kep->getUrl(),
                'fokep' => false,
                'selected' => array_key_exists($kep->getId(), $selids),
                'sorrend' => $selids[$kep->getId()] ?? null,
                'url' => $kep->getUrlMini()
            ];
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
        }
        echo $this->params->getNumRequestParam('id');
    }


}
