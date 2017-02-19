<div id="mattkarb-header">
	<h3>{at('')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{at('Név')}:</label></td>
				<td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
			</tr>
			<tr>
				<td><label for="CsoportEdit">{at('Csoport')}:</label></td>
				<td><input id="CsoportEdit" name="csoport" type="text" size="80" maxlength="255" value="{$egyed.csoport}"></td>
			</tr>
            {if ($setup.foglalas)}
			<tr>
				<td><label for="FoglalEdit">{at('Foglal')}:</label></td>
				<td><input id="FoglalEdit" name="foglal" type="checkbox"{if ($egyed.foglal)} checked="checked"{/if}"></td>
			</tr>
            {/if}
			<tr>
				<td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
				<td><input id="SorrendEdit" name="sorrend" type="text" size="80" maxlength="255" value="{$egyed.sorrend}"></td>
			</tr>
			<tr>
				<td><label for="EmailEdit">{at('Email sablon')}:</label></td>
				<td colspan="7"><select id="EmailEdit" name="emailtemplate">
					<option value="">{at('válasszon')}</option>
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
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>