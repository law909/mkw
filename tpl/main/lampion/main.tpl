{extends "base.tpl"}

{block "kozep"}
    <section class="hero">
        <div class="hasab">
            <h1>{$globaltitle|default:'Lampion 2000'|escape}</h1>
            <p class="heroszoveg">{t('Édesség és kegyeleti mécses – a teljes kínálatunk egy helyen, képekkel.')}</p>
            <a class="gomb" href="{$menu1[0].link|default:'/kereses'}">{t('Kínálat böngészése')}</a>
        </div>
    </section>

    <section class="hasab blokk">
        <h2>{t('Kategóriák')}</h2>
        <div class="racs">
            {foreach $kategoriak|default:$menu1 as $_kat}
                <article class="doboz katdoboz">
                    <a class="dobozkep" href="{$_kat.link}">
                        {if ($_kat.kozepeskepurl)}
                            <img src="{$_kat.kozepeskepurl}" alt="{$_kat.caption|escape}" loading="lazy">
                        {else}
                            <span class="nincskep" aria-hidden="true">🕯</span>
                        {/if}
                    </a>
                    <div class="dobozszoveg">
                        <h3><a href="{$_kat.link}">{$_kat.caption}</a></h3>
                    </div>
                </article>
            {/foreach}
        </div>
    </section>

    {if ($ajanlotttermekek|default)}
        <section class="hasab blokk">
            <h2>{t('Ajánlatunk')}</h2>
            <div class="racs">
                {foreach $ajanlotttermekek as $_termek}
                    {include "termekdoboz.tpl" termek=$_termek}
                {/foreach}
            </div>
        </section>
    {/if}

    {if ($legujabbtermekek|default)}
        <section class="hasab blokk">
            <h2>{t('Újdonságaink')}</h2>
            <div class="racs">
                {foreach $legujabbtermekek as $_termek}
                    {include "termekdoboz.tpl" termek=$_termek}
                {/foreach}
            </div>
        </section>
    {/if}

    {if ($hirek|default)}
        <section class="hasab blokk">
            <h2>{t('Hírek')}</h2>
            <div class="hirracs">
                {foreach $hirek as $_hir}
                    <article class="hirdoboz">
                        <div class="hirdatum">{$_hir.datum}</div>
                        <h3><a href="/hir/{$_hir.slug}">{$_hir.cim}</a></h3>
                        <p>{$_hir.lead|strip_tags|truncate:160}</p>
                    </article>
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
