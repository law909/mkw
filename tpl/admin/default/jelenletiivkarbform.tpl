<div id="mattkarb-header">
	<h3>{at('Jelenléti ív')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			{mezocsoport}
				{mezo cimke="Dolgozó"}
					<select id="DolgozoEdit" name="dolgozo" required autofocus>
						<option value="">{at('válasszon')}</option>
						{foreach $dolgozolist as $_mk}
						<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
						{/foreach}
					</select>
				{/mezo}
				{mezo cimke="Dátum" for="DatumEdit"}
					<input id="DatumEdit" name="datum" type="text" size="12" data-datum="{$egyed.datumstr}" required>
				{/mezo}
				{mezo cimke="Jelenlét" for="JelenlettipusEdit"}
					<div class="mattkarb-mezogomb">
						<select id="JelenlettipusEdit" name="jelenlettipus" required>
							<option value="">{at('válasszon')}</option>
							{foreach $jelenlettipuslist as $_mk}
							<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
							{/foreach}
						</select>
						<input id="MunkaidoEdit" name="munkaido" type="text" size="5" maxlength="2" value="{$egyed.munkaido}" required> {at('óra')}
					</div>
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