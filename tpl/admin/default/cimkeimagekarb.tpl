<table id="FoImageEdit"><tbody>
<tr>
	<td><a class="js-toFlyout" href="{$adminurl}{$cimke.kepurl}" target="_blank"><img class="js-cimkekep" src="{$adminurl}{$cimke.kepurlsmall}" alt="{$cimke.kepleiras}" title="{$cimke.kepleiras}"/></a></td>
	<td>
		<table><tbody>
			<tr>
			<td><label for="KepUrlEdit">{t('Kép')}:</label></td>
			<td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$cimke.kepurl}"></td>
			<td><a id="KepBrowseButton" href="#" title="{t('Browse')}">{t('...')}</a></td>
			</tr>
			<tr>
			<td><label for=KepLeirasEdit">{t('Kép leírása')}:</label></td>
			<td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$cimke.kepleiras}"></td>
			<td><a id="KepDelButton" href="#" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
			</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>