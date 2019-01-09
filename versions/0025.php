<?php
function run() {

    runsql('INSERT INTO bizonylattipus (id, nev, irany, nyomtatni, azonosito, kezdosorszam, peldanyszam,'
        . ' mozgat, penztmozgat, editprinted, showteljesites, showesedekesseg, showhatarido, tplname, showbizonylatstatuszeditor,'
        . ' showszamlabutton, showszallitobutton, showkivetbutton, showkeziszamlabutton, showuzenet, showszallitasicim, showerbizonylatszam,'
        . ' showfuvarlevelszam, showhaszonszazalek, showstorno, foglal, showbackorder, showbevetbutton, showmesebutton, showcsomagbutton,'
        . ' showfeketelistabutton, showkupon, showfoxpostterminaleditor, showfelhasznalo, checkkelt, showpdf) '
        . ' VALUES '
        . '("leltartobblet", "Leltár többlet", "1", "0", "LT", "1", "1",'
        . ' "1", "0", "1", "1", "0", "0", "biz_lt.tpl", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "1", "0"),'
        . '("leltarhiany", "Leltár hiány", "-1", "0", "LH", "1", "1",'
        . ' "1", "0", "1", "1", "0", "0", "biz_lh.tpl", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "0", "0", "0", "0",'
        . ' "0", "0", "0", "0", "1", "0")'
    );

    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Leltár többletek","/admin/leltartobbletfej/viewlist","/admin/leltartobbletfej",20,0,1155, ""),'
        . '(1, "Leltár hiányok","/admin/leltarhianyfej/viewlist","/admin/leltarhianyfej",20,0,1156, "")'
    );

}