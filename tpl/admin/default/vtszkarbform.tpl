<div id="mattkarb-header">
    <h3>{at('VTSZ')}</h3>
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
                    <td><label for="SzamEdit">{at('Szám')}:</label></td>
                    <td><input id="SzamEdit" name="szam" type="text" size="80" maxlength="255" value="{$egyed.szam}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
                </tr>
                <tr>
                    <td><label for="AfaEdit">{at('ÁFA kulcs')}:</label></td>
                    <td>
                        <select id="AfaEdit" name="afa" required="required">
                            {foreach $egyed.afalist as $_o}
                                <option value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="CskEdit">{at('CSK szám')}:</label></td>
                    <td>
                        <select id="CskEdit" name="csk">
                            <option value="">{at('válasszon')}</option>
                            {foreach $egyed.csklist as $_o}
                                <option value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="KtEdit">{at('KT kód')}:</label></td>
                    <td>
                        <select id="KtEdit" name="kt">
                            <option value="">{at('válasszon')}</option>
                            {foreach $egyed.ktlist as $_o}
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
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
