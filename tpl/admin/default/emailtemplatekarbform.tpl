<div id="mattkarb-header">
	<h3>{t('Email sablon')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{t('Azonosító')}:</label></td>
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
			</tr>
			<tr>
				<td><label for="TargyEdit">{t('Tárgy')}:</label></td>
				<td><input id="TargyEdit" name="targy" type="text" size="80" maxlength="255" value="{$egyed.targy}"></td>
			</tr>
			<tr>
				<td><label for="LeirasEdit">{t('Szöveg')}:</label></td>
				<td><textarea id="LeirasEdit" name="szoveg" class="emailtemplateleiras">{$egyed.szoveg}</textarea></td>
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