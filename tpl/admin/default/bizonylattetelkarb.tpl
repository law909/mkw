<div id="teteltable_{$tetel.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
<input name="tetelid[]" type="hidden" value="{$tetel.id}">
<input name="teteloper_{$tetel.id}" type="hidden" value="{$tetel.oper}">
<input name="tetelparentid_{$tetel.id}" type="hidden" value="{$tetel.parentid}">
<table><tbody>
<tr>
<td class="mattable-important"><label for="TermekSelect{$tetel.id}">{t('Termék')}:</label></td>
<td colspan="5">
	<input id="TermekSelect{$tetel.id}" type="text" name="teteltermeknev_{$tetel.id}" class="js-termekselect termekselect mattable-important" value="{$tetel.termeknev}" required="required">
	<input class="js-termekid" name="teteltermek_{$tetel.id}" type="hidden" value="{$tetel.termek}">
</td>
</tr>
<tr class="js-termekpicturerow_{$tetel.id}">
	<td><a class="js-toflyout" href="{$mainurl}{$tetel.kepurl}" target="_blank"><img src="{$mainurl}{$tetel.kiskepurl}"/></a></td>
	<td>{t('Link')}:<a class="js-termeklink" href="{$tetel.link}" target="_blank">{$tetel.link}</a></td>
</tr>
<tr><td><label for="NevEdit{$tetel.id}">{t('Név')}:</label></td>
<td colspan="5"><input id="NevEdit{$tetel.id}" name="tetelnev_{$tetel.id}" type="text" size="103" maxlength="255" value="{$tetel.termeknev}" required="required"></td>
</tr>
<tr><td><label for="ValtozatSelect{$tetel.id}">{t('Változat')}:</label></td>
	<td id="ValtozatPlaceholder{$tetel.id}">{include "bizonylatteteltermekvaltozatselect.tpl"}</td>
</tr>
<tr><td><label for="CikkszamEdit{$tetel.id}">{t('Cikkszám')}:</label></td>
<td><input id="CikkszamEdit{$tetel.id}" name="tetelcikkszam_{$tetel.id}" type="text" size="30" maxlength="50" value="{$tetel.cikkszam}"></td>
<td><label for="VtszSelect{$tetel.id}">{t('VTSZ')}:</label></td>
<td><select id="VtszSelect{$tetel.id}" name="tetelvtsz_{$tetel.id}" class="js-vtszselect" required="required">
<option value="">{t('válasszon')}</option>
{foreach $tetel.vtszlist as $_vtsz}
<option value="{$_vtsz.id}"{if ($_vtsz.selected)} selected="selected"{/if} data-afa="{$_vtsz.afa}">{$_vtsz.caption}</option>
{/foreach}
</select></td>
<td><label for="AfaSelect{$tetel.id}">{t('ÁFA')}:</label></td>
<td><select id="AfaSelect{$tetel.id}" name="tetelafa_{$tetel.id}" class="js-afaselect" required="required">
<option value="">{t('válasszon')}</option>
{foreach $tetel.afalist as $_afa}
<option value="{$_afa.id}"{if ($_afa.selected)} selected="selected"{/if} data-afakulcs="{$_afa.afakulcs}">{$_afa.caption}</option>
{/foreach}
</select></td>
</tr>
<tr>
<td class="mattable-important"><label for="MennyisegEdit{$tetel.id}">{t('Mennyiség')}:</label></td>
<td colspan="5"><input id="MennyisegEdit{$tetel.id}" class="js-mennyiseginput mattable-important" name="tetelmennyiseg_{$tetel.id}" type="number" step="any" value="{$tetel.mennyiseg}" maxlength="20" size="10" required="required">
<input name="tetelme_{$tetel.id}" type="text" value="{$tetel.me}" size="10" maxlength="20" placeholder="{t('ME')}"></td>
</tr>
</tbody></table>
<table><tbody>
<tr><td></td><td>{t('Nettó')}</td><td>{t('Bruttó')}</td>{if ($showvalutanem)}<td>{t('Nettó')}</td><td>{t('Bruttó')}</td>{/if}</tr>
<tr>
<td><label for="NettoegysarEdit{$tetel.id}">{t('Egységár')}:</label></td>
<td><input id="NettoegysarEdit{$tetel.id}" name="tetelnettoegysar_{$tetel.id}" type="number" step="any" value="{$tetel.nettoegysar}" class="js-nettoegysarinput" required="required"></td>
<td><input name="tetelbruttoegysar_{$tetel.id}" type="number" step="any" value="{$tetel.bruttoegysar}" class="js-bruttoegysarinput" required="required"></td>
{if ($showvalutanem)}
<td><input name="tetelnettoegysarhuf_{$tetel.id}" type="number" step="any" value="{$tetel.nettoegysarhuf}" required="required"></td>
<td><input name="tetelbruttoegysarhuf_{$tetel.id}" type="number" step="any" value="{$tetel.bruttoegysarhuf}" required="required"></td>
{/if}
</tr>
<tr>
<td class="mattable-important"><label for="NettoEdit{$tetel.id}">{t('Érték')}:</label></td>
<td><input id="NettoEdit{$tetel.id}" name="tetelnetto_{$tetel.id}" type="number" step="any" value="{$tetel.netto}" class="mattable-important js-nettoinput"></td>
<td><input name="tetelbrutto_{$tetel.id}" type="number" step="any" value="{$tetel.brutto}" class="mattable-important js-bruttoinput"></td>
{if ($showvalutanem)}
<td><input name="tetelnettohuf_{$tetel.id}" type="number" step="any" value="{$tetel.nettohuf}"></td>
<td><input name="tetelbruttohuf_{$tetel.id}" type="number" step="any" value="{$tetel.bruttohuf}"></td>
{/if}
</tr>
</tbody></table>
<a class="js-teteldelbutton" href="#" data-id="{$tetel.id}"{if (($tetel.oper=='add')||($tetel.oper=='inherit'))} data-source="client"{/if} title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
</div>
{if ($tetel.oper=='add')}
<a class="js-tetelnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}