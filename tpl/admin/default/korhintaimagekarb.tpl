{if ($oper!='add')}
{if ($egyed.kepurl=='')}
<table id="FoImageEdit" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"><tbody>
<tr>
	<td><label for="KepNevEdit">{t('Kép neve')}:</label></td>
	<td colspan="2"><input id="KepNevEdit" name="kepnev" type="text" size="83" maxlength="255" value="{$egyed.kepnev}"></td>
</tr>
<tr>
	<td><label for="KepLeirasEdit">{t('Kép leírása')}:</label></td>
	<td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$egyed.kepleiras}"></td>
	<td id="ImageEditButton"><a id="FoKepUploadButton">{t('Feltöltés')}</a></td>
</tr>
</tbody></table>
{else}
<table id="FoImageEdit" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"><tbody>
<tr class="imageupload">
	<td colspan="2"><a class="toFlyout" href="{$egyed.kepurl}" target="_blank"><img src="{$egyed.kepurl}" alt="{$egyed.kepleiras}" title="{$egyed.kepleiras}"/></a></td>
</tr>
<tr>
	<td><label for="KepLeirasEdit">{t('Kép leírása')}:</label></td>
	<td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$egyed.kepleiras}"></td>
	<td><a id="FoKepDelButton" href="#" data-id="{$egyed.id}" title="{t('Töröl')}"><span class="ui-icon ui-icon-circle-minus"></span></a></td>
</tr>
</tbody></table>
{/if}
{/if}