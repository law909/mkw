<div id="mattkarb-header">
    <h3>{at('Bankszámla')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="BanknevEdit">{at('Bank neve')}:</label></td>
                    <td><input id="BanknevEdit" name="banknev" type="text" size="80" maxlength="50" value="{$egyed.banknev}"></td>
                </tr>
                <tr>
                    <td><label for="BankcimEdit">{at('Bank címe')}:</label></td>
                    <td><input id="BankcimEdit" name="bankcim" type="text" size="80" maxlength="70" value="{$egyed.bankcim}"></td>
                </tr>
                <tr>
                    <td><label for="SzamlaszamEdit">{at('Számlaszám')}:</label></td>
                    <td><input id="SzamlaszamEdit" name="szamlaszam" type="text" size="80" maxlength="255" value="{$egyed.szamlaszam}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="SwiftEdit">{at('SWIFT')}:</label></td>
                    <td><input id="SwiftEdit" name="swift" type="text" size="80" maxlength="20" value="{$egyed.swift}"></td>
                </tr>
                <tr>
                    <td><label for="IbanEdit">{at('IBAN')}:</label></td>
                    <td><input id="IbanEdit" name="iban" type="text" size="80" maxlength="20" value="{$egyed.iban}"></td>
                </tr>
                <tr>
                    <td><label for="BankEdit">{at('Bank (tranzakció import)')}:</label></td>
                    <td>
                        <select id="BankEdit" name="bank">
                            <option value="">{at('válasszon')}</option>
                            {foreach $egyed.banklist as $_o}
                                <option value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="ValutanemEdit">{at('Valutanem')}:</label></td>
                    <td>
                        <select id="ValutanemEdit" name="valutanem">
                            <option value="">{at('válasszon')}</option>
                            {foreach $egyed.valutanemlist as $_o}
                                <option value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
