<div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
	<h3>{$pagetitle} - {$egyed.id}{if ($egyed.parentid|default)} ({$egyed.parentid}){/if}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			{mezocsoport}
				{mezo cimke="Kelt" for="KeltEdit" class="mezo-fontos"}
					<input id="KeltEdit" name="kelt" type="text" size="12" data-datum="{$egyed.keltstr}" class="mattable-important" required="required">
				{/mezo}
				{mezo cimke="Valutanem" for="ValutanemEdit" ujsor=true}
					<select id="ValutanemEdit" name="valutanem" required="required">
					<option value="">{at('válasszon')}</option>
					{foreach $valutanemlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-bankszamla="{$_mk.bankszamla}">{$_mk.caption}</option>
					{/foreach}
					</select>
				{/mezo}
				{mezo cimke="Bankszámla" for="BankszamlaEdit" szeles=true}
					<select id="BankszamlaEdit" name="bankszamla">
					<option value="">{at('válasszon')}</option>
					{foreach $bankszamlalist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				{/mezo}
				{if ($showerbizonylatszam)}
					{mezo cimke="Eredeti biz.szám" for="ErbizonylatszamEdit"}
						<input id="ErbizonylatszamEdit" name="erbizonylatszam" type="text" value="{$egyed.erbizonylatszam}">
					{/mezo}
				{/if}
				{mezo cimke="Megjegyzés" for="MegjegyzesEdit" szeles=true}
					<textarea id="MegjegyzesEdit" name="megjegyzes" rows="1" cols="100">{$egyed.megjegyzes}</textarea>
				{/mezo}
			{/mezocsoport}
			<div>
			{foreach $egyed.tetelek as $tetel}
			{include 'bankbizonylattetelkarb.tpl'}
			{/foreach}
			<a class="{if ($quick)}js-quicktetelnewbutton{else}js-tetelnewbutton{/if}" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
			</div>
            <div class="js-bizonylatosszesito ui-widget-content ui-corner-all bizonylatosszesito">
                <div class="bizonylatosszesito-cim">{at('Összesen')}</div>
                <div class="bizonylatosszesito-ertek bizonylatosszesito-fo"><span class="bizonylatosszesito-cimke">{at('Összeg')}</span><span class="js-osszegsum"></span></div>
            </div>
		</div>
	</div>
    <input name="quick" type="hidden" value="{$quick}">
	<input name="oper" type="hidden" value="{$oper}">
	<input name="id" type="hidden" value="{$egyed.id}">
	<input name="type" type="hidden" value="b">
    {if ($egyed.parentid|default)}
    <input name="parentid" type="hidden" value="{$egyed.parentid}">
    {/if}
	<div class="mattkarb-footer">
        {if ($egyed.nemrossz)}
		<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        {/if}
		<a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
	</div>
</form>