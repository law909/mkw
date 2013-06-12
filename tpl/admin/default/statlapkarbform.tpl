<div id="mattkarb-header">
	<h3>{t('Statikus lap')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
		{if ($setup.editstyle=='tab')}
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
			<li><a href="#WebSeoTab">{t('Metaadatok')}</a></li>
		</ul>
		{/if}
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Általános adatok')}" data-refcontrol="#AltalanosTab"></div>
		{/if}
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{t('Oldalcím')}:</label></td>
				<td><input id="NevEdit" name="oldalcim" type="text" size="80" maxlength="255" value="{$egyed.oldalcim}"></td>
			</tr>
			<tr>
				<td><label for="LeirasEdit">{t('Szöveg')}:</label></td>
				<td><textarea id="LeirasEdit" name="szoveg">{$egyed.szoveg}</textarea></td>
			</tr>
			</tbody></table>
		</div>
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Metaadatok')}" data-refcontrol="#WebSeoTab"></div>
		{/if}
		<div id="WebSeoTab" class="mattkarb-page"{if ($setup.editstyle=='dropdown')} data-visible="hidden"{/if}>
			<table><tbody>
			<tr>
				<td><label for="SeoDescriptionEdit">{t('META leírás')}:</label></td>
				<td><textarea id="SeoDescriptionEdit" name="seodescription" cols="70">{$egyed.seodescription}</textarea></td>
			</tr>
			<tr>
				<td><label for="SeoKeywordsEdit">{t('META kulcsszavak')}:</label></td>
				<td><textarea id="SeoKeywordsEdit" name="seokeywords" cols="70">{$egyed.seokeywords}</textarea></td>
			</tr>
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>