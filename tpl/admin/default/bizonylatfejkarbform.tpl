<div id="mattkarb-header">
	<h3>{t('Megrendelés')}</h3>
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
				<td class="mattable-important"><label for="PartnerEdit">{t('Partner')}:</label></td>
				<td colspan="7"><select id="PartnerEdit" name="partner" class="mattable-important" required="required" autofocus>
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
				<td><label for="RaktarEdit">{t('Raktár')}:</label></td>
				<td colspan="7"><select id="RaktarEdit" name="raktar" required="required">
					<option value="">{t('válasszon')}</option>
					{foreach $raktarlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td class="mattable-important"><label for="FizmodEdit">{t('Fizetési mód')}:</label></td>
				<td><select id="FizmodEdit" name="fizmod" class="mattable-important" required="required">
					<option value="">{t('válasszon')}</option>
					{foreach $fizmodlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-fizhatido="{$_mk.fizhatido}" data-bank="{$_mk.bank}">{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
				<td class="mattable-important"><label for="SzallitasimodEdit">{t('Szállítási mód')}:</label></td>
				<td><select id="SzallitasimodEdit" name="szallitasimod" class="mattable-important" required="required">
					<option value="">{t('válasszon')}</option>
					{foreach $szallitasimodlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
				<td class="mattable-important"><label for="KeltEdit">{t('Kelt')}:</label></td>
				<td><input id="KeltEdit" name="kelt" type="text" size="12" data-datum="{$egyed.keltstr}" class="mattable-important" required="required"></td>
				{if ($showteljesites)}
				<td class="mattable-important"><label for="TeljesitesEdit">{t('Teljesítés')}:</label></td>
				<td><input id="TeljesitesEdit" name="teljesites" type="text" size="12" data-datum="{$egyed.teljesitesstr}" class="mattable-important" required="required"></td>
				{/if}
				{if ($showesedekesseg)}
				<td class="mattable-important"><label for="EsedekessegEdit">{t('Esedékesség')}:</label></td>
				<td><input id="EsedekessegEdit" name="esedekesseg" type="text" size="12" data-datum="{$egyed.esedekessegstr}" data-alap="{$esedekessegalap}" class="mattable-important" required="required"></td>
				{/if}
				{if ($showhatarido)}
				<td class="mattable-important"><label for="HataridoEdit">{t('Határidő')}:</label></td>
				<td><input id="HataridoEdit" name="hatarido" type="text" size="12" data-datum="{$egyed.hataridostr}" class="mattable-important" required="required"></td>
				{/if}
			</tr>
			<tr>
				{if ($showvalutanem)}
				<td><label for="ValutanemEdit">{t('Valutanem')}:</label></td>
				<td><select id="ValutanemEdit" name="valutanem" required="required">
					<option value="">{t('válasszon')}</option>
					{foreach $valutanemlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-bankszamla="{$_mk.bankszamla}">{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
				<td><label for="ArfolyamEdit">{t('Árfolyam')}:</label></td>
				<td><input id="ArfolyamEdit" name="arfolyam" type="number" step="any" size="5" value="{$egyed.arfolyam}" required="required"></td>
				{/if}
				<td><label for="BankszamlaEdit">{t('Bankszámla')}:</label></td>
				<td colspan="3"><select id="BankszamlaEdit" name="bankszamla">
					<option value="">{t('válasszon')}</option>
					{foreach $bankszamlalist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="MegjegyzesEdit">{t('Megjegyzés')}:</label></td>
				<td colspan="7"><textarea id="MegjegyzesEdit" name="megjegyzes" rows="1" cols="100">{$egyed.megjegyzes}</textarea></td>
			</tr>
			</tbody></table>
			<div>
			{foreach $egyed.tetelek as $tetel}
			{include 'bizonylattetelkarb.tpl'}
			{/foreach}
			<a class="js-tetelnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
			</div>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>