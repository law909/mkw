<div id="mattkarb-header">
    <h3>{at('Időpont')}</h3>
    <h4>{if $egyed.nev}{$egyed.nev}{else}{$egyed.idoponttemanev}{/if} {$egyed.napnev} {$egyed.idotartam}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/idopont/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#KerdoivTab">{at('Kérdőív')}{if ($egyed.kerdoivkerdesdb)} ({$egyed.kerdoivkerdesdb}){/if}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Típus" for="TipusEdit"}
                    <select id="TipusEdit" name="tipus">
                        <option value="idopont"{if ($egyed.tipus != 'rendezveny')} selected="selected"{/if}>{at('Időpont (foglalható)')}</option>
                        <option value="rendezveny"{if ($egyed.tipus == 'rendezveny')} selected="selected"{/if}>{at('Rendezvény')}</option>
                    </select>
                {/mezo}
            {/mezocsoport}
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            <input id="OnlinevalaszthatoCheck" name="onlinevalaszthato" type="checkbox"
                   {if ($egyed.onlinevalaszthato)}checked="checked"{/if}>{at('"Online" is választható')}
            <input id="IsmetlodoCheck" name="ismetlodo" type="checkbox"
                   {if ($egyed.ismetlodo)}checked="checked"{/if}>{at('Ismétlődő (heti)')}
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit" szeles=true}
                    <input id="NevEdit" name="nev" type="text" size="83" maxlength="255"
                        value="{$egyed.nev}">
                {/mezo}
                {mezo cimke="Téma" for="IdoponttemaEdit"}
                    <select id="IdoponttemaEdit" name="idoponttema">
                        <option value="">{at('válasszon')}</option>
                        {foreach $idoponttemalist as $_d}
                            <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Tanár" for="DolgozoEdit"}
                    <select id="DolgozoEdit" name="dolgozo">
                        <option value="">{at('válasszon')}</option>
                        {foreach $dolgozolist as $_d}
                            <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Helyszín" for="JogahelyszinEdit"}
                    <select id="JogahelyszinEdit" name="jogahelyszin">
                        <option value="">{at('válasszon')}</option>
                        {foreach $jogahelyszinlist as $_d}
                            <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
            {/mezocsoport}
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
            <fieldset class="mattkarb-doboz">
                <legend>{at('Ár és számlázás')}</legend>
                {mezocsoport}
                    {mezo cimke="Ár" for="ArEdit"}
                        <input id="ArEdit" name="ar" type="number" step="any" value="{$egyed.ar}">
                    {/mezo}
                    {mezo cimke="Early bird ár" for="EarlybirdarEdit"}
                        <input id="EarlybirdarEdit" name="earlybirdar" type="number" step="any" value="{$egyed.earlybirdar}">
                    {/mezo}
                    {mezo cimke="Early bird vége" for="EarlybirdvegeEdit"}
                        <input id="EarlybirdvegeEdit" name="earlybirdvege" data-datum="{$egyed.earlybirdvege}">
                    {/mezo}
                    {mezo cimke="Termék a számlán" for="TermekEdit"}
                        <select id="TermekEdit" name="termek">
                            <option value="">{at('válasszon')}</option>
                            {foreach $termeklist as $_d}
                                <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                {/mezocsoport}
            </fieldset>
            {mezocsoport}
                {mezo cimke="Max. résztvevő szám" for="MaxresztvevoEdit"}
                    <input id="MaxresztvevoEdit" name="maxresztvevo" type="number" step="1" min="0"
                        value="{$egyed.maxresztvevo|default:0}"> <span class="mattkarb-hint">{at('0 = nincs korlát')}</span>
                {/mezo}
                {mezo cimke="Van várólista" for="VarolistavanEdit"}
                    <input id="VarolistavanEdit" name="varolistavan" type="checkbox"{if ($egyed.varolistavan)} checked="checked"{/if}>
                {/mezo}
                {if ($egyed.id && !$egyed.ismetlodo)}
                    {mezo cimke="Jelentkezések"}
                        {$egyed.foglalasdb}{if $egyed.maxresztvevo} / {$egyed.maxresztvevo}{/if}
                    {/mezo}
                {/if}
            {/mezocsoport}

            {mezocsoport}
                {mezo cimke="Állapot" for="AllapotEdit"}
                    <select id="AllapotEdit" name="idopontallapot">
                        <option value="">{at('válasszon')}</option>
                        {foreach $idopontallapotlist as $_d}
                            <option value="{$_d.id}"{if ($_d.selected)} selected="selected"{/if}>{$_d.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Webcím" for="UrlEdit" szeles=true}
                    <input id="UrlEdit" name="url" type="text" size="83" maxlength="255"
                        value="{$egyed.url}">
                {/mezo}
                {mezo cimke="Online link" for="OnlineUrlEdit" szeles=true}
                    <input id="OnlineUrlEdit" name="onlineurl" type="text" size="83" maxlength="255"
                        value="{$egyed.onlineurl}">
                {/mezo}
                {if ($setup.epp|default:0)}
                    {mezo cimke="WP oldal ID" for="WpoldalidEdit"}
                        <div class="mattkarb-mezogomb">
                            <input id="WpoldalidEdit" name="wpoldalid" type="number" min="1" class="mezo-rovid"
                                value="{$egyed.wpoldalid}">
                            <span>{at('a jelszóval védett WordPress oldal (post) azonosítója; a jelentkezők ehhez kaphatnak jelszót')}</span>
                        </div>
                    {/mezo}
                {/if}
                {mezo cimke="Számlázási adat bekérés" for="KellszamlazasiadatEdit"}
                    <input id="KellszamlazasiadatEdit" name="kellszamlazasiadat" type="checkbox"{if ($egyed.kellszamlazasiadat)} checked="checked"{/if}>
                {/mezo}
                {if ($egyed.id)}
                    {mezo cimke="Regisztrációs form"}
                        <a href="#" class="js-uidcopy" data-clipboard-text="{$egyed.reglink}">{at('Másolás vágólapra')}</a>
                    {/mezo}
                {/if}
            {/mezocsoport}
        </div>
        <div id="KerdoivTab" class="mattkarb-page" data-visible="visible">
            {include 'idopontkerdoivszerkeszto.tpl' kerdoivjson=$egyed.kerdoivjson
                kerdoivhint={at('A kérdőív a foglalási űrlapon jelenik meg a név, email és telefonszám után, a válaszok a jelentkezésen olvashatók. Kérdés nélkül az űrlapon semmi nem látszik. A téma kiválasztásakor a téma kérdőíve töltődik be; ha már vannak kérdések, előbb rákérdez.')}}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
