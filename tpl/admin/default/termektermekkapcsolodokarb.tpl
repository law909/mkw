<table id="kapcsolodotable_{$kapcsolodo.id}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"><tbody>
<input name="kapcsolodoid[]" type="hidden" value="{$kapcsolodo.id}">
<input name="kapcsolodooper_{$kapcsolodo.id}" type="hidden" value="{$kapcsolodo.oper}">
<tr>
<td><img src="{$kapcsolodo.altermekkepurl}"></td>
<td><label for="KapcsolodoAltermekEdit{$kapcsolodo.id}">{t('Termék')}:</label></td>
<td><select id="KapcsolodoAltermekEdit{$kapcsolodo.id}" name="kapcsolodoaltermek_{$kapcsolodo.id}" required="required">
<option value="">{t('válasszon')}</option>
{foreach $kapcsolodo.termeklist as $_altermek}
<option value="{$_altermek.id}"{if ($_altermek.selected)} selected="selected"{/if}>{$_altermek.caption}</option>
{/foreach}
</select></td>
<td>
<a class="kapcsolododelbutton" href="#" data-id="{$kapcsolodo.id}"{if ($kapcsolodo.oper=='add')} data-source="client"{/if} title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a>
</td></tr>
</tbody></table>
{if ($kapcsolodo.oper=='add')}
<a class="kapcsolodonewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}