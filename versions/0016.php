<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Eseti számlák","/admin/esetiszamlafej/viewlist","/admin/esetiszamlafej",15,1,950, "")'
    );

    runsql('INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt) '
        . ' VALUES '
        . '("esetiszamla", "Számla", "-1", "1", "ESZ", "1", "2",'
        . ' "1", "1", "0", "1", "1", "0", "biz_szamla.tpl", "0",'
        . ' "0", "0", "0", "0", "1", "1", "0",'
        . ' "1", "0", "1", "0", "0", "0", "0", "0",'
        . ' "0", "1", "0", "0", "1")'
    );
}