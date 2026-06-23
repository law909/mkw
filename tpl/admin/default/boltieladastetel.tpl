<tr class="js-boltieladas-tetel boltieladas-tetel" data-afakulcs="{$afakulcs}" data-enetto="{$enettoegysar}">
    <td class="js-be-cikkszam">{$cikkszam|escape}</td>
    <td class="js-be-nev">
        <input type="hidden" class="js-be-termekid" value="{$termekid}">
        <input type="hidden" class="js-be-valtozatid" value="{$valtozatid}">
        <input type="hidden" class="js-be-afaid" value="{$afaid}">
        {$nev|escape}
    </td>
    <td><input type="number" step="any" class="js-be-mennyiseg boltieladas-num" value="1"></td>
    <td><input type="number" step="any" class="js-be-kedvezmeny boltieladas-num" value="{$kedvezmeny}"></td>
    <td><input type="number" step="any" class="js-be-nettoegysar boltieladas-num" value="{$nettoegysar}"></td>
    <td><input type="number" step="any" class="js-be-bruttoegysar boltieladas-num" value="{$bruttoegysar}"></td>
    <td class="js-be-netto boltieladas-num">{number_format($nettoegysar,2,'.',' ')}</td>
    <td class="js-be-brutto boltieladas-num">{number_format($bruttoegysar,2,'.',' ')}</td>
    <td>
        <a href="#" class="js-be-del boltieladas-del ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
           title="Törlés"><span class="ui-button-text"><span class="ui-icon ui-icon-circle-minus"></span></span></a>
    </td>
</tr>
