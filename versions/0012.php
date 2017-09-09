<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend)'
        . ' VALUES '
        . '(7, "MiniCRM","/admin/minicrm/view","/admin/minicrm",20,0,750)'
    );
}