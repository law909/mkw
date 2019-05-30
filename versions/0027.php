<?php
function run() {

    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(8, "Helyettesítések","/admin/orarendhelyettesites/viewlist","/admin/orarendhelyettesites",20,0,120, "")'
    );

}