<div id="mattkarb-header">
    <h3>{at('Óratípus')}</h3>
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
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Leírás')}:</label></td>
                    <td><textarea id="LeirasEdit" name="leiras" rows="3" cols="70">{$egyed.leiras}</textarea></td>
                </tr>
                <tr>
                    <td><label for="SzinEdit">{at('Szín')}:</label></td>
                    <td><input id="SzinEdit" name="szin" type="text" size="80" maxlength="7" value="{$egyed.szin}"></td>
                </tr>
                <tr>
                    <td><label for="ArnoveloEdit">{at('Árnövelő')}:</label></td>
                    <td><input id="ArnoveloEdit" name="arnovelo" type="number" step="any" value="{$egyed.arnovelo}"></td>
                </tr>
                <tr>
                    <td><label for="InaktivEdit">{at('Inaktív')}:</label></td>
                    <td><input id="InaktivEdit" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="UrlEdit">{at('URL')}:</label></td>
                    <td><input id="UrlEdit" name="url" type="text" size="80" maxlength="255" value="{$egyed.url}"></td>
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
