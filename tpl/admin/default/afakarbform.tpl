<div id="mattkarb-header">
    <h3>{at('ÁFA kulcs')}</h3>
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
                    <td><label for="ErtekEdit">{at('ÁFA kulcs')}:</label></td>
                    <td><input id="ErtekEdit" name="ertek" type="number" step="any" value="{$egyed.ertek}" required="required"> %</td>
                </tr>
                <tr>
                    <td><label for="NavcaseEdit">{at('NAV case')}:</label></td>
                    <td>
                        <select id="NavcaseEdit" name="navcase">
                            {foreach $egyed.navcaselist as $_case}
                                <option value="{$_case.id}"{if ($_case.selected)} selected="selected"{/if}>{$_case.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="RlbkodEdit">{at('RLB kód')}:</label></td>
                    <td><input id="RlbkodEdit" name="rlbkod" type="number" value="{$egyed.rlbkod}"></td>
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
