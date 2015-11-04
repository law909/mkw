<?php


/**
 * showview
 */
$view->setVar('toldatum', date(\mkw\Store::$DateFormat));
$view->setVar('igdatum', date(\mkw\Store::$DateFormat));
$view->setVar('datumtipus', 'teljesites');


/**
 * feldolgozas
 */
$tolstr = $this->params->getStringRequestParam('tol');
$tolstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($tolstr)));

$igstr = $this->params->getStringRequestParam('ig');
$igstr = date(\mkw\Store::$DateFormat, strtotime(\mkw\Store::convDate($igstr)));

$mt = $this->params->getStringRequestParam('datumtipus');
switch ($mt) {
    case 'kelt':
        $datummezo = 'kelt';
        break;
    case 'teljesites':
        $datummezo = 'teljesites';
        break;
    case 'esedekesseg':
        $datummezo = 'esedekesseg';
        break;
    default:
        $datummezo = 'teljesites';
}