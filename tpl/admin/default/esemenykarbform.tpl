<div id="mattkarb-header">
	<h3>{t('Esemény')}</h3>
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
				<td><label for="NevEdit">{t('Bejegyzés')}:</label></td>
				<td><input id="NevEdit" name="bejegyzes" type="text" size="80" maxlength="255" value="{$egyed.bejegyzes}" required autofocus></td>
			</tr>
			<tr>
				<td><label for="EsedekesEdit">{t('Esedékes')}:</label></td>
				<td><input id="EsedekesEdit" name="esedekes" type="text" size="12" data-esedekes="{$egyed.esedekesstr}" required></td>
			</tr>
			<tr>
				<td><label for="PartnerEdit">{t('Partner')}:</label></td>
				<td><select id="PartnerEdit" name="partner">
					<option value="">{t('válasszon')}</option>
					{foreach $partnerlist as $_partner}
						<option value="{$_partner.id}"{if ($_partner.selected)} selected="selected"{/if}>{$_partner.caption}</option>
					{/foreach}
				</select></td>
			</tr>
			<tr>
				<td><label for="LeirasEdit">{t('Leírás')}:</label></td>
				<td><textarea id="LeirasEdit" name="leiras">{$egyed.leiras}</textarea></td>
			</tr>
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}"/>
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>