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
    {/if}
    {if ($idopontid)}
        <div class="foglalasfejlec">
            <div><strong>{$temanev}</strong></div>
            <div>{$napnev} - {$datum} {$idotartam}</div>
            <div>{$tanar}</div>
            {if ($helyszin)}
                <div>{$helyszin}{if ($helyszincim)} ({$helyszincim}){/if}</div>
            {/if}
        </div>
        <form id="idopontlemondasform" method="get" action="/idopont/lemond">
            <div class="form-group">
                <label class="form-label" for="lemondemailedit">A foglalásod lemondásához add meg az emailcímed</label>
                <input class="form-control" id="lemondemailedit" type="email" name="email" maxlength="255" required>
            </div>
            <input type="hidden" name="rid" value="{$idopontuid}">
            <input type="hidden" name="d" value="{$datumparam}">
            <input type="hidden" name="t" value="{$tanarkod}">
            <input type="hidden" name="tema" value="{$temakod}">
            <div class="form-group">
                <button class="lemondasbtn" type="submit">Lemondom</button>
            </div>
        </form>
    {/if}
    <div><a href="{$visszaurl}">Vissza az időpontokhoz</a></div>
</div>
</body>
</html>
