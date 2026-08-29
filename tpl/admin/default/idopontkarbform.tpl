<div id="mattkarb-header">
    <h3>{at('Időpont')}</h3>
    <h4>{if $egyed.nev}{$egyed.nev}{else}{$egyed.idoponttemanev}{/if} {$egyed.napnev} {$egyed.idotartam}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/idopont/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#DokTab">{at('Dokumentumok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="TipusEdit">{at('Típus')}:</label></td>
                    <td><select id="TipusEdit" name="tipus">
                            <option value="idopont"{if ($egyed.tipus != 'rendezveny')} selected="selected"{/if}>{at('Időpont (foglalható)')}</option>
                            <option value="rendezveny"{if ($egyed.tipus == 'rendezveny')} selected="selected"{/if}>{at('Rendezvény')}</option>
                        </select></td>
                </tr>
                </tbody>
            </table>
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            <input id="OnlinevalaszthatoCheck" name="onlinevalaszthato" type="checkbox"
                   {if ($egyed.onlinevalaszthato)}checked="checked"{/if}>{at('Online is választható')}
            <input id="IsmetlodoCheck" name="ismetlodo" type="checkbox"
                   {if ($egyed.ismetlodo)}checked="checked"{/if}>{at('Ismétlődő (heti)')}
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td colspan="3"><input id="NevEdit" name="nev" type="text" size="83" maxlength="255"
                                           value="{$egyed.nev}"></td>
                </tr>
                <tr>
                    <td><label for="IdoponttemaEdit">{at('Téma')}:</label></td>
                    <td><select id="IdoponttemaEdit" name="idoponttema">
                            <option value="">{at('válasszon')}</option>
                            {foreach $idoponttemalist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="DolgozoEdit">{at('Tanár')}:</label></td>
                    <td><select id="DolgozoEdit" name="dolgozo">
                            <option value="">{at('válasszon')}</option>
                            {foreach $dolgozolist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="JogahelyszinEdit">{at('Helyszín')}:</label></td>
                    <td><select id="JogahelyszinEdit" name="jogahelyszin">
                            <option value="">{at('válasszon')}</option>
                            {foreach $jogahelyszinlist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                </tbody>
            </table>
            {* a két blokk kizárja egymást, az ismétlődő jelölő kapcsol köztük (idopont.js) *}
            <table class="js-egyszeriblokk"{if ($egyed.ismetlodo)} style="display:none;"{/if}>
                <tbody>
                <tr>
                    <td><label for="KezdetEdit">{at('Kezdet')}:</label></td>
                    <td><input id="KezdetEdit" name="kezdet" type="datetime-local" value="{$egyed.kezdetinput}"></td>
                </tr>
                <tr>
                    <td><label for="VegEdit">{at('Vég')}:</label></td>
                    <td><input id="VegEdit" name="veg" type="datetime-local" value="{$egyed.veginput}"></td>
                </tr>
                </tbody>
            </table>
            <table class="js-ismetlodoblokk"{if (!$egyed.ismetlodo)} style="display:none;"{/if}>
                <tbody>
                <tr>
                    <td><label for="NapEdit">{at('Nap')}:</label></td>
                    <td><select id="NapEdit" name="nap">
                            <option value="">{at('válasszon')}</option>
                            {foreach $naplist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="KezdetidoEdit">{at('Kezdés')}:</label></td>
                    <td><input id="KezdetidoEdit" name="kezdetido" type="time" value="{$egyed.kezdetido}"></td>
                </tr>
                <tr>
                    <td><label for="VegidoEdit">{at('Vége')}:</label></td>
                    <td><input id="VegidoEdit" name="vegido" type="time" value="{$egyed.vegido}"></td>
                </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                <tr>
                    <td><label for="ArEdit">{at('Ár')}:</label></td>
                    <td><input id="ArEdit" name="ar" type="number" step="any" value="{$egyed.ar}"></td>
                </tr>
                <tr>
                    <td><label for="EarlybirdarEdit">{at('Early bird ár')}:</label></td>
                    <td><input id="EarlybirdarEdit" name="earlybirdar" type="number" step="any" value="{$egyed.earlybirdar}"></td>
                </tr>
                <tr>
                    <td><label for="EarlybirdvegeEdit">{at('Early bird vége')}:</label></td>
                    <td><input id="EarlybirdvegeEdit" name="earlybirdvege" data-datum="{$egyed.earlybirdvege}"></td>
                </tr>
                <tr>
                    <td><label for="TermekEdit">{at('Termék a számlán')}:</label></td>
                    <td><select id="TermekEdit" name="termek">
                            <option value="">{at('válasszon')}</option>
                            {foreach $termeklist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="MaxresztvevoEdit">{at('Max. résztvevő szám')}:</label></td>
                    <td><input id="MaxresztvevoEdit" name="maxresztvevo" type="number" step="1" min="0"
                               value="{$egyed.maxresztvevo|default:0}"> <span class="mattkarb-hint">{at('0 = nincs korlát')}</span></td>
                </tr>
                <tr>
                    <td><label for="VarolistavanEdit">{at('Van várólista')}:</label></td>
                    <td><input id="VarolistavanEdit" name="varolistavan" type="checkbox"{if ($egyed.varolistavan)} checked="checked"{/if}></td>
                </tr>
                {if ($egyed.id && !$egyed.ismetlodo)}
                    <tr>
                        <td>{at('Jelentkezések')}:</td>
                        <td>{$egyed.foglalasdb}{if $egyed.maxresztvevo} / {$egyed.maxresztvevo}{/if}</td>
                    </tr>
                {/if}
                </tbody>
            </table>

            <table>
                <tbody>
                <tr>
                    <td><label for="AllapotEdit">{at('Állapot')}:</label></td>
                    <td><select id="AllapotEdit" name="idopontallapot">
                            <option value="">{at('válasszon')}</option>
                            {foreach $idopontallapotlist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="CsomagEdit">{at('Csomag')}:</label></td>
                    <td><input id="CsomagEdit" name="csomag" type="checkbox"{if ($egyed.csomag)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="UrlEdit">{at('Webcím')}:</label></td>
                    <td colspan="3"><input id="UrlEdit" name="url" type="text" size="83" maxlength="255"
                                           value="{$egyed.url}"></td>
                </tr>
                <tr>
                    <td><label for="OnlineUrlEdit">{at('Online link')}:</label></td>
                    <td colspan="3"><input id="OnlineUrlEdit" name="onlineurl" type="text" size="83" maxlength="255"
                                           value="{$egyed.onlineurl}"></td>
                </tr>
                <tr>
                    <td><label for="KellszamlazasiadatEdit">{at('Számlázási adat bekérés')}:</label></td>
                    <td><input id="KellszamlazasiadatEdit" name="kellszamlazasiadat" type="checkbox"{if ($egyed.kellszamlazasiadat)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="OrarendbenszerepelEdit">{at('Órarendben szerepel')}:</label></td>
                    <td><input id="OrarendbenszerepelEdit" name="orarendbenszerepel" type="checkbox"{if ($egyed.orarendbenszerepel)} checked="checked"{/if}></td>
                </tr>
                {if ($egyed.id)}
                    <tr>
                        <td>{at('Regisztrációs form')}:</td>
                        <td><a href="#" class="js-uidcopy" data-clipboard-text="{$egyed.reglink}">{at('Másolás vágólapra')}</a></td>
                    </tr>
                {/if}
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
