<div id="mattkarb-header">
	<h3>{$pagetitle} - {$egyed.id}{if ($egyed.parentid|default)} ({$egyed.parentid}){/if}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
	<div id="mattkarb-tabs">
		<ul>
			<li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
		</ul>
		<div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
			<table><tbody>
			<tr>
				<td class="mattable-important"><label for="KeltEdit">{at('Kelt')}:</label></td>
				<td><input id="KeltEdit" name="kelt" type="text" size="12" data-datum="{$egyed.keltstr}" class="mattable-important" required="required"></td>
			</tr>
			<tr>
				<td><label for="ValutanemEdit">{at('Valutanem')}:</label></td>
				<td><select id="ValutanemEdit" name="valutanem" required="required">
					<option value="">{at('válasszon')}</option>
					{foreach $valutanemlist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-bankszamla="{$_mk.bankszamla}">{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
				<td><label for="BankszamlaEdit">{at('Bankszámla')}:</label></td>
				<td colspan="3"><select id="BankszamlaEdit" name="bankszamla">
					<option value="">{at('válasszon')}</option>
					{foreach $bankszamlalist as $_mk}
					<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
					{/foreach}
					</select>
				</td>
			</tr>
            {if ($showerbizonylatszam)}
            <tr>
                <td><label for="ErbizonylatszamEdit">{at('Eredeti biz.szám')}:</label></td>
                <td><input id="ErbizonylatszamEdit" name="erbizonylatszam" type="text" value="{$egyed.erbizonylatszam}"></td>
            </tr>
            {/if}
			<tr>
				<td><label for="MegjegyzesEdit">{at('Megjegyzés')}:</label></td>
				<td colspan="7"><textarea id="MegjegyzesEdit" name="megjegyzes" rows="1" cols="100">{$egyed.megjegyzes}</textarea></td>
			</tr>
			</tbody></table>
			<div>
			{foreach $egyed.tetelek as $tetel}
			{include 'bankbizonylattetelkarb.tpl'}
			{/foreach}
			<a class="{if ($quick)}js-quicktetelnewbutton{else}js-tetelnewbutton{/if}" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
			</div>
            <table class="js-bizonylatosszesito ui-widget-content bizonylatosszesito">
                <thead>
                    <tr>
                        <th class="mattable-cell mattable-rborder"></th>
                        <th class="mattable-cell mattable-rborder">{at('Összesen')}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="mattable-cell mattable-rborder mattable-tborder">{at('Összesen')}</th>
                        <td class="js-osszegsum mattable-cell mattable-rborder mattable-tborder textalignright"></td>
                    </tr>
                </tbody>
            </table>
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