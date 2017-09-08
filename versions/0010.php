<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend)'
        . ' VALUES '
        . '(4, "Tartozás","/admin/tartozaslista/view","/admin/tartozaslista",20,1,420)'
    );
}