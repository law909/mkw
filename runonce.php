<?php

use Doctrine\ORM\Query\ResultSetMapping;

$DBVersion = \mkw\store::getParameter(\mkw\consts::DBVersion, '');

if ($DBVersion < '0028') {
    \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Helyszínek","/admin/helyszin/viewlist","/admin/helyszin",20,0,50, "")');
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0028');
}

if ($DBVersion < '0029') {
    \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt, showpdf) '
        . ' VALUES '
        . '("bizsablon", "Bizonylat sablon", "-1", "0", "BSAB", "1", "1",'
        . ' "0", "0", "1", "1", "1", "0", "biz_sablon.tpl", "0",'
        . ' "1", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "0")');

    \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Biz. sablonok","/admin/bizsablonfej/viewlist","/admin/bizsablonfej",15,1,50, "")');

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0029');
}

if ($DBVersion < '0030') {
    if (\mkw\store::isDarshan()) {
        \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
            . ' VALUES '
            . '(8, "Óra látogatások","/admin/jogareszvetel/viewlist","/admin/jogareszvetel",15,1,200, "")');
    }
    else {
        \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
            . ' VALUES '
            . '(8, "Óra látogatások","/admin/jogareszvetel/viewlist","/admin/jogareszvetel",15,0,200, "")');
    }
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0030');
}

if ($DBVersion < '0031') {
    if (\mkw\store::isDarshan()) {
        \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
            . ' VALUES '
            . '(8, "Bérletek","/admin/jogaberlet/viewlist","/admin/jogaberlet",15,1,210, "")');
    }
    else {
        \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
            . ' VALUES '
            . '(8, "Bérletek","/admin/jogaberlet/viewlist","/admin/jogaberlet",15,0,210, "")');
    }
    \mkw\store::getEm()->getConnection()->executeUpdate("UPDATE menu SET sorrend=220 WHERE routename='/admin/naptar'");
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0031');
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