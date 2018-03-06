<div id="mattkarb-header">
    <h3>{at('Rendezvény')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/rendezveny/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="AllapotEdit">{at('Rendezvény állapota')}:</label></td>
                    <td><select id="AllapotEdit" name="rendezvenyallapot">
                            <option value="">{at('válasszon')}</option>
                            {foreach $rendezvenyallapotlist as $_tanar}
                                <option
                                    value="{$_tanar.id}"{if ($_tanar.selected)} selected="selected"{/if}>{$_tanar.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td colspan="3"><input id="NevEdit" name="nev" type="text" size="83" maxlength="255"
                                           value="{$egyed.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="KezdodatumEdit">{at('Rendezvény kezdő dátuma')}:</label></td>
                    <td><input id="KezdodatumEdit" name="kezdodatum" data-datum="{$egyed.kezdodatum}"></td>
                </tr>
                <tr>
                    <td><label for="TanarEdit">{at('Rendezvény tanára')}:</label></td>
                    <td><select id="TanarEdit" name="tanar">
                            <option value="">{at('válasszon')}</option>
                            {foreach $tanarlist as $_tanar}
                                <option
                                    value="{$_tanar.id}"{if ($_tanar.selected)} selected="selected"{/if}>{$_tanar.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="JogateremEdit">{at('Rendezvény helye')}:</label></td>
                    <td><select id="JogateremEdit" name="jogaterem">
                            <option value="">{at('válasszon')}</option>
                            {foreach $jogateremlist as $_tanar}
                                <option
                                    value="{$_tanar.id}"{if ($_tanar.selected)} selected="selected"{/if}>{$_tanar.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="TermekEdit">{at('Termék a számlán')}:</label></td>
                    <td><select id="TermekEdit" name="termek">
                            <option value="">{at('válasszon')}</option>
                            {foreach $termeklist as $_termek}
                                <option
                                    value="{$_termek.id}"{if ($_termek.selected)} selected="selected"{/if}>{$_termek.caption}</option>
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