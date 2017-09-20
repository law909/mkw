<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(8, "Órarend nyomtatás","/orarend/print","",15,1,150, "js-orarendprint")'
    );
}