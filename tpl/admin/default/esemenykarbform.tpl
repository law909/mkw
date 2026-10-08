<div id="mattkarb-header">
	<h3>{at('Esemény')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			{mezocsoport}
				{mezo cimke="Bejegyzés" for="NevEdit"}
					<input id="NevEdit" name="bejegyzes" type="text" size="80" maxlength="255" value="{$egyed.bejegyzes}" required autofocus>
				{/mezo}
				{mezo cimke="Esedékes" for="EsedekesEdit"}
					<input id="EsedekesEdit" name="esedekes" type="text" size="12" data-esedekes="{$egyed.esedekesstr}" required>
				{/mezo}
				{mezo cimke="Partner" for="PartnerEdit"}
					<select id="PartnerEdit" name="partner">
						<option value="">{at('válasszon')}</option>
						{foreach $partnerlist as $_partner}
							<option value="{$_partner.id}"{if ($_partner.selected)} selected="selected"{/if}>{$_partner.caption}</option>
						{/foreach}
					</select>
				{/mezo}
				{mezo cimke="Leírás" for="LeirasEdit"}
					<textarea id="LeirasEdit" name="leiras">{$egyed.leiras}</textarea>
				{/mezo}
			{/mezocsoport}
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}"/>
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>