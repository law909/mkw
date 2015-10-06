<table id="hatartable_{$hatar.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
        <input name="hatarid[]" type="hidden" value="{$hatar.id}">
        <input name="hataroper_{$hatar.id}" type="hidden" value="{$hatar.oper}">
        <tr>
            <td><label for="HatarValutaEdit{$ar.id}">{t('Valutanem')}:</label></td>
            <td><select id="HatarValutaEdit{$ar.id}" name="hatarvalutanem_{$hatar.id}" required="required">
                    <option value="">{t('válasszon')}</option>
                    {foreach $hatar.valutanemlist as $_valuta}
                        <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                    {/foreach}
                </select>
            </td>
            <td><label for="HatarertekEdit{$hatar.id}">{t('Alsó határ')}:</label></td>
            <td><input id="HatarertekEdit{$hatar.id}" type="number" step="any" name="hatarertek_{$hatar.id}" value="{$hatar.hatarertek}"></td>
            <td><label for="OsszegEdit{$hatar.id}">{t('Összeg')}:</label></td>
            <td><input id="OsszegEdit{$hatar.id}" type="number" step="any" name="osszeg_{$hatar.id}" value="{$hatar.osszeg}"></td>
            <td>
                <a class="js-hatardelbutton" href="#" data-id="{$hatar.id}"{if ($hatar.oper=='add')} data-source="client"{/if} title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
            </td>
        </tr>
    </tbody>
</table>
{if ($hatar.oper=='add')}
    <a class="js-hatarnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}