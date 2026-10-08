<div id="mattkarb-header">
    <h3>{at('Árfolyam')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Dátum" for="DatumEdit"}
                    <input id="DatumEdit" name="datum" type="text" size="12" data-datum="{$egyed.datumstr}" required="required">
                {/mezo}
                {mezo cimke="Árfolyam" for="ArfolyamEdit"}
                    <input id="ArfolyamEdit" name="arfolyam" type="number" step="any" value="{$egyed.arfolyam}" required="required">
                {/mezo}
                {mezo cimke="Valutanem" for="ValutanemEdit"}
                    <select id="ValutanemEdit" name="valutanem" required="required">
                        {foreach $egyed.valutanemlist as $_o}
                            <option value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
                        {/foreach}
                    </select>
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
