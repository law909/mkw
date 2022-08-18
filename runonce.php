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
    } else {
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
    } else {
        \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
            . ' VALUES '
            . '(8, "Bérletek","/admin/jogaberlet/viewlist","/admin/jogaberlet",15,0,210, "")');
    }
    \mkw\store::getEm()->getConnection()->executeUpdate("UPDATE menu SET sorrend=220 WHERE routename='/admin/naptar'");
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0031');
}

if ($DBVersion < '0032') {
    \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Partner összefűzés","/admin/partnermerge/view","/admin/partnermerge",90,1,1000, "")');
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0032');
}

if ($DBVersion < '0033') {
    /** @var \Entities\Bizonylattipus $bizt */
    $bizt = \mkw\store::getEm()->getRepository(\Entities\Bizonylattipus::class)->find('szamla');
    $bizt->setNavbekuldendo(true);
    \mkw\store::getEm()->persist($bizt);
    $bizt = \mkw\store::getEm()->getRepository(\Entities\Bizonylattipus::class)->find('esetiszamla');
    $bizt->setNavbekuldendo(true);
    \mkw\store::getEm()->persist($bizt);
    \mkw\store::getEm()->flush();
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0033');
}

if ($DBVersion < '0034') {
    \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt, showpdf, navbekuldendo) '
        . ' VALUES '
        . '("garancialevel", "Garancialevél", "-1", "0", "GAR", "1", "1",'
        . ' "1", "0", "1", "1", "1", "0", "biz_garancia.tpl", "0",'
        . ' "1", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "1", "0")');

    \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Garancialevelek","/admin/garancialevelfej/viewlist","/admin/garancialevelfej",15,0,850, "")');

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0034');
}

if ($DBVersion < '0035') {
    \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt, showpdf, navbekuldendo) '
        . ' VALUES '
        . '("kolcsonzes", "Kölcsönzés", "-1", "0", "KLCS", "1", "1",'
        . ' "0", "0", "1", "1", "0", "0", "biz_kolcsonzes.tpl", "0",'
        . ' "1", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "1", "0")');

    \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Kölcsönzés","/admin/kolcsonzesfej/viewlist","/admin/kolcsonzesfej",15,0,250, "")');

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0035');
}

if ($DBVersion < '0036') {
    \mkw\store::getEm()->getConnection()->executeUpdate('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(4, "Számla XML export","/admin/xmlszamlaexport/view","/admin/xmlszamlaexport",20,1,890, "")');

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0036');
}

if ($DBVersion < '0037') {
    \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE bizonylattipus SET showemailbutton = 1 WHERE (id = "kolcsonzes")');
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0037');
}

if ($DBVersion < '0038') {
    \mkw\store::getEm()->getConnection()->executeStatement('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "MNR Statikus lapok","/admin/mnrstatic/viewlist","/admin/mnrstatic",20,0,450, "")');

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0038');
}

if ($DBVersion < '0039') {
    $lathato = '0';
    if (\mkw\store::isSuperzoneB2B() || \mkw\store::isMugenrace() || \mkw\store::isMugenrace()) {
        $lathato = '1';
    }
    \mkw\store::getEm()->getConnection()->executeStatement('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(4, "Árlista","/admin/arlista/view","/admin/arlista",20,' . $lathato . ',1000, "")');

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0039');
}

/*********************************************************
 *
 * NAV ONLINE 1.1 (2019.06.01)
 *
 */
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
        \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE termek SET me_id=' . $x->getId() . ' WHERE me=\'' . $me . '\'');
        \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE bizonylattetel SET me_id=' . $x->getId() . ' WHERE me=\'' . $me . '\'');
    }
    \mkw\store::setParameter(\mkw\consts::NAVOnlineME1_1Kesz, 1);
}

/*********************************************************
 *
 * NAV ONLINE 3.0 (2021.03.31)
 *
 */
if (!\mkw\store::getParameter(\mkw\consts::NAVOnlinePartner3_0Kesz, 0)) {
// magánszemélyek
    \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE partner SET vatstatus=2'
        . ' WHERE ((vatstatus IS NULL) OR (vatstatus=0)) AND ((adoszam IS NULL) OR (adoszam=\'\')) AND ((euadoszam IS NULL) OR (euadoszam=\'\')) AND ((thirdadoszam IS NULL) OR (thirdadoszam=\'\'))'
    );

// belföldi
    $rsm = new ResultSetMapping();
    $rsm->addScalarResult('id', 'id');
    $rsm->addScalarResult('adoszam', 'adoszam');
    $q = \mkw\store::getEm()->createNativeQuery('SELECT id,adoszam'
        . ' FROM partner WHERE (adoszam IS NOT NULL) AND (adoszam <> \'\')', $rsm);
    $ps = $q->getScalarResult();
    foreach ($ps as $p) {
        if (\mkw\store::isMagyarAdoszam($p['adoszam'])) {
            \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE partner SET szamlatipus=0 WHERE id=' . $p['id']);
        }
    }

    \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE partner SET vatstatus=1'
        . ' WHERE ((vatstatus IS NULL) OR (vatstatus=0)) AND (adoszam IS NOT NULL) AND (adoszam <> \'\') AND (szamlatipus=0)'
    );

// egyeb
    \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE partner SET vatstatus=3'
        . ' WHERE ((vatstatus IS NULL) OR (vatstatus=0))'
    );

    $rsm = new ResultSetMapping();
    $rsm->addScalarResult('id', 'id');
    $rsm->addScalarResult('adoszam', 'adoszam');
    $rsm->addScalarResult('euadoszam', 'euadoszam');
    $rsm->addScalarResult('thirdadoszam', 'thirdadoszam');
    $rsm->addScalarResult('szamlatipus', 'szamlatipus');
    $q = \mkw\store::getEm()->createNativeQuery('SELECT id,adoszam,euadoszam,thirdadoszam,szamlatipus'
        . ' FROM partner WHERE vatstatus=3', $rsm);
    $ps = $q->getScalarResult();
    foreach ($ps as $p) {
        if ($p['szamlatipus'] == 1) {
            if (!$p['euadoszam']) {
                \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE partner SET euadoszam=adoszam WHERE id=' . $p['id']);
            }
        }
        if ($p['szamlatipus'] == 2) {
            if (!$p['thirdadoszam']) {
                \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE partner SET thirdadoszam=adoszam WHERE id=' . $p['id']);
            }
        }
    }
    \mkw\store::setParameter(\mkw\consts::NAVOnlinePartner3_0Kesz, 1);
}

$now = \Carbon\Carbon::now()->format(\mkw\store::$SQLDateFormat);

if (!\mkw\store::getNAVOnlineEnv()) {
    \mkw\store::setParameter(\mkw\consts::NAVOnlineEnv, 'prod');
}
if (\mkw\store::getParameter(\mkw\consts::NAVOnlineVersion, '') < '3_0') {
    $no = new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam(), \mkw\store::getNAVOnlineEnv());
    if ($no->version()) {
        $nover = $no->getResult();
        \mkw\store::setParameter(\mkw\consts::NAVOnlineVersion, $nover);
    }
}

/**
 * ures partner nevbe betenni vezeteknev+keresztnevet
 * partner nevben cserelni dupla es tripla szokozoket szokozre
 * partner keresztnevet es vezeteknevet stripelni
 * bizonylatfej ures partner nevbe betenni vezeteknev+keresztnevet
 * bizonylatfej nevben cserelni dupla es tripla szokozoket szokozre
 * bizonylatfej keresztnevet es vezeteknevet stripelni
 */