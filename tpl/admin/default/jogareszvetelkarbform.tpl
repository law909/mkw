<div id="mattkarb-header">
	<h3>{at('Óra látogatás')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			{mezocsoport}
				{mezo cimke="Üres terem" for="UresTeremEdit" class="mezo-fontos"}
					<input id="UresTeremEdit" name="uresterem" type="checkbox">
				{/mezo}
				{mezo cimke="Résztvevő" for="PartnerEdit" szeles=true class="mezo-fontos"}
					<select id="PartnerEdit" name="partner" class="js-partneredit mattable-important">
					    <option value="">{at('válassz')}</option>
					    <option value="-1">{at('Új felvitel')}</option>
					    {foreach $egyed.partnerlist as $_mk}
					        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.nev} ({$_mk.email})</option>
					    {/foreach}
					</select>
				{/mezo}
				{mezo cimke="Vezetéknév" for="PartnervezeteknevEdit" sugo="Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain" ujsor=true}
					<input id="PartnervezeteknevEdit" name="partnervezeteknev">
				{/mezo}
				{mezo cimke="Keresztnév" for="PartnerkeresztnevEdit" sugo="Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain"}
					<input id="PartnerkeresztnevEdit" name="partnerkeresztnev">
				{/mezo}
				{mezo cimke="Cím" sugo="Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain" szeles=true}
					<div class="mattkarb-mezogomb">
						<input id="PartnerirszamEdit" name="partnerirszam" size="6" maxlength="10">
						<input id="PartnervarosEdit" name="partnervaros" size="20" maxlength="40">
						<input id="PartnerutcaEdit" name="partnerutca" size="40" maxlength="60">
					</div>
				{/mezo}
				{mezo cimke="Email" for="PartneremailEdit" sugo="Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain" ujsor=true class="mezo-fontos"}
					<input id="PartneremailEdit" name="partneremail">
				{/mezo}
				{mezo cimke="Telefon" for="PartnertelefonEdit" sugo="Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain"}
					<input id="PartnertelefonEdit" name="partnertelefon">
				{/mezo}
				{mezo cimke="Bérlet" for="BerletEdit"}
					<select id="BerletEdit" name="jogaberlet" class="js-berletedit mattable-important" data-id="{$egyed.id}">
					    <option value="">{at('válassz')}</option>
					</select>
				{/mezo}
				{mezo cimke="Bérlet, órajegy" for="TermekEdit" sugo="Milyen bérlettel, órajeggyel vett részt?" class="mezo-fontos"}
					<select id="TermekEdit" name="termek" class="js-termekedit mattable-important" data-id="{$egyed.id}">
					    <option value="">{at('válassz')}</option>
					    {foreach $egyed.termeklist as $_mk}
					        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					    {/foreach}
					</select>
				{/mezo}
				{mezo cimke="Ár" for="ArEdit" class="mezo-fontos"}
					<input id="ArEdit" name="ar" type="number" step="any" class="mattable-important" value="{$egyed.bruttoegysar}">
				{/mezo}
				{mezo cimke="Fizetési mód" for="JRFizmodEdit_{$egyed.id}" sugo="Hogyan fizetett?" class="mezo-fontos"}
					<select id="JRFizmodEdit_{$egyed.id}" name="fizmod" class="mattable-important">
					    <option value="">{at('válassz')}</option>
					    {foreach $egyed.fizmodlist as $_mk}
					        <option value="{$_mk.id}"
					                data-tipus="{if ($_mk.bank)}B{else}P{/if}"
					                data-szepkartya="{$_mk.szepkartya}"
					                data-sportkartya="{$_mk.sportkartya}"
					                data-aycm="{$_mk.aycm}">{$_mk.caption}</option>
					    {/foreach}
					</select>
				{/mezo}
			{/mezocsoport}
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>