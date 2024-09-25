<div id="mattkarb-header">
    <h3>{at('')}</h3>
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
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
                </tr>
                <tr>
                    <td><label for="CsoportEdit">{at('Csoport')}:</label></td>
                    <td><input id="CsoportEdit" name="csoport" type="text" size="80" maxlength="255" value="{$egyed.csoport}"></td>
                </tr>
                <tr>
                    <td><label for="NemertekelhetoEdit">{at('Nem értékelhető')}:</label></td>
                    <td><input id="NemertekelhetoEdit" name="nemertekelheto" type="checkbox"{if ($egyed.nemertekelheto)} checked="checked"{/if}"></td>
                </tr>
                {if ($setup.foglalas)}
                    <tr>
                        <td><label for="FoglalEdit">{at('Foglal')}:</label></td>
                        <td><input id="FoglalEdit" name="foglal" type="checkbox"{if ($egyed.foglal)} checked="checked"{/if}"></td>
                    </tr>
                {/if}
                <tr>
                    <td><label for="WcidEdit">{at('WooCommerce ID')}:</label></td>
                    <td><select id="WcidEdit" name="wcid">
                            <option value=""{if ($egyed.wcid=='')} selected="selected"{/if}>válasszon</option>
                            <option value="pending"{if ($egyed.wcid=='pending')} selected="selected"{/if}>pending</option>
                            <option value="processing"{if ($egyed.wcid=='processing')} selected="selected"{/if}>processing</option>
                            <option value="on-hold"{if ($egyed.wcid=='on-hold')} selected="selected"{/if}>on-hold</option>
                            <option value="completed"{if ($egyed.wcid=='completed')} selected="selected"{/if}>completed</option>
                            <option value="cancelled"{if ($egyed.wcid=='cancelled')} selected="selected"{/if}>cancelled</option>
                            <option value="refunded"{if ($egyed.wcid=='refunded')} selected="selected"{/if}>refunded</option>
                            <option value="failed"{if ($egyed.wcid=='failed')} selected="selected"{/if}>failed</option>
                            <option value="trash"{if ($egyed.wcid=='trash')} selected="selected"{/if}>trash</option>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
                    <td><input id="SorrendEdit" name="sorrend" type="text" size="80" maxlength="255" value="{$egyed.sorrend}"></td>
                </tr>
                <tr>
                    <td><label for="EmailEdit">{at('Email sablon')}:</label></td>
                    <td colspan="7"><select id="EmailEdit" name="emailtemplate">
                            <option value="">{at('válasszon')}</option>
                            {foreach $emailtemplatelist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="FizmodEdit">{at('Fizetési mód')}:</label></td>
                    <td><select id="FizmodEdit" name="fizmod">
                            <option value="">{at('válasszon')}</option>
                            {foreach $fizmodlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="SzallitasimodEdit">{at('Szállítási mód')}:</label></td>
                    <td><select id="SzallitasimodEdit" name="szallitasimod">
                            <option value="">{at('válasszon')}</option>
                            {foreach $szallitasimodlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
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