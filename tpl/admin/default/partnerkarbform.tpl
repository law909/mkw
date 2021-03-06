<div id="mattkarb-header">
	<h3>{at('Partner')}</h3>
	<h4>{$partner.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/partner/save" autocomplete="off">
    <input type="text" name="fakename" class="hidden">
    <input type="password" name="fakepassword" class="hidden">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
			<li><a href="#ElerhetosegTab">{at('Elérhetőségek')}</a></li>
            <li><a href="#MegjegyzesTab">{at('Megjegyzés')}</a></li>
			<li><a href="#KedvezmenyTab">{at('Termékkategória kedvezmények')}</a></li>
            <li><a href="#TermekKedvezmenyTab">{at('Termék kedvezmények')}</a></li>
			<li><a href="#LoginTab">{at('Bejelentkezés')}</a></li>
			<li><a href="#BankTab">{at('Banki adatok')}</a></li>
			<li><a href="#EgyebAzonositoTab">{at('Egyéb azonosító adatok')}</a></li>
            <li><a href="#DokTab">{at('Dokumentumok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NevEdit">{at('Név')}:</label></td>
				<td colspan="3"><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$partner.nev|escape}" required="required" autofocus></td>
			</tr>
			<tr>
				<td><label for="VezeteknevEdit">{at('Vezetéknév')}:</label></td>
				<td><input id="VezeteknevEdit" name="vezeteknev" type="text" size="20" maxlength="255" value="{$partner.vezeteknev|escape}">
				<td><label for="KeresztnevEdit">{at('Keresztnév')}:</label></td>
				<td><input id="KeresztnevEdit" name="keresztnev" type="text" size="20" maxlength="255" value="{$partner.keresztnev|escape}">
			</tr>
			<tr>
				<td><label for="SzallitoEdit">{at('Beszállító')}:</label></td>
				<td><input id="SzallitoEdit" name="szallito" type="checkbox"{if ($partner.szallito==1)} checked="checked"{/if}></td>
				<td><label for="EzuzletkotoEdit">{at('Üzletkötő')}:</label></td>
				<td><input id="EzuzletkotoEdit" name="ezuzletkoto" type="checkbox"{if ($partner.ezuzletkoto==1)} checked="checked"{/if}></td>
			</tr>
            <tr>
                <td><label for="ExportbanKeszletEdit">{at('Termék exp.ba csak készletes termékek')}:</label></td>
                <td><input id="ExportbanKeszletEdit" name="exportbacsakkeszlet" type="checkbox"{if ($partner.exportbacsakkeszlet==1)} checked="checked"{/if}></td>
                <td><label for="KulsosEdit">{at('Külsős')}:</label></td>
                <td><input id="KulsosEdit" name="kulsos" type="checkbox"{if ($partner.kulsos==1)} checked="checked"{/if}></td>
            </tr>
            <tr>
                <td><label for="KeszletetlathatEdit">{at('Készletet láthat')}:</label></td>
                <td><input id="KeszletetlathatEdit" name="mennyisegetlathat" type="checkbox"{if ($partner.mennyisegetlathat==1)} checked="checked"{/if}></td>
            </tr>
			<tr>
				<td><label for="IrszamEdit">{at('Cím')}:</label></td>
				<td colspan="3">
					<input id="IrszamEdit" name="irszam" type="text" size="6" maxlength="10" value="{$partner.irszam}" placeholder="{at('ir.szám')}" required="required">
					<input id="VarosEdit" name="varos" type="text" size="20" maxlength="40" value="{$partner.varos}" placeholder="{at('város')}" required="required">
					<input id="UtcaEdit" name="utca" type="text" size="40" maxlength="60" value="{$partner.utca}" placeholder="{at('utca')}">
                    <input id="HazszamEdit" name="hazszam" type="text" size="20" maxlength="40" value="{$partner.hazszam}" placeholder="{at('házszám')}">
				</td>
            </tr>
            <tr>
                <td><label for="OrszagEdit">{at('Ország')}:</label></td>
                <td><select id="OrszagEdit" name="orszag">
                        <option value="">{at('válasszon')}</option>
                        {foreach $orszaglist as $_szt}
                            <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                        {/foreach}
                    </select>
                </td>
                <td><label for="BizonylatnyelvEdit">{at('Bizonylatok nyelve')}:</label></td>
                <td><select id="BizonylatnyelvEdit" name="bizonylatnyelv">
                        <option value="">{at('válasszon')}</option>
                        {foreach $bizonylatnyelvlist as $_szt}
                            <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                        {/foreach}
                    </select></td>
			</tr>
            <tr>
                <td><label for="SzamlatipusEdit">{at('Származás')}:</label></td>
                <td><select id="SzamlatipusEdit" name="szamlatipus">
                        <option value="">{at('válasszon')}</option>
                        {foreach $szamlatipuslist as $_szt}
                            <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                        {/foreach}
                    </select></td>
                <td><label for="VatstatusEdit">{at('NAV státusz')}:</label></td>
                <td><select id="VatstatusEdit" name="vatstatus">
                        <option value="">{at('válasszon')}</option>
                        {foreach $vatstatuslist as $_szt}
                            <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                        {/foreach}
                    </select></td>
            </tr>
			<tr>
				<td><label for="AdoszamEdit">{at('Adószám')}:</label></td>
				<td><input id="AdoszamEdit" name="adoszam" type="text" size="13" maxlength="13" value="{$partner.adoszam}"></td>
				<td><label for="EUAdoszamEdit">{at('Közösségi adószám')}:</label></td>
				<td><input id="EUAdoszamEdit" name="euadoszam" type="text" size="13" maxlength="30" value="{$partner.euadoszam}"></td>
			</tr>
            <tr>
                <td><label for="ThirdAdoszamEdit">{at('Harmadik ország adószám')}:</label></td>
                <td><input id="ThirdAdoszamEdit" name="thirdadoszam" type="text" size="13" maxlength="30" value="{$partner.thirdadoszam}"></td>
            </tr>
            <tr>
                <td><label for="PartnertipusEdit">{at('Partner típus')}:</label></td>
                <td><select id="PartnertipusEdit" name="partnertipus">
                        <option value="">{at('válasszon')}</option>
                        {foreach $partnertipuslist as $_szt}
                            <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                        {/foreach}
                    </select></td>
            </tr>
            {if ($setup.multilang)}
			<tr>
            </tr>
            {/if}
            <tr>
				<td><label for="SzallmodEdit">{at('Szállítási mód')}:</label></td>
				<td><select id="SzallmodEdit" name="szallitasimod">
					<option value="">{at('válasszon')}</option>
					{foreach $szallitasimodlist as $_szm}
					<option value="{$_szm.id}"{if ($_szm.selected)} selected="selected"{/if}>{$_szm.caption}</option>
					{/foreach}
				</select></td>
                <td><label for="SzallitasiidoEdit">{at('Szállítási idő')}:</label></td>
                <td><input id="SzallitasiidoEdit" name="szallitasiido" type="number" size="5" maxlength="3" value="{$partner.szallitasiido}"></td>
			</tr>
            {if ($setup.arsavok)}
                <tr>
                    <td><label for="ValutanemEdit">{at('Valutanem')}:</label></td>
                    <td><select id="ValutanemEdit" name="valutanem">
                        <option value="">{at('válasszon')}</option>
                        {foreach $valutanemlist as $_vt}
                        <option value="{$_vt.id}"{if ($_vt.selected)} selected="selected"{/if}>{$_vt.caption}</option>
                        {/foreach}
                    </select></td>
                    <td><label for="TermekarEdit">{at('Ársáv')}:</label></td>
                    <td><select id="TermekarEdit" name="termekarazonosito">
                        <option value="">{at('válasszon')}</option>
                        {foreach $termekarazonositolist as $_ta}
                        <option value="{$_ta.id}"{if ($_ta.selected)} selected="selected"{/if}>{$_ta.caption}</option>
                        {/foreach}
                    </select></td>
                </tr>
            {/if}
			<tr>
                <td><label for="FizmodEdit">{at('Fizetési mód')}:</label></td>
                <td><select id="FizmodEdit" name="fizmod">
                        <option value="">{at('válasszon')}</option>
                        {foreach $fizmodlist as $_fizmod}
                            <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                        {/foreach}
                    </select></td>
				<td><label for="FizhatidoEdit">{at('Fizetési haladék')}:</label></td>
				<td><input id="FizhatidoEdit" name="fizhatido" type="number" size="5" maxlength="3" value="{$partner.fizhatido}"></td>
			</tr>
            <tr>
                <td><label for="UzletkotoEdit">{at('Üzletkötő')}:</label></td>
                <td><select id="UzletkotoEdit" name="uzletkoto">
                        <option value="">{at('válasszon')}</option>
                        {foreach $uzletkotolist as $_uk}
                            <option value="{$_uk.id}"{if ($_uk.selected)} selected="selected"{/if}>{$_uk.caption}</option>
                        {/foreach}
                    </select></td>
            </tr>
			<tr>
				<td><label for="AkcioshirlevelkellEdit">{at('Kér akciós hírlevelet')}:</label></td>
				<td><input id="AkcioshirlevelkellEdit" name="akcioshirlevelkell" type="checkbox"{if ($partner.akcioshirlevelkell==1)} checked="checked"{/if}></td>
				<td><label for="UjdonsaghirlevelkellEdit">{at('Kér újdonság hírlevelet')}:</label></td>
				<td><input id="UjdonsaghirlevelkellEdit" name="ujdonsaghirlevelkell" type="checkbox"{if ($partner.ujdonsaghirlevelkell==1)} checked="checked"{/if}></td>
			</tr>
            <tr>
                <td><label for="KtdatalanyEdit">{at('KTD átalány')}:</label></td>
                <td><input id="KtdatalanyEdit" name="ktdatalany" type="checkbox"{if ($partner.ktdatalany==1)} checked="checked"{/if}></td>
                <td><label for="KtdatvallalEdit">{at('KTD átvállal')}:</label></td>
                <td><input id="KtdatvallalEdit" name="ktdatvallal" type="checkbox"{if ($partner.ktdatvallal==1)} checked="checked"{/if}></td>
            </tr>
            <tr>
                <td><label for="KtdszerzszamEdit">{at('KTD szerz.szám')}:</label></td>
                <td><input id="KtdszerzszamEdit" name="ktdszerzszam" type="text" value="{$partner.ktdszerzszam}"></td>
            </tr>
            <tr>
                <td><label for="SzamlalevelmegszolitasEdit">{at('Számlalevél megszólítás')}:</label></td>
                <td><input id="SzamlalevelmegszolitasEdit" name="szamlalevelmegszolitas" type="text" value="{$partner.szamlalevelmegszolitas}"></td>
            </tr>
			</tbody></table>
				<div id="cimkekarbcontainer">
				{foreach $cimkekat as $_cimkekat}
				<div class="mattedit-titlebar ui-widget-header ui-helper-clearfix js-cimkekarbcloseupbutton" data-refcontrol="#partnerkarb{$_cimkekat.id}">
					<a href="#" class="mattedit-titlebar-close">
						<span class="ui-icon ui-icon-circle-triangle-s"></span>
					</a>
					<span>{$_cimkekat.caption}</span>
				</div>
				<div id="partnerkarb{$_cimkekat.id}" class="js-cimkekarbpage cimkelista" data-visible="hidden">
					{foreach $_cimkekat.cimkek as $_cimke}
					{include 'cimkeselector.tpl'}
					{/foreach}
					<input id="ujcimkenev_{$_cimkekat.id}" type="text">&nbsp;<a class="js-cimkeadd" href="#" data-refcontrol="#ujcimkenev_{$_cimkekat.id}">&nbsp;+&nbsp;</a>
				</div>
				{/foreach}
				</div>
		</div>
		<div id="ElerhetosegTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
            {if ($maintheme === 'mkwcansas' && $partner.telefon && (!$partner.telkorzet || !$partner.telszam))}
                <tr>
                    <td>{at('Telefonszám')}:</td>
                    <td>{$partner.telefon}</td>
                </tr>
            {/if}
            <tr>
				<td><label for="TelefonEdit">{at('Telefon')}:</label></td>
                {if ($maintheme === 'mkwcansas')}
                    <td><select id="TelkorzetEdit" name="telkorzet" required="required" data-errormsg="{t('Hibás telefonszám')}">
                            <option value="">{t('válasszon')}</option>
                            {foreach $telkorzetlist as $tk}
                                <option value="{$tk.id}" data-hossz="{$tk.hossz}"{if ($tk.selected)} selected="selected"{/if}>{$tk.id}</option>
                            {/foreach}
                        </select>
                        <input id="TelszamEdit" type="text" name="telszam" value="{$partner.telszam}" required="required">
                    </td>
                {else}
                    <td><input id="TelefonEdit" name="telefon" type="text" size="40" maxlength="40" value="{$partner.telefon}"></td>
                {/if}
			</tr>
			<tr>
				<td><label for="MobilEdit">{at('Mobil')}:</label></td>
				<td><input id="MobilEdit" name="mobil" type="text" size="40" maxlength="40" value="{$partner.mobil}"></td>
			</tr>
			<tr>
				<td><label for="FaxEdit">{at('Fax')}:</label></td>
				<td><input id="FaxEdit" name="fax" type="text" size="40" maxlength="40" value="{$partner.fax}"></td>
			</tr>
			<tr>
				<td><label for="EmailEdit">{at('Email')}:</label></td>
				<td><input id="EmailEdit" name="email" type="text" size="40" maxlength="100" value="{$partner.email}" title="Vesszővel elválasztva"></td>
			</tr>
			<tr>
				<td><label for="HonlapEdit">{at('Honlap')}:</label></td>
				<td><input id="HonlapEdit" name="honlap" type="text" size="40" maxlength="200" value="{$partner.honlap}"></td>
			</tr>
			</tbody></table>
			<table><tbody>
			<tr>
				<td><label for="SzallNevEdit">{at('Szállítási név')}:</label></td>
				<td colspan="3"><input id="SzallNevEdit" name="szallnev" type="text" size="80" maxlength="255" value="{$partner.szallnev}"></td>
			</tr>
			<tr>
				<td><label for="SzallIrszamEdit">{at('Cím')}:</label></td>
				<td colspan="3">
					<input id="SzallIrszamEdit" name="szallirszam" type="text" size="6" maxlength="10" value="{$partner.szallirszam}" placeholder="{at('ir.szám')}">
					<input id="SzallVarosEdit" name="szallvaros" type="text" size="20" maxlength="40" value="{$partner.szallvaros}" placeholder="{at('város')}">
					<input id="SzallUtcaEdit" name="szallutca" type="text" size="40" maxlength="60" value="{$partner.szallutca}" placeholder="{at('utca')}" autocomplete="off">
                    <input id="SzallHazszamEdit" name="szallhazszam" type="text" size="20" maxlength="40" value="{$partner.szallhazszam}" placeholder="{at('házszám')}" autocomplete="off">
				</td>
			</tr>
			</tbody></table>
		</div>
        <div id="MegjegyzesTab" class="mattkarb-page" data-visible="visible">
            <label for="MegjegyzesEdit"></label>
            <textarea id="MegjegyzesEdit" name="megjegyzes" cols=120 rows="10">{$partner.megjegyzes}</textarea>
        </div>
		<div id="KedvezmenyTab" class="mattkarb-page" data-visible="visible">
			{foreach $partner.termekcsoportkedvezmenyek as $kd}
				{include 'partnertermekcsoportkedvezmenykarb.tpl'}
			{/foreach}
			<a class="js-termekcsoportkedvezmenynewbutton" href="#" title="{at('Új')}">
				<span class="ui-icon ui-icon-circle-plus"></span>
			</a>
		</div>
        <div id="TermekKedvezmenyTab" class="mattkarb-page" data-visible="visible">
            {foreach $partner.termekkedvezmenyek as $kd}
                {include 'partnertermekkedvezmenykarb.tpl'}
            {/foreach}
            <a class="js-termekkedvezmenynewbutton" href="#" title="{at('Új')}">
                <span class="ui-icon ui-icon-circle-plus"></span>
            </a>
        </div>
		<div id="LoginTab" class="mattkarb-page" data-visible="visible">
			<table>
                <tbody>
                <tr>
                    <td><label>{at('Email')}:</label></td>
                    <td><span class="js-email">{$partner.email}</span></td>
                </tr>
                <tr>
                    <td><label for="Jelszo1Edit">{at('Jelszó 1')}:</label></td>
                    <td><input id="Jelszo1Edit" name="jelszo1" type="password" size="20" maxlength="255" value="" autocomplete="off">
                    <td><label for="Jelszo2Edit">{at('Jelszó 2')}:</label></td>
                    <td><input id="Jelszo2Edit" name="jelszo2" type="password" size="20" maxlength="255" value="">
                </tr>
                </tbody>
            </table>
        </div>
		<div id="BankTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="BanknevEdit">{at('Bank neve')}:</label></td>
				<td><input id="BanknevEdit" name="banknev" type="text" size="40" maxlength="255" value="{$partner.banknev}"></td>
			</tr>
			<tr>
				<td><label for="BankcimEdit">{at('Bank címe')}:</label></td>
				<td><input id="BankcimEdit" name="bankcim" type="text" size="40" maxlength="255" value="{$partner.bankcim}"></td>
			</tr>
			<tr>
				<td><label for="IbanEdit">{at('IBAN')}:</label></td>
				<td><input id="IbanEdit" name="iban" type="text" size="40" maxlength="255" value="{$partner.iban}"></td>
			</tr>
			<tr>
				<td><label for="SwiftEdit">{at('SWIFT')}:</label></td>
				<td><input id="SwiftEdit" name="swift" type="text" size="40" maxlength="255" value="{$partner.swift}"></td>
			</tr>
			</tbody></table>
		</div>
		<div id="EgyebAzonositoTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="NemEdit">{at('Neme')}</label></td>
				<td><select id="NemEdit" name="nem">
						<option value="0">{at('válasszon')}</option>
						<option value="1"{if ($partner.nem=='1')} selected="selected"{/if}>{at('férfi')}</option>
						<option value="2"{if ($partner.nem=='2')} selected="selected"{/if}>{at('nő')}</option>
					</select>
				</td>
				<td><label for="SzuletesiidoEdit">{at('Születési idő')}</label></td>
				<td><input id="SzuletesiidoEdit" name="szuletesiido" type="text" size="12" data-datum="{$partner.szuletesiidostr}"></td>
			</tr>
			<tr>
				<td><label for="MukEngszamEdit" title="{at('Működési engedély szám')}">{at('Működési eng.szám')}:</label></td>
				<td><input id="MukEngszamEdit" name="mukengszam" type="text" size="20" maxlength="20" value="{$partner.mukengszam}"></td>
				<td><label for="JovEngszamEdit" title="{at('Jövedéki engedély szám')}">{at('Jövedéki eng.szám')}:</label></td>
				<td><input id="JovEngszamEdit" name="jovengszam" type="text" size="20" maxlength="20" value="{$partner.jovengszam}"></td>
			</tr>
			<tr>
				<td><label for="OstermszamEdit" title="{at('Őstermelői igazolvány szám')}">{at('Őstermelői ig.szám')}:</label></td>
				<td><input id="OstermszamEdit" name="ostermszam" type="text" size="20" maxlength="20" value="{$partner.ostermszam}"></td>
				<td><label for="ValligszamEdit" title="{at('Vállalkozói igazolvány szám')}">{at('Vállalkozói ig.szám')}:</label></td>
				<td><input id="ValligszamEdit" name="valligszam" type="text" size="20" maxlength="20" value="{$partner.valligszam}"></td>
			</tr>
			<tr>
				<td><label for=FvmszamEdit">{at('FVM szám')}:</label></td>
				<td><input id="FvmszamEdit" name="fvmszam" type="text" size="20" maxlength="20" value="{$partner.fvmszam}"></td>
				<td><label for="cjszamEdit">{at('Cégjegyzékszám')}:</label></td>
				<td><input id="cjszamEdit" name="cjszam" type="text" size="20" maxlength="20" value="{$partner.cjszam}"></td>
			</tr>
            <tr>
                <td><label for="MiniCRMProjectIdEdit">{at('MiniCRM project ID')}:</label></td>
                <td><input id="MiniCRMProjectIdEdit" name="minicrmprojectid" type="text" size="20" maxlength="20" value="{$partner.minicrmprojectid}"></td>
                <td><label for="MiniCRMContactIdEdit">{at('MiniCRM contact ID')}:</label></td>
                <td><input id="MiniCRMContactIdEdit" name="minicrmcontactid" type="text" size="20" maxlength="20" value="{$partner.minicrmcontactid}"></td>
            </tr>
			</tbody></table>
		</div>
        <div id="DokTab" class="mattkarb-page" data-visible="visible">
            {foreach $partner.dokok as $dok}
                {include 'dokumentumtarkarb.tpl'}
            {/foreach}
            <a class="js-doknewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
        </div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$partner.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>
