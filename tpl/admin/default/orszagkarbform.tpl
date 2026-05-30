<div id="mattkarb-header">
    <h3>{at('Ország')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#LathatosagTab">{at('Láthatóság')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="Iso3166Edit">{at('ISO 3166')}:</label></td>
                    <td><input id="Iso3166Edit" name="iso3166" type="text" size="5" maxlength="5" value="{$egyed.iso3166}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="ValutanemEdit">{at('Valutanem')}:</label></td>
                    <td>
                        <select id="ValutanemEdit" name="valutanem">
                            <option value="">{at('válasszon')}</option>
                            {foreach $egyed.valutanemlist as $_valuta}
                                <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="LathatosagTab" class="mattkarb-page" data-visible="visible">
            <div>
                <input id="LathatoCheck" name="lathato" type="checkbox"
                       {if ($egyed.lathato)}checked="checked"{/if}>{at('Látható')} {$webshop1name}
                {if ($setup.multishop)}
                    {for $cikl = 2 to $enabledwebshops}
                        <input id="Lathato{$cikl}Check" name="lathato{$cikl}" type="checkbox"
                               {if ($egyed["lathato$cikl"])}checked="checked"{/if}>{at('Látható')} {$webshop{$cikl}name}
                    {/for}
                {/if}
            </div>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
