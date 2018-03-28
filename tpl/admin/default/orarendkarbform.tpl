<div id="mattkarb-header">
    <h3>{at('Órarend')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/orarend/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            <input id="AlkalmiCheck" name="alkalmi" type="checkbox"
                   {if ($egyed.alkalmi)}checked="checked"{/if}>{at('Alkalmi')}
            <input id="ElmaradCheck" name="elmarad" type="checkbox"
                   {if ($egyed.elmarad)}checked="checked"{/if}>{at('Elmarad')}
            <table>
                <tbody>
                <tr>
                    <td><label for="NapEdit">{at('Nap')}:</label></td>
                    <td><select id="NapEdit" name="nap" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $naplist as $_tcs}
                                <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="KezdetEdit">{at('Kezdet')}:</label></td>
                    <td><input id="KezdetEdit" name="kezdet" type="text" value="{$egyed.kezdet}" required></td>
                    <td><label for="VegEdit">{at('Vég')}:</label></td>
                    <td><input id="VegEdit" name="veg" type="text" value="{$egyed.veg}" required></td>
                </tr>
                <tr>
                    <td><label for="JogateremEdit">{at('Terem')}:</label></td>
                    <td><select id="JogateremEdit" name="jogaterem" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $jogateremlist as $_tcs}
                                <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if} data-maxferohely="{$_tcs.maxferohely}">{$_tcs.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="JogaoratipusEdit">{at('Óratípus')}:</label></td>
                    <td><select id="JogaoratipusEdit" name="jogaoratipus" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $jogaoratipuslist as $_tcs}
                                <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="OktatoEdit">{at('Oktató')}:</label></td>
                    <td><select id="OktatoEdit" name="dolgozo" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $dolgozolist as $_tcs}
                                <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="HelyettesitoEdit">{at('Helyettesítő')}:</label></td>
                    <td><select id="HelyettesitoEdit" name="helyettesito">
                            <option value="">{at('válasszon')}</option>
                            {foreach $helyettesitolist as $_tcs}
                                <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td colspan="3"><input id="NevEdit" name="nev" type="text" size="83" maxlength="255"
                                           value="{$egyed.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="MaxferohelyEdit">{at('Max. férőhely')}:</label></td>
                    <td><input id="MaxferohelyEdit" name="maxferohely" type="number" size="5" maxlength="5" step="any"
                               value="{$egyed.maxferohely}"></td>
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