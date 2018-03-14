<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(8, "Tanár elszámolás","/admin/tanarelszamolas/view","/admin/tanarelszamolas",20,0,400, "")'
    );
}