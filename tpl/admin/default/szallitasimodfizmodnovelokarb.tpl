<table id="fizmodtable_{$fizmod.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="fizmodid[]" type="hidden" value="{$fizmod.id}">
    <input name="fizmodoper_{$fizmod.id}" type="hidden" value="{$fizmod.oper}">
    <tr>
        <td><label for="FizmodFizmodEdit{$fizmod.id}">{at('Fiz.mód')}:</label></td>
        <td><select id="FizmodFizmodEdit{$fizmod.id}" name="fizmodfizmod_{$fizmod.id}" required="required">
                <option value="">{at('válasszon')}</option>
                {foreach $fizmod.fizmodlist as $_valuta}
                    <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                {/foreach}
            </select>
        </td>
        <td><label for="FizmodOsszegEdit{$fizmod.id}">{at('Összeg')}:</label></td>
        <td><input id="FizmodOsszegEdit{$fizmod.id}" type="number" step="any" name="fizmodosszeg_{$fizmod.id}" value="{$fizmod.osszeg}"></td>
        <td><label for="FizmodMaxhatarEdit{$fizmod.id}">Max. kosár érték:</label></td>
        <td><input id="FizmodMaxhatarEdit{$fizmod.id}" type="number" step="any" name="fizmodmaxhatar_{$fizmod.id}" value="{$fizmod.maxhatar}"></td>
        <td><label for="FizmodKosarSzazEdit{$fizmod.id}">Kosár érték százaléka:</label></td>
        <td><input id="FizmodKosarSzazEdit{$fizmod.id}" type="number" step="any" name="fizmodertekszazalek_{$fizmod.id}" value="{$fizmod.ertekszazalek}"></td>
        <td>
            <a class="js-fizmoddelbutton" href="#" data-id="{$fizmod.id}"{if ($fizmod.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
{if ($orszag.oper=='add')}
    <a class="js-fizmodnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}