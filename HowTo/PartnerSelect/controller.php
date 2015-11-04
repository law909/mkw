<?php


/**
 * betoltes viewba
 */
$partner = new partnerController($this->params);
$x['partnerlist'] = $partner->getSelectList();
