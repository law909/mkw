<?php

use Doctrine\ORM\Query\ResultSetMapping;


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


$DBVersion = \mkw\store::getParameter(\mkw\consts::DBVersion, '');

if ($DBVersion < '0028') {
    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Helyszínek","/admin/helyszin/viewlist","/admin/helyszin",20,0,50, "")'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0028');
}

if ($DBVersion < '0029') {
    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt, showpdf) '
        . ' VALUES '
        . '("bizsablon", "Bizonylat sablon", "-1", "0", "BSAB", "1", "1",'
        . ' "0", "0", "1", "1", "1", "0", "biz_sablon.tpl", "0",'
        . ' "1", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "0")'
    );

    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Biz. sablonok","/admin/bizsablonfej/viewlist","/admin/bizsablonfej",15,1,50, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0029');
}

if ($DBVersion < '0030') {
    if (\mkw\store::isDarshan()) {
        \mkw\store::getEm()->getConnection()->executeUpdate(
            'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
            . ' VALUES '
            . '(8, "Óra látogatások","/admin/jogareszvetel/viewlist","/admin/jogareszvetel",15,1,200, "")'
        );
    } else {
        \mkw\store::getEm()->getConnection()->executeUpdate(
            'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
            . ' VALUES '
            . '(8, "Óra látogatások","/admin/jogareszvetel/viewlist","/admin/jogareszvetel",15,0,200, "")'
        );
    }
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0030');
}

if ($DBVersion < '0031') {
    if (\mkw\store::isDarshan()) {
        \mkw\store::getEm()->getConnection()->executeUpdate(
            'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
            . ' VALUES '
            . '(8, "Bérletek","/admin/jogaberlet/viewlist","/admin/jogaberlet",15,1,210, "")'
        );
    } else {
        \mkw\store::getEm()->getConnection()->executeUpdate(
            'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
            . ' VALUES '
            . '(8, "Bérletek","/admin/jogaberlet/viewlist","/admin/jogaberlet",15,0,210, "")'
        );
    }
    \mkw\store::getEm()->getConnection()->executeUpdate("UPDATE menu SET sorrend=220 WHERE routename='/admin/naptar'");
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0031');
}

if ($DBVersion < '0032') {
    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Partner összefűzés","/admin/partnermerge/view","/admin/partnermerge",90,1,1000, "")'
    );
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
    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt, showpdf, navbekuldendo) '
        . ' VALUES '
        . '("garancialevel", "Garancialevél", "-1", "0", "GAR", "1", "1",'
        . ' "1", "0", "1", "1", "1", "0", "biz_garancia.tpl", "0",'
        . ' "1", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "1", "0")'
    );

    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Garancialevelek","/admin/garancialevelfej/viewlist","/admin/garancialevelfej",15,0,850, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0034');
}

if ($DBVersion < '0035') {
    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt, showpdf, navbekuldendo) '
        . ' VALUES '
        . '("kolcsonzes", "Kölcsönzés", "-1", "0", "KLCS", "1", "1",'
        . ' "0", "0", "1", "1", "0", "0", "biz_kolcsonzes.tpl", "0",'
        . ' "1", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "1", "0")'
    );

    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Kölcsönzés","/admin/kolcsonzesfej/viewlist","/admin/kolcsonzesfej",15,0,250, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0035');
}

if ($DBVersion < '0036') {
    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(4, "Számla XML export","/admin/xmlszamlaexport/view","/admin/xmlszamlaexport",20,1,890, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0036');
}

if ($DBVersion < '0037') {
    \mkw\store::getEm()->getConnection()->executeUpdate('UPDATE bizonylattipus SET showemailbutton = 1 WHERE (id = "kolcsonzes")');
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0037');
}

if ($DBVersion < '0038') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "MNR Statikus lapok","/admin/mnrstatic/viewlist","/admin/mnrstatic",20,0,450, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0038');
}

if ($DBVersion < '0039') {
    $lathato = '0';
    if (\mkw\store::isSuperzoneB2B() || \mkw\store::isMugenrace() || \mkw\store::isMugenrace()) {
        $lathato = '1';
    }
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(4, "Árlista","/admin/arlista/view","/admin/arlista",20,' . $lathato . ',1000, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0039');
}

if ($DBVersion < '0040') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "MNR Navigáció","/admin/mnrnavigation/viewlist","/admin/mnrnavigation",20,0,440, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0040');
}

