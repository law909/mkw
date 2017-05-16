<table id="FoImageEdit"><tbody>
<tr>
	<td>{if ($cimke.kepurl)}<a class="js-toFlyout" href="{$mainurl}{$cimke.kepurl}" target="_blank"><img class="js-cimkekep" src="{$mainurl}{$cimke.kepurlsmall}" alt="{$cimke.kepleiras}" title="{$cimke.kepleiras}"/></a>{/if}</td>
	<td>
		<table><tbody>
			<tr>
			<td><label for="KepUrlEdit">{at('Kép')}:</label></td>
			<td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$cimke.kepurl}"></td>
			<td><a id="KepBrowseButton" href="#" title="{at('Browse')}">{at('...')}</a></td>
			</tr>
			<tr>
			<td><label for=KepLeirasEdit">{at('Kép leírása')}:</label></td>
			<td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$cimke.kepleiras}"></td>
			<td><a id="KepDelButton" href="#" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
			</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>