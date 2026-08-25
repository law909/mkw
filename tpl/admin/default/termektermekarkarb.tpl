<table id="artable_{$ar.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="arid[]" type="hidden" value="{$ar.id}">
    <input name="aroper_{$ar.id}" type="hidden" value="{$ar.oper}">
    <tr>
        <td><label for="AzonEdit{$ar.id}">{at('Azonosító')}:</label></td>
        <td><select id="AzonEdit{$ar.id}" name="arsav_{$ar.id}" required="required">
                <option value="">{at('válasszon')}</option>
                {foreach $ar.arsavlist as $_valuta}
                    <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                {/foreach}
            </select>
        </td>
        <td><label for="ArValutaEdit{$ar.id}">{at('Valutanem')}:</label></td>
        <td><select id="ArValutaEdit{$ar.id}" name="arvalutanem_{$ar.id}" required="required">
                <option value="">{at('válasszon')}</option>
                {foreach $ar.valutanemlist as $_valuta}
                    <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                {/foreach}
            </select>
        </td>
        <td><label for="NettoEdit{$ar.id}">{at('Nettó')}:</label></td>
        <td><input id="NettoEdit{$ar.id}" type="text" name="arnetto_{$ar.id}" value="{$ar.netto}"></td>
        <td><label for="BruttoEdit{$ar.id}">{at('Bruttó')}:</label></td>
        <td><input id="BruttoEdit{$ar.id}" type="text" name="arbrutto_{$ar.id}" value="{$ar.brutto}"></td>
        <td>
            <a class="js-ardelbutton" href="#" data-id="{$ar.id}"{if ($ar.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    <tr>
        <td colspan="9">
            <input id="KepletesEdit{$ar.id}" class="js-arkepletes" name="arkepletes_{$ar.id}" type="checkbox"
                   data-id="{$ar.id}"{if ($ar.kepletes)} checked="checked"{/if}>
            <label for="KepletesEdit{$ar.id}">{at('Képlettel számolt ár')}</label>
        </td>
    </tr>
    <tr class="js-arkepletrow_{$ar.id}"{if (!$ar.kepletes)} style="display:none;"{/if}>
        <td colspan="9">
            <label for="ForrasArsavEdit{$ar.id}">{at('Forrás ársáv')}:</label>
            <select id="ForrasArsavEdit{$ar.id}" name="arforrasarsav_{$ar.id}">
                <option value="">{at('válasszon')}</option>
                {foreach $ar.forrasarsavlist as $_fa}
                    <option value="{$_fa.id}"{if ($_fa.selected)} selected="selected"{/if}>{$_fa.caption}</option>
                {/foreach}
            </select>
            <label for="SzazalekEdit{$ar.id}">{at('Szorzó')}:</label>
            <input id="SzazalekEdit{$ar.id}" type="number" step="any" size="6" name="arszazalek_{$ar.id}" value="{$ar.szazalek}"> %
            <label for="KivonEdit{$ar.id}">{at('Kivonandó')}:</label>
            <input id="KivonEdit{$ar.id}" type="number" step="any" size="8" name="arkivon_{$ar.id}" value="{$ar.kivon}">
            <label for="HozzaadEdit{$ar.id}">{at('Hozzáadandó')}:</label>
            <input id="HozzaadEdit{$ar.id}" type="number" step="any" size="8" name="arhozzaad_{$ar.id}" value="{$ar.hozzaad}">
        </td>
    </tr>
    <tr class="js-arkepletrow_{$ar.id}"{if (!$ar.kepletes)} style="display:none;"{/if}>
        <td colspan="9">
            <label>{at('Hozzáadandó kapcsolódó költségek')}:</label>
            {foreach $ar.kepletkoltseglist as $_kk}
                <input id="KepletKoltseg{$ar.id}_{$_kk.id}" name="arkepletkoltseg_{$ar.id}[]" type="checkbox"
                       value="{$_kk.id}"{if ($_kk.selected)} checked="checked"{/if}>
                <label for="KepletKoltseg{$ar.id}_{$_kk.id}">{$_kk.caption}</label>
            {foreachelse}
                {at('Nincs kapcsolódó költség rögzítve.')}
            {/foreach}
        </td>
    </tr>
    </tbody>
</table>
{if ($ar.oper=='add')}
    <a class="js-arnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}