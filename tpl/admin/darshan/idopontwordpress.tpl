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
                        {* a saját webcím erősebb a témáénál: az az alkalom kiírása a wordpress oldalon *}
                        {* a törzsekben relatív útvonalak állnak, ezért megy mindegyik prefixUrl-lel *}
                        <div class="margin-bottom-5">
                            {if ($idopont['url'])}
                                <a href="{prefixUrl('http://jogadarshan.hu/', $idopont['url'])}" target="_parent">{$idopont['nev']}</a>
                            {elseif ($idopont['temaurl'])}
                                <a href="{prefixUrl('http://jogadarshan.hu/', $idopont['temaurl'])}" target="_parent">{$idopont['nev']}</a>
                            {else}
                                {$idopont['nev']}
                            {/if}
                        </div>
                        <div class="margin-bottom-5">
                            {if ($idopont['tanarurl'])}
                                <a href="{prefixUrl('http://jogadarshan.hu/', $idopont['tanarurl'])}" target="_parent">{$idopont['tanar']}</a>
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
                        {if ($idopont['szabadhely'] !== null)}
                            <div>{$idopont['szabadhely']} szabad hely</div>
                        {/if}
                    </div>
                    <div class="dttgombok">
                        {if ($idopont['megvanhely'])}
                            {if ($idopont['url'])}
                                <a href="{prefixUrl('http://jogadarshan.hu/', $idopont['url'])}" target="_parent"
                                   class="dttorarendbutton margin-bottom-5">{if ($idopont['rendezveny'])}Jelentkezek{else}Foglalok{/if}</a>
                            {else}
                                <a href="/idopont/foglalas?id={$idopont['id']}&d={$idopont['datum']}{$szuroparam}"
                                   class="dttorarendbutton margin-bottom-5">{if ($idopont['rendezveny'])}Jelentkezek{else}Foglalok{/if}</a>
                            {/if}
                        {else}
                            <div class="pirosszoveg margin-bottom-5">BETELT</div>
                        {/if}
                        <a href="/idopont/lemond?rid={$idopont['uid']|escape:'url'}&d={$idopont['datum']}{$szuroparam}"
                           class="dttorarendbutton">Lemondom</a>
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
