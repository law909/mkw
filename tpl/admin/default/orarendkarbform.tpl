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
            {mezocsoport}
                {mezo cimke="Nap" for="NapEdit"}
                    <select id="NapEdit" name="nap" required="required">
                        <option value="">{at('válasszon')}</option>
                        {foreach $naplist as $_tcs}
                            <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Kezdet" for="KezdetEdit" ujsor=true}
                    <input id="KezdetEdit" name="kezdet" type="text" value="{$egyed.kezdet}" required>
                {/mezo}
                {mezo cimke="Vég" for="VegEdit"}
                    <input id="VegEdit" name="veg" type="text" value="{$egyed.veg}" required>
                {/mezo}
                {mezo cimke="Helyszín" for="JogahelyszinEdit"}
                    <select id="JogahelyszinEdit" name="jogahelyszin">
                        <option value="">{at('válasszon')}</option>
                        {foreach $jogahelyszinlist as $_tcs}
                            <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Óratípus" for="JogaoratipusEdit"}
                    <select id="JogaoratipusEdit" name="jogaoratipus" required="required">
                        <option value="">{at('válasszon')}</option>
                        {foreach $jogaoratipuslist as $_tcs}
                            <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Oktató" for="OktatoEdit"}
                    <select id="OktatoEdit" name="dolgozo" required="required">
                        <option value="">{at('válasszon')}</option>
                        {foreach $dolgozolist as $_tcs}
                            <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Név" for="NevEdit" szeles=true}
                    <input id="NevEdit" name="nev" type="text" size="83" maxlength="255"
                        value="{$egyed.nev}" required autofocus>
                {/mezo}
                {mezo cimke="Max. férőhely" for="MaxferohelyEdit"}
                    <input id="MaxferohelyEdit" name="maxferohely" type="number" size="5" maxlength="5" step="any"
                        value="{$egyed.maxferohely}">
                {/mezo}
                {mezo cimke="Átlagos résztvevőszám" for="AtlagresztvevoszamEdit"}
                    <input id="AtlagresztvevoszamEdit" name="atlagresztvevoszam" type="number" size="5" maxlength="5" step="any"
                        value="{$egyed.atlagresztvevoszam}">
                {/mezo}
                {mezo cimke="Minimum bejelentkezés" for="MinbejelentkezesEdit"}
                    <input id="MinbejelentkezesEdit" name="minbejelentkezes" type="number" size="5" maxlength="5" step="any"
                        value="{$egyed.minbejelentkezes}">
                {/mezo}
                {mezo cimke="Jutalék %" for="JutalekSzazalekEdit"}
                    <input id="JutalekSzazalekEdit" name="jutalekszazalek" type="number" size="5" maxlength="5" step="any"
                        value="{$egyed.jutalekszazalek}" placeholder="{at('alapértelmezett')}">
                {/mezo}
                {mezo cimke="Online óra link" for="OnlineUrlEdit" szeles=true}
                    <input id="OnlineUrlEdit" name="onlineurl" type="text" size="83" maxlength="255"
                        value="{$egyed.onlineurl}">
                {/mezo}
            {/mezocsoport}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>