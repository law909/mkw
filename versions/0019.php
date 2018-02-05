<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(2, "SZÉP kártya kifizetés","/admin/szepkartyakifizetes/view","/admin/szepkartyakifizetes",20,0,250, "")'
    );
}