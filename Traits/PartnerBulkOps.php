<?php

namespace Traits;

use Entities\Emailtemplate;
use Entities\Partner;
use Entities\PartnerTermekcsoportKedvezmeny;

trait PartnerBulkOps
{
    public function doAnonym()
    {
        $partnerid = $this->params->getIntRequestParam('id');
        $this->getRepo()->doAnonym($partnerid);
    }

    public function arsavcsere()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $arsav = $this->params->getIntRequestParam('arsav');
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }

        $partnerek = $this->getRepo()->getAll($filter);

        /** @var Partner $partner */
        foreach ($partnerek as $partner) {
            $partner->setArsav($arsav);
            $this->getEm()->persist($partner);
        }
        $this->getEm()->flush();
    }

    public function tcskedit()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $tcs = $this->params->getStringRequestParam('tcs');
        $kedvvalt = $this->params->getNumRequestParam('kedv');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }
        $partnerek = $this->getRepo()->getAll($filter);
        /** @var Partner $partner */
        foreach ($partnerek as $partner) {
            /** @var PartnerTermekcsoportKedvezmeny $tcsk */
            foreach ($partner->getTermekcsoportkedvezmenyek() as $tcsk) {
                if ($tcsk->getTermekcsoportId() == $tcs) {
                    $tcsk->setKedvezmeny($kedvvalt);
                    $this->getEm()->persist($tcsk);
                }
            }
        }
        $this->getEm()->flush();
    }

    public function setflag()
    {
        $id = $this->params->getIntRequestParam('id');
        $kibe = $this->params->getBoolRequestParam('kibe');
        $flag = $this->params->getStringRequestParam('flag');
        /** @var \Entities\Partner $obj */
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

    public function sendEmailSablonok()
    {
        $ids = $this->params->getArrayRequestParam('ids');
        $sablon = $this->getRepo(Emailtemplate::class)->find($this->params->getIntRequestParam('sablon'));
        if ($sablon) {
            foreach ($ids as $id) {
                /** @var \Entities\Partner $partner */
                $partner = $this->getRepo()->find($id);
                if ($partner) {
                    $partner->sendEmailSablon($sablon);
                }
            }
        }
    }

}
