<div id="mattkarb-header">
	<h3>{at('Email sablon')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<input id="AszfCheck" name="aszfcsatolaskell" type="checkbox"
				   {if ($egyed.aszfcsatolaskell)}checked="checked"{/if}>{at('ÁSZF csatolás kell')}
			{mezocsoport}
				{mezo cimke="Azonosító" for="NevEdit"}
					<input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}">
				{/mezo}
				{mezo cimke="Tárgy" for="TargyEdit"}
					<input id="TargyEdit" name="targy" type="text" size="80" maxlength="255" value="{$egyed.targy}">
				{/mezo}
				{mezo cimke="Szöveg" for="LeirasEdit"}
					<textarea id="LeirasEdit" name="szoveg" class="emailtemplateleiras">{$egyed.szoveg}</textarea>
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