<table id="orszagtable_{$orszag.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
        <input name="orszagid[]" type="hidden" value="{$orszag.id}">
        <input name="orszagoper_{$orszag.id}" type="hidden" value="{$orszag.oper}">
        <tr>
            <td><label for="OrszagOrszagEdit{$orszag.id}">{at('Ország')}:</label></td>
            <td><select id="OrszagOrszagEdit{$orszag.id}" name="orszagorszag_{$orszag.id}" required="required">
                    <option value="">{at('válasszon')}</option>
                    {foreach $orszag.orszaglist as $_valuta}
                        <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                    {/foreach}
                </select>
            </td>
            <td><label for="OrszagValutaEdit{$orszag.id}">{at('Valutanem')}:</label></td>
            <td><select id="OrszagValutaEdit{$orszag.id}" name="orszagvalutanem_{$orszag.id}" required="required">
                    <option value="">{at('válasszon')}</option>
                    {foreach $orszag.valutanemlist as $_valuta}
                        <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                    {/foreach}
                </select>
            </td>
            <td><label for="OrszagertekEdit{$orszag.id}">{at('Alsó határ')}:</label></td>
            <td><input id="OrszagertekEdit{$orszag.id}" type="number" step="any" name="orszagertek_{$orszag.id}" value="{$orszag.hatarertek}"></td>
            <td><label for="OrszagOsszegEdit{$orszag.id}">{at('Összeg')}:</label></td>
            <td><input id="OrszagOsszegEdit{$orszag.id}" type="number" step="any" name="orszagosszeg_{$orszag.id}" value="{$orszag.osszeg}"></td>
            <td>
                <a class="js-orszagdelbutton" href="#" data-id="{$orszag.id}"{if ($orszag.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
            </td>
        </tr>
    </tbody>
</table>
{if ($orszag.oper=='add')}
    <a class="js-orszagnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}