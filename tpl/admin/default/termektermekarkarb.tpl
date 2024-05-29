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
    </tbody>
</table>
{if ($ar.oper=='add')}
    <a class="js-arnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}