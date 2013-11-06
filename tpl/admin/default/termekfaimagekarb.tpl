<table id="ImageEdit"><tbody>
<tr>
	<td><a class="js-toflyout" href="{$adminurl}{$fa.kepurl}" target="_blank"><img class="js-termekfakep" src="{$adminurl}{$fa.kepurlsmall}" alt="{$fa.kepleiras}" title="{$fa.kepleiras}"/></a></td>
	<td>
		<table><tbody>
			<tr>
			<td><label for="KepUrlEdit">{t('Kép')}:</label></td>
			<td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$fa.kepurl}" autofocus></td>
			<td><a id="KepBrowseButton" href="#" data-id="{$fa.id}" title="{t('Browse')}">{t('...')}</a></td>
			</tr>
			<tr>
			<td><label for="KepLeirasEdit">{t('Kép leírása')}:</label></td>
			<td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$fa.kepleiras}"></td>
			<td><a id="KepDelButton" href="#" data-id="{$fa.id}" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
			</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>