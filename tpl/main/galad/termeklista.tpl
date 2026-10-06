{extends "base.tpl"}

{block "body"}
    <div class="row js-termeklista">
        <div class="col-md-12">
            <h3>{t('Keresés eredménye')}{if ($keresett|default:'')}: {$keresett|escape}{/if}{foreach $gyartoszurolist|default:[] as $_gy}{if ($_gy.selected)} ({t('gyártó')}: {$_gy.caption|escape}){/if}{/foreach}</h3>
        </div>
    </div>
    {if (count($termeklista) > 0)}
        <div class="row szinracs">
            {foreach $termeklista as $_termek}
                <div class="col-md-4">
                    <div class="szindoboz">
                        <a href="{$_termek.link}">
                            {if ($_termek.valtozatdb|default:0 > 1)}
                                <span class="valtozatjelveny">{if ($_termek.szindb > 1)}{$_termek.szindb} {t('szín')}, {/if}{$_termek.valtozatdb} {t('változat')}</span>
                            {/if}
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
