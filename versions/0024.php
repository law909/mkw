<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(4, "Számla PDF export","/admin/pdfszamlaexport/view","/admin/pdfszamlaexport",20,0,880, "")'
    );
}