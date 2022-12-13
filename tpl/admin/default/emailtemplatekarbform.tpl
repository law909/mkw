<div id="mattkarb-header">
	<h3>{at('Email sablon')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<input id="AszfCheck" name="aszfcsatolaskell" type="checkbox"
				   {if ($egyed.aszfcsatolaskell)}checked="checked"{/if}>{at('ÁSZF csatolás kell')}
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{at('Azonosító')}:</label></td>
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
			</tr>
			<tr>
				<td><label for="TargyEdit">{at('Tárgy')}:</label></td>
				<td><input id="TargyEdit" name="targy" type="text" size="80" maxlength="255" value="{$egyed.targy}"></td>
			</tr>
			<tr>
				<td><label for="LeirasEdit">{at('Szöveg')}:</label></td>
				<td><textarea id="LeirasEdit" name="szoveg" class="emailtemplateleiras">{$egyed.szoveg}</textarea></td>
			</tr>
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>