<div id="teteltable_{$tetel.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <input name="tetelid[]" type="hidden" value="{$tetel.id}">
    <input name="teteloper_{$tetel.id}" type="hidden" value="{$tetel.oper}">
    {if ($tetel.parentid|default)}
        <input name="tetelparentid_{$tetel.id}" type="hidden" value="{$tetel.parentid}">
    {/if}
    <table>
        <tbody>
            <tr>
                <td class="mattable-important"><label for="DatumEdit{$tetel.id}">{at('Dátum')}:</label></td>
                <td><input id="DatumEdit{$tetel.id}" name="teteldatum_{$tetel.id}" type="text" size="12" data-datum="{$tetel.datumstr}" class="mattable-important" required="required"></td>
            </tr>
            <tr>
                <td><label for="ErBizonylatEdit{$tetel.id}">{at('Eredeti bizonylat')}:</label></td>
                <td>
                    <input id="ErBizonylatEdit{$tetel.id}" name="tetelerbizonylatszam_{$tetel.id}" value="{$tetel.erbizonylatszam}">
                </td>
            </tr>
            <tr>
                <td class="mattable-important"><label for="PartnerEdit{$tetel.id}">{at('Partner')}:</label></td>
                {if ($setup.partnerautocomplete)}
                <td colspan="7">
                    <input id="PartnerEdit{$tetel.id}" type="text" name="partnerautocomlete_{$tetel.id}" class="js-partnerautocomplete mattable-important"
                           value="{$tetel.partnernev}"
                           size=90 autofocus
                           data-tetelid="{$tetel.id}"
                           {if ($egyed.partnerafa)} data-afa="{$egyed.partnerafa}" data-afakulcs="{$egyed.partnerafakulcs}"{/if}
                    >
                    <input class="js-partnerid" name="tetelpartner_{$tetel.id}" type="hidden" value="{$tetel.partner}">
                </td>
                {else}
                <td>
                    <select id="PartnerEdit{$tetel.id}" name="tetelpartner_{$tetel.id}" class="mattable-important" required="required"
                            autofocus
                            {if ($tetel.partnerafa)} data-afa="{$tetel.partnerafa}" data-afakulcs="{$tetel.partnerafakulcs}"{/if}
                    >
                        <option value="">{at('válasszon')}</option>
                        {foreach $tetel.partnerlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                </td>
                {/if}
            </tr>
            <tr>
                <td><label>{at('Irány')}:</label></td>
                <td>
                    <input id="IranyEdit{$tetel.id}" type="radio" name="tetelirany_{$tetel.id}" class="js-iranyedit" value="1"{if ($tetel.irany >= 0)} checked="checked"{/if}>Be
                    <input id="IranyEdit{$tetel.id}" type="radio" name="tetelirany_{$tetel.id}" class="js-iranyedit" value="-1"{if ($tetel.irany < 0)} checked="checked"{/if}>Ki
                </td>
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
                <td><input id="OsszegEdit{$tetel.id}" name="tetelosszeg_{$tetel.id}" class="js-osszegedit" type="number" required="required" step="any" value="{$tetel.brutto}"></td>
            </tr>
        </tbody>
    </table>
    <a class="js-teteldelbutton" href="#" data-id="{$tetel.id}"{if (($tetel.oper=='add')||($tetel.oper=='inherit'))} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
</div>
{if ($tetel.oper=='add')}
    <a class="js-tetelnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}