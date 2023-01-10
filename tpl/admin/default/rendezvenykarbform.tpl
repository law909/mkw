<div id="mattkarb-header">
    <h3>{at('Rendezvény')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/rendezveny/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#DokTab">{at('Dokumentumok')}</a></li>
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
                    <td><label for="UrlEdit">{at('Webcím')}:</label></td>
                    <td colspan="3"><input id="UrlEdit" name="url" type="text" size="83" maxlength="255"
                                           value="{$egyed.url}"></td>
                </tr>
                <tr>
                    <td><label for="KezdodatumEdit">{at('Rendezvény kezdő dátuma')}:</label></td>
                    <td><input id="KezdodatumEdit" name="kezdodatum" data-datum="{$egyed.kezdodatum}"></td>
                </tr>
                <tr>
                    <td><label for="KezdoidoEdit">{at('Rendezvény kezdés ideje')}:</label></td>
                    <td><input id="KezdoidoEdit" name="kezdoido" value="{$egyed.kezdoido}"></td>
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
                    <td><label for="HelszinEdit">{at('Rendezvény helyszíne')}:</label></td>
                    <td><select id="HelyszinEdit" name="helyszin">
                            <option value="">{at('válasszon')}</option>
                            {foreach $helyszinlist as $_tanar}
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
                    <td><label for="KellszamlazasiadatEdit">{at('Számlázási adat bekérés')}:</label></td>
                    <td><input id="KellszamlazasiadatEdit" name="kellszamlazasiadat" type="checkbox"{if ($egyed.kellszamlazasiadat)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="OrarendbenszerepelEdit">{at('Órarendben szerepel')}:</label></td>
                    <td><input id="OrarendbenszerepelEdit" name="orarendbenszerepel" type="checkbox"{if ($egyed.orarendbenszerepel)} checked="checked"{/if}></td>
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
                <tr>
                    <td><label for="">{at('Ár')}:</label></td>
                    <td><input name="ar" type="number" step="any" value="{$egyed.ar}" required="required"></td>
                </tr>
                <tr>
                    <td><label for="OnlineUrlEdit">{at('Online link')}:</label></td>
                    <td colspan="3"><input id="OnlineUrlEdit" name="onlineurl" type="text" size="83" maxlength="255"
                                           value="{$egyed.onlineurl}"></td>
                </tr>
                <tr>
                    <td><label for="MaxferohelyEdit">{at('Max.férphely')}:</label></td>
                    <td><input id="MaxferohelyEdit" type="number" name="maxferohely" step="any" value="{$egyed.maxferohely}"></td>
                </tr>
                <tr>
                    <td><label for="VanvarolistaEdit">{at('Van várólista')}:</label></td>
                    <td><input id="VanvarolistaEdit" name="varolistavan" type="checkbox"{if ($egyed.varolistavan)} checked="checked"{/if}></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="DokTab" class="mattkarb-page" data-visible="visible">
            {foreach $egyed.dokok as $dok}
                {include 'dokumentumtarkarb.tpl'}
            {/foreach}
            <a class="js-doknewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>