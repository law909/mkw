<table id="valtozatartable_{$ar.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <tbody>
    <input name="valtozatarid_{$ar.valtozatid}[]" type="hidden" value="{$ar.id}">
    <input name="valtozataroper_{$ar.id}" type="hidden" value="{$ar.oper}">
    <tr>
        <td><label for="VArsavEdit{$ar.id}">{at('Azonosító')}:</label></td>
        <td><select id="VArsavEdit{$ar.id}" name="valtozatarsav_{$ar.id}" required="required">
                <option value="">{at('válasszon')}</option>
                {foreach $ar.arsavlist as $_arsav}
                    <option value="{$_arsav.id}"{if ($_arsav.selected)} selected="selected"{/if}>{$_arsav.caption}</option>
                {/foreach}
            </select>
        </td>
        <td><label for="VArValutaEdit{$ar.id}">{at('Valutanem')}:</label></td>
        <td><select id="VArValutaEdit{$ar.id}" name="valtozatarvalutanem_{$ar.id}" required="required">
                <option value="">{at('válasszon')}</option>
                {foreach $ar.valutanemlist as $_valuta}
                    <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                {/foreach}
            </select>
        </td>
        <td><label for="VArNettoEdit{$ar.id}">{at('Nettó')}:</label></td>
        <td><input id="VArNettoEdit{$ar.id}" class="js-valtozatarnetto" data-par="VArBruttoEdit{$ar.id}" type="text"
                   name="valtozatarnetto_{$ar.id}" value="{$ar.netto}"></td>
        <td><label for="VArBruttoEdit{$ar.id}">{at('Bruttó')}:</label></td>
        <td><input id="VArBruttoEdit{$ar.id}" class="js-valtozatarbrutto" data-par="VArNettoEdit{$ar.id}" type="text"
                   name="valtozatarbrutto_{$ar.id}" value="{$ar.brutto}"></td>
        <td>
            <a class="js-valtozatardelbutton" href="#" data-id="{$ar.id}"{if ($ar.oper=='add')} data-source="client"{/if} title="{at('Töröl')}"><span
                    class="ui-icon ui-icon-circle-minus"></span></a>
        </td>
    </tr>
    </tbody>
</table>
