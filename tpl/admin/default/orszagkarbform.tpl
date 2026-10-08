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
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required="required">
                {/mezo}
                {mezo cimke="ISO 3166" for="Iso3166Edit"}
                    <input id="Iso3166Edit" name="iso3166" type="text" size="5" maxlength="5" value="{$egyed.iso3166}" required="required">
                {/mezo}
                {mezo cimke="Valutanem" for="ValutanemEdit"}
                    <select id="ValutanemEdit" name="valutanem">
                        <option value="">{at('válasszon')}</option>
                        {foreach $egyed.valutanemlist as $_valuta}
                            <option value="{$_valuta.id}"{if ($_valuta.selected)} selected="selected"{/if}>{$_valuta.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Áfakulcs" for="AfaEdit"}
                    <select id="AfaEdit" name="afa">
                        <option value="">{at('válasszon')}</option>
                        {foreach $egyed.afalist as $_afa}
                            <option value="{$_afa.id}"{if ($_afa.selected)} selected="selected"{/if}>{$_afa.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="EU-n belüli" for="EuEdit"}
                    <input id="EuEdit" name="eu" type="checkbox"{if ($egyed.eu)} checked="checked"{/if}>
                {/mezo}
            {/mezocsoport}
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
