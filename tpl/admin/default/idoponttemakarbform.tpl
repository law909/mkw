<div id="mattkarb-header">
    <h3>{at('Időpont téma')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/idoponttema/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#KerdoivTab">{at('Kérdőív')}{if ($egyed.kerdoivkerdesdb)} ({$egyed.kerdoivkerdesdb}){/if}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="UrlEdit">{at('Webcím')}:</label></td>
                    <td><input id="UrlEdit" name="url" type="text" size="80" maxlength="255" value="{$egyed.url}"></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Leírás')}:</label></td>
                    <td><textarea id="LeirasEdit" name="leiras" rows="5" cols="80">{$egyed.leiras}</textarea></td>
                </tr>
                <tr>
                    <td><label for="InaktivEdit">{at('Inaktív')}:</label></td>
                    <td><input id="InaktivEdit" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)} checked="checked"{/if}></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="KerdoivTab" class="mattkarb-page" data-visible="visible">
            {include 'idopontkerdoivszerkeszto.tpl' kerdoivjson=$egyed.kerdoivjson
                kerdoivhint={at('A téma kérdőíve az új időpontba másolódik, amikor a témát kiválasztod; a már létrehozott időpontok kérdőívét nem változtatja meg.')}}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
