<div id="mattkarb-header">
	<h3>{t('')}</h3>
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
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
			</tr>
			<tr>
				<td><label for="CsoportEdit">{t('Csoport')}:</label></td>
				<td><input id="CsoportEdit" name="csoport" type="text" size="80" maxlength="255" value="{$egyed.csoport}"></td>
			</tr>
			<tr>
				<td><label for="SorrendEdit">{t('Sorrend')}:</label></td>
				<td><input id="SorrendEdit" name="sorrend" type="text" size="80" maxlength="255" value="{$egyed.sorrend}"></td>
			</tr>
			<tr>
				<td><label for="EmailEdit">{t('Email sablon')}:</label></td>
				<td colspan="7"><select id="EmailEdit" name="emailtemplate">
					<option value="">{t('válasszon')}</option>
					{foreach $emailtemplatelist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
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