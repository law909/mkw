<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend)'
        . ' VALUES '
        . '(4, "Munkaidő elszámolás","/admin/munkaidolista/view","/admin/munkaidolista",20,1,1000)'
    );
}