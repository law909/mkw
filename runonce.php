<?php

use Doctrine\ORM\Query\ResultSetMapping;

$DBVersion = \mkw\store::getParameter(\mkw\consts::DBVersion, '');

if ($DBVersion < '0028') {
    \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Helyszínek","/admin/helyszin/viewlist","/admin/helyszin",20,0,50, "")');
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0028');
}


if (!\mkw\store::getParameter(\mkw\consts::NAVOnlineME1_1Kesz, 0)) {
    $mes2 = array();
    $rsm = new ResultSetMapping();
    $rsm->addScalarResult('me', 'me');
    $q = \mkw\store::getEm()->createNativeQuery('SELECT DISTINCT me'
        . ' FROM bizonylattetel ', $rsm);
    $mes = $q->getScalarResult();
    foreach ($mes as $m) {
        $mes2[$m['me']] = $m['me'];
    }
    $q = \mkw\store::getEm()->createNativeQuery('SELECT DISTINCT me'
        . ' FROM termek ', $rsm);
    $mes = $q->getScalarResult();
    foreach ($mes as $m) {
        $mes2[$m['me']] = $m['me'];
    }
    foreach ($mes2 as $me) {
        $x = new \Entities\ME();
        $x->setNev($me);
        \mkw\store::getEm()->persist($x);
        \mkw\store::getEm()->flush();
        \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE termek SET me_id=' . $x->getId() . ' WHERE me=\'' . $me .'\'');
        \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE bizonylattetel SET me_id=' . $x->getId() . ' WHERE me=\'' . $me .'\'');
    }
    \mkw\store::setParameter(\mkw\consts::NAVOnlineME1_1Kesz, 1);
}


/**
 * ures partner nevbe betenni vezeteknev+keresztnevet
 * partner nevben cserelni dupla es tripla szokozoket szokozre
 * partner keresztnevet es vezeteknevet stripelni
 * bizonylatfej ures partner nevbe betenni vezeteknev+keresztnevet
 * bizonylatfej nevben cserelni dupla es tripla szokozoket szokozre
 * bizonylatfej keresztnevet es vezeteknevet stripelni
 */