<table id="kedvezmenytable_{$kd.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="kedvezmenyid[]" type="hidden" value="{$kd.id}">
    <input name="kedvezmenyoper_{$kd.id}" type="hidden" value="{$kd.oper}">
    <tr>
        <td><label for="KedvezmenyTermekcsoportEdit{$kd.id}">{at('Termékcsoport')}:</label></td>
        <td><select id="KedvezmenyTermekcsoportEdit{$kd.id}" name="kedvezmenytermekcsoport_{$kd.id}" required="required">
                <option value="">{at('válasszon')}</option>
                {foreach $kd.termekcsoportlist as $_valuta}
                    <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                {/foreach}
            </select>
        </td>
        <td><label for="KedvezmenyEdit{$kd.id}">{at('Kedvezmény %')}:</label></td>
        <td><input id="KedvezmenyEdit{$kd.id}" type="text" name="kedvezmeny_{$kd.id}" value="{$kd.kedvezmeny}"></td>
        <td>
            <a class="js-termekcsoportkedvezmenydelbutton" href="#" data-id="{$kd.id}"{if ($kd.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
{if ($kd.oper=='add')}
    <a class="js-termekcsoportkedvezmenynewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}