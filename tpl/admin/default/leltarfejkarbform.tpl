<div id="mattkarb-header">
	<h3>{at('Leltár')}</h3>
	<h4>{$leltarfej.nev} {$leltarfej.nyitasstr}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/leltarfej/save" autocomplete="off">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			{mezocsoport}
				{mezo cimke="Név" for="NevEdit" szeles=true}
					<input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$leltarfej.nev}" required="required" autofocus>
				{/mezo}
				{mezo cimke="Raktár" for="RaktarEdit"}
					<select id="RaktarEdit" name="raktar">
					    <option value="">{at('válasszon')}</option>
					    {foreach $raktarlist as $_szt}
					        <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
					    {/foreach}
					</select>
				{/mezo}
			{/mezocsoport}
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$leltarfej.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>
