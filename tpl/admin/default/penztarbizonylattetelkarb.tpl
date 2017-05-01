<div id="teteltable_{$tetel.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <input name="tetelid[]" type="hidden" value="{$tetel.id}">
    <input name="teteloper_{$tetel.id}" type="hidden" value="{$tetel.oper}">
    {if ($tetel.parentid|default)}
        <input name="tetelparentid_{$tetel.id}" type="hidden" value="{$tetel.parentid}">
    {/if}
    <table>
        <tbody>
            <tr>
                <td><label for="SzovegEdit{$tetel.id}">{at('Szöveg')}:</label></td>
                <td><input id="SzovegEdit{$tetel.id}" name="tetelszoveg_{$tetel.id}" value="{$tetel.szoveg}"></td>
            </tr>
            <tr>
                <td class="mattable-important"><label for="JogcimEdit{$tetel.id}">{at('Jogcím')}:</label></td>
                <td>
                    <select id="JogcimEdit{$tetel.id}" name="teteljogcim_{$tetel.id}" class="mattable-important" required="required">
                        <option value="">{at('válasszon')}</option>
                        {foreach $tetel.jogcimlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="mattable-important"><label for="HivatkozottBizonylatEdit{$tetel.id}">{at('Hivatkozott bizonylat')}:</label></td>
                <td>
                    <input id="HivatkozottBizonylatEdit{$tetel.id}" name="tetelhivatkozottbizonylat_{$tetel.id}" value="{$tetel.hivatkozottbizonylat}">
                    <a class="js-hivatkozottbizonylatbutton" data-id="{$tetel.id}">{at('Keresés')}</a>
                </td>
            </tr>
            <tr>
                <td><label for="EsedekessegEdit{$tetel.id}">{at('Esedékesség')}:</label></td>
                <td><input id="EsedekessegEdit{$tetel.id}" name="tetelhivatkozottdatum_{$tetel.id}" value="{$tetel.hivatkozottdatumstr}" readonly></td>
            </tr>
            <tr>
                <td><label for="OsszegEdit{$tetel.id}">{at('Összeg')}:</label></td>
                <td><input id="OsszegEdit{$tetel.id}" name="tetelosszeg_{$tetel.id}" type="number" required="required" step="any" value="{$tetel.brutto}"></td>
            </tr>
        </tbody>
    </table>
    <a class="js-teteldelbutton" href="#" data-id="{$tetel.id}"{if (($tetel.oper=='add')||($tetel.oper=='inherit'))} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
</div>
{if ($tetel.oper=='add')}
    <a class="js-tetelnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}