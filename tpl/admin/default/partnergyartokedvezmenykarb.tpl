<table id="gyartokedvezmenytable_{$kd.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="gyartokedvezmenyid[]" type="hidden" value="{$kd.id}">
    <input name="gyartokedvezmenyoper_{$kd.id}" type="hidden" value="{$kd.oper}">
    <tr>
        <td><label for="GyartoKedvezmenyGyartoEdit{$kd.id}">{at('Gyártó')}:</label></td>
        <td><select id="GyartoKedvezmenyGyartoEdit{$kd.id}" name="gyartokedvezmenygyarto_{$kd.id}" required="required">
                <option value="">{at('válasszon')}</option>
                {foreach $kd.gyartolist as $_gy}
                    <option value="{$_gy.id}"{if ($_gy.selected)} selected="selected"{/if}>{$_gy.caption}</option>
                {/foreach}
            </select>
        </td>
        <td><label for="GyartoKedvezmenyEdit{$kd.id}">{at('Kedvezmény %')}:</label></td>
        <td><input id="GyartoKedvezmenyEdit{$kd.id}" type="text" name="gyartokedvezmeny_{$kd.id}" value="{$kd.kedvezmeny}"></td>
        <td>
            <a class="js-gyartokedvezmenydelbutton" href="#" data-id="{$kd.id}"{if ($kd.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
{if ($kd.oper=='add')}
    <a class="js-gyartokedvezmenynewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}
