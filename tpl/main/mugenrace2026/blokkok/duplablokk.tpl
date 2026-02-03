{if isset($blokk.lathato) && $blokk.lathato}
    <section id="{$blokk.id}" class="content-grid {$blokk.cssclass}" {if (isset($blokk.cssstyle) && $blokk.cssstyle)} style="{$blokk.cssstyle}" {/if}>
        <div class="grid-item">
            <img src="{$imagepath}{$blokk.hatterkepurl2000}" alt="{$blokk.cim}">
            <div class="grid-content inverse flex-cc flex-col">
                {if isset($blokk.cim) && $blokk.cim}<h3>{$blokk.cim}</h3>{/if}
                {if isset($blokk.leiras) && $blokk.leiras}<p>{$blokk.leiras}</p>{/if}
                {if isset($blokk.gomburl) && $blokk.gomburl}<a href="{$blokk.gomburl}" class="button bordered inverse">{$blokk.gombfelirat}</a>{/if}
            </div>
        </div>
        <div class="grid-item">
            <img src="{$imagepath}{$blokk.hatterkepurl22000}" alt="{$blokk.cim2}">
            <div class="grid-content inverse flex-cc flex-col">
                {if isset($blokk.cim2) && $blokk.cim2}<h3>{$blokk.cim2}</h3>{/if}
                {if isset($blokk.leiras2) && $blokk.leiras2}<p>{$blokk.leiras2}</p>{/if}
                {if isset($blokk.gomburl2) && $blokk.gomburl2}<a href="{$blokk.gomburl2}" class="button bordered inverse">{$blokk.gombfelirat2}</a>{/if}
            </div>
        </div>
    </section>
{/if}