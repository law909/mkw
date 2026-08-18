<div id="mattkarb-header">
    <h3>{at('Időpont')}</h3>
    <h4>{$egyed.idoponttemanev} {$egyed.napnev} {$egyed.idotartam}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/idopont/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            <input id="OnlinevalaszthatoCheck" name="onlinevalaszthato" type="checkbox"
                   {if ($egyed.onlinevalaszthato)}checked="checked"{/if}>{at('Online is választható')}
            <input id="IsmetlodoCheck" name="ismetlodo" type="checkbox"
                   {if ($egyed.ismetlodo)}checked="checked"{/if}>{at('Ismétlődő (heti)')}
            <table>
                <tbody>
                <tr>
                    <td><label for="IdoponttemaEdit">{at('Téma')}:</label></td>
                    <td><select id="IdoponttemaEdit" name="idoponttema" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $idoponttemalist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="DolgozoEdit">{at('Tanár')}:</label></td>
                    <td><select id="DolgozoEdit" name="dolgozo" required="required">
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
                    <td><label for="MaxresztvevoEdit">{at('Max. résztvevő szám')}:</label></td>
                    <td><input id="MaxresztvevoEdit" name="maxresztvevo" type="number" step="1" min="1"
                               value="{$egyed.maxresztvevo|default:1}" required></td>
                </tr>
                {if ($egyed.id && !$egyed.ismetlodo)}
                    <tr>
                        <td>{at('Foglalások')}:</td>
                        <td>{$egyed.foglalasdb} / {$egyed.maxresztvevo}</td>
                    </tr>
                {/if}
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
