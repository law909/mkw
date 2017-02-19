<table id="FoImageEdit" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"><tbody>
<tr class="imageupload">
	<td>{if ($termek.kepurl)}<a class="js-toflyout" href="{$mainurl}{$termek.kepurl}" target="_blank"><img src="{$mainurl}{$termek.kepurlsmall}" alt="{$termek.kepleiras}" title="{$termek.kepleiras}"/></a>{/if}</td>
	<td>
		<table><tbody>
			<tr>
			<td><label for="KepUrlEdit">{at('Kép')}:</label></td>
			<td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$termek.kepurl}"></td>
			<td><a id="FoKepBrowseButton" href="#" data-id="{$termek.id}" title="{at('Browse')}">{at('...')}</a></td>
			</tr>
			<tr>
			<td><label for="KepLeirasEdit">{at('Kép leírása')}:</label></td>
			<td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$termek.kepleiras}"></td>
			<td><a id="FoKepDelButton" href="#" data-id="{$termek.id}" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
			</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>