<div id="teteltable_{$tetel.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
<!--input name="tetelid[]" type="hidden" value="{$tetel.id}">
<input name="teteloper_{$tetel.id}" type="hidden" value="{$tetel.oper}"-->
{if ($tetel.parentid|default)}
<input name="tetelparentid_{$tetel.id}" type="hidden" value="{$tetel.parentid}">
{/if}
<table><tbody>
<tr>
    <td class="mattable-important"><label for="TermekSelect{$tetel.id}">{t('Termék')}:</label></td>
    <td colspan="5">
        <input id="TermekSelect{$tetel.id}" type="text" name="qteteltermeknev_{$tetel.id}" class="js-termekselect termekselect mattable-important" value="{$tetel.termeknev}" required="required">
        <input class="js-termekid" name="qteteltermek_{$tetel.id}" type="hidden">
        <input name="qtetelafa_{$tetel.id}" type="hidden">
        <input name="qtetelme_{$tetel.id}" type="hidden">
    </td>
</tr>
<tr class="js-termekpicturerow_{$tetel.id}">
	<td><a class="js-toflyout" href="{$mainurl}{$tetel.kepurl}" target="_blank"><img src="{$mainurl}{$tetel.kiskepurl}"/></a></td>
	<td colspan="5">{t('Link')}:<a class="js-termeklink" href="{$tetel.link}" target="_blank">{$tetel.link}</a></td>
</tr>
<tr>
    <td><label for="CikkszamEdit{$tetel.id}">{t('Cikkszám')}:</label></td>
    <td><input id="CikkszamEdit{$tetel.id}" name="qtetelcikkszam_{$tetel.id}" type="text" maxlength="50" value="{$tetel.cikkszam}"></td>
</tr>
<tr>
    <td></td>
    <td>{t('Nettó')}</td>
    <td>{t('Bruttó')}</td>
    {if ($showvalutanem)}
        <td class="hufprice">{t('Nettó HUF')}</td>
        <td class="hufprice">{t('Bruttó HUF')}</td>
    {/if}
</tr>
<tr>
    <td><label for="ENettoegysarEdit{$tetel.id}">{t('Er.egységár')}:</label></td>
    <td><input id="ENettoegysarEdit{$tetel.id}" name="qtetelenettoegysar_{$tetel.id}" type="number" step="any" value="{$tetel.enettoegysar}" class="js-quickenettoegysarinput" readonly></td>
    <td><input name="qtetelebruttoegysar_{$tetel.id}" type="number" step="any" value="{$tetel.ebruttoegysar}" class="js-quickebruttoegysarinput" readonly></td>
    {if ($showvalutanem)}
        <td><input name="qtetelenettoegysarhuf_{$tetel.id}" type="number" step="any" value="{$tetel.enettoegysarhuf}" readonly></td>
        <td><input name="qtetelebruttoegysarhuf_{$tetel.id}" type="number" step="any" value="{$tetel.ebruttoegysarhuf}" readonly></td>
    {/if}
</tr>
<tr>
    <td><label for="KedvezmenyEdit">{t('Kedvezmény %')}:</label></td>
    <td><input id="KedvezmenyEdit" name="qtetelkedvezmeny_{$tetel.id}" type="text" value="{$tetel.kedvezmeny}" class="js-quickkedvezmeny"></td>
</tr>
<tr>
    <td><label for="NettoegysarEdit{$tetel.id}">{t('Egységár')}:</label></td>
    <td><input id="NettoegysarEdit{$tetel.id}" name="qtetelnettoegysar_{$tetel.id}" type="number" step="any" value="{$tetel.nettoegysar}" class="js-quicknettoegysarinput"></td>
    <td><input id="BruttoegysarEdit{$tetel.id}" name="qtetelbruttoegysar_{$tetel.id}" type="number" step="any" value="{$tetel.bruttoegysar}" class="js-quickbruttoegysarinput"></td>
    {if ($showvalutanem)}
    <td><input id="NettoegysarHufEdit{$tetel.id}" name="qtetelnettoegysarhuf_{$tetel.id}" type="number" step="any" value="{$tetel.nettoegysarhuf}" readonly></td>
    <td><input id="BruttoegysarHufEdit{$tetel.id}" name="qtetelbruttoegysarhuf_{$tetel.id}" type="number" step="any" value="{$tetel.bruttoegysarhuf}" readonly></td>
    {/if}
</tr>
<tr>
    <table class="bizonylattetelvaltozattable">
        <thead>
            <tr>
                <th>Változat</th>
                <th>Mennyiség</th>
            </tr>
        </thead>
        <tbody id="valtozattable_{$tetel.id}" data-id="{$tetel.id}">
        </tbody>
    </table>
</tr>

</tbody></table>
<a class="js-teteldelbutton" href="#" data-id="{$tetel.id}"{if (($tetel.oper=='add')||($tetel.oper=='inherit'))} data-source="client"{/if} title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
</div>
{if ($tetel.oper=='add')}
<a class="js-quicktetelnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}
