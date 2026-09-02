<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    {include 'idopontpublicstyle.tpl'}
</head>

<body>
<div class="dtt">
    {if ($hiba)}
        <div class="foglalashiba">{$hiba}</div>
    {else}
        <div class="foglalasfejlec">
            <div><strong>A foglalásodat lemondtuk{if ($partnernev)}, {$partnernev}{/if}.</strong></div>
            <div>{$temanev}</div>
            <div>{$napnev} - {$datum} {$idotartam}</div>
            <div>{$tanar}</div>
            {if ($helyszin)}
                <div>{$helyszin}{if ($helyszincim)} ({$helyszincim}){/if}</div>
            {/if}
        </div>
    {/if}
    <div><a href="{$visszaurl}">Vissza az időpontokhoz</a></div>
</div>
</body>
</html>
