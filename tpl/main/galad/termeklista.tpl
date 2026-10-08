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
                            {if ($_termek.szindb|default:0 > 1 || $_termek.meretdb|default:0 > 1)}
                                <span class="valtozatjelveny">{if ($_termek.szindb > 1)}{$_termek.szindb} {t('szín')}{if ($_termek.meretdb > 1)}, {/if}{/if}{if ($_termek.meretdb > 1)}{$_termek.meretdb} {t('méret')}{/if}</span>
                            {elseif ($_termek.valtozatdb|default:0 > 1)}
                                {* variants without a Szín/Méret törzs reference: only their number is known *}
                                <span class="valtozatjelveny">{$_termek.valtozatdb} {t('változat')}</span>
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
