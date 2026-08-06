<div id="mattkarb-header">
    <h3>{at('Pénztár')}</h3>
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
                    <td><label for="ValutanemEdit">{at('Valutanem')}:</label></td>
                    <td>
                        <select id="ValutanemEdit" name="valutanem">
                            <option value="">{at('válasszon')}</option>
                            {foreach $egyed.valutanemlist as $_o}
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
