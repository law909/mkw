<?php
function run() {
    runsql('UPDATE menu SET nev="Leltárak", url="/admin/leltarfej/viewlist", routename="/admin/leltarfej" WHERE routename="/admin/leltar"');
}