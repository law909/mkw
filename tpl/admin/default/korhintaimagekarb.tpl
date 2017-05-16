<table id="FoImageEdit" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"><tbody>
<tr class="imageupload">
	<td>{if ($egyed.kepurl)}<a class="js-toFlyout" href="{$mainurl}{$egyed.kepurl}" target="_blank"><img class="js-korhintakep" src="{$mainurl}{$egyed.kepurlsmall}" alt="{$egyed.kepleiras}" title="{$egyed.kepleiras}"/></a>{/if}</td>
	<td>
		<table><tbody>
			<tr>
			<td><label for="KepUrlEdit">{at('Kép')}:</label></td>
			<td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$egyed.kepurl}"></td>
			<td><a id="FoKepBrowseButton" href="#" data-id="{$egyed.id}" title="{at('Browse')}">{at('...')}</a></td>
			</tr>
			<tr>
			<td><label for="KepLeirasEdit">{at('Kép leírása')}:</label></td>
			<td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$egyed.kepleiras}"></td>
			<td><a id="FoKepDelButton" href="#" data-id="{$egyed.id}" title="{at('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
			</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>
