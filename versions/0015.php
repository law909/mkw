<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(7, "Biz.partner javítás","","",90,1,900, "js-bizpartnerjavit")'
    );
}