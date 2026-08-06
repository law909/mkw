<div id="mattkarb-header">
    <h3>{at('Átirányítás')}</h3>
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
                    <td><label for="FromurlEdit">{at('Forrás URL')}:</label></td>
                    <td><textarea id="FromurlEdit" name="fromurl" rows="3" cols="70" required="required">{$egyed.fromurl}</textarea></td>
                </tr>
                <tr>
                    <td><label for="TourlEdit">{at('Cél URL')}:</label></td>
                    <td><textarea id="TourlEdit" name="tourl" rows="3" cols="70" required="required">{$egyed.tourl}</textarea></td>
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
