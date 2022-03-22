<table id="FoImageEdit" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"><tbody>
<tr class="imageupload">
	<td>{if ($egyed.kepurl)}<a class="js-toflyout" href="{$mainurl}{$egyed.kepurl}" target="_blank"><img src="{$mainurl}{$egyed.kepurlsmall}"/></a>{/if}</td>
	<td>
		<table><tbody>
			<tr>
			<td><label for="KepUrlEdit">{at('Kép')}:</label></td>
			<td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$egyed.kepurl}"></td>
			<td><a id="FoKepBrowseButton" href="#" data-id="{$egyed.id}" title="{at('Browse')}">{at('...')}</a></td>
			</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>