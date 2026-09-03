<div id="mattkarb-header">
    <h3>{$pagetitle}</h3>
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
                    <td><label for="KodEdit">{at('Kód')}:</label></td>
                    <td><input id="KodEdit" name="kod" type="text" size="30" maxlength="30" value="{$egyed.kod|escape}"></td>
                </tr>
                <tr>
                    <td class="mattable-important"><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev|escape}"
                               class="mattable-important" required="required"></td>
                </tr>
                <tr>
                    <td><label for="EmailtemplateEdit">{at('Email sablon')}:</label></td>
                    <td><select id="EmailtemplateEdit" name="emailtemplate">
                            <option value="">{at('válasszon')}</option>
                            {foreach $emailtemplatelist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption|escape}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
                    <td><input id="SorrendEdit" name="sorrend" type="number" step="1" size="8" value="{$egyed.sorrend}"></td>
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
