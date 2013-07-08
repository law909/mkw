<div id="mattkarb-header">
	<h3>{t('Hír')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
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
				<td colspan="2"><input id="LathatoCheck" name="lathato" type="checkbox"{if ($egyed.lathato)}checked="checked"{/if}>{t('Weboldalon látható')}</input></td>
			</tr>
			<tr>
				<td><label for="DatumEdit">{t('Hír dátuma')}:</label></td>
				<td><input id="DatumEdit" name="datum" type="text" size="12" data-datum="{$egyed.datumstr}" required></td>
			</tr>
			<tr>
				<td><label for="ElsoDatumEdit">{t('Első megjelenés')}:</label></td>
				<td><input id="ElsoDatumEdit" name="elsodatum" type="text" size="12" data-datum="{$egyed.elsodatumstr}" required></td>
			</tr>
			<tr>
				<td><label for="UtolsoDatumEdit">{t('Utolsó megjelenés')}:</label></td>
				<td><input id="UtolsoDatumEdit" name="utolsodatum" type="text" size="12" data-datum="{$egyed.utolsodatumstr}" required></td>
			</tr>
			<tr>
				<td><label for="CimEdit">{t('Cím')}:</label></td>
				<td colspan="5"><input id="CimEdit" name="cim" type="text" size="80" maxlength="255" value="{$egyed.cim}"></td>
			</tr>
			<tr>
				<td><label for="SorrendEdit">{t('Sorrend')}:</label></td>
				<td><input id="SorrendEdit" name="sorrend" type="number" size="10" maxlength="10" value="{$egyed.sorrend}"></td>
			</tr>
			<tr>
				<td><label for="ForrasEdit">{t('Forrás')}:</label></td>
				<td colspan="5"><input id="ForrasEdit" name="forras" type="text" size="80" maxlength="255" value="{$egyed.forras}"></td>
			</tr>
			<tr>
				<td><label for="LeadEdit">{t('Lead')}:</label></td>
				<td colspan="5"><textarea id="LeadEdit" name="lead" cols="70">{$egyed.lead}</textarea></td>
			</tr>
			<tr>
				<td><label for="SzovegEdit">{t('Szöveg')}:</label></td>
				<td colspan="5"><textarea id="SzovegEdit" name="szoveg" cols="70">{$egyed.szoveg}</textarea></td>
			</tr>
			<tr>
				<td><label for="SeoDescriptionEdit">{t('META leírás')}:</label></td>
				<td><textarea id="SeoDescriptionEdit" name="seodescription" cols="70">{$egyed.seodescription}</textarea></td>
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