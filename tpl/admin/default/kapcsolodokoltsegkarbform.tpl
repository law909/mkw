<div id="mattkarb-header">
    <h3>{at('Kapcsolódó költség')}</h3>
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
                    <td><label for="CsoportEdit">{at('Csoport')}:</label></td>
                    <td>
                        <select id="CsoportEdit" name="csoport" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $egyed.csoportlist as $_cs}
                                <option value="{$_cs.id}"{if ($_cs.selected)} selected="selected"{/if}>{$_cs.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="SzamitasalapEdit">{at('Számítás alapja')}:</label></td>
                    <td>
                        <select id="SzamitasalapEdit" name="szamitasalap" required="required">
                            {foreach $egyed.szamitasalaplist as $_sza}
                                <option value="{$_sza.id}"{if ($_sza.selected)} selected="selected"{/if}>{$_sza.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="ArEdit">{at('Ár')}:</label></td>
                    <td><input id="ArEdit" name="ar" type="number" step="any" value="{$egyed.ar}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="NavfeladandoEdit">{at('NAV-nak feladandó')}:</label></td>
                    <td><input id="NavfeladandoEdit" name="navfeladando" type="checkbox"{if ($egyed.navfeladando)} checked="checked"{/if}></td>
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
