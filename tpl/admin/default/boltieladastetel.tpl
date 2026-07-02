<tr class="js-boltieladas-tetel boltieladas-tetel" data-afakulcs="{$afakulcs}" data-enetto="{$enettoegysar}">
    <td class="js-be-cikkszam">{$cikkszam|escape}</td>
    <td class="js-be-nev">
        <input type="hidden" class="js-be-termekid" value="{$termekid}">
        <input type="hidden" class="js-be-valtozatid" value="{$valtozatid}">
        <input type="hidden" class="js-be-afaid" value="{$afaid}">
        {* A nettó értékeket rejtve tartjuk: a mentés és az összesítés szerverhívása is használja őket, csak az UI-ról vettük le az oszlopot. *}
        <input type="hidden" class="js-be-nettoegysar" value="{$nettoegysar}">
        <span class="js-be-netto" style="display:none">{number_format($nettoegysar,2,'.',' ')}</span>
        {$nev|escape}
    </td>
    <td class="js-be-raktaron boltieladas-raktaron {if $raktaron}greentext{else}redtext{/if}">{if $raktaron}{t('Van')}{else}{t('Nincs')}{/if} ({$keszlet|string_format:"%g"})</td>
    <td><input type="number" step="any" class="js-be-mennyiseg boltieladas-num" value="1"></td>
    <td><input type="number" step="any" class="js-be-kedvezmeny boltieladas-num" value="{$kedvezmeny}"></td>
    <td><input type="number" step="any" class="js-be-bruttoegysar boltieladas-num" value="{$bruttoegysar}"></td>
    <td class="js-be-brutto boltieladas-num">{number_format($bruttoegysar,2,'.',' ')}</td>
    <td>
        <a href="#" class="js-be-del boltieladas-del ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
           title="Törlés"><span class="ui-button-text"><span class="ui-icon ui-icon-circle-minus"></span></span></a>
    </td>
</tr>
