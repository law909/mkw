<?php

/**
 * cimkek lekerdezese
 */
$pcc = new partnercimkekatController($this->params);
/** @var \Doctrine\Common\Collections\ArrayCollection() $selected */
$selected = ...;
$view->setVar('cimkekat', $pcc->getWithCimkek($selected));



/**
 * partnerek szurese a ui-bol erkezo cimkefilterrel
 */
$partnerkodok = $this->getRepo('Entities\Partner')->getByCimkek($this->params->getArrayRequestParam('cimkefilter'));


/**
 * ui-bol erkezo cimkek feldolgozasa, pl. hozzaadas partnerhez
 */
$cimkekpar = $this->params->getArrayRequestParam('cimkek');
foreach ($cimkekpar as $cimkekod) {
    $cimke = $this->getEm()->getRepository('Entities\Partnercimketorzs')->find($cimkekod);
    if ($cimke) {

    }
}