if ($DBVersion < '0041') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "MNR Landing page","/admin/mnrlanding/viewlist","/admin/mnrlanding",20,0,430, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0041');
}

if ($DBVersion < '0042') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "Termék értékelések","/admin/termekertekeles/viewlist","/admin/termekertekeles",20,0,1100, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0042');
}

if ($DBVersion < '0043') {
    \mkw\store::getEm()->getConnection()->executeStatement('UPDATE termekertekeles SET elutasitva=0 WHERE elutasitva IS NULL');

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0043');
}

if ($DBVersion < '0044') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Szakmai anyagok","/admin/mptngyszakmaianyag/viewlist","/admin/mptngyszakmaianyag",20,0,1700, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0044');
}

if ($DBVersion < '0045') {
    \mkw\store::getEm()->getConnection()->executeStatement('UPDATE mptngyszakmaianyag SET egyebszerzokorg = egyebszerzok');

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0045');
}

if ($DBVersion < '0046') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(2, "Bank tranzakciók","/admin/banktranzakcio/viewlist","/admin/banktranzakcio",20,0,250, "")'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(2, "Bank tranzakciók feltöltése","/admin/banktranzakcio/viewupload","/admin/banktranzakcio",20,0,260, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0046');
}

if ($DBVersion < '0047') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Partner termék kedv. feltöltés","/admin/partnertermekkedvezmenyupload/view","/admin/partnertermekkedvezmenyupload",20,0,550, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0047');
}

if ($DBVersion < '0048') {
    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt, showpdf, navbekuldendo,'
        . ' showemailbutton, showeddigimegrendeleseiurl, showgarancialisadatok) '
        . ' VALUES '
        . '("szallmegr", "Szállítói megrendelés", "1", "0", "SZMR", "1", "1",'
        . ' "0", "0", "1", "1", "0", "0", "biz_szallmegr.tpl", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "1", "0", "0",'
        . ' "0", "0", "0", "0", "0", "1", "0",'
        . ' "0", "0", "0")'
    );

    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Szállítói megrendelés","/admin/szallmegrfej/viewlist","/admin/szallmegrfej",20,0,150, "")'
    );

    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt, showpdf, navbekuldendo,'
        . ' showemailbutton, showeddigimegrendeleseiurl, showgarancialisadatok) '
        . ' VALUES '
        . '("garanciaugy", "Garanciális ügy", "1", "0", "GUGY", "1", "1",'
        . ' "0", "0", "1", "1", "0", "1", "biz_garanciaugy.tpl", "1",'
        . ' "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "1", "0", "0",'
        . ' "0", "0", "0", "0", "0", "1", "0",'
        . ' "0", "0", "1")'
    );

    \mkw\store::getEm()->getConnection()->executeUpdate(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Garanciális ügy","/admin/garanciaugyfej/viewlist","/admin/garanciaugyfej",20,0,270, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0048');
}

if ($DBVersion < '0049') {
    $result = \mkw\store::getEm()->getConnection()->executeQuery(
        '(SELECT distinct(azonosito) AS azonosito FROM termekar) union '
        . '(SELECT distinct(termekarazonosito) AS azonosito from partner) union '
        . '(SELECT distinct(partnertermekarazonosito) AS azonosito from uzletkoto) '
        . 'ORDER BY azonosito'
    );
    $savok = $result->fetchAllAssociative();
    foreach ($savok as $sav) {
        if ($sav['azonosito']) {
            \mkw\store::getEm()->getConnection()->executeStatement('INSERT INTO arsav (nev) VALUES (\'' . $sav['azonosito'] . '\')');
        }
    }
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0049');
}

if ($DBVersion < '0050') {
    $query = \mkw\store::getEm()->getConnection()->executeQuery('SELECT * FROM arsav');
    $arsavok = $query->fetchAllAssociative();
    foreach ($arsavok as $arsav) {
        \mkw\store::getEm()->getConnection()->executeStatement('UPDATE termekar SET arsav_id=' . $arsav['id'] . ' WHERE azonosito=\'' . $arsav['nev'] . '\'');
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE partner SET arsav_id=' . $arsav['id'] . ' WHERE termekarazonosito=\'' . $arsav['nev'] . '\''
        );
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE uzletkoto SET arsav_id=' . $arsav['id'] . ' WHERE partnertermekarazonosito=\'' . $arsav['nev'] . '\''
        );
    }
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0050');
}

