<table id="FoImageEdit" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"><tbody>
<tr class="imageupload">
	<td><a class="js-toflyout" href="{$adminurl}{$termek.kepurl}" target="_blank"><img src="{$adminurl}{$termek.kepurlsmall}" alt="{$termek.kepleiras}" title="{$termek.kepleiras}"/></a></td>
	<td>
		<table><tbody>
			<tr>
			<td><label for="KepUrlEdit">{t('Kép')}:</label></td>
			<td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$termek.kepurl}"></td>
			<td><a id="FoKepBrowseButton" href="#" data-id="{$termek.id}" title="{t('Browse')}">{t('...')}</a></td>
			</tr>
			<tr>
			<td><label for="KepLeirasEdit">{t('Kép leírása')}:</label></td>
			<td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$termek.kepleiras}"></td>
			<td><a id="FoKepDelButton" href="#" data-id="{$termek.id}" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
			</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>