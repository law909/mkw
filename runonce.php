<?php

use Doctrine\ORM\Query\ResultSetMapping;

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
