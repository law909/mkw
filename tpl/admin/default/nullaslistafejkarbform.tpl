<div id="mattkarb-header">
	<h3>{t('Nullás lista')}</h3>
	<h4>{$egyed.partnernev}</h4>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
		{if ($setup.editstyle=='tab')}
		<ul>
			<li><a href="#TetelTab">{t('Tétel adatok')}</a></li>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
		</ul>
		{/if}
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Tétel adatok')}" data-refcontrol="#TetelTab"></div>
		{/if}
		<div id="TetelTab" class="mattkarb-page" data-visible="visible">
		<table id="TetelGrid"></table>
		<div id="TetelGridPager"></div>
		</div>
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Általános adatok')}" data-refcontrol="#AltalanosTab"></div>
		{/if}
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td class="mattable-important">{t('Partner')}:</td>
				<td colspan="7"><select id="PartnerEdit" name="partner" class="mattable-important" required autofocus>
					<option value="">{t('válasszon')}</option>
					{foreach $partnerlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-fizmod="{$_mk.fizmod}" data-fizhatido="{$_mk.fizhatido}"
						data-cim="{$_mk.irszam} {$_mk.varos}, {$_mk.utca}">{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="7"><span id="PartnerCim">{$egyed.partnerirszam} {$egyed.partnervaros}, {$egyed.partnerutca}</span></td>
			</tr>
			<tr>
				<td>{t('Raktár')}:</td>
				<td colspan="7"><select id="RaktarEdit" name="raktar" required>
					<option value="">{t('válasszon')}</option>
					{foreach $raktarlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td class="mattable-important"><label for="KeltEdit">{t('Kelt')}:</label></td>
				<td><input id="KeltEdit" name="kelt" type="text" size="12" data-datum="{$egyed.keltstr}" class="mattable-important"></td>
			</tr>
			<tr>
				<td><label for="MegjegyzesEdit">{t('Megjegyzés')}:</label></td>
				<td colspan="7"><textarea id="MegjegyzesEdit" name="megjegyzes" rows="1" cols="100">{$egyed.megjegyzes}</textarea></td>
			</tr>
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input id="IdEdit" name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>