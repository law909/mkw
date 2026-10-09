<div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
	<h3>{at('Jóga bérlet')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			{mezocsoport}
				{mezo cimke="Partner" for="NevEdit"}
					<div class="mattkarb-mezogomb">
						{if ($setup.partnerautocomplete)}
							<input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete mattable-important" value="{$egyed.partnernev}" size=90 autofocus>
							<input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partner}">
							<input class="js-ujpartnercb" type="checkbox">Új</input>
						{else}
							<select id="PartnerEdit" name="partner" class="js-partnerid mattable-important" required="required" autofocus>
							    <option value="">{at('válasszon')}</option>
							    <option value="-1">{at('Új felvitel')}</option>
							    {foreach $partnerlist as $_mk}
							        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							    {/foreach}
							</select>
						{/if}
					</div>
				{/mezo}
				{mezo cimke="Bérlet" for="TermekEdit" class="mezo-fontos"}
					<select id="TermekEdit" name="termek" class="js-termekedit mattable-important" required="required">
					    <option value="">{at('válassz')}</option>
					    {foreach $termeklist as $_mk}
					        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					    {/foreach}
					</select>
				{/mezo}
				{mezo cimke="Ár" for="ArEdit" class="mezo-fontos"}
					<input id="ArEdit" name="bruttoar" type="number" step="any" class="mattable-important" value="{$egyed.bruttoar}">
				{/mezo}
				{mezo cimke="Vásárlás dátuma" for="VasarlasDatumEdit"}
					<input id="VasarlasDatumEdit" name="vasarlasnapja" type="text" size="12" data-datum="{$egyed.vasarlasnapja}" class="mattable-important">
				{/mezo}
				{mezo cimke="Lejárat dátuma" for="LejaratDatumEdit"}
					<input id="LejaratDatumEdit" name="lejaratdatum" type="text" size="12" data-datum="{$egyed.lejaratdatum}" class="mattable-important">
				{/mezo}
				{mezo cimke="Elfogyott alkalom" for="ElfogyottAlkalomEdit"}
					<input id="ElfogyottAlkalomEdit" name="elfogyottalkalom" type="text" value="{$egyed.elfogyottalkalom}">
				{/mezo}
				{mezo cimke="Offline elfogyott alkalom" for="OElfogyottAlkalomEdit"}
					<input id="OElfogyottAlkalomEdit" name="offlineelfogyottalkalom" type="text" value="{$egyed.offlineelfogyottalkalom}">
				{/mezo}
				{mezo cimke="Nincs kifizetve" for="NincsfizetveEdit"}
					<input id="NincsfizetveEdit" name="nincsfizetve" type="checkbox"{if ($egyed.nincsfizetve)} checked="checked"{/if}>
				{/mezo}
				{mezo cimke="Lejárt" for="LejartEdit"}
					<input id="LejartEdit" name="lejart" type="checkbox"{if ($egyed.lejart)} checked="checked"{/if}>
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