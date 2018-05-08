<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(4, "RLB CSV export","/admin/rlbcsvexport","/admin/rlbcsvexport",20,0,870, "")'
    );
}