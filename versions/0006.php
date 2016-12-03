<?php
function run() {
    runsql('INSERT INTO bizonylattipus ('
        . 'id,nev,irany,nyomtatni,azonosito,kezdosorszam,peldanyszam,mozgat,penztmozgat,editprinted,'
        . 'showteljesites,showesedekesseg,showhatarido,tplname,showbizonylatstatuszeditor,showszamlabutton,showszallitobutton,showkivetbutton,showkeziszamlabutton,'
        . 'showuzenet,showszallitasicim,showerbizonylatszam,showfuvarlevelszam,showhaszonszazalek,showstorno,foglal,showbackorder,showbevetbutton,showmesebutton,'
        . 'showcsomagbutton,showfeketelistabutton,showkupon)'
        . ' VALUES '
        . '("koltsegszamla","Költségszámla",1,0,"KTG",1,1,0,1,1,'
        . '1,1,0,"biz_koltsegszamla.tpl",0,0,0,0,0,'
        . '0,0,1,1,0,0,0,0,0,0,'
        . '0,0,0)'
    );
}