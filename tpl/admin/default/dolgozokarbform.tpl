<div id="mattkarb-header">
    <h3>{at('Dolgozó')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#MegjelenesTab">{at('Megjelenés')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Inaktív" for="InaktivEdit"}
                    <input id="InaktivEdit" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)} checked{/if}>
                {/mezo}
                {mezo cimke="Óra elmaradásról értesítés a könyvelőnek" for="oraelmaradaskonyvelonekEdit"}
                    <input id="oraelmaradaskonyvelonekEdit" name="oraelmaradaskonyvelonek"
                        type="checkbox"{if ($egyed.oraelmaradaskonyvelonek)} checked{/if}>
                {/mezo}
                {mezo cimke="Név" for="NevEdit" szeles=true}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required autofocus>
                {/mezo}
                {mezo cimke="Születési idő" for="SzulidoEdit" ujsor=true}
                    <input id="SzulidoEdit" name="szulido" type="text" size="12" data-datum="{$egyed.szulidostr}">
                {/mezo}
                {mezo cimke="Születési hely" for="SzulhelyEdit"}
                    <input id="SzulhelyEdit" name="szulhely" type="text" size="40" maxlength="60" value="{$egyed.szulhely}">
                {/mezo}
                {mezo cimke="Cím" for="IrszamEdit" szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="IrszamEdit" name="irszam" type="text" size="6" maxlength="10" value="{$egyed.irszam}">
                        <input id="VarosEdit" name="varos" type="text" size="20" maxlength="40" value="{$egyed.varos}">
                        <input id="UtcaEdit" name="utca" type="text" size="40" maxlength="60" value="{$egyed.utca}">
                    </div>
                {/mezo}
                {mezo cimke="Telefon" for="TelefonEdit"}
                    <input id="TelefonEdit" name="telefon" type="text" size="20" maxlength="40" value="{$egyed.telefon}">
                {/mezo}
                {mezo cimke="Email" for="EmailEdit"}
                    <input id="EmailEdit" name="email" type="email" size="40" maxlength="100" value="{$egyed.email}" required>
                {/mezo}
                {mezo cimke="URL" for="UrlEdit"}
                    <input id="UrlEdit" name="url" type="text" size="40" maxlength="255" value="{$egyed.url}">
                {/mezo}
                {if (($egyed.id == $loggedinuser.id) || $loggedinuser.admin)}
                    {mezo cimke="Jelszó 1" for="Pass1Edit" ujsor=true}
                        <input id="Pass1Edit" name="jelszo1" type="password" size="40"{if ($oper == 'add')} required{/if}>
                    {/mezo}
                    {mezo cimke="Jelszó 2" for="Pass2Edit"}
                        <input id="Pass2Edit" name="jelszo2" type="password" size="40"{if ($oper == 'add')} required{/if}>
                    {/mezo}
                    {mezo cimke="Jelszó szöveg" for="JelszoTextEdit"}
                        <input id="JelszoTextEdit" name="jelszotext" type="text" size="40" value="{$egyed.jelszotext}">
                    {/mezo}
                {/if}
                {if ($loggedinuser.admin)}
                    {mezo cimke="Munkakör" for="MunkakorEdit" ujsor=true}
                        <select id="MunkakorEdit" name="munkakor">
                            <option value="">{at('válasszon')}</option>
                            {foreach $munkakorlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {mezo cimke="Munkaviszony kezdete" for="MunkaviszonykezdeteEdit"}
                        <input id="MunkaviszonykezdeteEdit" name="munkaviszonykezdete" type="text" size="12" data-datum="{$egyed.munkaviszonykezdetestr}"
                            required>
                    {/mezo}
                    {mezo cimke="Munkaidő" for="MunkakezdesEdit"}
                        <div class="mattkarb-mezogomb">
                            <input id="MunkakezdesEdit" name="munkakezdes" type="time" value="{$egyed.munkakezdesstr}">
                                -
                            <input id="MunkavegeEdit" name="munkavege" type="time" value="{$egyed.munkavegestr}">
                        </div>
                    {/mezo}
                    {mezo cimke="Munkanapok" szeles=true}
                        {foreach $egyed.munkanapok as $_nap}
                            <input id="Munkanap{$_nap.id}Edit" name="munkanap{$_nap.id}" type="checkbox"{if ($_nap.checked)} checked{/if}>
                            <label for="Munkanap{$_nap.id}Edit">{$_nap.nev}</label>
                        {/foreach}
                    {/mezo}
                    {mezo cimke="Nem szerepel a jelenléti íven" for="NemjelenletiivEdit"}
                        <input id="NemjelenletiivEdit" name="nemjelenletiiv" type="checkbox"{if ($egyed.nemjelenletiiv)} checked{/if}>
                    {/mezo}
                    {mezo cimke="Éves max. szabadság" for="EvesmaxszabiEdit"}
                        <div class="mattkarb-mezogomb">
                            <input id="EvesmaxszabiEdit" name="evesmaxszabi" type="number" size="5" maxlength="5"
                                value="{$egyed.evesmaxszabi}"> {at('nap')}
                        </div>
                    {/mezo}
                {/if}
                {mezo cimke="Havi levonás" for="HavilevonasEdit"}
                    <input id="HavilevonasEdit" name="havilevonas" type="number" step="any" value="{$egyed.havilevonas}">
                {/mezo}
                {mezo cimke="Napi levonás" for="NapilevonasEdit"}
                    <input id="NapilevonasEdit" name="napilevonas" type="number" step="any" value="{$egyed.napilevonas}">
                {/mezo}
                {mezo cimke="Számlát ad" for="SzamlatadEdit"}
                    <input id="SzamlatadEdit" name="szamlatad" type="checkbox"{if ($egyed.szamlatad)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Fizetési mód" for="FizmodEdit"}
                    <select id="FizmodEdit" name="fizmod">
                        <option value="">{at('válasszon')}</option>
                        {foreach $fizmodlist as $_fizmod}
                            <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Alapértelmezett raktár" for="AlapertelmezettRaktarEdit"}
                    <select id="AlapertelmezettRaktarEdit" name="alapertelmezettraktar">
                        <option value="">{at('válasszon')}</option>
                        {foreach $raktarlist as $_raktar}
                            <option value="{$_raktar.id}"{if ($_raktar.selected)} selected="selected"{/if}>{$_raktar.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Bérlet eladáskor automatikusan készüljön számla" for="AutoSzamlaEdit"}
                    <input id="AutoSzamlaEdit" name="autoszamla" type="checkbox"{if ($egyed.autoszamla)} checked="checked"{/if}>
                {/mezo}
                {if ($setup.mptngy)}
                    {mezo cimke="Maximum vállalt absztrakt" for="MPTNGYMaxvallaltdbEdit"}
                        <input id="MPTNGYMaxvallaltdbEdit" name="mptngymaxdb" type="number" step="any" value="{$egyed.mptngymaxdb}">
                    {/mezo}
                {/if}
            {/mezocsoport}
        </div>
        <div id="MegjelenesTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Téma" for="UithemeEdit"}
                    <select id="UithemeEdit" name="uitheme">
                        {foreach $uithemes as $_uitheme}
                            <option value="{$_uitheme}"{if ($_uitheme == $egyed.uitheme)} selected="selected"{/if}>{$_uitheme}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Kiemelő szín"}
                    {include "../partials/uiaccentpicker.tpl" accentname="uiaccent" accentvalue=$egyed.uiaccent}
                    <div class="mattkarb-megjegyzes">{at('A kiemelő szín a modern és a modern-dark témában látszik. Bármilyen szín keverhető: a túl világos vagy túl sötét színből a téma olvasható árnyalatot számol.')}</div>
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