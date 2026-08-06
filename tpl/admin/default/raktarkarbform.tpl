<div id="mattkarb-header">
    <h3>{at('Raktár')}</h3>
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
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="50" value="{$egyed.nev}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="MozgatEdit">{at('Készletet mozgat')}:</label></td>
                    <td><input id="MozgatEdit" name="mozgat" type="checkbox"{if ($egyed.mozgat)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="ArchivEdit">{at('Archív')}:</label></td>
                    <td><input id="ArchivEdit" name="archiv" type="checkbox"{if ($egyed.archiv)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="IdegenkodEdit">{at('Idegen kód')}:</label></td>
                    <td><input id="IdegenkodEdit" name="idegenkod" type="text" size="80" maxlength="255" value="{$egyed.idegenkod}"></td>
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
