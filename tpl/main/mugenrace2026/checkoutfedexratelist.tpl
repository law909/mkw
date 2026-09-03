{if ($hiba)}
    <div class="chk-fedexhiba folyoszoveg">{$hiba}</div>
{else}
    {foreach $fedexratelist as $rate}
        <label class="radio">
            <input type="radio" name="fedexservice" class="js-fedexservice" value="{$rate.servicetype}"{if ($rate.selected)} checked{/if}
                   data-caption="{$rate.servicename}">
            {$rate.servicename} ({number_format($rate.szallitasidij,0,',',' ')} {$valutanemnev})
            {if ($rate.kiszallitasdatum)}
                <span class="chk-fedexdatum">{t('várható kiszállítás')}: {$rate.kiszallitasdatum}</span>
            {/if}
        </label>
    {/foreach}
{/if}
