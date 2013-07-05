<div id="mattkarb-header">
	<h3>{t('Partner')}</h3>
	<h4>{$partner.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/partner/save">
	<div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
		{if ($setup.editstyle=='tab')}
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
			<li><a href="#ElerhetosegTab">{t('Elérhetőségek')}</a></li>
			<li><a href="#KontaktTab">{t('Kontaktok')}</a></li>
			<li><a href="#EgyebAzonositoTab">{t('Egyéb azonosító adatok')}</a></li>
		</ul>
		{/if}
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Általános adatok')}" data-refcontrol="#AltalanosTab"></div>
		{/if}
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{t('Név')}:</label></td>
				<td colspan="3"><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$partner.nev}" required="required" autofocus></td>
			</tr>
			<tr>
				<td><label for="VezeteknevEdit">{t('Vezetéknév')}:</label></td>
				<td><input id="VezeteknevEdit" name="vezeteknev" type="text" size="20" maxlength="255" value="{$partner.vezeteknev}">
				<td><label for="KeresztnevEdit">{t('Keresztnév')}:</label></td>
				<td><input id="KeresztnevEdit" name="keresztnev" type="text" size="20" maxlength="255" value="{$partner.keresztnev}">
			</tr>
			<tr>
				<td><label for="IrszamEdit">{t('Cím')}:</label></td>
				<td colspan="3">
					<input id="IrszamEdit" name="irszam" type="text" size="6" maxlength="10" value="{$partner.irszam}" placeholder="{t('ir.szám')}" required="required">
					<input id="VarosEdit" name="varos" type="text" size="20" maxlength="40" value="{$partner.varos}" placeholder="{t('város')}" required="required">
					<input id="UtcaEdit" name="utca" type="text" size="40" maxlength="60" value="{$partner.utca}" placeholder="{t('utca, házszám')}">
				</td>
			</tr>
			<tr>
				<td><label for="AdoszamEdit">{t('Adószám')}:</label></td>
				<td><input id="AdoszamEdit" name="adoszam" type="text" size="13" maxlength="13" value="{$partner.adoszam}"></td>
				<td><label for="EUAdoszamEdit">{t('Közösségi adószám')}:</label></td>
				<td><input id="EUAdoszamEdit" name="euadoszam" type="text" size="13" maxlength="30" value="{$partner.euadoszam}"></td>
			</tr>
			<tr>
				<td><label for="FizmodEdit">{t('Fizetési mód')}:</label></td>
				<td><select id="FizmodEdit" name="fizmod">
					<option value="">{t('válasszon')}</option>
					{foreach $fizmodlist as $_fizmod}
					<option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
					{/foreach}
				</select></td>
				<td><label for="UzletkotoEdit">{t('Üzletkötő')}:</label></td>
				<td><select id="UzletkotoEdit" name="uzletkoto">
					<option value="">{t('válasszon')}</option>
					{foreach $uzletkotolist as $_uk}
					<option value="{$_uk.id}"{if ($_uk.selected)} selected="selected"{/if}>{$_uk.caption}</option>
					{/foreach}
				</select></td>
			</tr>
			<tr>
				<td><label for="FizhatidoEdit">{t('Fizetési haladék')}:</label></td>
				<td><input id="FizhatidoEdit" name="fizhatido" type="number" size="5" maxlength="3" value="{$partner.fizhatido}"></td>
			</tr>
			<tr>
				<td><label for="AkcioshirlevelkellEdit">{t('Kér akciós hírlevelet')}:</label></td>
				<td><input id="AkcioshirlevelkellEdit" name="akcioshirlevelkell" type="checkbox"{if ($partner.akcioshirlevelkell==1)} checked="checked"{/if}></td>
				<td><label for="UjdonsaghirlevelkellEdit">{t('Kér újdonság hírlevelet')}:</label></td>
				<td><input id="UjdonsaghirlevelkellEdit" name="ujdonsaghirlevelkell" type="checkbox"{if ($partner.ujdonsaghirlevelkell==1)} checked="checked"{/if}></td>
			</tr>
			</tbody></table>
				<div id="cimkekarbcontainer">
				{foreach $cimkekat as $_cimkekat}
				<div class="mattedit-titlebar ui-widget-header ui-helper-clearfix cimkekarbcloseupbutton" data-refcontrol="#partnerkarb{$_cimkekat.id}">
					<a href="#" class="mattedit-titlebar-close">
						<span class="ui-icon ui-icon-circle-triangle-s"></span>
					</a>
					<span>{$_cimkekat.caption}</span>
				</div>
				<div id="partnerkarb{$_cimkekat.id}" class="cimkekarbpage cimkelista" data-visible="hidden">
					{foreach $_cimkekat.cimkek as $_cimke}
					{include 'cimkeselector.tpl'}
					{/foreach}
					<input id="ujcimkenev_{$_cimkekat.id}" type="text">&nbsp;<a class="cimkeadd" href="#" data-refcontrol="#ujcimkenev_{$_cimkekat.id}">&nbsp;+&nbsp;</a>
				</div>
				{/foreach}
				</div>
		</div>
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Elérhetőségek')}" data-refcontrol="#ElerhetosegTab"></div>
		{/if}
		<div id="ElerhetosegTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="TelefonEdit">{t('Telefon')}:</label></td>
				<td><input id="TelefonEdit" name="telefon" type="text" size="40" maxlength="40" value="{$partner.telefon}"></td>
			</tr>
			<tr>
				<td><label for="MobilEdit">{t('Mobil')}:</label></td>
				<td><input id="MobilEdit" name="mobil" type="text" size="40" maxlength="40" value="{$partner.mobil}"></td>
			</tr>
			<tr>
				<td><label for="FaxEdit">{t('Fax')}:</label></td>
				<td><input id="FaxEdit" name="fax" type="text" size="40" maxlength="40" value="{$partner.fax}"></td>
			</tr>
			<tr>
				<td><label for="EmailEdit">{t('Email')}:</label></td>
				<td><input id="EmailEdit" name="email" type="email" size="40" maxlength="100" value="{$partner.email}"></td>
			</tr>
			<tr>
				<td><label for="HonlapEdit">{t('Honlap')}:</label></td>
				<td><input id="HonlapEdit" name="honlap" type="url" size="40" maxlength="200" value="{$partner.honlap}"></td>
			</tr>
			</tbody></table>
			<table><tbody>
			<tr>
				<td><label for="SzamlaNevEdit">{t('Számlázási név')}:</label></td>
				<td colspan="3"><input id="SzamlaNevEdit" name="szamlanev" type="text" size="80" maxlength="255" value="{$partner.szamlanev}"></td>
			</tr>
			<tr>
				<td><label for="SzamlaIrszamEdit">{t('Cím')}:</label></td>
				<td colspan="3">
					<input id="SzamlaIrszamEdit" name="szamlairszam" type="text" size="6" maxlength="10" value="{$partner.szamlairszam}" placeholder="{t('ir.szám')}">
					<input id="SzamlaVarosEdit" name="szamlavaros" type="text" size="20" maxlength="40" value="{$partner.szamlavaros}" placeholder="{t('város')}">
					<input id="SzamlaUtcaEdit" name="szamlautca" type="text" size="40" maxlength="60" value="{$partner.szamlautca}" placeholder="{t('utca, házszám')}">
				</td>
			</tr>
			<tr>
				<td><label for="SzamlaAdoszamEdit">{t('Számlázási adószám')}:</label></td>
				<td><input id="SzamlaAdoszamEdit" name="szamlaadoszam" type="text" size="13" maxlength="13" value="{$partner.szamlaadoszam}"></td>
			</tr>
			</tbody></table>
			<table><tbody>
			<tr>
				<td><label for="SzallNevEdit">{t('Szállítási név')}:</label></td>
				<td colspan="3"><input id="SzallNevEdit" name="szallnev" type="text" size="80" maxlength="255" value="{$partner.szallnev}"></td>
			</tr>
			<tr>
				<td><label for="SzallIrszamEdit">{t('Cím')}:</label></td>
				<td colspan="3">
					<input id="SzallIrszamEdit" name="szallirszam" type="text" size="6" maxlength="10" value="{$partner.szallirszam}" placeholder="{t('ir.szám')}">
					<input id="SzallVarosEdit" name="szallvaros" type="text" size="20" maxlength="40" value="{$partner.szallvaros}" placeholder="{t('város')}">
					<input id="SzallUtcaEdit" name="szallutca" type="text" size="40" maxlength="60" value="{$partner.szallutca}" placeholder="{t('utca, házszám')}">
				</td>
			</tr>
			</tbody></table>
		</div>
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Kontaktok')}" data-refcontrol="#KontaktTab"></div>
		{/if}
		<div id="KontaktTab" class="karbpage" data-visible="visible">
			{foreach $partner.kontaktok as $kontakt}
			{include 'partnerkontaktkarb.tpl'}
			{/foreach}
			<a class="kontaktnewbutton" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
		</div>
		{if ($setup.editstyle=='dropdown')}
		<div class="mattkarb-titlebar" data-caption="{t('Egyéb azonosító adatok')}" data-refcontrol="#EgyebAzonositoTab"></div>
		{/if}
		<div id="EgyebAzonositoTab" class="karbpage" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NemEdit">{t('Nem')}</label></td>
				<td><select id="NemEdit" name="nem">
						<option value="0">{t('válasszon')}</option>
						<option value="1"{if ($partner.nem=='1')} selected="selected"{/if}>{t('férfi')}</option>
						<option value="2"{if ($partner.nem=='2')} selected="selected"{/if}>{t('nő')}</option>
					</select>
				</td>
				<td><label for="SzuletesiidoEdit">{t('Születési idő')}</label></td>
				<td><input id="SzuletesiidoEdit" name="szuletesiido" type="text" size="12" data-datum="{$partner.szuletesiidostr}"></td>
			</tr>
			<tr>
				<td><label for="MukEngszamEdit" title="{t('Működési engedély szám')}">{t('Működési eng.szám')}:</label></td>
				<td><input id="MukEngszamEdit" name="mukengszam" type="text" size="20" maxlength="20" value="{$partner.mukengszam}"></td>
				<td><label for="JovEngszamEdit" title="{t('Jövedéki engedély szám')}">{t('Jövedéki eng.szám')}:</label></td>
				<td><input id="JovEngszamEdit" name="jovengszam" type="text" size="20" maxlength="20" value="{$partner.jovengszam}"></td>
			</tr>
			<tr>
				<td><label for="OstermszamEdit" title="{t('Őstermelői igazolvány szám')}">{t('Őstermelői ig.szám')}:</label></td>
				<td><input id="OstermszamEdit" name="ostermszam" type="text" size="20" maxlength="20" value="{$partner.ostermszam}"></td>
				<td><label for="ValligszamEdit" title="{t('Vállalkozói igazolvány szám')}">{t('Vállalkozói ig.szám')}:</label></td>
				<td><input id="ValligszamEdit" name="valligszam" type="text" size="20" maxlength="20" value="{$partner.valligszam}"></td>
			</tr>
			<tr>
				<td><label for=FvmszamEdit">{t('FVM szám')}:</label></td>
				<td><input id="FvmszamEdit" name="fvmszam" type="text" size="20" maxlength="20" value="{$partner.fvmszam}"></td>
				<td><label for="cjszamEdit">{t('Cégjegyzékszám')}:</label></td>
				<td><input id="cjszamEdit" name="cjszam" type="text" size="20" maxlength="20" value="{$partner.cjszam}"></td>
			</tr>
			</tbody></table>
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$partner.id}">
	<div class="admin-form-footer">
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>
