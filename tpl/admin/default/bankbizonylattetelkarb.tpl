<div id="teteltable_{$tetel.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <input name="tetelid[]" type="hidden" value="{$tetel.id}">
    <input name="teteloper_{$tetel.id}" type="hidden" value="{$tetel.oper}">
    {if ($tetel.parentid|default)}
        <input name="tetelparentid_{$tetel.id}" type="hidden" value="{$tetel.parentid}">
    {/if}
    <table>
        <tbody>
            <tr>
                <td class="mattable-important"><label for="DatumEdit{$tetel.id}">{t('Dátum')}:</label></td>
                <td><input id="DatumEdit{$tetel.id}" name="teteldatum_{$tetel.id}" type="text" size="12" data-datum="{$tetel.datumstr}" class="mattable-important" required="required"></td>
            </tr>
            <tr>
                <td class="mattable-important"><label for="PartnerEdit{$tetel.id}">{t('Partner')}:</label></td>
                <td>
                    <select id="PartnerEdit{$tetel.id}" name="tetelpartner_{$tetel.id}" class="mattable-important" required="required" autofocus{if ($tetel.partnerafa)} data-afa="{$tetel.partnerafa}" data-afakulcs="{$tetel.partnerafakulcs}"{/if}>
                        <option value="">{t('válasszon')}</option>
                        {foreach $tetel.partnerlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="mattable-important"><label for="JogcimEdit{$tetel.id}">{t('Jogcím')}:</label></td>
                <td>
                    <select id="JogcimEdit{$tetel.id}" name="teteljogcim_{$tetel.id}" class="mattable-important" required="required">
                        <option value="">{t('válasszon')}</option>
                        {foreach $tetel.jogcimlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="mattable-important"><label for="HivatkozottBizonylatEdit{$tetel.id}">{t('Hivatkozott bizonylat')}:</label></td>
                <td><input id="HivatkozottBizonylatEdit{$tetel.id}" name="tetelhivatkozottbizonylat_{$tetel.id}" value="{$tetel.hivatkozottbizonylat}"><a class="js-hivatkozottbizonylatbutton">{t('Keresés')}</a></td>
            </tr>
            <tr>
                <td><label for="EsedekessegEdit{$tetel.id}">{t('Esedékesség')}:</label></td>
                <td><input id="EsedekessegEdit{$tetel.id}" name="tetelhivatkozottdatum_{$tetel.id}" readonly></td>
            </tr>
            <tr>
                <td><label for="OsszegEdit{$tetel.id}">{t('Összeg')}:</label></td>
                <td><input id="OsszegEdit{$tetel.id}" name="tetelosszeg_{$tetel.id}" type="number" required="required" value="{$tetel.brutto}"></td>
            </tr>
        </tbody>
    </table>
    <a class="js-teteldelbutton" href="#" data-id="{$tetel.id}"{if (($tetel.oper=='add')||($tetel.oper=='inherit'))} data-source="client"{/if} title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
</div>
{if ($tetel.oper=='add')}
    <a class="js-tetelnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}