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
            <input id="MultilangCheck" name="multilang" type="checkbox"
                   {if ($egyed.multilang)}checked="checked"{/if}>{at('Több nyelvű')}
            <input id="BejelentkezeskellCheck" name="bejelentkezeskell" type="checkbox"
                   {if ($egyed.bejelentkezeskell)}checked="checked"{/if}>{at('Bejelentkezés kell')}
            <input id="BejelentkezesertesitokellCheck" name="bejelentkezesertesitokell" type="checkbox"
                   {if ($egyed.bejelentkezesertesitokell)}checked="checked"{/if}>{at('Bejel. értesítő kell')}
            <input id="LemondhatoCheck" name="lemondhato" type="checkbox"
                   {if ($egyed.lemondhato)}checked="checked"{/if}>{at('Lemondható')}
            <input id="OrarendbennincsCheck" name="orarendbennincs" type="checkbox"
                   {if ($egyed.orarendbennincs)}checked="checked"{/if}>{at('Órarendben NEM látszik')}
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
                                <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}
                                        data-maxferohely="{$_tcs.maxferohely}">{$_tcs.caption}</option>
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
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td colspan="3"><input id="NevEdit" name="nev" type="text" size="83" maxlength="255"
                                           value="{$egyed.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="MaxferohelyEdit">{at('Max. férőhely')}:</label></td>
                    <td><input id="MaxferohelyEdit" name="maxferohely" type="number" size="5" maxlength="5" step="any"
                               value="{$egyed.maxferohely}"></td>
                </tr>
                <tr>
                    <td><label for="AtlagresztvevoszamEdit">{at('Átlagos résztvevőszám')}:</label></td>
                    <td><input id="AtlagresztvevoszamEdit" name="atlagresztvevoszam" type="number" size="5" maxlength="5" step="any"
                               value="{$egyed.atlagresztvevoszam}"></td>
                </tr>
                <tr>
                    <td><label for="MinbejelentkezesEdit">{at('Minimum bejelentkezés')}:</label></td>
                    <td><input id="MinbejelentkezesEdit" name="minbejelentkezes" type="number" size="5" maxlength="5" step="any"
                               value="{$egyed.minbejelentkezes}"></td>
                </tr>
                <tr>
                    <td><label for="JutalekSzazalekEdit">{at('Jutalék %')}:</label></td>
                    <td><input id="JutalekSzazalekEdit" name="jutalekszazalek" type="number" size="5" maxlength="5" step="any"
                               value="{$egyed.jutalekszazalek}"></td>
                </tr>
                <tr>
                    <td><label for="OnlineUrlEdit">{at('Online óra link')}:</label></td>
                    <td colspan="3"><input id="OnlineUrlEdit" name="onlineurl" type="text" size="83" maxlength="255"
                                           value="{$egyed.onlineurl}"></td>
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