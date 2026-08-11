{* Egy POS tételsor. A mezőnevek eleve a klasszikus rögzítőé (tetelid[], tetel*_<uid>),
   ezért a mentés a gyorsrögzítő ágán megy át fordítás nélkül. *}
<tr class="js-postetel bizonylatpos-tetel" data-tetelid="{$tetelid}" data-afakulcs="{$afakulcs}"
    data-enetto="{$enettoegysar}" data-ebrutto="{$ebruttoegysar}">
    <td class="js-pos-cikkszam">{$cikkszam|escape}</td>
    <td>
        <input name="tetelid[]" type="hidden" value="{$tetelid}">
        <input name="teteloper_{$tetelid}" type="hidden" value="add">
        {* a js-termekid osztályból számolja a checkBizonylatFej() a tételeket – e nélkül a
           mentés "Nincsenek tételek a bizonylaton"-nal áll meg *}
        <input class="js-termekid" name="teteltermek_{$tetelid}" type="hidden" value="{$termekid}">
        <input name="tetelvaltozat_{$tetelid}" type="hidden" value="{$valtozatid}">
        <input name="tetelafa_{$tetelid}" type="hidden" value="{$afaid}">
        <input class="js-posenettoegysar" name="tetelenettoegysar_{$tetelid}" type="hidden" value="{$enettoegysar}">
        <input class="js-posebruttoegysar" name="tetelebruttoegysar_{$tetelid}" type="hidden" value="{$ebruttoegysar}">
        <input class="js-posnettoegysar" name="tetelnettoegysar_{$tetelid}" type="hidden" value="{$nettoegysar}">
        {$nev|escape}
    </td>
    <td class="bizonylatpos-raktaron {if $raktaron}greentext{else}redtext{/if}">
        {if $raktaron}{at('Van')}{else}{at('Nincs')}{/if} ({$keszlet|string_format:"%g"})
    </td>
    <td><input class="js-posmennyiseg bizonylatpos-num" name="tetelmennyiseg_{$tetelid}" type="number" step="any" value="1"></td>
    <td><input class="js-poskedvezmeny bizonylatpos-num" name="tetelkedvezmeny_{$tetelid}" type="number" step="any" value="{$kedvezmeny}"></td>
    <td><input class="js-posbruttoegysar bizonylatpos-num" name="tetelbruttoegysar_{$tetelid}" type="number" step="any" value="{$bruttoegysar}"></td>
    <td class="js-posbrutto bizonylatpos-num">{number_format($bruttoegysar,2,'.',' ')}</td>
    <td>
        <a class="js-postetheldel bizonylatpos-del ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
           href="#" title="{at('Töröl')}"><span class="ui-button-text"><span class="ui-icon ui-icon-circle-minus"></span></span></a>
    </td>
</tr>
