<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    {include 'idopontpublicstyle.tpl'}
</head>

<body>
<div class="dtt foglalaseredmeny">
    <div class="foglalasfejlec">
        {if ($varolista)}
            <div><strong>Felvettünk a várólistára, {$partnernev|escape}!</strong></div>
        {else}
            <div><strong>Köszönjük a foglalást, {$partnernev|escape}!</strong></div>
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
    </div>
    <div class="foglalasmegjegyzes">A számlát a nálunk tárolt adataid alapján állítjuk ki. Ez az űrlap a már megadott
        adataidat nem írja át – ha változtak, szólj nekünk.</div>
    <div><a href="{$visszaurl}">Vissza az időpontokhoz</a></div>
</div>
</body>
</html>
