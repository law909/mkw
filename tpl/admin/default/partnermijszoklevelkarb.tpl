<table id="mijszokleveltable_{$mijszoklevel.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="mijszoklevelid[]" type="hidden" value="{$mijszoklevel.id}">
    <input name="mijszokleveloper_{$mijszoklevel.id}" type="hidden" value="{$mijszoklevel.oper}">
    <tr>
        <td><label for="MIJSZOklevelOklevelkibocsajtoEdit{$mijszoklevel.id}">{at('Oklevél kibocsájtó')}:</label></td>
        <td><select id="MIJSZOklevelOklevelkibocsajtoEdit{$mijszoklevel.id}" name="mijszokleveloklevelkibocsajto_{$mijszoklevel.id}" required="required">
                <option value="">{at('válasszon')}</option>
                {foreach $mijszoklevel.mijszoklevelkibocsajtolist as $_valuta}
                    <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                {/foreach}
            </select>
        </td>
        <td><label for="MIJSZOklevelOklevelszintEdit{$mijszoklevel.id}">{at('Oklevél szint')}:</label></td>
        <td><select id="MIJSZOklevelOklevelszintEdit{$mijszoklevel.id}" name="mijszokleveloklevelszint_{$mijszoklevel.id}" required="required">
                <option value="">{at('válasszon')}</option>
                {foreach $mijszoklevel.mijszoklevelszintlist as $_valuta}
                    <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                {/foreach}
            </select>
        </td>
        <td><label for="OklevelevEdit{$mijszoklevel.id}">{at('Oklevél éve')}:</label></td>
        <td><input id="OklevelevEdit{$mijszoklevel.id}" type="text" name="mijszokleveloklevelev_{$mijszoklevel.id}" value="{$mijszoklevel.oklevelev}"></td>
        <td>
            <a class="js-mijszokleveldelbutton" href="#" data-id="{$mijszoklevel.id}"{if ($mijszoklevel.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
{if ($mijszoklevel.oper=='add')}
    <a class="js-mijszoklevelnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}