<div id="mattkarb-header">
	<h3>{$headcaption}</h3>
	<h4>{$cimke.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}" data-id="{$cimke.id}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
			<li><a href="#WebTab">{at('Webes adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			{mezocsoport}
				{mezo cimke="Név" for="NevEdit"}
					<input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$cimke.nev}" required autofocus>
				{/mezo}
				{mezo cimke="Címkecsoport" for="CimkecsoportEdit"}
					<select id="CimkecsoportEdit" name="cimkecsoport" required>
						<option value="">{at("válasszon")}</option>
						{foreach $cimkecsoportlist as $_ccs}
							<option value="{$_ccs.id}"{if ($_ccs.selected)} selected="selected"{/if}>{$_ccs.caption}</option>
						{/foreach}
					</select>
				{/mezo}
				{mezo cimke="Sorrend" for="SorrendEdit"}
					<input id="SorrendEdit" name="sorrend" type="number" size="10" maxlength="10" value="{$cimke.sorrend}">
				{/mezo}
				{if ($cimketipus === 'termek')}
					{mezo cimke="Gyártó" for="GyartoEdit" szeles=true}
						<select id="GyartoEdit" name="gyarto">
						    <option value="">{at('válasszon')}</option>
						    {foreach $gyartolist as $_gyarto}
						        <option
						            value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
						    {/foreach}
						</select>
					{/mezo}
					{mezo cimke="Színkód" for="SzinkodEdit"}
						<input id="SzinkodEdit" name="szinkod" type="text" maxlength="7" value="{$cimke.szinkod}">
					{/mezo}
				{/if}
			{/mezocsoport}
			{if ($cimketipus === 'termek')}
				{include 'cimkeimagekarb.tpl'}
			{/if}
		</div>
		<div id="WebTab" class="mattkarb-page">
			<input id="Menu1LathatoCheck" name="menu1lathato" type="checkbox"{if ($cimke.menu1lathato)}checked="checked"{/if}>{at('Menü 1')}
			<input id="Menu2LathatoCheck" name="menu2lathato" type="checkbox"{if ($cimke.menu2lathato)}checked="checked"{/if}>{at('Menü 2')}
			<input id="Menu3LathatoCheck" name="menu3lathato" type="checkbox"{if ($cimke.menu3lathato)}checked="checked"{/if}>{at('Menü 3')}
			<input id="Menu4LathatoCheck" name="menu4lathato" type="checkbox"{if ($cimke.menu4lathato)}checked="checked"{/if}>{at('Menü 4')}
			<input id="KiemeltCheck" name="kiemelt" type="checkbox"{if ($cimke.kiemelt)}checked="checked"{/if}>{at('Kiemelt')}
			{mezocsoport}
				{mezo cimke="Lap címe" for="OldalCimEdit"}
					<input id="OldalCimEdit" name="oldalcim" type="text" size="100" maxlength="255" value="{$cimke.oldalcim}">
				{/mezo}
				{mezo cimke="Leírás" for="LeirasEdit" szeles=true}
					<textarea id="LeirasEdit" name="leiras">{$cimke.leiras}</textarea>
				{/mezo}
			{/mezocsoport}
		</div>
	</div>
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$cimke.id}">
	<div class="mattkarb-footer">
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}"/>
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>
