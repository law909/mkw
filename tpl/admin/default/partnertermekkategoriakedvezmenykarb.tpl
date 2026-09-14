<table id="kedvezmenytable_{$kd.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="kedvezmenyid[]" type="hidden" value="{$kd.id}">
    <input name="kedvezmenyoper_{$kd.id}" type="hidden" value="{$kd.oper}">
    <tr>
        <td><label>{at('Termékkategória')}:</label></td>
        <td>
            <input name="kedvezmenytermekfa_{$kd.id}" type="hidden" value="{$kd.termekfa}">
            <a class="js-termekkategoriafabutton" href="#" data-text="{at('válasszon')}">{if ($kd.termekfanev)}{$kd.termekfanev}{else}{at('válasszon')}{/if}</a>
        </td>
        <td><label for="KedvezmenyEdit{$kd.id}">{at('Kedvezmény %')}:</label></td>
        <td><input id="KedvezmenyEdit{$kd.id}" type="text" name="kedvezmeny_{$kd.id}" value="{$kd.kedvezmeny}"></td>
        <td>
            <a class="js-termekkategoriakedvezmenydelbutton" href="#" data-id="{$kd.id}"{if ($kd.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
{if ($kd.oper=='add')}
    <a class="js-termekkategoriakedvezmenynewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}
