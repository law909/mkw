<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\JogaSzamlazatlanEladas;
use Entities\Partner;
use mkwhelpers\Controller;
use mkwhelpers\FilterDescriptor;

/**
 * A pubadminos órajegy/bérlet eladásokból ki nem számlázott sorok naplója. A sorokat a
 * {@see pubadminController::setResztvevoOrajegy()} írja, itt csak olvasás és a „Megoldva” van.
 */
class jogaszamlazatlaneladasController extends Controller
{
    public function __construct()
    {
        $this->setEntityName(JogaSzamlazatlanEladas::class);
        parent::__construct();
    }

    /**
     * A főoldali doboz tartalma: a még megoldatlan sorok.
     */
    public function getList()
    {
        $result = [];
        $filter = new FilterDescriptor();
        $filter->addFilter('megoldva', '=', false);
        /** @var JogaSzamlazatlanEladas $log */
        foreach ($this->getRepo()->getAll($filter, ['created' => 'DESC']) as $log) {
            $row = $log->toLista();
            $row['partnerlink'] = $log->getPartnerId()
                ? \mkw\store::getRouter()->generate('adminpartnerviewkarb') . '?id=' . $log->getPartnerId()
                : null;
            $result[] = $row;
        }
        return $result;
    }

    /**
     * A napló egy sorának feljegyzése. A partner adatait denormalizáltan is eltesszük, mert a
     * sort később is olvasni kell.
     *
     * @param \Entities\Partner|null $partner
     */
    public function log($partner, $partnernev, $partneremail, $megnevezes, $osszeg, $oka)
    {
        $log = new JogaSzamlazatlanEladas();
        $log->setPartner($partner);
        $log->setPartnernev($partnernev);
        $log->setPartneremail($partneremail);
        $log->setMegnevezes($megnevezes);
        $log->setOsszeg($osszeg);
        $log->setOka($oka);
        $this->getEm()->persist($log);
        $this->getEm()->flush();
        return $log;
    }

    public function solve()
    {
        /** @var JogaSzamlazatlanEladas $log */
        $log = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        if ($log && !$log->isMegoldva()) {
            $log->setMegoldva(true);
            $log->setMegoldasdatum();
            $log->setMegoldotta($this->getRepo(Dolgozo::class)->find(\mkw\store::getAdminSession()->pk));
            $this->getEm()->persist($log);
            $this->getEm()->flush();
        }
    }
}