if ($DBVersion < '0051') {
    function _UpdateArsavParameter($par)
    {
        $oldval = \mkw\store::getParameter($par);
        $arsav = \mkw\store::getEm()->getConnection()->executeQuery('SELECT * FROM arsav WHERE nev="' . $oldval . '"')->fetchAssociative();
        if ($arsav['nev']) {
            \mkw\store::setParameter($par, $arsav['id']);
        }
    }

    _UpdateArsavParameter(\mkw\consts::Arsav);
    _UpdateArsavParameter(\mkw\consts::ShowTermekArsav);
    _UpdateArsavParameter(\mkw\consts::Webshop2Price);
    _UpdateArsavParameter(\mkw\consts::Webshop2Discount);
    _UpdateArsavParameter(\mkw\consts::Webshop3Price);
    _UpdateArsavParameter(\mkw\consts::Webshop3Discount);
    _UpdateArsavParameter(\mkw\consts::Webshop4Price);
    _UpdateArsavParameter(\mkw\consts::Webshop4Discount);
    _UpdateArsavParameter(\mkw\consts::Webshop5Price);
    _UpdateArsavParameter(\mkw\consts::Webshop5Discount);

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0051');
}

if ($DBVersion < '0052') {
    \mkw\store::getEm()->getConnection()->executeStatement('UPDATE termek set wctiltva=inaktiv');

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0052');
}

if ($DBVersion < '0053') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt, showpdf, navbekuldendo,'
        . ' showemailbutton, showeddigimegrendeleseiurl, showgarancialisadatok) '
        . ' VALUES '
        . '("webshopbiz", "Webshop rendelés", "-1", "0", "WS", "1", "1",'
        . ' "1", "0", "1", "1", "0", "0", "biz_webshopbiz.tpl", "1",'
        . ' "0", "0", "0", "0", "0", "0", "1",'
        . ' "0", "0", "0", "1", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "1", "0",'
        . ' "0", "0", "0")'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Webshop rendelés","/admin/webshopbizfej/viewlist","/admin/webshopbizfej",20,0,550, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0053');
}

if ($DBVersion < '0054') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "Popupok","/admin/popup/viewlist","/admin/popup",20,0,1200, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0054');
}

if ($DBVersion < '0055') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "Termék menü","/admin/termekmenu/viewlist","/admin/termekmenu",20,0,1300, "")'
    );

    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO termekmenu (parent_id, nev, slug, karkod, lathato, lathato2, lathato3, lathato4, lathato5, lathato6, lathato7, lathato8, lathato9,'
        . ' lathato10, lathato11, lathato12, lathato13, lathato14,lathato15)'
        . ' VALUES '
        . '(null, "Termék menü","termekmenu","00001", 1,0,0,0,0,0,0,0,0,0,0,0,0,0,0)'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0055');
}

if ($DBVersion < '0056') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Termék menü rendezése","","",40,0,800, "js-regeneratemenukarkod")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0056');
}

if ($DBVersion < '0057') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE termek SET feltoltheto=lathato,feltoltheto2=lathato2,feltoltheto3=lathato3,feltoltheto4=lathato4,feltoltheto5=lathato5'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0057');
}

if ($DBVersion < '0058') {
    \mkw\store::setParameter(\mkw\consts::GLSSM2, 1);
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0058');
}

if ($DBVersion < '0059') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(2, "Bank tételek","/admin/bankbizonylattetel/viewlist","/admin/bankbizonylattetel",40,1,150, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0059');
}

if ($DBVersion < '0060') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Színek","/admin/szin/viewlist","/admin/szin",40,0,220, ""),'
        . '(7, "Méretek","/admin/meret/viewlist","/admin/meret",40,0,230, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0060');
}

/**
 * ures partner nevbe betenni vezeteknev+keresztnevet
 * partner nevben cserelni dupla es tripla szokozoket szokozre
 * partner keresztnevet es vezeteknevet stripelni
 * bizonylatfej ures partner nevbe betenni vezeteknev+keresztnevet
 * bizonylatfej nevben cserelni dupla es tripla szokozoket szokozre
 * bizonylatfej keresztnevet es vezeteknevet stripelni
 */