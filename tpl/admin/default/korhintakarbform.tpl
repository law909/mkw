<div id="mattkarb-header">
	<h3>{t('Körhinta')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}" data-id="{$egyed.id}">
	<div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
		{if ($setup.editstyle=='tab')}
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
		</ul>
		{/if}
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Általános adatok')}" data-refcontrol="#AltalanosTab"></div>
		{/if}
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><input id="LathatoCheck" name="lathato" type="checkbox"{if ($egyed.lathato)}checked="checked"{/if}>{t('Weboldalon látható')}</input></td>
			</tr>
			<tr>
				<td><label for="SorrendEdit">{t('Sorrend')}:</label></td>
				<td><input id="SorrendEdit" name="sorrend" type="text" value="{$egyed.sorrend}"></td>
			</tr>
			<tr>
				<td><label for="NevEdit">{t('Cím')}:</label></td>
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
			</tr>
			<tr>
				<td><label for="SzovegEdit">{t('Szöveg')}:</label></td>
				<td><textarea id="SzovegEdit" name="szoveg">{$egyed.szoveg}</textarea></td>
			</tr>
			<tr>
				<td><label for="UrlEdit">{t('URL')}:</label></td>
				<td><input id="UrlEdit" name="url" type="text" size="80" maxlength="255" value="{$egyed.url}"></td>
			</tr>
			<tr>
			{include 'korhintaimagekarb.tpl'}
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