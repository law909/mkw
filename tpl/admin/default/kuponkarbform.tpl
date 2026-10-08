<div id="mattkarb-header">
	<h3>{at('Kupon')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/kupon/save">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			{mezocsoport}
				{mezo cimke="Id" for="IdEdit"}
					<input id="IdEdit" type="text" name="xid" value="{$egyed.id}">
				{/mezo}
				{mezo cimke="Típus" for="TipusEdit"}
					<select id="TipusEdit" name="tipus">
					    <option value="">{at('válasszon')}</option>
					    {foreach $tipuslist as $_tcs}
					        <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
					    {/foreach}
					</select>
				{/mezo}
				{mezo cimke="Állapot" for="LejaratEdit"}
					<select id="LejaratEdit" name="lejart">
					    <option value="">{at('válasszon')}</option>
					    {foreach $lejaratlist as $_tcs}
					        <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
					    {/foreach}
					</select>
				{/mezo}
				{mezo cimke="Összeg" for="OsszegEdit"}
					<input id="OsszegEdit" type="text" name="osszeg" value="{$egyed.osszeg}">
				{/mezo}
				{mezo cimke="Minimum kosárérték" for="MinOsszegEdit"}
					<input id="MinOsszegEdit" type="text" name="minimumosszeg" value="{$egyed.minimumosszeg}">
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