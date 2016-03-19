<?php


/**
 * betoltes viewba
 */
$partner = new partnerController($this->params);
$x['gyartolist'] = $partner->getSzallitoSelectList(0);


/**
 * használat
 */
$gyartoid = $this->params->getIntRequestParam('gyartoid');

