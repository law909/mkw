<?php

namespace Traits;

use Entities\Bizonylatfej;
use Entities\Folyoszamla;
use Entities\Partner;
use mkwhelpers\FilterDescriptor;

trait PartnerDataProvider
{
    /**
     * @param $selid
     * @param FilterDescriptor | array $filter
     *
     * @return array
     */
    public function getSelectList($selid = null, $filter = [])
    {
        $f = new FilterDescriptor();
        $f->addFilter('inaktiv', '=', false);
        if ($filter) {
            $f->merge($filter);
        }
        $rec = $this->getRepo()->getAllForSelectList($f, ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor['id'],
                'caption' => $sor['nev'] . ' ' . $sor['irszam'] . ' ' . $sor['varos'] . ' ' . $sor['utca'] . ' ' . $sor['hazszam'],
                'nevvaros' => $sor['nev'] . ' ' . $sor['varos'],
                'nev' => $sor['nev'],
                'cim' => $sor['irszam'] . ' ' . $sor['varos'] . ' ' . $sor['utca'] . ' ' . $sor['hazszam'],
                'email' => $sor['email'],
                'selected' => ($sor['id'] == $selid)
            ];
        }
        return $res;
    }

    public function getBizonylatfejSelectList()
    {
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = [];
        if ($term) {
            $r = \mkw\store::getEm()->getRepository(Partner::class);
            $res = $r->getBizonylatfejLista($term);

            /** @var Partner $r */
            foreach ($res as $r) {
                $x = [
                    'id' => $r->getId(),
                    'value' => $r->getNev() . ' ' . $r->getCim() . ' (' . $r->getEmail() . ')'
                ];
                $afaoverride = $r->getAFAOverride();
                if ($afaoverride) {
                    $x['afa'] = $afaoverride->getId();
                    $x['afakulcs'] = $afaoverride->getErtek();
                }
                $ret[] = $x;
            }
        }
        echo json_encode($ret);
    }

    public function getPartnerData()
    {
        $pid = $this->params->getIntRequestParam('partnerid');
        $email = $this->params->getStringRequestParam('email');
        if ($pid) {
            /** @var Partner $partner */
            $partner = $this->getRepo()->find($pid);
        } elseif ($email) {
            /** @var Partner $partner */
            $partner = $this->getRepo()->findOneBy(['email' => $email]);
        } else {
            /** @var Partner $partner */
            $partner = $this->getRepo()->getLoggedInUser();
        }

        $ret = [];
        if ($partner) {
            $ret = [
                'id' => $partner->getId(),
                'fizmod' => $partner->getFizmodId(),
                'fizhatido' => $partner->getFizhatido(),
                'nev' => $partner->getNev(),
                'nevelotag' => $partner->getNevelotag(),
                'vezeteknev' => $partner->getVezeteknev(),
                'keresztnev' => $partner->getKeresztnev(),
                'szlanev' => $partner->getSzlanev(),
                'irszam' => $partner->getIrszam(),
                'varos' => $partner->getVaros(),
                'utca' => $partner->getUtca(),
                'hazszam' => $partner->getHazszam(),
                'szallnev' => $partner->getSzallnev(),
                'szallirszam' => $partner->getSzallirszam(),
                'szallvaros' => $partner->getSzallvaros(),
                'szallutca' => $partner->getSzallutca(),
                'szallhazszam' => $partner->getSzallhazszam(),
                'adoszam' => $partner->getAdoszam(),
                'euadoszam' => $partner->getEuadoszam(),
                'thirdadoszam' => $partner->getThirdadoszam(),
                'telefon' => $partner->getTelefon(),
                'email' => $partner->getEmail(),
                'szallitasimod' => $partner->getSzallitasimodId(),
                'valutanem' => $partner->getValutanemId(),
                'uzletkoto' => $partner->getUzletkotoId(),
                'bizonylatnyelv' => $partner->getBizonylatnyelv(),
                'orszag' => $partner->getOrszagId(),
                'szallorszag' => $partner->getSzallorszagId(),
                'vatstatus' => $partner->getVatstatus(),
                'szamlatipus' => $partner->getSzamlatipus(),
                'szamlaegyeb' => $partner->getSzamlaegyeb(),
                'mptngyegyetem' => $partner->getMPTNGYEgyetemId(),
                'mptngyegyetemnev' => $partner->getMPTNGYEgyetemNev(),
                'mptngykar' => $partner->getMPTNGYKarId(),
                'mptngykarnev' => $partner->getMPTNGYKarNev(),
                'mptngyegyetemegyeb' => $partner->getMptngyegyetemegyeb(),
                'mptngybankszamlaszam' => $partner->getMptngybankszamlaszam(),
                'mptngycsoportosfizetes' => $partner->getMptngycsoportosfizetes(),
                'mptngykapcsolatnev' => $partner->getMptngykapcsolatnev(),
                'mpt_munkahelynev' => $partner->getMptMunkahelynev(),
                'mptngynemveszreszt' => $partner->isMptngynemveszreszt(),
                'mptngynapreszvetel1' => $partner->isMptngynapreszvetel1(),
                'mptngynapreszvetel2' => $partner->isMptngynapreszvetel2(),
                'mptngynapreszvetel3' => $partner->isMptngynapreszvetel3(),
                'mptngyvipvacsora' => $partner->isMptngyvipvacsora(),
                'mptngybankett' => $partner->isMptngybankett(),
                'mptnyugdijasdiak' => $partner->isMptngynyugdijas() ? t('Nyugdíjas') : ($partner->isMptngydiak() ? t('Diák') : ($partner->isMptngyphd() ? t(
                    'Phd hallgató'
                ) : '')),
                'mptngydiak' => $partner->isMptngydiak(),
                'mptngynyugdijas' => $partner->isMptngynyugdijas(),
                'mptngyphd' => $partner->isMptngyphd(),
                'mptngympttag' => $partner->isMptngympttag(),
                'mptngyszerepkor' => $partner->getMptngyszerepkorId(),
                'mpttag' => $partner->isMptngympttag() ? t('MPT tag') : t('nem MPT tag'),
            ];
            $afaoverride = $partner->getAFAOverride();
            if ($afaoverride) {
                $ret['afa'] = $afaoverride->getId();
                $ret['afakulcs'] = $afaoverride->getErtek();
            }
        }
        echo json_encode($ret);
    }

    public function getAFAOverride()
    {
        $afa = Partner::calcAFAOverride(
            $this->params->getIntRequestParam('szallorszag'),
            $this->params->getIntRequestParam('orszag'),
            $this->params->getIntRequestParam('szamlatipus'),
            $this->params->getStringRequestParam('euadoszam')
        );
        if ($afa) {
            echo json_encode(['id' => $afa->getId(), 'ertek' => $afa->getErtek()]);
        } else {
            echo json_encode(['id' => false, 'ertek' => false]);
        }
    }

    public function getSzallitoSelectList($selid)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('szallito', '=', true);
        $rec = $this->getRepo()->getAll($filter, ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                'fizmod' => $sor->getFizmodId(),
                'fizhatido' => $sor->getFizhatido(),
                'irszam' => $sor->getIrszam(),
                'varos' => $sor->getVaros(),
                'utca' => $sor->getUtca(),
                'hazszam' => $sor->getHazszam()
            ];
        }
        return $res;
    }

    public function getKiegyenlitetlenBiz()
    {
        $partnerid = $this->params->getIntRequestParam('partner');
        $irany = $this->params->getIntRequestParam('irany', 1);
        $br = $this->getRepo(Bizonylatfej::class);
        $bizs = $this->getRepo(Folyoszamla::class)->getSumByPartner($partnerid, $irany);
        $adat = [];
        foreach ($bizs as $biz) {
            if ($biz['hivatkozottdatum']) {
                $datum = $biz['hivatkozottdatum']->format(\mkw\store::$DateFormat);
            } else {
                $datum = '';
            }
            /** @var \Entities\Bizonylatfej $hbiz */
            $hbiz = $br->find($biz['hivatkozottbizonylat']);
            if ($hbiz) {
                $erbizszam = $hbiz->getErbizonylatszam();
            } else {
                $erbizszam = '';
            }
            $adat[] = [
                'bizszam' => $biz['hivatkozottbizonylat'],
                'erbizszam' => $erbizszam,
                'datum' => $datum,
                'egyenleg' => $biz['egyenleg'] * 1 * $irany,
                'fizmod' => $biz['fizmodnev']
            ];
        }
        $view = $this->createView('kiegyenlitetlenselect.tpl');
        $view->setVar('bizonylatok', $adat);
        $ret = [
            'html' => $view->getTemplateResult()
        ];
        echo json_encode($ret);
    }

    public function queryTaxpayer()
    {
        $payernum = $this->params->getStringRequestParam('adoszam');
        $payernum = substr($payernum, 0, 8);

        $no = new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam());
        if ($no->querytaxpayer($payernum)) {
            echo $no->getResult();
        } else {
            $noerrors = $no->getErrors();
            \mkw\store::writelog($payernum . ' querytaxpayer', 'navonline.log');
            \mkw\store::writelog(print_r($noerrors, true), 'navonline.log');
        }
    }
}
