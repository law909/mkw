<?php
function run() {

    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(1, "Blogposztok","/admin/blogposzt/viewlist","/admin/blogposzt",20,0,350, "")'
    );

}