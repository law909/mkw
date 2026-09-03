{extends "base.tpl"}

{block "body"}
    <div class="row">
        <div class="col-md-12">
            <h3>{t('Keresés eredménye')}{if ($keresett|default:'')}: {$keresett|escape}{/if}</h3>
        </div>
    </div>
    {if (count($termeklista) > 0)}
        <div class="row">
            {foreach $termeklista as $_termek}
                <div class="col-md-4">
                    <div class="szindoboz">
                        <a href="{$_termek.link}">
                            <img src="{$imagepath}{$_termek.kiskepurl}" class="szinkep">
                            <div class="szinszoveg">{$_termek.cikkszam} {$_termek.caption}</div>
                        </a>
                    </div>
                </div>
            {/foreach}
        </div>
    {else}
        <div class="row">
            <div class="col-md-12">
                <p>{t('Nincs a keresésnek megfelelő termék.')}</p>
            </div>
        </div>
    {/if}
{/block}
