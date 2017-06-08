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
                <td><label>{at('Irány')}:</label></td>
                <td>
                    {if ($oper == 'add' || $oper == 'addreopen')}
                    <input id="IranyEdit" type="radio" name="irany" value="1"{if ($egyed.irany >= 0)} checked="checked"{/if} autofocus required="required">{at('Befizetés')}
                    <input id="IranyEdit" type="radio" name="irany" value="-1"{if ($egyed.irany < 0)} checked="checked"{/if}>{at('Kifizetés')}
                    {else}
                        {if ($egyed.irany<0)}
                            {at('Kifizetés')}
                            {else}
                            {at('Befizetés')}
                        {/if}
                    {/if}
                </td>
            </tr>
			<tr>
				<td class="mattable-important"><label for="KeltEdit">{at('Kelt')}:</label></td>
				<td><input id="KeltEdit" name="kelt" type="text" size="12" data-datum="{$egyed.keltstr}" class="mattable-important" required="required"></td>
			</tr>
			<tr>
                <td><label for="PenztarEdit">{at('Pénztár')}:</label></td>
                <td>
                    {if ($oper == 'add' || $oper == 'addreopen')}
                    <select id="PenztarEdit" name="penztar" required="required">
                        <option value="">{at('válasszon')}</option>
                        {foreach $penztarlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                        {/foreach}
                    </select>
                    {else}
                    {$egyed.penztarnev}
                    {/if}
                </td>
			</tr>
            <tr>
                <td><label for="ValutanemEdit">{at('Valutanem')}:</label></td>
                <td><input type="hidden" name="valutanem" value="{$egyed.valutanemid}">
                    <select id="ValutanemEdit" name="valutanemselect" disabled="disabled">
                        <option value="">{at('válasszon')}</option>
                        {foreach $valutanemlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                </td>
                <td><label for="ArfolyamEdit">{at('Árfolyam')}:</label></td>
                <td><input id="ArfolyamEdit" name="arfolyam" type="text" value="{$egyed.arfolyam}"></td>
            </tr>
            <tr>
                <td class="mattable-important"><label for="PartnerEdit">{at('Partner')}:</label></td>
                <td colspan="3">
                    <select id="PartnerEdit" name="partner" class="mattable-important" required="required">
                        <option value="">{at('válasszon')}</option>
                        {foreach $partnerlist as $_mk}
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
			{include 'penztarbizonylattetelkarb.tpl'}
			{/foreach}
			<a class="{if ($quick)}js-quicktetelnewbutton{else}js-tetelnewbutton{/if}" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
			</div>
            <table class="js-bizonylatosszesito ui-widget-content bizonylatosszesito">
                <thead>
                    <tr>
                        <th class="mattable-cell mattable-rborder"></th>
                        <th class="mattable-cell mattable-rborder">{at('Nettó')}</th>
                        <th class="mattable-cell mattable-rborder">{at('Bruttó')}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="mattable-cell mattable-rborder mattable-tborder">{at('Összesen')}</th>
                        <td class="js-nettosum mattable-cell mattable-rborder mattable-tborder textalignright"></td>
                        <td class="js-bruttosum mattable-cell mattable-rborder mattable-tborder textalignright"></td>
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