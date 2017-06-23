<?php
function run() {
    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend)'
        . ' VALUES '
        . '(8, "Rendezvények","/admin/rendezveny/viewlist","/admin/rendezveny",20,1,300),'
        . '(8, "Rendezvény jelentkezések","/admin/rendezvenyjelentkezes/viewlist","/admin/rendezvenyjelentkezes",20,1,350)'
    );
}