<div id="mattkarb-header">
	<h3>{at('Körhinta')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}" data-id="{$egyed.id}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			{mezocsoport}
				{mezo szeles=true}
					<input id="LathatoCheck" name="lathato" type="checkbox"{if ($egyed.lathato)}checked="checked"{/if}>{at('Weboldalon látható')}</input>
				{/mezo}
				{mezo cimke="Sorrend" for="SorrendEdit"}
					<input id="SorrendEdit" name="sorrend" type="text" value="{$egyed.sorrend}">
				{/mezo}
				{mezo cimke="Cím" for="NevEdit"}
					<input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}">
				{/mezo}
				{mezo cimke="Szöveg" for="SzovegEdit" szeles=true}
					<textarea id="SzovegEdit" name="szoveg">{$egyed.szoveg}</textarea>
				{/mezo}
				{mezo cimke="URL" for="UrlEdit"}
					<input id="UrlEdit" name="url" type="text" size="80" maxlength="255" value="{$egyed.url}">
				{/mezo}
			{/mezocsoport}
			{include 'korhintaimagekarb.tpl'}
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>