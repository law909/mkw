<div id="mattkarb-header">
    <h3>{at('Helyszín')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/jogahelyszin/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="IrszamEdit">{at('Irányítószám')}:</label></td>
                    <td><input id="IrszamEdit" name="irszam" type="text" size="10" maxlength="10" value="{$egyed.irszam}"></td>
                </tr>
                <tr>
                    <td><label for="VarosEdit">{at('Város')}:</label></td>
                    <td><input id="VarosEdit" name="varos" type="text" size="40" maxlength="255" value="{$egyed.varos}"></td>
                </tr>
                <tr>
                    <td><label for="UtcaEdit">{at('Utca')}:</label></td>
                    <td><input id="UtcaEdit" name="utca" type="text" size="40" maxlength="255" value="{$egyed.utca}"></td>
                </tr>
                <tr>
                    <td><label for="HazszamEdit">{at('Házszám')}:</label></td>
                    <td><input id="HazszamEdit" name="hazszam" type="text" size="15" maxlength="50" value="{$egyed.hazszam}"></td>
                </tr>
                <tr>
                    <td><label for="UrlEdit">{at('Webcím')}:</label></td>
                    <td><input id="UrlEdit" name="url" type="text" size="80" maxlength="255" value="{$egyed.url}"></td>
                </tr>
                <tr>
                    <td><label for="InaktivEdit">{at('Inaktív')}:</label></td>
                    <td><input id="InaktivEdit" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Helyszín szövege a levelekben')}:</label></td>
                    <td><textarea id="LeirasEdit" name="emailsablon" class="emailtemplateleiras">{$egyed.emailsablon}</textarea></td>
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
