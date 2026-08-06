<div id="mattkarb-header">
    <h3>{at('Felhasználó')}</h3>
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
                    <td><label for="FelhasznalonevEdit">{at('Felhasználónév')}:</label></td>
                    <td>{if ($oper == 'add')}<input id="FelhasznalonevEdit" name="felhasznalonev" type="text" size="80" maxlength="16" value="{$egyed.felhasznalonev}" required="required">{else}<input id="FelhasznalonevEdit" type="text" size="80" value="{$egyed.felhasznalonev}" readonly="readonly"><input name="felhasznalonev" type="hidden" value="{$egyed.felhasznalonev}">{/if}</td>
                </tr>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="JelszoEdit">{at('Jelszó')}:</label></td>
                    <td><input id="JelszoEdit" name="jelszo" type="password" size="20" maxlength="16" value="{$egyed.jelszo}"></td>
                </tr>
                <tr>
                    <td><label for="UzletkotoEdit">{at('Üzletkötő')}:</label></td>
                    <td>
                        <select id="UzletkotoEdit" name="uzletkoto">
                            <option value="">{at('válasszon')}</option>
                            {foreach $egyed.uzletkotolist as $_o}
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
    <input name="id" type="hidden" value="{$egyed.felhasznalonev}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
