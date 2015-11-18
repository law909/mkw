<?php

$fv = $this->params->getArrayRequestParam('fafilter');
if (!empty($fv)) {
    $ff = new \mkwhelpers\FilterDescriptor();
    $ff->addFilter('id', 'IN', $fv);
    $res = \mkw\Store::getEm()->getRepository('Entities\TermekFa')->getAll($ff, array());
    $faszuro = array();
    foreach ($res as $sor) {
        $faszuro[] = $sor->getKarkod() . '%';
    }
    $filter->addFilter(array('_xx.termekfa1karkod', '_xx.termekfa2karkod', '_xx.termekfa3karkod'), 'LIKE', $faszuro);
}
