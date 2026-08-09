<?php

namespace Controllers;

use Entities\Apierrorlog;
use Entities\Bizonylatfej;
use mkwhelpers\Controller;
use mkwhelpers\FilterDescriptor;

class apierrorlogController extends Controller
{
    public function __construct()
    {
        $this->setEntityName(Apierrorlog::class);
        parent::__construct();
    }

    public function getList()
    {
        $result = [];
        $filter = new FilterDescriptor();
        $filter->addFilter('closed', '=', false);
        $all = $this->getRepo()->getAll($filter);
        foreach ($all as $log) {
            $result[] = $this->addBizonylat($log->toLista());
        }
        return $result;
    }

    /**
     * A bizonylathoz köthető hibák üzenete "<bizonylatszám>: …" alakú (UnasGetOrderService::logWarnings),
     * ezt emeljük külön mezőbe, hogy a főoldalon a bizonylatra lehessen ugrani.
     */
    private function addBizonylat(array $row)
    {
        $row['bizonylatszam'] = '';
        $row['bizonylatlink'] = null;
        $parts = explode(': ', $row['message'], 2);
        // a bizonylatfej id 30 karakter, ennél hosszabb előtag biztosan nem bizonylatszám
        if (count($parts) < 2 || $parts[0] === '' || mb_strlen($parts[0]) > 30) {
            return $row;
        }
        $fej = $this->getEm()->find(Bizonylatfej::class, $parts[0]);
        if (!$fej) {
            return $row;
        }
        $row['bizonylatszam'] = $fej->getId();
        // ha a típusnak nincs karbantartó útvonala, legalább a listára vigyen
        $row['bizonylatlink'] = $fej->getKarbUrl() ?: $fej->getListaUrl();
        $row['message'] = $parts[1];
        return $row;
    }

    public function close()
    {
        $log = $this->getRepo()->find($this->params->getIntRequestParam('id'));
        if ($log) {
            $log->setClosed(true);
            $this->getEm()->persist($log);
            $this->getEm()->flush();
        }
    }
}