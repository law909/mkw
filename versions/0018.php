<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Leltár","/admin/leltar/view","/admin/leltar",20,0,1150, "")'
    );
}