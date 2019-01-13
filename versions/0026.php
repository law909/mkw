<?php
function run() {

    runsql('INSERT INTO menu (menucsoport_id, nev, url, routename, jogosultsag, lathato, sorrend, class)'
        . ' VALUES '
        . '(3, "Blogposztok","/admin/blogposzt/viewlist","/admin/blogposzt",20,1,350, "")'
    );

}