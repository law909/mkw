<div id="mattkarb-header">
    <h3>{at('Bankszámla')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport egyoszlop=true}
                {mezo cimke="Bank neve" for="BanknevEdit"}
                    <input id="BanknevEdit" name="banknev" type="text" size="80" maxlength="50" value="{$egyed.banknev}">
                {/mezo}
                {mezo cimke="Bank címe" for="BankcimEdit"}
                    <input id="BankcimEdit" name="bankcim" type="text" size="80" maxlength="70" value="{$egyed.bankcim}">
                {/mezo}
                {mezo cimke="Számlaszám" for="SzamlaszamEdit"}
                    <input id="SzamlaszamEdit" name="szamlaszam" type="text" size="80" maxlength="255" value="{$egyed.szamlaszam}" required="required">
                {/mezo}
                {mezo cimke="SWIFT" for="SwiftEdit"}
                    <input id="SwiftEdit" name="swift" type="text" size="80" maxlength="20" value="{$egyed.swift}">
                {/mezo}
                {mezo cimke="IBAN" for="IbanEdit"}
                    <input id="IbanEdit" name="iban" type="text" size="80" maxlength="20" value="{$egyed.iban}">
                {/mezo}
                {mezo cimke="Bank (tranzakció import)" for="BankEdit"}
                    <select id="BankEdit" name="bank">
                        <option value="">{at('válasszon')}</option>
                        {foreach $egyed.banklist as $_o}
                            <option value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Valutanem" for="ValutanemEdit"}
                    <select id="ValutanemEdit" name="valutanem">
                        <option value="">{at('válasszon')}</option>
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
