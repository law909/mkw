<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/idopontiframe.js"></script>
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    {include 'idopontpublicstyle.tpl'}
</head>

<body>
<div class="dtt foglalaseredmeny">
    <div class="foglalasfejlec">
        {if ($varolista)}
            <div class="foglalascim">Felvettünk a várólistára, {$partnernev|escape}!</div>
        {else}
            <div class="foglalascim">Köszönjük a foglalást, {$partnernev|escape}!</div>
        {/if}
        <div>{$temanev}</div>
        <div>{$napnev} - {$datum} {$idotartam}</div>
        <div>{$tanar}</div>
        {if ($helyszin)}
            <div>{$helyszin}{if ($helyszincim)} ({$helyszincim}){/if}</div>
        {/if}
        <div>Részvétel: {if ($online)}online{else}élőben{/if}</div>
        {if ($varolista)}
            <div>Ha felszabadul hely, emailben értesítünk.</div>
        {/if}
        {if ($emailkiment)}
            <div class="foglalasemail">A visszaigazolást elküldtük a(z) {$email|escape} címre.</div>
        {/if}
    </div>
    <div class="foglalasmegjegyzes">A számlát a nálunk tárolt adataid alapján állítjuk ki. Ez az űrlap a már megadott
        adataidat nem írja át – ha változtak, szólj nekünk.</div>
    <div><a href="{$visszaurl}">Vissza az időpontokhoz</a></div>
</div>
</body>
</html>
