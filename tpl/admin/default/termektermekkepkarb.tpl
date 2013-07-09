<table id="keptable_{$kep.id}" data-oper="{$kep.oper}" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"><tbody>
<tr>
	<input type="hidden" name="kepid[]" value="{$kep.id}">
	<input type="hidden" name="kepoper_{$kep.id}" value="{$kep.oper}">
	<td><a class="js-toflyout" href="{$kep.url}" target="_blank"><img src="{$kep.urlsmall}" alt="{$kep.url}" title="{$kep.url}"/></a></td>
	<td>
		<table><tbody>
		<tr>
		<td><label for="KepUrlEdit_{$kep.id}">{t('Kép')}:</label></td>
		<td><input id="KepUrlEdit_{$kep.id}" name="kepurl_{$kep.id}" type="text" size="70" maxlength="255" value="{$kep.url}"></td>
		<td><a class="js-kepbrowsebutton" href="#" data-id="{$kep.id}" title="{t('Browse')}">{t('...')}</a></td>
		</tr>
		<tr>
		<td><label for="KepLeirasEdit_{$kep.id}">{t('Kép leírása')}:</label></td>
		<td><input id="KepLeirasEdit_{$kep.id}" name="kepleiras_{$kep.id}" type="text" size="70" value="{$kep.leiras}"></td>
		<td><a class="js-kepdelbutton" href="#" data-id="{$kep.id}" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
		</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>
{if ($kep.oper=='add')}
<a class="js-kepnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
{/if}