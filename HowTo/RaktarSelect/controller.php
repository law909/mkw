<?php


/**
 * betoltes viewba
 */
$raktar = new raktarController($this->params);
$x['raktarlist'] = $raktar->getSelectList();
