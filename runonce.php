<?php

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Afa;
use Entities\Termek;


if (\mkw\store::getParameter(\mkw\consts::NAVOnlineVersion, '') < '3_0') {
    $no = new \mkwhelpers\NAVOnline(\mkw\store::getTulajAdoszam());
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
    if (\mkw\store::isSuperzoneB2B()) {
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
        . '(3, "Csapatok","/admin/csapat/viewlist","admincsapatviewlist",20,0,460, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0060');
}

if ($DBVersion < '0061') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "Versenyzők","/admin/versenyzo/viewlist","adminversenyzoviewlist",20,0,470, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0061');
}

if ($DBVersion < '0062') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "Blokkok","/admin/blokk/viewlist","adminblokkviewlist",20,0,455, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0062');
}

if ($DBVersion < '0063') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Színek","/admin/szin/viewlist","/admin/szin",40,0,220, ""),'
        . '(7, "Méretek","/admin/meret/viewlist","/admin/meret",40,0,230, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0063');
}

if ($DBVersion < '0064') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Méret sorok","/admin/meretsor/viewlist","/admin/meretsor",40,0,240, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0064');
}

if ($DBVersion < '0065') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Raktár készlet nullázás","/admin/raktarkeszletnullazo/view","adminraktarkeszletnullazoview",90,0,250, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0065');
}

if ($DBVersion < '0066') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `termekrecept`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `termekrecepttipus`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `partnermijszoklevel`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `partnermijszoralatogatas`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `partnermijszpune`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `partnermijsztanitas`'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0066');
}

