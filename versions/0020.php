<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(4, "Teljesítmény jelentés","/admin/teljesitmenyjelentes/view","/admin/teljesitmenyjelentes",20,0,850, "")'
    );
}