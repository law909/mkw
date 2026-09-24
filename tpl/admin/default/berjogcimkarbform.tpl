<div id="mattkarb-header">
    <h3>{at('Bér jogcím')}</h3>
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
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev|escape}" required="required" autofocus></td>
                </tr>
                <tr>
                    <td><label for="InaktivEdit">{at('Inaktív')}:</label></td>
                    <td><input id="InaktivEdit" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)} checked="checked"{/if}>
                        <span class="mattkarb-hint">{at('Új bérhez nem választható, a régi sorok megtartják.')}</span></td>
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
