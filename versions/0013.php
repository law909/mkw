<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend)'
        . ' VALUES '
        . '(2, "Pénztár zárás","/admin/penztarzaras/view","/admin/penztarzaras",20,1,400)'
    );
}