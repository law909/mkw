<div id="mattkarb-header">
    <h3>{at('Szótár bejegyzés')}</h3>
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
                    <td><label for="MitEdit">{at('Mit')}:</label></td>
                    <td>{if ($oper == 'add')}<input id="MitEdit" name="mit" type="text" size="80" maxlength="255" value="{$egyed.mit}" required="required">{else}<input id="MitEdit" type="text" size="80" value="{$egyed.mit}" readonly="readonly"><input name="mit" type="hidden" value="{$egyed.mit}">{/if}</td>
                </tr>
                <tr>
                    <td><label for="MireEdit">{at('Mire')}:</label></td>
                    <td><input id="MireEdit" name="mire" type="text" size="80" maxlength="255" value="{$egyed.mire}"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.mit}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
