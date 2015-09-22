<div id="mattkarb-header">
	<h3>{$pagetitle} - {$egyed.id}{if ($egyed.parentid|default)} ({$egyed.parentid}){/if}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
            {if ($showbizonylatstatuszeditor)}
			<tr>
                <td class="mattable-important"><label for="BizonylatStatuszEdit">Státusz:</label></td>
                <td><select id="BizonylatStatuszEdit" name="bizonylatstatusz" class="js-bizonylatstatuszedit">
                    <option value="">{t('válasszon')}</option>
                    {foreach $egyed.bizonylatstatuszlist as $_role}
                    <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
                    {/foreach}
                </select></td>
                <td><label for="BizonylatStatuszErtesitoEdit">Értesítés kell:</label></td>
                <td><input id="BizonylatStatuszErtesitoEdit" type="checkbox" name="bizonylatstatuszertesito"></td>
			</tr>
            {/if}
            {if ($setup.fanta)}
            <tr>
				<td class="mattable-important"><label for="FixEdit">{t('Fix')}:</label></td>
                <td><input id="FixEdit" type="checkbox" name="fix"{if ($egyed.fix)} checked{/if}></td>
            </tr>
            {/if}
			<tr>
				<td class="mattable-important"><label for="PartnerEdit">{t('Partner')}:</label></td>
				<td colspan="7"><select id="PartnerEdit" name="partner" class="mattable-important" required="required" autofocus{if ($egyed.partnerafa)} data-afa="{$egyed.partnerafa}" data-afakulcs="{$egyed.partnerafakulcs}"{/if}>
					<option value="">{t('válasszon')}</option>
					<option value="-1">{t('Új felvitel')}</option>
					{foreach $partnerlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
			</tr>
            <tr>
                <td><label>Név:</label></td>
                <td>
                    <input name="partnernev" value="{$egyed.partnernev}">
                </td>
                <td><label>Vezetéknév:</td>
                <td>
                    <input name="partnervezeteknev" value="{$egyed.partnervezeteknev}">
                </td>
                <td><label>Keresztnév:</td>
                <td colspan="3">
                    <input name="partnerkeresztnev" value="{$egyed.partnerkeresztnev}">
                </td>
            </tr>
			<tr>
				<td>Számlázási cím:</td>
				<td colspan="7">
					<input name="partnerirszam" value="{$egyed.partnerirszam}" size="6" maxlength="10">
					<input name="partnervaros" value="{$egyed.partnervaros}" size="20" maxlength="40">
					<input name="partnerutca" value="{$egyed.partnerutca}" size="40" maxlength="60">
				</td>
			</tr>
			<tr>
				<td><label for="AdoszamEdit">{t('Adószám')}:</label></td>
				<td>
					<input id="AdoszamEdit" name="partneradoszam" value="{$egyed.partneradoszam}">
				</td>
				<td><label for="EUAdoszamEdit">{t('EU adószám')}:</label></td>
				<td colspan="5">
					<input id="EUAdoszamEdit" name="partnereuadoszam" value="{$egyed.partnereuadoszam}">
				</td>
			</tr>
            {if ($showszallitasicim)}
			<tr>
				<td><label for="SzallnevEdit">{t('Szállítási név')}:</label></td>
				<td colspan="7">
					<input id="SzallnevEdit" name="szallnev" value="{$egyed.szallnev}">
				</td>
			</tr>
			<tr>
				<td><label for="SzallirszamEdit">{t('Szállítási cím')}:</label></td>
				<td colspan="7">
					<input id="SzallirszamEdit" name="szallirszam" value="{$egyed.szallirszam}" size="6" maxlength="10">
					<input name="szallvaros" value="{$egyed.szallvaros}" size="20" maxlength="40">
					<input name="szallutca" value="{$egyed.szallutca}" size="40" maxlength="60">
				</td>
			</tr>
            {/if}
			<tr>
				<td><label for="TelefonEdit">{t('Telefon')}:</label></td>
				<td>
					<input id="TelefonEdit" name="partnertelefon" value="{$egyed.partnertelefon}">
				</td>
				<td><label for="EmailEdit">{t('Email')}:</label></td>
				<td colspan="5">
					<input id="EmailEdit" name="partneremail" value="{$egyed.partneremail}">
				</td>
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
				<td class="mattable-important"><label for="UzletkotoEdit">{t('Üzletkötő')}:</label></td>
				<td><select id="UzletkotoEdit" name="uzletkoto" class="mattable-important">
					<option value="">{t('válasszon')}</option>
					{foreach $uzletkotolist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td class="mattable-important"><label for="KeltEdit">{t('Kelt')}:</label></td>
				<td><input id="KeltEdit" name="kelt" type="text" size="12" data-datum="{$egyed.keltstr}" class="mattable-important" required="required"></td>
				{if ($showteljesites)}
				<td class="mattable-important"><label for="TeljesitesEdit">{t('Teljesítés')}:</label></td>
				<td><input id="TeljesitesEdit" name="teljesites" type="text" size="12" data-datum="{$egyed.teljesitesstr}" class="mattable-important" required="required"></td>
				{/if}
				{if ($showesedekesseg)}
				<td class="mattable-important"><label for="EsedekessegEdit">{t('Esedékesség')}:</label></td>
				<td><input id="EsedekessegEdit" name="esedekesseg" type="text" size="12" data-datum="{$egyed.esedekessegstr}" class="mattable-important" required="required"></td>
				{/if}
				{if ($showhatarido)}
				<td class="mattable-important"><label for="HataridoEdit">{t('Határidő')}:</label></td>
				<td><input id="HataridoEdit" name="hatarido" type="text" size="12" data-datum="{$egyed.hataridostr}" class="mattable-important" required="required"></td>
				{/if}
			</tr>
			<tr>
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
                {if ($setup.multilang)}
                <td><label for="BizonylatnyelvEdit">{t('Adatok nyelve')}:</label></td>
                <td><select id="BizonylatnyelvEdit" name="bizonylatnyelv">
                    <option value="">{t('válasszon')}</option>
                    {foreach $bizonylatnyelvlist as $_mk}
                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                    {/foreach}
                    </select>
                </td>
                {/if}
                <td><label for="ReportfileEdit">{t('Nyomtatási forma')}:</label></td>
                <td><select id="ReportfileEdit" name="reportfile">
                    <option value="">{t('válasszon')}</option>
                    {foreach $reportfilelist as $_mk}
                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                    {/foreach}
                    </select>
                </td>
            </tr>
            {if ($showerbizonylatszam)}
            <tr>
                <td><label for="ErbizonylatszamEdit">{t('Eredeti biz.szám')}:</label></td>
                <td><input id="ErbizonylatszamEdit" name="erbizonylatszam" type="text" value="{$egyed.erbizonylatszam}"></td>
            </tr>
            {/if}
            {if ($showfuvarlevelszam)}
            <tr>
                <td><label for="FuvarlevelszamEdit">{t('Fuvarlevélszám')}:</label></td>
                <td><input id="FuvarlevelszamEdit" name="fuvarlevelszam" type="text" value="{$egyed.fuvarlevelszam}"></td>
            </tr>
            {/if}
            <tr>
                <td><label for="SzallitasiktgkellEdit">{t('Szállítási költséget kell számolni')}:</label></td>
                <td><input id="SzallitasiktgkellEdit" name="szallitasiktgkell" type="checkbox"></td>
            </tr>
			<tr>
				<td><label for="MegjegyzesEdit">{t('Megjegyzés')}:</label></td>
				<td colspan="7"><textarea id="MegjegyzesEdit" name="megjegyzes" rows="1" cols="100">{$egyed.megjegyzes}</textarea></td>
			</tr>
			<tr>
				<td><label for="BelsomegjegyzesEdit">{t('Belső megjegyzés')}:</label></td>
				<td colspan="7"><textarea id="BelsomegjegyzesEdit" name="belsomegjegyzes" rows="1" cols="100">{$egyed.belsomegjegyzes}</textarea></td>
			</tr>
            {if ($showuzenet)}
			<tr>
				<td><label for="WebshopmessageEdit">{t('Üzenet a webáruháznak')}:</label></td>
				<td colspan="7"><textarea id="WebshopmessageEdit" name="webshopmessage" rows="1" cols="100">{$egyed.webshopmessage}</textarea></td>
			</tr>
			<tr>
				<td><label for="CouriermessageEdit">{t('Üzenet a futárnak')}:</label></td>
				<td colspan="7"><textarea id="CouriermessageEdit" name="couriermessage" rows="1" cols="100">{$egyed.couriermessage}</textarea></td>
			</tr>
            {/if}
			</tbody></table>
            {if ($egyed.showotpay)}
            <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable"><tbody>
                    {if ($egyed.fizetve)}
                        <tr><td class='mattable-important'>Fizetve</td><td></td></tr>
                    {else}
                        <tr><td class='mattable-important'>Nincs fizetve</td><td></td></tr>
                    {/if}
                    <tr>
                        <td><label>{t('Merch Trx ID')}:</label></td>
                        <td>{$egyed.trxid}</td>
                    </tr>
                    <tr>
                        <td><label>{t('OTPay ID')}:</label></td>
                        <td>{$egyed.otpayid}</td>
                    </tr>
                    <tr>
                        <td><label>{t('OTPay MSISDN')}:</label></td>
                        <td>{$egyed.otpaymsisdn}</td>
                    </tr>
                    <tr>
                        <td><label>{t('OTPay MPID')}:</label></td>
                        <td>{$egyed.otpaympid}</td>
                    </tr>
                    <tr>
                        <td><label>{t('OTPay result')}:</label></td>
                        <td>{$egyed.otpayresulttext}</td>
                    </tr>
            <tbody></table>
            {/if}
			<div>
			{foreach $egyed.tetelek as $tetel}
			{include 'bizonylattetelkarb.tpl'}
			{/foreach}
			<a class="{if ($quick)}js-quicktetelnewbutton{else}js-tetelnewbutton{/if}" href="#" title="{t('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
			</div>
            <table class="js-bizonylatosszesito ui-widget-content bizonylatosszesito">
                <thead>
                    <tr>
                        <th class="mattable-cell mattable-rborder"></th>
                        <th class="mattable-cell mattable-rborder">Nettó</th>
                        <th class="mattable-cell mattable-rborder">Bruttó</th>
                        {if ($showvalutanem)}
                        <th class="mattable-cell mattable-rborder">Nettó HUF</th>
                        <th class="mattable-cell">Bruttó HUF</th>
                        {/if}
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="mattable-cell mattable-rborder mattable-tborder">Összesen</th>
                        <td class="js-nettosum mattable-cell mattable-rborder mattable-tborder textalignright"></td>
                        <td class="js-bruttosum mattable-cell mattable-rborder mattable-tborder textalignright"></td>
                        {if ($showvalutanem)}
                        <td class="js-nettohufsum mattable-cell mattable-rborder mattable-tborder textalignright"></td>
                        <td class="js-bruttohufsum mattable-cell mattable-tborder textalignright"></td>
                        {/if}
                    </tr>
                </tbody>
            </table>
		</div>
	</div>
    <input name="quick" type="hidden" value="{$quick}">
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
    {if ($egyed.parentid|default)}
    <input name="parentid" type="hidden" value="{$egyed.parentid}">
    {/if}
	<div class="mattkarb-footer">
        {if ($egyed.nemrossz)}
		<input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
        {/if}
		<a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
        {if ($egyed.nemrossz)}
            {if ($showszamlabutton)}
            <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="szamlafej" data-oper="inherit" title="{t('Számla')}" target="_blank">Számla</a>
            {/if}
            {if ($showkeziszamlabutton)}
            <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="keziszamlafej" data-oper="inherit" title="{t('Kézi számla')}" target="_blank">Kézi számla</a>
            {/if}
            {if ($showkivetbutton)}
            <a class="js-inheritbizonylat" href="#" data-egyedid="{$egyed.id}" data-egyednev="kivetfej" data-oper="inherit" title="{t('Kivét')}" target="_blank">Kivét</a>
            {/if}
        {/if}
	</div>
</form>