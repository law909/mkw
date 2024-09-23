<?php

namespace Controllers;

use Entities\Apierrorlog;
use mkwhelpers\Controller;
use mkwhelpers\FilterDescriptor;

class apierrorlogController extends Controller
{
    public function __construct($params)
    {
        $this->setEntityName(Apierrorlog::class);
        parent::__construct($params);
    }

    public function getList()
    {
        $result = [];
        $filter = new FilterDescriptor();
        $filter->addFilter('closed', '=', false);
        $all = $this->getRepo()->getAll($filter);
        foreach ($all as $log) {
            $result[] = $log->toLista();
        }
        return $result;
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