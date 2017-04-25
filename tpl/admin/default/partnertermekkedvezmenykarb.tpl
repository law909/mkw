<table id="termekkedvezmenytable_{$kd.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="termekkedvezmenyid[]" type="hidden" value="{$kd.id}">
    <input name="termekkedvezmenyoper_{$kd.id}" type="hidden" value="{$kd.oper}">
    <tr>
        <td><label for="KedvezmenyTermekEdit{$kd.id}">{at('Termék')}:</label></td>
        <td><input id="KedvezmenyTermekEdit{$kd.id}" type="text" name="termekkedvezmenynev_{$kd.id}" class="js-termekkedvezmenytermekselect termekselect" value="{$kd.termekcikkszam} {$kd.termeknev}" required="required">
            <input class="js-termekkedvezmenytermekid" name="termekkedvezmenytermek_{$kd.id}" type="hidden" value="{$kd.termek}">
        </td>
        <td><label for="KedvezmenyEdit{$kd.id}">{at('Kedvezmény %')}:</label></td>
        <td><input id="KedvezmenyEdit{$kd.id}" type="text" name="termekkedvezmeny_{$kd.id}" value="{$kd.kedvezmeny}"></td>
        <td>
            <a class="js-termekkedvezmenydelbutton" href="#" data-id="{$kd.id}"{if ($kd.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
{if ($kd.oper=='add')}
    <a class="js-termekkedvezmenynewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}