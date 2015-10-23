<div id="teteltable_{$tetel.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <input name="tetelid[]" type="hidden" value="{$tetel.id}">
    <input name="teteloper_{$tetel.id}" type="hidden" value="{$tetel.oper}">
    {if ($tetel.parentid|default)}
        <input name="tetelparentid_{$tetel.id}" type="hidden" value="{$tetel.parentid}">
    {/if}
    <table>
        <tbody>
            <tr>
                <td class="mattable-important"><label for="PartnerEdit">{t('Partner')}:</label></td>
                <td colspan="7"><select id="PartnerEdit" name="partner" class="mattable-important" required="required" autofocus{if ($egyed.partnerafa)} data-afa="{$egyed.partnerafa}" data-afakulcs="{$egyed.partnerafakulcs}"{/if}>
                        <option value="">{t('válasszon')}</option>
                        {foreach $partnerlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                <td></td>
                <td>{t('Nettó')}</td>
                <td>{t('Bruttó')}</td>
                {if ($showhaszonszazalek)}
                    <td>Haszon %</td>
                    <td>Eladási br.ár</td>
                {/if}
                {if ($showvalutanem)}
                    <td class="hufprice">{t('Nettó HUF')}</td>
                    <td class="hufprice">{t('Bruttó HUF')}</td>
                {/if}
            </tr>
            <tr>
                <td><label>{t('Er.egységár')}:</label></td>
                <td><input name="tetelenettoegysar_{$tetel.id}" type="text" value="{$tetel.enettoegysar}" readonly class="js-enettoegysarinput"></td>
                <td><input name="tetelebruttoegysar_{$tetel.id}" type="text" value="{$tetel.ebruttoegysar}" readonly class="js-ebruttoegysarinput"></td>
                {if ($showvalutanem)}
                    <td><input name="tetelenettoegysarhuf_{$tetel.id}" type="text" value="{$tetel.enettoegysarhuf}" readonly></td>
                    <td><input name="tetelebruttoegysarhuf_{$tetel.id}" type="text" value="{$tetel.ebruttoegysarhuf}" readonly></td>
                {/if}
            </tr>
            <tr>
                <td><label for="KedvezmenyEdit">{t('Kedvezmény %')}:</label></td>
                <td><input id="KedvezmenyEdit" name="tetelkedvezmeny_{$tetel.id}" type="text" value="{$tetel.kedvezmeny}" class="js-kedvezmeny"></td>
            </tr>
            <tr>
                <td><label for="NettoegysarEdit{$tetel.id}">{t('Egységár')}:</label></td>
                <td><input id="NettoegysarEdit{$tetel.id}" name="tetelnettoegysar_{$tetel.id}" type="number" step="any" value="{$tetel.nettoegysar}" class="js-nettoegysarinput" required="required"></td>
                <td><input name="tetelbruttoegysar_{$tetel.id}" type="number" step="any" value="{$tetel.bruttoegysar}" class="js-bruttoegysarinput" required="required"></td>
                {if ($showhaszonszazalek)}
                    <td id="haszonszazalek_{$tetel.id}">{number_format($tetel.haszonszazalek,2,'.',' ')}</td>
                    <td id="eladasibruttoar_{$tetel.id}" data-ertek="{$tetel.eladasibrutto}">{number_format($tetel.eladasibrutto,2,'.',' ')}</td>
                {/if}
                {if ($showvalutanem)}
                    <td><input name="tetelnettoegysarhuf_{$tetel.id}" type="number" step="any" value="{$tetel.nettoegysarhuf}" readonly></td>
                    <td><input name="tetelbruttoegysarhuf_{$tetel.id}" type="number" step="any" value="{$tetel.bruttoegysarhuf}" readonly></td>
                {/if}
            </tr>
            <tr>
                <td class="mattable-important"><label for="NettoEdit{$tetel.id}">{t('Érték')}:</label></td>
                <td><input id="NettoEdit{$tetel.id}" name="tetelnetto_{$tetel.id}" type="number" step="any" value="{$tetel.netto}" class="mattable-important js-nettoinput"></td>
                <td><input name="tetelbrutto_{$tetel.id}" type="number" step="any" value="{$tetel.brutto}" class="mattable-important js-bruttoinput"></td>
                {if ($showhaszonszazalek)}
                    <td></td>
                    <td></td>
                {/if}
                {if ($showvalutanem)}
                    <td><input name="tetelnettohuf_{$tetel.id}" type="number" step="any" value="{$tetel.nettohuf}" readonly></td>
                    <td><input name="tetelbruttohuf_{$tetel.id}" type="number" step="any" value="{$tetel.bruttohuf}" readonly></td>
                {/if}
            </tr>
        </tbody>
    </table>
    <a class="js-teteldelbutton" href="#" data-id="{$tetel.id}"{if (($tetel.oper=='add')||($tetel.oper=='inherit'))} data-source="client"{/if} title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
</div>
{if ($tetel.oper=='add')}
    <a class="js-tetelnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}