<div id="teteltable_{$tetel.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
    <input name="tetelid[]" type="hidden" value="{$tetel.id}">
    <input name="teteloper_{$tetel.id}" type="hidden" value="{$tetel.oper}">
    <input name="tetelmozgat_{$tetel.id}" type="hidden" value="{$tetel.mozgat}">
    {if ($tetel.parentid|default)}
        <input name="tetelparentid_{$tetel.id}" type="hidden" value="{$tetel.parentid}">
    {/if}
    <table>
        <tbody>
            <tr>
                <td><label for="ElolegtipusSelect{$tetel.id}">{at('Előleg típus')}:</label></td>
                <td>
                    <select id="ElolegtipusSelect{$tetel.id}" name="tetelelolegtipus_{$tetel.id}">
                        <option value="0"{if ($tetel.elolegtipus == '')} selected="selected"{/if}>{at('nincs')}</option>
                        <option value="1"{if ($tetel.elolegtipus == 'eloleg')} selected="selected"{/if}>{at('előleg')}</option>
                        <option value="2"{if ($tetel.elolegtipus == 'veg')} selected="selected"{/if}>{at('végszámla')}</option>
                    </select>
                </td>
            </tr>
            {if ($setup.mijsz)}
                <tr>
                    <td><label for="MIJSZPartnerSelect{$tetel.id}">{at('Vontakozó partner')}:</label></td>
                    <td><select id="MIJSZPartnerSelect{$tetel.id}" name="tetelmijszpartner_{$tetel.id}">
                            <option value="">{at('válasszon')}</option>
                            {foreach $tetel.mijszpartnerlist as $_vtsz}
                                <option value="{$_vtsz.id}"{if ($_vtsz.selected)} selected="selected"{/if} data-afa="{$_vtsz.afa}">{$_vtsz.caption}</option>
                            {/foreach}
                        </select></td>
                    <td><label for="MIJSZEvEdit{$tetel.id}">{at('Év')}:</label></td>
                    <td><input id="MIJSZEvEdit{$tetel.id}" name="tetelmijszev_{$tetel.id}" type="number" value="{$tetel.mijszev}"></td>
                </tr>
            {/if}
            <tr>
                <td class="mattable-important"><label for="TermekSelect{$tetel.id}">{at('Termék')}:</label></td>
                <td colspan="5">
                    {if ($tetel.oper === 'add')}
                    <input id="TermekSelect{$tetel.id}" type="text" name="teteltermeknev_{$tetel.id}" class="js-termekselect termekselect mattable-important" value="{$tetel.termeknev}" required="required">
                    {else}
                    {$tetel.termeknev}
                    {/if}
                    <input class="js-termekid" name="teteltermek_{$tetel.id}" type="hidden" value="{$tetel.termek}">
                </td>
            </tr>
            <tr class="js-termekpicturerow_{$tetel.id}">
                <td><a class="js-toflyout" href="{$mainurl}{$tetel.kepurl}" target="_blank"><img src="{$mainurl}{$tetel.kiskepurl}"/></a></td>
                <td>{at('Link')}:<a class="js-termeklink" href="{$tetel.link}" target="_blank">{$tetel.link}</a></td>
                <td><a class="js-kartonlink" href="{$tetel.kartonurl|default:'#'}" target="_blank">Karton</a></td>
            </tr>
            <tr>
                <td><label for="NevEdit{$tetel.id}">{at('Név')}:</label></td>
                <td colspan="5"><input id="NevEdit{$tetel.id}" name="tetelnev_{$tetel.id}" type="text" size="103" maxlength="255" value="{$tetel.termeknev}" required="required"></td>
            </tr>
            <tr>
                <td><label for="ValtozatSelect{$tetel.id}">{at('Változat')}:</label></td>
                <td id="ValtozatPlaceholder{$tetel.id}">{include "bizonylatteteltermekvaltozatselect.tpl"}</td>
            </tr>
            <tr>
                <td><label for="CikkszamEdit{$tetel.id}">{at('Cikkszám')}:</label></td>
                <td><input id="CikkszamEdit{$tetel.id}" name="tetelcikkszam_{$tetel.id}" type="text" size="30" maxlength="50" value="{$tetel.cikkszam}"></td>
                <td><label for="VtszSelect{$tetel.id}">{at('VTSZ')}:</label></td>
                <td><select id="VtszSelect{$tetel.id}" name="tetelvtsz_{$tetel.id}" class="js-vtszselect" required="required">
                        <option value="">{at('válasszon')}</option>
                        {foreach $tetel.vtszlist as $_vtsz}
                            <option value="{$_vtsz.id}"{if ($_vtsz.selected)} selected="selected"{/if} data-afa="{$_vtsz.afa}">{$_vtsz.caption}</option>
                        {/foreach}
                    </select></td>
                <td><label for="AfaSelect{$tetel.id}">{at('ÁFA')}:</label></td>
                <td><select id="AfaSelect{$tetel.id}" name="tetelafa_{$tetel.id}" class="js-afaselect" required="required">
                        <option value="">{at('válasszon')}</option>
                        {foreach $tetel.afalist as $_afa}
                            <option value="{$_afa.id}"{if ($_afa.selected)} selected="selected"{/if} data-afakulcs="{$_afa.afakulcs}">{$_afa.caption}</option>
                        {/foreach}
                    </select></td>
            </tr>
            <tr>
                <td class="mattable-important"><label for="MennyisegEdit{$tetel.id}">{at('Mennyiség')}:</label></td>
                <td colspan="5"><input id="MennyisegEdit{$tetel.id}" class="js-mennyiseginput mattable-important" name="tetelmennyiseg_{$tetel.id}" type="number" step="any" value="{$tetel.mennyiseg}" maxlength="20" size="10" required="required">
                    <input name="tetelme_{$tetel.id}" type="text" value="{$tetel.me}" size="10" maxlength="20" placeholder="{at('ME')}"></td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                <td></td>
                <td>{at('Nettó')}</td>
                <td>{at('Bruttó')}</td>
                {if ($showhaszonszazalek)}
                    <td>{at('Haszon %')}</td>
                    <td>{at('Eladási br.ár')}</td>
                {/if}
                {if ($showvalutanem)}
                    <td class="hufprice">{at('Nettó HUF')}</td>
                    <td class="hufprice">{at('Bruttó HUF')}</td>
                {/if}
            </tr>
            <tr>
                <td><label>{at('Er.egységár')}:</label></td>
                <td><input name="tetelenettoegysar_{$tetel.id}" type="text" value="{$tetel.enettoegysar}" readonly class="js-enettoegysarinput"></td>
                <td><input name="tetelebruttoegysar_{$tetel.id}" type="text" value="{$tetel.ebruttoegysar}" readonly class="js-ebruttoegysarinput"></td>
                {if ($showvalutanem)}
                    <td><input name="tetelenettoegysarhuf_{$tetel.id}" type="text" value="{$tetel.enettoegysarhuf}" readonly></td>
                    <td><input name="tetelebruttoegysarhuf_{$tetel.id}" type="text" value="{$tetel.ebruttoegysarhuf}" readonly></td>
                {/if}
            </tr>
            <tr>
                <td><label for="KedvezmenyEdit">{at('Kedvezmény %')}:</label></td>
                <td><input id="KedvezmenyEdit" name="tetelkedvezmeny_{$tetel.id}" type="text" value="{$tetel.kedvezmeny}" class="js-kedvezmeny"></td>
            </tr>
            <tr>
                <td><label for="NettoegysarEdit{$tetel.id}">{at('Egységár')}:</label></td>
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
                <td class="mattable-important"><label for="NettoEdit{$tetel.id}">{at('Érték')}:</label></td>
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
    <a class="js-teteldelbutton" href="#" data-id="{$tetel.id}"{if (($tetel.oper=='add')||($tetel.oper=='inherit'))} data-source="client"{/if} title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
</div>
{if ($tetel.oper=='add')}
    <a class="js-tetelnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}