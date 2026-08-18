<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    {include 'idopontpublicstyle.tpl'}
</head>

<body>
<div class="dtt">
    <div class="foglalasfejlec">
        <div><strong>Köszönjük a foglalást, {$partnernev}!</strong></div>
        <div>{$temanev}</div>
        <div>{$napnev} - {$datum} {$idotartam}</div>
        <div>{$tanar}</div>
        {if ($helyszin)}
            <div>{$helyszin}{if ($helyszincim)} ({$helyszincim}){/if}</div>
        {/if}
        <div>Részvétel: {if ($online)}online{else}élőben{/if}</div>
    </div>
    <div><a href="{$visszaurl}">Vissza az időpontokhoz</a></div>
</div>
</body>
</html>
