{extends "base.tpl"}

{block "kozep"}
    <div class="hasab">
        <div class="listafej">
            {if ($keresett|default)}
                <h1>{t('Keresés')}: {$keresett|escape}</h1>
            {else}
                <h1>{$kategoria.nev|default:$kategorianev|default:t('Termékek')}</h1>
            {/if}
            {if ($lapozo|default)}
                <div class="talalatdb">{$lapozo.elemcount} {t('termék')}</div>
            {/if}
        </div>

        {if ($kategoria.leiras2|default)}<div class="katleiras">{$kategoria.leiras2}</div>{/if}

        {if ($termekek|default)}
            <form class="rendezes" method="get" action="{$url}">
                {if ($keresett|default)}<input type="hidden" name="keresett" value="{$keresett|escape}">{/if}
                <label for="order">{t('Sorrend')}</label>
                <select name="order" id="order" onchange="this.form.submit()">
                    <option value="nevasc"{if ($order|default) == 'nevasc'} selected{/if}>{t('Név szerint A-Z')}</option>
                    <option value="nevdesc"{if ($order|default) == 'nevdesc'} selected{/if}>{t('Név szerint Z-A')}</option>
                    <option value="arasc"{if ($order|default) == 'arasc'} selected{/if}>{t('Ár szerint növekvő')}</option>
                    <option value="ardesc"{if ($order|default) == 'ardesc'} selected{/if}>{t('Ár szerint csökkenő')}</option>
                </select>
            </form>

            <div class="racs">
                {foreach $termekek as $_termek}
                    {include "termekdoboz.tpl" termek=$_termek}
                {/foreach}
            </div>

            {include "lapozo.tpl"}
        {else}
            <p class="ures">{t('Ebben a kategóriában jelenleg nincs megjeleníthető termék.')}</p>
        {/if}

        {if ($kategoria.leiras3|default)}<div class="katleiras">{$kategoria.leiras3}</div>{/if}
    </div>
{/block}
