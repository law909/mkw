<div id="mattkarb-header">
	<h3>{t('Felhasználó')}</h3>
	<h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{t('Név')}:</label></td>
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="100" value="{$egyed.nev}" required autofocus></td>
				<td><label for="FelhasznaloEdit">{t('Felhasználónév')}:</label></td>
				<td><input id="FelhasznalonevEdit" name="felhasznalonev" type="text" size="80" maxlength="16" value="{$egyed.felhasznalonev}" required></td>
				<td><label for="JelszoEdit">{t('Jelszó')}:</label></td>
				<td><input id="JelszoEdit" name="jelszo" type="password" size="80" maxlength="16" value="{$egyed.jelszo}" required></td>
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