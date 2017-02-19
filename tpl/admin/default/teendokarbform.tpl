<div id="mattkarb-header">
	<h3>{at('Teendő')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{at('Bejegyzés')}:</label></td>
				<td><input id="NevEdit" name="bejegyzes" type="text" size="80" maxlength="255" value="{$egyed.bejegyzes}" required autofocus></td>
			</tr>
			<tr>
				<td><label for="EsedekesEdit">{at('Esedékes')}:</label></td>
				<td><input id="EsedekesEdit" name="esedekes" type="text" size="12" data-esedekes="{$egyed.esedekesstr}" required></td>
			</tr>
			<tr>
				<td><label for="PartnerEdit">{at('Partner')}:</label></td>
				<td><select id="PartnerEdit" name="partner">
					<option value="">{at('válasszon')}</option>
					{foreach $partnerlist as $_partner}
						<option value="{$_partner.id}"{if ($_partner.selected)} selected="selected"{/if}>{$_partner.caption}</option>
					{/foreach}
				</select></td>
			</tr>
			<tr>
				<td><label for="LeirasEdit">{at('Leírás')}:</label></td>
				<td><textarea id="LeirasEdit" name="leiras">{$egyed.leiras}</textarea></td>
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