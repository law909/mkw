<?php
function run() {
    runsql('INSERT INTO bizonylattipus ('
        . 'id,nev,irany,nyomtatni,azonosito,kezdosorszam,peldanyszam,mozgat,penztmozgat,editprinted,'
        . 'showteljesites,showesedekesseg,showhatarido,tplname,showbizonylatstatuszeditor,showszamlabutton,showszallitobutton,showkivetbutton,showkeziszamlabutton,'
        . 'showuzenet,showszallitasicim,showerbizonylatszam,showfuvarlevelszam,showhaszonszazalek,showstorno,foglal,showbackorder,showbevetbutton,showmesebutton,'
        . 'showcsomagbutton,showfeketelistabutton,showkupon)'
        . ' VALUES '
        . '("penztar","Pénztár bizonylat",0,0,"PENZ",1,1,0,1,1,'
        . '0,0,0,"biz_penztar.tpl",0,0,0,0,0,'
        . '0,0,1,0,0,0,0,0,0,0,'
        . '0,0,0)'
    );
}