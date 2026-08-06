<div id="mattkarb-header">
    <h3>{at('Körzetszám')}</h3>
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
                    <td><label for="IdEdit">{at('Szám')}:</label></td>
                    <td>{if ($oper == 'add')}<input id="IdEdit" name="id" type="text" size="80" maxlength="6" value="{$egyed.id}" required="required">{else}<input id="IdEdit" type="text" size="80" value="{$egyed.id}" readonly="readonly"><input name="id" type="hidden" value="{$egyed.id}">{/if}</td>
                </tr>
                <tr>
                    <td><label for="HosszEdit">{at('Hossz')}:</label></td>
                    <td><input id="HosszEdit" name="hossz" type="number" step="1" value="{$egyed.hossz}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
                    <td><input id="SorrendEdit" name="sorrend" type="number" step="1" value="{$egyed.sorrend}"></td>
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
