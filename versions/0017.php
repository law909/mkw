<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(4, "Óralátogatás összesítő","/admin/partnermijszoralatogatasosszesitolista/view","/admin/partnermijszoralatogatasosszesitolista",20,0,1000, "")'
    );
}