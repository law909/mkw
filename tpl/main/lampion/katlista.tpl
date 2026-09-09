{extends "base.tpl"}

{block "kozep"}
    <div class="hasab">
        <div class="listafej">
            <h1>{$kategorianev|default:t('Kategóriák')}</h1>
        </div>

        <div class="racs">
            {foreach $children as $_kat}
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
                        {if ($_kat.leiras)}<p class="rovid">{$_kat.leiras|strip_tags|truncate:110}</p>{/if}
                    </div>
                </article>
            {/foreach}
        </div>

        {if ($termekek|default)}
            <h2 class="alcim">{t('Termékek ebben a kategóriában')}</h2>
            <div class="racs">
                {foreach $termekek as $_termek}
                    {include "termekdoboz.tpl" termek=$_termek}
                {/foreach}
            </div>
            {include "lapozo.tpl"}
        {elseif !($children|default)}
            <p class="ures">{t('Ez a kategória jelenleg üres.')}</p>
        {/if}
    </div>
{/block}
