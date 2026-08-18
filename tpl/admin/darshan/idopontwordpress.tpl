<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="/js/main/darshan/iframeResizer.contentWindow.min.js"></script>
    {include 'idopontpublicstyle.tpl'}
</head>

<body>
<div class="dtt">
    <div class="dttlapozo">
        <a href="/idopont/wp?o={$prevoffset}{$szuroparam}" class="dttprev">Előző hét</a>
        <a href="/idopont/wp?o=0{$szuroparam}" class="dttakt">Aktuális hét</a>
        <a href="/idopont/wp?o={$nextoffset}{$szuroparam}" class="dttnext">Következő hét</a>
    </div>
    {foreach $idopontok as $nap}
        <div class="dttnap">
            <div class="dttnapnev">{$nap['napnev']} - {$nap['napdatum']}</div>
            {foreach $nap['idopontok'] as $idopont}
                <div class="dttora">
                    <div class="dttidopont{if ($idopont['delelott'])} delelott{/if}">{$idopont['kezdet']}-{$idopont['veg']}</div>
                    <div class="dttoranev">
                        <div class="margin-bottom-5">
                            {if ($idopont['temaurl'])}
                                <a href="{$idopont['temaurl']}" target="_parent">{$idopont['temanev']}</a>
                            {else}
                                {$idopont['temanev']}
                            {/if}
                        </div>
                        <div class="margin-bottom-5">
                            {if ($idopont['tanarurl'])}
                                <a href="{$idopont['tanarurl']}" target="_parent">{$idopont['tanar']}</a>
                            {else}
                                {$idopont['tanar']}
                            {/if}
                        </div>
                        {if ($idopont['helyszin'])}
                            <div class="margin-bottom-5">{$idopont['helyszin']}{if ($idopont['helyszincim'])} ({$idopont['helyszincim']}){/if}</div>
                        {/if}
                        {if ($idopont['ar'] > 0)}
                            <div class="margin-bottom-5">{$idopont['ar']|number_format:0:",":" "} Ft</div>
                        {/if}
                        <div>{$idopont['szabadhely']} szabad hely</div>
                    </div>
                    <div class="dtttanar">
                        {if ($idopont['megvanhely'])}
                            <a href="/idopont/foglalas?id={$idopont['id']}&d={$idopont['datum']}{$szuroparam}"
                               class="dttorarendbutton">Foglalok</a>
                        {else}
                            <div class="pirosszoveg">BETELT</div>
                        {/if}
                    </div>
                </div>
            {/foreach}
        </div>
    {foreachelse}
        <div class="dttures">Ezen a héten nincs meghirdetett időpont.</div>
    {/foreach}
</div>
</body>
</html>
