<div id="mattkarb-header">
	<h3>{t('Partner')}</h3>
	<h4>{$partner.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/partner/save" autocomplete="off">
    <input type="text" name="fakename" class="hidden">
    <input type="password" name="fakepassword" class="hidden">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
			<li><a href="#ElerhetosegTab">{t('Elérhetőségek')}</a></li>
            <li><a href="#MegjegyzesTab">{t('Megjegyzés')}</a></li>
			<li><a href="#KedvezmenyTab">{t('Kedvezmények')}</a></li>
            {if ($setup.mijsz)}
                <li><a href="#MIJSZOklevelTab">{t('Oklevelek')}</a></li>
            {/if}
			<li><a href="#LoginTab">{t('Bejelentkezés')}</a></li>
			<li><a href="#BankTab">{t('Banki adatok')}</a></li>
			<li><a href="#EgyebAzonositoTab">{t('Egyéb azonosító adatok')}</a></li>
		</ul>
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
            {if (!$setup.mijsz)}
			<tr>
				<td><label for="SzallitoEdit">{t('Beszállító')}:</label></td>
				<td><input id="SzallitoEdit" name="szallito" type="checkbox"{if ($partner.szallito==1)} checked="checked"{/if}></td>
				<td><label for="EzuzletkotoEdit">{t('Üzletkötő')}:</label></td>
				<td><input id="EzuzletkotoEdit" name="ezuzletkoto" type="checkbox"{if ($partner.ezuzletkoto==1)} checked="checked"{/if}></td>
			</tr>
            {/if}
			<tr>
				<td><label for="IrszamEdit">{t('Cím')}:</label></td>
				<td colspan="3">
					<input id="IrszamEdit" name="irszam" type="text" size="6" maxlength="10" value="{$partner.irszam}" placeholder="{t('ir.szám')}" required="required">
					<input id="VarosEdit" name="varos" type="text" size="20" maxlength="40" value="{$partner.varos}" placeholder="{t('város')}" required="required">
					<input id="UtcaEdit" name="utca" type="text" size="40" maxlength="60" value="{$partner.utca}" placeholder="{t('utca, házszám')}">
				</td>
            </tr>
            <tr>
                <td><label for="OrszagEdit">{t('Ország')}:</label></td>
                <td><select id="OrszagEdit" name="orszag">
                        <option value="">{t('válasszon')}</option>
                        {foreach $orszaglist as $_szt}
                            <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                        {/foreach}
                    </select>
                </td>
			</tr>
			<tr>
				<td><label for="AdoszamEdit">{t('Adószám')}:</label></td>
				<td><input id="AdoszamEdit" name="adoszam" type="text" size="13" maxlength="13" value="{$partner.adoszam}"></td>
				<td><label for="EUAdoszamEdit">{t('Közösségi adószám')}:</label></td>
				<td><input id="EUAdoszamEdit" name="euadoszam" type="text" size="13" maxlength="30" value="{$partner.euadoszam}"></td>
			</tr>
            {if ($setup.mijsz)}
                <tr>
                    <td><label for="MIJSZExportTiltvaEdit">{t('MIJSZ export tiltva')}:</label></td>
                    <td><input id="MIJSZExportTiltvaEdit" name="mijszexporttiltva" type="checkbox"{if ($partner.mijszexporttiltva)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="MIJSZMiotajogazikEdit">{t('Mióta jógázik')}:</label></td>
                    <td><input id="MIJSZMiotajogazikEdit" name="mijszmiotajogazik" type="text" size="20" maxlength="255" value="{$partner.mijszmiotajogazik}">
                    <td><label for="MIJSZMiotatanitEdit">{t('Mióta tanít')}:</label></td>
                    <td><input id="MIJSZMiotatanitEdit" name="mijszmiotatanit" type="text" size="20" maxlength="255" value="{$partner.mijszmiotatanit}">
                </tr>
                <tr>
                    <td><label for="MIJSZMembershipBesidesHUEdit">{t('Tagság MIJSZ-en kívül')}:</label></td>
                    <td><input id="MIJSZMembershipBesidesHUEdit" name="mijszmembershipbesideshu" type="text" size="20" maxlength="255" value="{$partner.mijszmembershipbesideshu}">
                    <td><label for="MIJSZBusinessEdit">{t('Business')}:</label></td>
                    <td><input id="MIJSZBusinessEdit" name="mijszbusiness" type="text" size="20" maxlength="255" value="{$partner.mijszbusiness}">
                </tr>
            {/if}
            <tr>
                <td><label for="PartnertipusEdit">{t('Partner típus')}:</label></td>
                <td><select id="PartnertipusEdit" name="partnertipus">
                        <option value="">{t('válasszon')}</option>
                        {foreach $partnertipuslist as $_szt}
                            <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                        {/foreach}
                    </select></td>
            </tr>
			<tr>
				<td><label for="SzamlatipusEdit">{t('Számla típus')}:</label></td>
				<td><select id="SzamlatipusEdit" name="szamlatipus">
					<option value="">{t('válasszon')}</option>
					{foreach $szamlatipuslist as $_szt}
					<option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
					{/foreach}
				</select></td>
				<td><label for="FizmodEdit">{t('Fizetési mód')}:</label></td>
				<td><select id="FizmodEdit" name="fizmod">
					<option value="">{t('válasszon')}</option>
					{foreach $fizmodlist as $_fizmod}
					<option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
					{/foreach}
				</select></td>
            </tr>
            {if ($setup.multilang)}
			<tr>
				<td><label for="BizonylatnyelvEdit">{t('Bizonylatok nyelve')}:</label></td>
				<td><select id="BizonylatnyelvEdit" name="bizonylatnyelv">
					<option value="">{t('válasszon')}</option>
					{foreach $bizonylatnyelvlist as $_szt}
					<option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
					{/foreach}
				</select></td>
            </tr>
            {/if}
            {if (!$setup.mijsz)}
            <tr>
				<td><label for="SzallmodEdit">{t('Szállítási mód')}:</label></td>
				<td><select id="SzallmodEdit" name="szallitasimod">
					<option value="">{t('válasszon')}</option>
					{foreach $szallitasimodlist as $_szm}
					<option value="{$_szm.id}"{if ($_szm.selected)} selected="selected"{/if}>{$_szm.caption}</option>
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
            {/if}
            {if ($setup.arsavok)}
                <tr>
                    <td><label for="ValutanemEdit">{t('Valutanem')}:</label></td>
                    <td><select id="ValutanemEdit" name="valutanem">
                        <option value="">{t('válasszon')}</option>
                        {foreach $valutanemlist as $_vt}
                        <option value="{$_vt.id}"{if ($_vt.selected)} selected="selected"{/if}>{$_vt.caption}</option>
                        {/foreach}
                    </select></td>
                    <td><label for="TermekarEdit">{t('Ársáv')}:</label></td>
                    <td><select id="TermekarEdit" name="termekarazonosito">
                        <option value="">{t('válasszon')}</option>
                        {foreach $termekarazonositolist as $_ta}
                        <option value="{$_ta.id}"{if ($_ta.selected)} selected="selected"{/if}>{$_ta.caption}</option>
                        {/foreach}
                    </select></td>
                </tr>
            {/if}
            {if (!$setup.mijsz)}
			<tr>
				<td><label for="FizhatidoEdit">{t('Fizetési haladék')}:</label></td>
				<td><input id="FizhatidoEdit" name="fizhatido" type="number" size="5" maxlength="3" value="{$partner.fizhatido}"></td>
				<td><label for="SzallitasiidoEdit">{t('Szállítási idő')}:</label></td>
				<td><input id="SzallitasiidoEdit" name="szallitasiido" type="number" size="5" maxlength="3" value="{$partner.szallitasiido}"></td>
			</tr>
			<tr>
				<td><label for="AkcioshirlevelkellEdit">{t('Kér akciós hírlevelet')}:</label></td>
				<td><input id="AkcioshirlevelkellEdit" name="akcioshirlevelkell" type="checkbox"{if ($partner.akcioshirlevelkell==1)} checked="checked"{/if}></td>
				<td><label for="UjdonsaghirlevelkellEdit">{t('Kér újdonság hírlevelet')}:</label></td>
				<td><input id="UjdonsaghirlevelkellEdit" name="ujdonsaghirlevelkell" type="checkbox"{if ($partner.ujdonsaghirlevelkell==1)} checked="checked"{/if}></td>
			</tr>
            <tr>
                <td><label for="KtdatalanyEdit">{t('KTD átalány')}:</label></td>
                <td><input id="KtdatalanyEdit" name="ktdatalany" type="checkbox"{if ($partner.ktdatalany==1)} checked="checked"{/if}></td>
                <td><label for="KtdatvallalEdit">{t('KTD átvállal')}:</label></td>
                <td><input id="KtdatvallalEdit" name="ktdatvallal" type="checkbox"{if ($partner.ktdatvallal==1)} checked="checked"{/if}></td>
            </tr>
            <tr>
                <td><label for="KtdszerzszamEdit">{t('KTD szerz.szám')}:</label></td>
                <td><input id="KtdszerzszamEdit" name="ktdszerzszam" type="text" value="{$partner.ktdszerzszam}"></td>
            </tr>
            {/if}
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
				<td><input id="HonlapEdit" name="honlap" type="text" size="40" maxlength="200" value="{$partner.honlap}"></td>
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
					<input id="SzallUtcaEdit" name="szallutca" type="text" size="40" maxlength="60" value="{$partner.szallutca}" placeholder="{t('utca, házszám')}" autocomplete="off">
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
			<a class="js-termekcsoportkedvezmenynewbutton" href="#" title="{t('Új')}">
				<span class="ui-icon ui-icon-circle-plus"></span>
			</a>
		</div>
        {if ($setup.mijsz)}
            <div id="MIJSZOklevelTab" class="mattkarb-page" data-visible="visible">
                {foreach $partner.mijszoklevelek as $mijszoklevel}
                    {include 'partnermijszoklevelkarb.tpl'}
                {/foreach}
                <a class="js-mijszoklevelnewbutton" href="#" title="{t('Új')}">
                    <span class="ui-icon ui-icon-circle-plus"></span>
                </a>
            </div>
        {/if}
		<div id="LoginTab" class="mattkarb-page" data-visible="visible">
			<table>
                <tbody>
                <tr>
                    <td><label>{t('Email')}:</label></td>
                    <td><span class="js-email">{$partner.email}</span></td>
                </tr>
                <tr>
                    <td><label for="Jelszo1Edit">{t('Jelszó 1')}:</label></td>
                    <td><input id="Jelszo1Edit" name="jelszo1" type="password" size="20" maxlength="255" value="" autocomplete="off">
                    <td><label for="Jelszo2Edit">{t('Jelszó 2')}:</label></td>
                    <td><input id="Jelszo2Edit" name="jelszo2" type="password" size="20" maxlength="255" value="">
                </tr>
                </tbody>
            </table>
        </div>
		<div id="BankTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td><label for="BanknevEdit">{t('Bank neve')}:</label></td>
				<td><input id="BanknevEdit" name="banknev" type="text" size="40" maxlength="255" value="{$partner.banknev}"></td>
			</tr>
			<tr>
				<td><label for="BankcimEdit">{t('Bank címe')}:</label></td>
				<td><input id="BankcimEdit" name="bankcim" type="text" size="40" maxlength="255" value="{$partner.bankcim}"></td>
			</tr>
			<tr>
				<td><label for="IbanEdit">{t('IBAN')}:</label></td>
				<td><input id="IbanEdit" name="iban" type="text" size="40" maxlength="255" value="{$partner.iban}"></td>
			</tr>
			<tr>
				<td><label for="SwiftEdit">{t('SWIFT')}:</label></td>
				<td><input id="SwiftEdit" name="swift" type="text" size="40" maxlength="255" value="{$partner.swift}"></td>
			</tr>
			</tbody></table>
		</div>
		<div id="EgyebAzonositoTab" class="mattkarb-page" data-visible="visible">
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
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
	</div>
</form>
