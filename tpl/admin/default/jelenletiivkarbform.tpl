<div id="mattkarb-header">
	<h3>{t('Jelenléti ív')}</h3>
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
				<td><label>{t('Dolgozó')}:</label></td>
				<td><select id="DolgozoEdit" name="dolgozo" required autofocus>
					<option value="">{t('válasszon')}</option>
					{foreach $dolgozolist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="DatumEdit">{t('Dátum')}:</label></td>
				<td><input id="DatumEdit" name="datum" type="text" size="12" data-datum="{$egyed.datumstr}" required></td>
			</tr>
			<tr>
				<td><label for="JelenlettipusEdit">{t('Jelenlét')}:</label></td>
				<td><select id="JelenlettipusEdit" name="jelenlettipus" required>
					<option value="">{t('válasszon')}</option>
					{foreach $jelenlettipuslist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
				<td><input id="MunkaidoEdit" name="munkaido" type="text" size="5" maxlength="2" value="{$egyed.munkaido}" required> {t('óra')}</td>
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