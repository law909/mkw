<?php

function run() {
    runsql('INSERT INTO bizonylattipus ('
        . 'id,nev,irany,nyomtatni,azonosito,kezdosorszam,peldanyszam,mozgat,penztmozgat,editprinted,'
        . 'showteljesites,showesedekesseg,showhatarido,tplname,showbizonylatstatuszeditor,showszamlabutton,showszallitobutton,showkivetbutton,showkeziszamlabutton,'
        . 'showuzenet,showszallitasicim,showerbizonylatszam,showfuvarlevelszam,showhaszonszazalek,showstorno,foglal,showbackorder,showbevetbutton,showmesebutton,'
        . 'showcsomagbutton)'
        . ' VALUES '
        . '("selejt","Selejtezés",-1,0,"SEL",1,1,1,0,1,'
        . '1,0,0,"biz_selejt.tpl",0,0,0,0,0,'
        . '0,0,1,0,0,0,0,0,0,0,'
        . '0),'
        . '("csomag","Csomag",-1,0,"CSOM",1,1,1,0,1,'
        . '1,0,0,"biz_csomag.tpl",0,1,1,1,1,'
        . '0,1,0,1,0,0,0,0,0,0,'
        . '0)'
    );

    runsql('UPDATE bizonylattipus SET showcsomagbutton=1 WHERE id="megrendeles"');
}