if ($DBVersion < '0067') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mijszgyakorlasszint`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mijszoklevelkibocsajto`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mijszoklevelszint`'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0067');
}

if ($DBVersion < '0068') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mnrlanding_translations`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mnrlanding`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mnrnavigation_translations`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mnrnavigation`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mnrstatic_translations`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mnrstaticpage_translations`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mnrstaticpagekep`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mnrstaticpage`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `mnrstatic`'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0068');
}

if ($DBVersion < '0069') {
    $fms = \mkw\store::getEm()->getConnection()->executeQuery('SELECT * FROM fizmod_translations')->fetchAllAssociative();
    foreach ($fms as $fm) {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE fizmod SET ' . $fm['field'] . '_l1=\'' . $fm['content'] . '\' WHERE id=' . $fm['object_id']
        );
    }

    $fms = \mkw\store::getEm()->getConnection()->executeQuery('SELECT * FROM szallitasimod_translations')->fetchAllAssociative();
    foreach ($fms as $fm) {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE szallitasimod SET ' . $fm['field'] . '_l1=\'' . $fm['content'] . '\' WHERE id=' . $fm['object_id']
        );
    }

    $fms = \mkw\store::getEm()->getConnection()->executeQuery('SELECT * FROM bizonylatfej_translations')->fetchAllAssociative();
    foreach ($fms as $fm) {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE bizonylatfej SET ' . $fm['field'] . '_l1=\'' . $fm['content'] . '\' WHERE id=\'' . $fm['object_id'] . '\''
        );
    }

    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `fizmod_translations`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `szallitasimod_translations`'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `bizonylatfej_translations`'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0069');
}

if ($DBVersion < '0070') {
    $fms = \mkw\store::getEm()->getConnection()->executeQuery('SELECT * FROM statlap_translations')->fetchAllAssociative();
    foreach ($fms as $fm) {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE statlap SET ' . $fm['field'] . '_l1=\'' . $fm['content'] . '\' WHERE id=' . $fm['object_id']
        );
    }

    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `statlap_translations`'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0070');
}

if ($DBVersion < '0071') {
    $fms = \mkw\store::getEm()->getConnection()->executeQuery('SELECT * FROM termekmenu_translations')->fetchAllAssociative();
    foreach ($fms as $fm) {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE termekmenu SET ' . $fm['field'] . '_l1=\'' . $fm['content'] . '\' WHERE id=' . $fm['object_id']
        );
    }

    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `termekmenu_translations`'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0071');
}

if ($DBVersion < '0072') {
    $fms = \mkw\store::getEm()->getConnection()->executeQuery('SELECT * FROM termekfa_translations')->fetchAllAssociative();
    foreach ($fms as $fm) {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE termekfa SET ' . $fm['field'] . '_l1=\'' . $fm['content'] . '\' WHERE id=' . $fm['object_id']
        );
    }

    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `termekfa_translations`'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0072');
}

if ($DBVersion < '0073') {
    $fms = \mkw\store::getEm()->getConnection()->executeQuery('SELECT * FROM bizonylattetel_translations')->fetchAllAssociative();
    foreach ($fms as $fm) {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE bizonylattetel SET ' . $fm['field'] . '_l1=\'' . $fm['content'] . '\' WHERE id=' . $fm['object_id']
        );
    }

    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `bizonylattetel_translations`'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0073');
}

if ($DBVersion < '0074') {
    $fms = \mkw\store::getEm()->getConnection()->executeQuery('SELECT * FROM termek_translations')->fetchAllAssociative();
    foreach ($fms as $fm) {
        $escapedContent = addslashes($fm['content']);
        if ($fm['field'] == 'rovidleiras') {
            $escapedContent = mb_substr($escapedContent, 0, 255);
        }
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE termek SET ' . $fm['field'] . '_l1=\'' . $escapedContent . '\' WHERE id=' . $fm['object_id']
        );
    }

    \mkw\store::getEm()->getConnection()->executeStatement(
        'DROP TABLE IF EXISTS `termek_translations`'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0074');
}

if ($DBVersion < '0075') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Országok","/admin/orszag/viewlist","/admin/orszag",40,1,245, "")'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0075');
}

if ($DBVersion < '0076') {
    // EU-tagországok áfakulcsai, iso3166 szerint.
    // Az érték az általános áfakulcs egész százalékban (az Afa.ertek mező egész szám).
    // Finnország (FI) tényleges kulcsa 25,5% lenne, ami egész számként nem tárolható,
    // ezért ott csak az EU jelölést kapcsoljuk be, áfakulcsot nem állítunk.
    if (\mkw\store::isSuperzoneB2B() || \mkw\store::isMugenrace2026()) {
        $euafak = [
            'HU' => 27,
            'FI' => null,
            'HR' => 25,
            'DK' => 25,
            'SE' => 25,
            'EE' => 24,
            'GR' => 24,
            'RO' => 24,
            'PL' => 23,
            'PT' => 23,
            'IE' => 23,
            'SK' => 23,
            'IT' => 22,
            'SI' => 22,
            'CZ' => 21,
            'BE' => 21,
            'ES' => 21,
            'NL' => 21,
            'LV' => 21,
            'LT' => 21,
            'AT' => 20,
            'FR' => 20,
            'BG' => 20,
            'DE' => 19,
            'CY' => 19,
            'MT' => 18,
            'LU' => 17,
        ];
        $em = \mkw\store::getEm();
        $orszagrepo = $em->getRepository(\Entities\Orszag::class);
        $afarepo = $em->getRepository(\Entities\Afa::class);
        $afacache = [];
        foreach ($euafak as $iso => $ertek) {
            /** @var \Entities\Orszag $orszag */
            $orszag = $orszagrepo->findOneBy(['iso3166' => $iso]);
            if (!$orszag) {
                continue;
            }
            $orszag->setEu(1);
            if (!is_null($ertek)) {
                if (!isset($afacache[$ertek])) {
                    $afa = $afarepo->findOneBy(['ertek' => $ertek]);
                    if (!$afa) {
                        $afa = new \Entities\Afa();
                        $afa->setNev($ertek . '%');
                        $afa->setErtek($ertek);
                        $em->persist($afa);
                    }
                    $afacache[$ertek] = $afa;
                }
                $orszag->setAfa($afacache[$ertek]);
            }
            $em->persist($orszag);
        }
        $em->flush();
    }
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0076');
}

if ($DBVersion < '0077') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "ÁFA kulcsok","/admin/afa/viewlist","/admin/afa",40,1,247, "")'
    );
    if (\mkw\store::isSuperzoneB2B() || \mkw\store::isMugenrace2026()) {
        $em = \mkw\store::getEm();
        $afa = $em->getRepository(\Entities\Afa::class)->findOneBy(['ertek' => 25.5]);
        if (!$afa) {
            $afa = new \Entities\Afa();
            $afa->setNev('25.5%');
            $afa->setErtek(25.5);
            $em->persist($afa);
        }
        $orszag = $em->getRepository(\Entities\Orszag::class)->findOneBy(['iso3166' => 'FI']);
        if ($orszag) {
            $orszag->setAfa($afa);
            $em->persist($orszag);
        }
        $em->flush();
    }
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0077');
}

if ($DBVersion < '0078') {
    $afa = \mkw\store::getEm()->getRepository(Afa::class)->find(\mkw\store::getParameter(\mkw\consts::NullasAfa));
    if ($afa) {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE orszag SET afa_id="' . $afa->getId() . '" WHERE afa_id IS NULL'
        );
    }
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0078');
}

if ($DBVersion < '0079') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "Elállások","/admin/elallas/viewlist","/admin/elallas",40,1,950, "")'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0079');
}

if ($DBVersion < '0080') {
    $bt = \mkw\store::getEm()->getRepository(\Entities\Bizonylattipus::class)->find('boltieladas');
    if (!$bt) {
        $bt = new \Entities\Bizonylattipus();
        $bt->setId('boltieladas');
        $bt->setNev('Bolti eladás');
        $bt->setIrany(-1);
        $bt->setNyomtatni(false);
        $bt->setAzonosito('BO');
        $bt->setKezdosorszam(1);
        $bt->setPeldanyszam(1);
        $bt->setMozgat(true);
        $bt->setPenztmozgat(true);
        $bt->setShowteljesites(true);
        $bt->setShowesedekesseg(true);

        \mkw\store::getEm()->persist($bt);
        \mkw\store::getEm()->flush();
    }
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0080');
}

if ($DBVersion < '0081') {
    // "Bolti eladások" admin menüpont a bolti eladás bizonylatok CRUD-jához. Csak azoknál a
    // deploymenteknél látható alapból, ahol a bolti eladás funkció él (galad, superzoneb2b).
    $lathato = (\mkw\store::isGalad() || \mkw\store::isSuperzoneB2B()) ? 1 : 0;
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Bolti eladások","/admin/boltieladasfej/viewlist","/admin/boltieladasfej",15,' . $lathato . ',860, "")'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0081');
}

if ($DBVersion < '0082') {
    // A termékfa gyökér elemének (parent_id IS NULL) nevét egységesen "Termék kategóriák"-ra állítjuk.
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE termekfa SET nev = ? WHERE parent_id IS NULL',
        ['Termék kategóriák']
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0082');
}

if ($DBVersion < '0083') {
    $afa = \mkw\store::getEm()->getRepository(\Entities\Afa::class)->findOneBy(['ertek' => 27]);
    if ($afa) {
        $orszag = \mkw\store::getEm()->getRepository(\Entities\Orszag::class)->findOneBy(['iso3166' => 'HU']);
        if ($orszag) {
            $orszag->setAfa($afa);
            $orszag->setEu(1);
            \mkw\store::getEm()->persist($orszag);
            \mkw\store::getEm()->flush();
        }
    }
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0083');
}

if ($DBVersion < '0084') {
    // Seed the full-page cache allow-list (comma-separated route names) so it is
    // editable from the parameters table. Don't clobber an existing value.
    if (\mkw\store::getParameter(\mkw\consts::PagecacheRoutes, null) === null) {
        \mkw\store::setParameter(\mkw\consts::PagecacheRoutes, \mkw\pagecache::defaultRoutesCsv());
    }
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0084');
}

if ($DBVersion < '0085') {
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0085');
}

if ($DBVersion < '0086') {
    \mkw\store::getEm()->getConnection()->executeStatement('UPDATE bizonylattipus SET showszallitasicim=1 WHERE id="webshopbiz"');

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0086');
}

if ($DBVersion < '0087') {
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0087');
}

if ($DBVersion < '0088') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE bizonylattipus SET '
        . 'showesedekesseg=1, showhatarido=1,showszamlabutton=1,showszallitobutton=1,showkivetbutton=1,showuzenet=1,showbackorder=1,'
        . 'showcsomagbutton=1,showkupon=1,showeddigimegrendeleseiurl=1,tplname_l1="biz_webshopbiz_eng.tpl"'
        . ' WHERE id="webshopbiz"'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0088');
}

if ($DBVersion < '0089') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE bizonylattipus SET showkeziszamlabutton=0'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0089');
}

if ($DBVersion < '0090') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET lathato=0 WHERE routename = "/admin/template"'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0090');
}

if ($DBVersion < '0091') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(4, "Rendelt / beérkezett","/admin/rendbevlista/view","/admin/rendbevlista",40,1,1100, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0091');
}

if ($DBVersion < '0092') {
    // a termek.cimkenevek gyorsító mező bennragadt régi címkeneveinek helyretétele
    // (címke átnevezésekor eddig nem frissült – lásd termekcimkeController::afterSave())
    \mkw\store::getEm()->getRepository(Termek::class)->refreshCimkenevek();

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0092');
}

if ($DBVersion < '0093') {
    // NAV bejövő számla import menüpont a Költségszámlák alá
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "NAV bejövő számla import","/admin/koltsegszamlaimport/view","adminkoltsegszamlaimportview",40,0,650, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0093');
}

if ($DBVersion < '0094') {
    // A bizonylatfej.nincspenzmozgas mező megszűnt: eddig ez (a fizetési módról átmásolva)
    // külön tiltotta a folyószámla képzését, mostantól a penztmozgat mező hordozza ezt.
    // A pénzmozgás nélküli fizetési móddal rögzített bizonylatokon ezért kikapcsoljuk a
    // penztmozgat-ot, különben újramentéskor folyószámla készülne hozzájuk.
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE bizonylatfej b INNER JOIN fizmod f ON f.id=b.fizmod_id'
        . ' SET b.penztmozgat=0'
        . ' WHERE f.nincspenzmozgas=1 AND b.penztmozgat=1'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0094');
}

if ($DBVersion < '0095') {
    // Házipénztár tételek lista menüpont a Házipénztár alá
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(2, "Házipénztár tételek","/admin/penztarbizonylattetel/viewlist","/admin/penztarbizonylattetel",15,1,220, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0095');
}

if ($DBVersion < '0096') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE bizonylattipus SET tplname="biz_boltieladas.tpl" WHERE id="boltieladas"'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0096');
}

if ($DBVersion < '0097') {
    if (\mkw\store::getSetupValue('autopenztarbizonylat', false)) {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE bizonylattipus SET autopenztarbizonylat=1'
            . ' WHERE penztmozgat=1 AND id NOT IN ("bank","penztar")'
        );
    }

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0097');
}

if ($DBVersion < '0098') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE afa SET magyar=1'
        . ' WHERE navcase IN ("AAM","TAM","ATK")'
        . '    OR ((navcase IS NULL OR navcase="") AND ertek IN (27, 5))'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0098');
}

if ($DBVersion < '0099') {
    // Az "Egyéb adatok" jqGrid-es gyűjtőképernyő törzsadatai önálló mattable képernyőket kaptak.
    // 1. szakasz: az egymezős törzsadatok. A setup-flag mögötti képernyők sora minden telepítésbe
    // bekerül, de csak az érintett ügyfélnél látszik (lathato=1) – utólag DB-ből bekapcsolható.
    $mpt = \mkw\store::isMPT() ? 1 : 0;
    $mptngy = \mkw\store::isMPTNGY() ? 1 : 0;
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Termékcsoportok","/admin/termekcsoport/viewlist","/admin/termekcsoport",40,1,251, ""),'
        . '(7, "Jelenlét típusok","/admin/jelenlettipus/viewlist","/admin/jelenlettipus",40,1,252, ""),'
        . '(7, "Kapcsolatfelvétel témák","/admin/kapcsolatfelveteltema/viewlist","/admin/kapcsolatfelveteltema",40,1,253, ""),'
        . '(7, "Termékváltozat adattípusok","/admin/termekvaltozatadattipus/viewlist","/admin/termekvaltozatadattipus",40,1,254, ""),'
        . '(7, "Munkakörök","/admin/munkakor/viewlist","/admin/munkakor",40,1,255, ""),'
        . '(7, "Ársávok","/admin/arsav/viewlist","/admin/arsav",40,' . (\mkw\store::isArsavok() ? 1 : 0) . ',256, ""),'
        . '(7, "Jogcímek","/admin/jogcim/viewlist","/admin/jogcim",40,' . (\mkw\store::isBankpenztar() ? 1 : 0) . ',257, ""),'
        . '(7, "MPT szekciók","/admin/mptszekcio/viewlist","/admin/mptszekcio",40,' . $mpt . ',258, ""),'
        . '(7, "MPT tagozatok","/admin/mpttagozat/viewlist","/admin/mpttagozat",40,' . $mpt . ',259, ""),'
        . '(7, "MPT tagság formák","/admin/mpttagsagforma/viewlist","/admin/mpttagsagforma",40,' . $mpt . ',260, ""),'
        . '(7, "MPT NGY témakörök","/admin/mptngytemakor/viewlist","/admin/mptngytemakor",40,' . $mptngy . ',261, ""),'
        . '(7, "MPT NGY témák","/admin/mptngytema/viewlist","/admin/mptngytema",40,' . $mptngy . ',262, ""),'
        . '(7, "MPT NGY szerepkörök","/admin/mptngyszerepkor/viewlist","/admin/mptngyszerepkor",40,' . $mptngy . ',263, ""),'
        . '(7, "MPT NGY szakmai anyag típusok","/admin/mptngyszakmaianyagtipus/viewlist","/admin/mptngyszakmaianyagtipus",40,' . $mptngy . ',264, ""),'
        . '(7, "MPT NGY egyetemek","/admin/mptngyegyetem/viewlist","/admin/mptngyegyetem",40,' . $mptngy . ',265, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0099');
}

if ($DBVersion < '0100') {
    // 2. szakasz: az összetett törzsadatok is önálló mattable képernyőt kaptak.
    // Ezzel az "Egyéb adatok" lapról minden jqGrid rács eltűnt.
    $mptngy = \mkw\store::isMPTNGY() ? 1 : 0;
    $bp = \mkw\store::isBankpenztar() ? 1 : 0;
    $rw = \mkw\store::getSetupValue('rewrite301') ? 1 : 0;
    $joga = \mkw\store::isDarshan() ? 1 : 0;
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "VTSZ","/admin/vtsz/viewlist","/admin/vtsz",40,1,266, ""),'
        . '(7, "Valutanemek","/admin/valutanem/viewlist","/admin/valutanem",40,1,267, ""),'
        . '(7, "Árfolyamok","/admin/arfolyam/viewlist","/admin/arfolyam",40,1,268, ""),'
        . '(7, "Bankszámlák","/admin/bankszamla/viewlist","/admin/bankszamla",40,1,269, ""),'
        . '(7, "Raktárak","/admin/raktar/viewlist","/admin/raktar",40,1,270, ""),'
        . '(7, "Irányítószámok","/admin/irszam/viewlist","/admin/irszam",40,1,271, ""),'
        . '(7, "Körzetszámok","/admin/korzetszam/viewlist","/admin/korzetszam",40,1,272, ""),'
        . '(7, "Mennyiségi egységek","/admin/me/viewlist","/admin/me",40,1,273, ""),'
        . '(7, "CSK kódok","/admin/csk/viewlist","/admin/csk",40,1,274, ""),'
        . '(7, "Ünnepnapok","/admin/unnepnap/viewlist","/admin/unnepnap",40,1,275, ""),'
        . '(7, "Szótár","/admin/szotar/viewlist","/admin/szotar",40,1,276, ""),'
        . '(7, "Partner típusok","/admin/partnertipus/viewlist","/admin/partnertipus",40,1,277, ""),'
        . '(7, "Partnercímke csoportok","/admin/partnercimkekat/viewlist","/admin/partnercimkekat",40,1,278, ""),'
        . '(7, "Termékcímke csoportok","/admin/termekcimkekat/viewlist","/admin/termekcimkekat",40,1,279, ""),'
        . '(7, "Felhasználók (webshop)","/admin/felhasznalo/viewlist","/admin/felhasznalo",40,1,280, ""),'
        . '(7, "Pénztárak","/admin/penztar/viewlist","/admin/penztar",40,' . $bp . ',281, ""),'
        . '(7, "Termek","/admin/jogaterem/viewlist","/admin/jogaterem",40,' . $joga . ',282, ""),'
        . '(7, "Óratípusok","/admin/jogaoratipus/viewlist","/admin/jogaoratipus",40,' . $joga . ',283, ""),'
        . '(7, "Rendezvény állapotok","/admin/rendezvenyallapot/viewlist","/admin/rendezvenyallapot",40,' . $joga . ',284, ""),'
        . '(7, "Átirányítások (301)","/admin/rw301/viewlist","/admin/rw301",40,' . $rw . ',285, ""),'
        . '(7, "MPT NGY karok","/admin/mptngykar/viewlist","/admin/mptngykar",40,' . $mptngy . ',286, "")'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0100');
}

if ($DBVersion < '0101') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menucsoport (nev,lathato,sorrend) VALUES ("Egyéb műveletek","1",90)'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET menucsoport_id=9 WHERE (routename IN ('
        . '"/admin/export","/admin/import","/admin/partnermerge",'
        . '"/admin/partnertermekkedvezmenyupload","adminraktarkeszletnullazoview","adminkoltsegszamlaimportview"))'
        . ' OR (class IN ("js-regeneratekarkod","js-regeneratemenukarkod"))'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET routename="/admin/raktarkeszletnullazo" WHERE routename="adminraktarkeszletnullazoview"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET routename="/admin/koltsegszamlaimport" WHERE routename="adminkoltsegszamlaimportview"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET sorrend=100 WHERE routename="/admin/import"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET sorrend=200 WHERE routename="/admin/koltsegszamlaimport"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET sorrend=300 WHERE class="js-regeneratekarkod"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET sorrend=400 WHERE class="js-regeneratemenukarkod"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET sorrend=500 WHERE routename="/admin/partnertermekkedvezmenyupload"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET sorrend=600 WHERE routename="/admin/export"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET sorrend=700 WHERE routename="/admin/partnermerge"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE menu SET sorrend=800 WHERE routename="/admin/raktarkeszletnullazo"'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0101');
}

if ($DBVersion < '0102') {
    $menuk = \mkw\store::getEm()->getConnection()
        ->executeQuery('SELECT id FROM menu WHERE menucsoport_id=7 ORDER BY nev')
        ->fetchAllAssociative();
    $sorrend = 0;
    foreach ($menuk as $m) {
        $sorrend += 100;
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE menu SET sorrend=' . $sorrend . ' WHERE id=' . $m['id']
        );
    }

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0102');
}

if ($DBVersion < '0103') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DELETE FROM menu WHERE routename="/admin/egyebtorzs"'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0103');
}

if ($DBVersion < '0104') {
    // A mattable listák "Mindig nyitva" pipája mostantól dolgozónként tárolódik
    // (dolgozoparameterek tábla, \Services\DolgozoParameterService). A régi, mindenkire
    // érvényes sorok a parameterek táblában voltak, csupasz lista-URL kulccsal; az új kulcs
    // a lista URL-je + a paraméter neve (…/viewlist_mindignyitva), mert listánként több
    // paraméter is lesz.
    // A bekapcsolt (ertek=1) beállításokat átvisszük minden dolgozóra, hogy senkinek se
    // változzon a megszokott képernyője, a kikapcsoltak pedig egyszerűen eldobhatók.
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO dolgozoparameterek (dolgozo_id, par, ertek)'
        . ' SELECT d.id, CONCAT(p.id, "_mindignyitva"), p.ertek FROM dolgozo d, parameterek p'
        . ' WHERE p.id LIKE "/admin/%" AND p.ertek="1"'
        . ' AND NOT EXISTS (SELECT 1 FROM dolgozoparameterek dp'
        . '                  WHERE dp.dolgozo_id=d.id AND dp.par=CONCAT(p.id, "_mindignyitva"))'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'DELETE FROM parameterek WHERE id LIKE "/admin/%"'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0104');
}

if ($DBVersion < '0105') {
    // A banktranzakcio import több bankot is tud (Raiffeisen, OTP), ezért a sor mostantól
    // viszi, melyik bank kivonatából jött – az azonosító ugyanis csak bankon belül egyedi.
    // A korábban importált sorok mind Raiffeisen kivonatból származnak: ha üresen maradnának,
    // egy újbóli Raiffeisen import nem ismerné fel őket, és duplán hozná létre a tételeket.
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE banktranzakcio SET bank="raiffeisen" WHERE bank IS NULL OR bank=""'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0105');
}

if ($DBVersion < '0106') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE bizonylattipus SET showszallitasicim=1'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0106');
}

if ($DBVersion < '0107') {
    // UNAS: alapértékek és a termékimport menüpont. A DDL az entitásokból jön (./updateschema.sh).
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT IGNORE INTO parameterek (id, ertek, specialchars) VALUES (?, ?, 1)',
        [\mkw\consts::UnasApiUrl, 'https://api.unas.eu/shop']
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT IGNORE INTO parameterek (id, ertek, specialchars) VALUES (?, ?, 0)',
        [\mkw\consts::UnasNyelv, 'hu']
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT IGNORE INTO parameterek (id, ertek, specialchars) VALUES (?, ?, 0)',
        [\mkw\consts::UnasNyelvL1, 'en']
    );
    // kapcsoló nélkül a route sem létezik, ezért alapból nem látszik
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(9, "UNAS termékimport","/admin/unastermekimport/view","/admin/unastermekimport",40,'
        . (\mkw\store::isUnas() ? '1' : '0') . ',250, "")'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0107');
}

if ($DBVersion < '0108') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE bizonylatfej b'
        . ' JOIN fizmod f ON f.id = b.fizmod_id'
        . ' SET b.fizmodnev_l1 = f.nev_l1'
        . ' WHERE b.fizmodnev_l1 IS NULL OR b.fizmodnev_l1 = ""'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE bizonylatfej b'
        . ' JOIN szallitasimod f ON f.id = b.szallitasimod_id'
        . ' SET b.szallitasimodnev_l1 = f.nev_l1'
        . ' WHERE b.szallitasimodnev_l1 IS NULL OR b.szallitasimodnev_l1 = ""'
    );

    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE partner SET bizonylatnyelv = "hu_hu" WHERE bizonylatnyelv="hu"'
    );

    \mkw\store::setParameter(\mkw\consts::DBVersion, '0108');
}

if ($DBVersion < '0109') {
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE partner SET bizonylatnyelv = "en_us" WHERE bizonylatnyelv = "en"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE bizonylatfej SET bizonylatnyelv = "hu_hu" WHERE bizonylatnyelv = "hu"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE bizonylatfej SET bizonylatnyelv = "en_us" WHERE bizonylatnyelv = "en"'
    );
    \mkw\store::getEm()->getConnection()->executeStatement(
        'UPDATE bizonylatfej b LEFT JOIN partner p ON p.id = b.partner_id '
        . ' SET b.bizonylatnyelv = COALESCE (NULLIF (p.bizonylatnyelv, ""), "hu_hu") '
        . ' WHERE b.bizonylatnyelv IS NULL OR b.bizonylatnyelv = ""'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0109');
}

if ($DBVersion < '0110') {
    // fájlt töröl, ezért a bontós műveletek 90-es jogosultságával megy, nem az importok 40-esével
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' SELECT 9, "UNAS képtakarítás", "/admin/unaskepcleanup/view", "/admin/unaskepcleanup", 90,'
        . (\mkw\store::isUnas() ? '1' : '0') . ', 260, ""'
        . ' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM (SELECT id FROM menu WHERE url = "/admin/unaskepcleanup/view") m)'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0110');
}

if ($DBVersion < '0111') {
    // UNAS megrendelés-import: menüpont + alapértékek. A DDL az entitásokból jön (./updateschema.sh).
    // Az importok 40-es jogosultságával megy, mint a termékimport.
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' SELECT 9, "UNAS megrendelések", "/admin/unasrendeles/view", "/admin/unasrendeles", 40,'
        . (\mkw\store::isUnas() ? '1' : '0') . ', 255, ""'
        . ' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM (SELECT id FROM menu WHERE url = "/admin/unasrendeles/view") m)'
    );
    // a rendelések a beállított alapraktárba jönnek, amíg valaki mást nem választ
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT IGNORE INTO parameterek (id, ertek, specialchars)'
        . ' SELECT ?, ertek, 0 FROM parameterek WHERE id = ?',
        [\mkw\consts::UnasRaktar, \mkw\consts::Raktar]
    );
    // a „függőben" státusz a nyitott rendelések tartaléka
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT IGNORE INTO parameterek (id, ertek, specialchars)'
        . ' SELECT ?, ertek, 0 FROM parameterek WHERE id = ?',
        [\mkw\consts::UnasStatuszOpenNormal, \mkw\consts::BizonylatStatuszFuggoben]
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0111');
}

if ($DBVersion < '0112') {
    // Cron napló: minden telepítésen látszik, mert a takarító feladat mindenütt fut.
    // Üzemeltetői nézet, ezért 90-es jogosultsággal. A DDL az entitásból jön (./updateschema.sh).
    \mkw\store::getEm()->getConnection()->executeStatement(
        'INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' SELECT 9, "Cron napló", "/admin/cronlog/viewlist", "/admin/cronlog", 90, 1, 900, ""'
        . ' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM (SELECT id FROM menu WHERE url = "/admin/cronlog/viewlist") m)'
    );
    \mkw\store::setParameter(\mkw\consts::DBVersion, '0112');
}

if ($DBVersion < '0113') {
    // a mai globális min.bolti készlet az alapraktár cellájába kerül, hogy a raktáras mátrix
    // a jelenlegi állapotot mutassa; a globális oszlop marad a többi raktár fallbackje
    $conn = \mkw\store::getEm()->getConnection();
    // a runonce az első admin kérésen fut, ami megelőzheti a kézi ./updateschema.sh-t:
    // hiányzó táblára az INSERT minden admin kérést fatalra vinne
    $tablakvannak = $conn->executeQuery(
        'SELECT COUNT(*) FROM information_schema.TABLES'
        . ' WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME IN (?, ?)',
        ['termekminboltikeszlet', 'termekvaltozatminboltikeszlet']
    )->fetchOne();
    if ($tablakvannak == 2) {
        $raktarid = \mkw\store::getParameter(\mkw\consts::Raktar);
        $vanraktar = $raktarid
            ? $conn->executeQuery('SELECT COUNT(*) FROM raktar WHERE id = ?', [$raktarid])->fetchOne()
            : 0;
        if ($vanraktar) {
            // created kézzel, mert a nyers SQL megkerüli a Gedmo Timestampable-t
            $conn->executeStatement(
                'INSERT IGNORE INTO termekminboltikeszlet (termek_id, raktar_id, minboltikeszlet, created)'
                . ' SELECT t.id, ?, t.minboltikeszlet, NOW() FROM termek t'
                . ' WHERE (t.minboltikeszlet IS NOT NULL) AND (t.minboltikeszlet <> 0)',
                [$raktarid]
            );
            $conn->executeStatement(
                'INSERT IGNORE INTO termekvaltozatminboltikeszlet (termekvaltozat_id, raktar_id, minboltikeszlet, created)'
                . ' SELECT v.id, ?, v.minboltikeszlet, NOW() FROM termekvaltozat v'
                . ' WHERE (v.minboltikeszlet IS NOT NULL) AND (v.minboltikeszlet <> 0)',
                [$raktarid]
            );
        }
        \mkw\store::setParameter(\mkw\consts::DBVersion, '0113');
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