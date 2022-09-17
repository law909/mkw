{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/darshan/appinit.js"></script>
    <script type="text/javascript" src="/js/admin/darshan/eladas.js"></script>
    <script type="text/javascript" src="/js/admin/darshan/koltseg.js"></script>
    <script type="text/javascript" src="/js/admin/darshan/bepenztar.js"></script>
    <script type="text/javascript" src="/js/admin/darshan/kipenztar.js"></script>
    <script type="text/javascript" src="/js/admin/darshan/jogareszvetel.js"></script>
{/block}

{block "kozep"}
    <div class="clearboth">
        {include "../default/comp_noallapot.tpl"}
    </div>
    <div class="clearboth">
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Jelenléti ív</div>
            </div>
            <div class="mainboxinner">
                {if ($dolgozojelen)}
                    <a href="#" class="js-jelenlet" data-url="/admin/jelenki?dolgozo={$userloggedin}">Munka befejezés</a>
                {else}
                    <a href="#" class="js-jelenlet" data-url="/admin/jelenbe?dolgozo={$userloggedin}">Munka kezdés</a>
                {/if}
            </div>
        </div>
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Bérlet lejáratás</div>
            </div>
            <div class="mainboxinner">
                <a href="#" class="js-berletlejaratas" data-url="/admin/jogaberlet/lejarat">Lejáratás</a>
            </div>
        </div>
    </div>
    <div class="clearboth">
    <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
        <div class="ui-widget-header ui-corner-top">
            <div class="mainboxinner ui-corner-top">Statisztikák</div>
        </div>
        <div class="mainboxinner">
            {include "../default/comp_idoszak.tpl" comptype="datum"}
            <div><label>Tanár elszámolás </label>
                <input id="telszelozo" name="telszidoszak" type="radio" value="1" checked><label for="telszelozo">előző hó</label>
                <input id="telszaktualis" name="telszidoszak" type="radio" value="2"><label for="telszaktualis">aktuális hó</label>
            </div>
            <a id="StatRefreshButton" href="#"><span>Frissít</span></a>
            <div id="stateredmeny"></div>
        </div>
    </div>
    <div class="clearboth">
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Óra látogatások</div>
            </div>
            <div class="mainboxinner">
                <form id="JogareszvetelForm" method="post" action="{$jogareszvetelformaction}">
                    <div>
                        <label for="JRDatumEdit">{at('Dátum')}:</label>
                        <input id="JRDatumEdit" name="datum" type="text" size="12" data-datum="{$datumstr}" class="mattable-important" required="required">
                        <label for="JRJogateremEdit">{at('Terem')}:</label>
                        <select id="JRJogateremEdit" name="jogaterem" class="mattable-important" required="required">
                            <option value="">{at('válassz')}</option>
                            {foreach $jogateremlist as $e}
                                <option value="{$e.id}"{if ($e.selected)} selected="selected"{/if}>{$e.caption}</option>
                            {/foreach}
                        </select>
                        <label for="JRJogaoratipusEdit">{at('Óra típus')}:</label>
                        <select id="JRJogaoratipusEdit" name="jogaoratipus" class="mattable-important" required="required">
                            <option value="">{at('válassz')}</option>
                            {foreach $jogaoratipuslist as $e}
                                <option value="{$e.id}"{if ($e.selected)} selected="selected"{/if}>{$e.caption}</option>
                            {/foreach}
                        </select>
                        <label for="JRTanarEdit">{at('Tanár')}:</label>
                        <select id="JRTanarEdit" name="tanar" class="mattable-important" required="required">
                            <option value="">{at('válassz')}</option>
                            {foreach $tanarlist as $e}
                                <option value="{$e.id}"{if ($e.selected)} selected="selected"{/if}>{$e.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                    <a class="js-jrreszvetelnewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
                    <div class="matt-hseparator"></div>
                    <input id="JROKButton" type="submit" value="OK">
                    <a id="JRCancelButton" href="#"><span>Mégsem</span></a>
                </form>
            </div>
        </div>
    </div>
    <div class="clearboth">
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">ELADOK bérletet, órajegyet, hengerpárnát, stb.</div>
            </div>
            <div class="mainboxinner">
                <form id="EladasForm" method="post" action="{$eladasformaction}">
                    <table>
                        <tbody>
                        <tr>
                            <td><input id="ElSzamlaEdit" name="biztipus" type="radio" class="mattable-important" value="szamla" required="required">Számla</td>
                            <td><input id="ElSzamlaEdit" name="biztipus" type="radio" class="mattable-important" value="egyeb" required="required" checked="checked">Egyéb mozgás</td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="ElKeltEdit">{at('Kelt')}:</label></td>
                            <td><input id="ElKeltEdit" name="kelt" type="text" size="12" data-datum="{$keltstr}" class="mattable-important" required="required"></td>
                        </tr>
                        <tr id="ElTeljesitesRow" class="hidden">
                            <td class="mattable-important"><label for="ElTeljesitesEdit">{at('Teljesítés')}:</label></td>
                            <td><input id="ElTeljesitesEdit" name="teljesites" type="text" size="12" data-datum="{$keltstr}" class="mattable-important"></td>
                        </tr>
                        <tr id="ElEsedekessegRow" class="hidden">
                            <td class="mattable-important"><label for="ElEsedekessegEdit">{at('Esedékesség')}:</label></td>
                            <td><input id="ElEsedekessegEdit" name="esedekesseg" type="text" size="12" data-datum="{$keltstr}" class="mattable-important"></td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="ElPartnerEdit">{at('Kinek')}:</label></td>
                            <td colspan="3">
                                <select id="ElPartnerEdit" name="partner" class="mattable-important" required="required">
                                    <option value="">{at('válassz')}</option>
                                    <option value="-1">{at('Új felvitel')}</option>
                                    {foreach $partnerlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.nev} ({$_mk.email})</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="ElPartnervezeteknevEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Vezetéknév')}:</td>
                            <td><input id="ElPartnervezeteknevEdit" name="partnervezeteknev"></td>
                            <td><label for="ElPartnerkeresztnevEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Keresztnév')}:</td>
                            <td><input id="ElPartnerkeresztnevEdit" name="partnerkeresztnev"></td>
                        </tr>
                        <tr>
                            <td><label title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Cím')}:</label></td>
                            <td colspan="7">
                                <input id="ElPartnerirszamEdit" name="partnerirszam" size="6" maxlength="10">
                                <input id="ElPartnervarosEdit" name="partnervaros" size="20" maxlength="40">
                                <input id="ElPartnerutcaEdit" name="partnerutca" size="40" maxlength="60">
                            </td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="ElPartneremailEdit" class="mattable-important" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Email')}:</label></td>
                            <td><input id="ElPartneremailEdit" name="partneremail"></td>
                            <td><label for="ElPartnertelefonEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Telefon')}:</label></td>
                            <td><input id="ElPartnertelefonEdit" name="partnertelefon"></td>
                        </tr>
                        <tr>
                            <td><label for="ElFizmodEdit" class="mattable-important" title="{at('Hogyan fizetett?')}">{at('Fizetési mód')}:</label></td>
                            <td>
                                <select id="ElFizmodEdit" name="fizmod" class="mattable-important" required="required">
                                    <option value="">{at('válassz')}</option>
                                    {foreach $fizmodlist as $_mk}
                                        <option value="{$_mk.id}"
                                                data-tipus="{if ($_mk.bank)}B{else}P{/if}"
                                                data-szepkartya="{$_mk.szepkartya}"
                                                data-sportkartya="{$_mk.sportkartya}"
                                                data-aycm="{$_mk.aycm}">{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr class="szepkartya hidden">
                            <td><label for="SZEPKartyaTipusEdit">{at('Kártya típusa')}:</label></td>
                            <td>
                                <select id="SZEPKartyaTipusEdit" name="szepkartyatipus">
                                    <option value="">{at('válassz')}</option>
                                    <option value="1">{at('OTP')}</option>
                                    <option value="2">{at('MKB')}</option>
                                    <option value="3">{at('K&H')}</option>
                                </select>
                            </td>
                            <td><label for="SZEPKartyaSzamEdit">{at('Kártya száma')}:</label></td>
                            <td><input id="SZEPKartyaSzamEdit" name="szepkartyaszam" type="text"></td>
                        </tr>
                        <tr class="szepkartya hidden">
                            <td><label for="SZEPKartyaNevEdit">{at('Kártyára írt név')}:</label></td>
                            <td><input id="SZEPKartyaNevEdit" name="szepkartyanev" type="text"></td>
                            <td><label for="SZEPKartyaErvenyessegEdit">{at('Kártya érvényessége')}:</label></td>
                            <td><input id="SZEPKartyaErvenyessegEdit" name="szepkartyaervenyesseg" type="text"></td>
                        </tr>
                        <tr>
                            <td><label for="ElTermekEdit" class="mattable-important">{at('MIT adsz el')}:</label></td>
                            <td>
                                <select id="ElTermekEdit" name="termek" class="mattable-important" required="required">
                                    <option value="">{at('válassz')}</option>
                                    {foreach $eladhatotermeklist as $_mk}
                                        <option value="{$_mk.id}">{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="ElMennyisegEdit" class="mattable-important">{at('MENNYIT adsz el')}:</label></td>
                            <td><input id="ElMennyisegEdit" class="mattable-important" name="mennyiseg" type="number" step="any" value="1"></td>
                        </tr>
                        <tr>
                            <td><label for="ElEgysarEdit" class="mattable-important" title="{at('Az eladott termék egységára')}">{at('MENNYIBE kerül')}:</label></td>
                            <td><input id="ElEgysarEdit" name="egysegar" type="number" step="any" class="mattable-important" required="required"></td>
                        </tr>
                        <tr>
                            <td class="mattable-important">{at('FIZETENDŐ')}:</td>
                            <td class="mattable-important"><span id="ElErtek" class="js-ertek"></span></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <input id="ElVanPenzmozgas" type="checkbox" name="vanpenzmozgas" checked="checked">
                        <label for="ElVanPenzmozgas">Ha kapsz pénzt VAGY beérkezett átutalást rögzítesz, kapcsold be a pipát és add meg ezeket az adatokat is!</label>
                        <table>
                            <tbody>
                            <tr>
                                <td class="mattable-important"><label for="ElPenzdatumEdit">{at('Mikor kapod')}:</label></td>
                                <td><input id="ElPenzdatumEdit" name="penzdatum" type="text" size="12" data-datum="{$keltstr}" class="mattable-important" required="required"></td>
                            </tr>
                            <tr id="ElPenztarRow">
                                <td><label for="ElPenztarEdit" class="mattable-important">{at('Hova teszed a pénzt')}:</label></td>
                                <td>
                                    <select id="ElPenztarEdit" name="penztar" class="mattable-important">
                                        <option value="">{at('válassz')}</option>
                                        {foreach $penztarlist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr id="ElBankszamlaRow" class="hidden">
                                <td><label for="ElBankszamlaEdit" class="mattable-important">{at('Hova érkezett pénz')}:</label></td>
                                <td>
                                    <select id="ElBankszamlaEdit" name="bankszamla" class="mattable-important">
                                        <option value="">{at('válassz')}</option>
                                        {foreach $bankszamlalist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="mattable-important"><label for="ElJogcimEdit" title="{at('Milyen célból kaptál pénzt?')}">{at('Jogcím')}:</label></td>
                                <td>
                                    <select id="ElJogcimEdit" name="teteljogcim" class="mattable-important">
                                        <option value="">{at('válassz')}</option>
                                        {foreach $jogcimlist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="ElOsszegEdit" class="mattable-important">{at('MENNYI pénzt kaptál')}:</label></td>
                                <td><input id="ElOsszegEdit" name="penz" type="number" step="any" class="mattable-important"></td>
                            </tr>
                            <tr>
                                <td><label for="ElMegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                                <td colspan="7"><textarea id="ElMegjegyzesEdit" name="megjegyzes" rows="1" cols="50"></textarea></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <input id="ElOKButton" type="submit" value="OK">
                    <a id="ElCancelButton" href="#"><span>Mégsem</span></a>
                </form>
            </div>
        </div>
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">VETTEM valamit a Darshannak</div>
            </div>
            <div class="mainboxinner">
                <form id="KoltsegForm" method="post" action="{$eladasformaction}">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <input id="KtgSzamlaEdit" name="biztipus" type="radio" class="mattable-important" value="bevet" required="required">
                                <label title="{at('Ha érkezett valamilyen áru, amit eladásra szánunk, pl. könyv, hengerpárna')}">Bevételezés</label>
                            </td>
                            <td>
                                <input id="KtgSzamlaEdit" name="biztipus" type="radio" class="mattable-important" value="koltsegszamla" required="required" checked="checked">
                                <label title="{at('Ha valamilyen költséget rögzítesz, pl. tanári fizetés, villanyszámla, nyomtatás')}">Költségszámla</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="KtgErbizonylatszamEdit" class="mattable-important" title="{at('Az eredeti számla számát írd ide')}">{at('Eredeti biz.szám')}:</label></td>
                            <td><input id="KtgErbizonylatszamEdit" type="text" name="erbizonylatszam"></td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="KtgKeltEdit" title="{at('Az eredeti bizonylat kelte')}">{at('Kelt')}:</label></td>
                            <td><input id="KtgKeltEdit" name="kelt" type="text" size="12" data-datum="{$keltstr}" class="mattable-important" required="required"></td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="KtgTeljesitesEdit" title="{at('Az eredeti bizonylat teljesítése')}">{at('Teljesítés')}:</label></td>
                            <td><input id="KtgTeljesitesEdit" name="teljesites" type="text" size="12" data-datum="{$keltstr}" class="mattable-important" required="required"></td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="KtgEsedekessegEdit" title="{at('Az eredeti bizonylat esedékessége (fizetési határideje)')}">{at('Esedékesség')}:</label></td>
                            <td><input id="KtgEsedekessegEdit" name="esedekesseg" type="text" size="12" data-datum="{$keltstr}" class="mattable-important" required="required"></td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="KtgPartnerEdit">{at('Kitől')}:</label></td>
                            <td colspan="3">
                                <select id="KtgPartnerEdit" name="partner" class="mattable-important" required="required">
                                    <option value="">{at('válassz')}</option>
                                    <option value="-1">{at('Új felvitel')}</option>
                                    {foreach $szallitolist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.nev} ({$_mk.email})</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="KtgPartnervezeteknevEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Vezetéknév')}:</td>
                            <td><input id="KtgPartnervezeteknevEdit" name="partnervezeteknev"></td>
                            <td><label for="KtgPartnerkeresztnevEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Keresztnév')}:</td>
                            <td><input id="KtgPartnerkeresztnevEdit" name="partnerkeresztnev"></td>
                        </tr>
                        <tr>
                            <td><label title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Cím')}:</label></td>
                            <td colspan="7">
                                <input id="KtgPartnerirszamEdit" name="partnerirszam" size="6" maxlength="10">
                                <input id="KtgPartnervarosEdit" name="partnervaros" size="20" maxlength="40">
                                <input id="KtgPartnerutcaEdit" name="partnerutca" size="40" maxlength="60">
                            </td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="KtgPartneremailEdit" class="mattable-important" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Email')}:</label></td>
                            <td><input id="KtgPartneremailEdit" type="email" name="partneremail"></td>
                            <td><label for="KtgPartnertelefonEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Telefon')}:</label></td>
                            <td><input id="KtgPartnertelefonEdit" name="partnertelefon"></td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="KtgDolgozoEdit">{at('Ki költött')}:</label></td>
                            <td><select id="KtgDolgozoEdit" name="felhasznalo" required="required">
                                    <option value="">{at('válassz')}</option>
                                    {foreach $felhasznalolist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="KtgFizmodEdit" class="mattable-important" title="{at('Hogyan fizettél?')}">{at('Fizetési mód')}:</label></td>
                            <td>
                                <select id="KtgFizmodEdit" name="fizmod" class="mattable-important" required="required">
                                    <option value="">{at('válassz')}</option>
                                    {foreach $fizmodlist as $_mk}
                                        <option value="{$_mk.id}" data-tipus="{if ($_mk.bank)}B{else}P{/if}">{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="KtgTermekEdit" class="mattable-important">{at('MIT vettél')}:</label></td>
                            <td>
                                <select id="KtgTermekEdit" name="termek" class="mattable-important" required="required">
                                    <option value="">{at('válassz')}</option>
                                    {foreach $termeklist as $_mk}
                                        <option value="{$_mk.id}">{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="KtgTermeknevEdit" class="mattable-important">{at('MIT vettél, szöveggel')}:</label></td>
                            <td colspan="7"><input id="KtgTermeknevEdit" class="mattable-important" name="termeknev" type="text" size="60"></td>
                        </tr>
                        <tr>
                            <td><label for="KtgMennyisegEdit" class="mattable-important">{at('MENNYIT vettél')}:</label></td>
                            <td><input id="KtgMennyisegEdit" class="mattable-important" name="mennyiseg" type="number" step="any" value="1"></td>
                        </tr>
                        <tr>
                            <td><label for="KtgEgysarEdit" class="mattable-important" title="{at('Az vásárolt termék egységára')}">{at('MENNYIBE kerül')}:</label></td>
                            <td><input id="KtgEgysarEdit" name="egysegar" type="number" step="any" class="mattable-important" required="required"></td>
                        </tr>
                        <tr>
                            <td class="mattable-important">{at('FIZETENDŐ')}:</td>
                            <td class="mattable-important"><span id="KtgErtek" class="js-ertek"></span></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                        <input id="KtgVanPenzmozgas" type="checkbox" name="vanpenzmozgas" checked="checked">
                        <label for="KtgVanPenzmozgas">Ha készpénzzel fizetsz, add meg ezeket az adatokat is!</label>
                        <table>
                            <tbody>
                            <tr>
                                <td class="mattable-important"><label for="KtgPenzdatumEdit">{at('Mikor fizettél')}:</label></td>
                                <td><input id="KtgPenzdatumEdit" name="penzdatum" type="text" size="12" data-datum="{$keltstr}" class="mattable-important" required="required"></td>
                            </tr>
                            <tr>
                                <td><label for="KtgPenztarEdit" class="mattable-important">{at('Honnan veszed ki a pénzt')}:</label></td>
                                <td>
                                    <select id="KtgPenztarEdit" name="penztar" class="mattable-important">
                                        <option value="">{at('válassz')}</option>
                                        {foreach $penztarlist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="mattable-important"><label for="KtgJogcimEdit" title="{at('Milyen célból költesz pénzt?')}">{at('Jogcím')}:</label></td>
                                <td>
                                    <select id="KtgJogcimEdit" name="teteljogcim" class="mattable-important">
                                        <option value="">{at('válassz')}</option>
                                        {foreach $jogcimlist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="KtgOsszegEdit" class="mattable-important">{at('MENNYI pénzt költesz')}:</label></td>
                                <td><input id="KtgOsszegEdit" name="penz" type="number" step="any" class="mattable-important"></td>
                            </tr>
                            <tr>
                                <td><label for="KtgMegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                                <td colspan="7"><textarea id="KtgMegjegyzesEdit" name="megjegyzes" rows="1" cols="50"></textarea></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <input id="KtgOKButton" type="submit" value="OK">
                    <a id="KtgCancelButton" href="#"><span>Mégsem</span></a>
                </form>

            </div>
        </div>
    </div>
    <div class="clearboth">
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">{at('Pénzt VESZEK KI')}</div>
            </div>
            <div class="mainboxinner">
                <form id="KipenztarForm" method="post" action="{$penztarformaction}">
                    <input id="KiIranyEdit" name="irany" value="-1" type="hidden">
                    <input name="quick" value="1" type="hidden">
                    <input name="oper" value="add" type="hidden">
                    <table>
                        <tbody>
                            <tr>
                                <td class="mattable-important"><label for="KeltEdit">{at('Mikor')}:</label></td>
                                <td><input id="KiKeltEdit" name="kelt" type="text" size="12" data-datum="{$keltstr}" class="mattable-important" required="required"></td>
                            </tr>
                            <tr>
                                <td><label for="KiPenztarEdit">{at('Honnan')}:</label></td>
                                <td>
                                    <select id="KiPenztarEdit" name="penztar" required="required">
                                        <option value="">{at('válassz')}</option>
                                        {foreach $penztarlist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="mattable-important"><label for="KiPartnerEdit">{at('Kinek adom')}:</label></td>
                                <td colspan="3">
                                    <select id="KiPartnerEdit" name="partner" class="mattable-important" required="required">
                                        <option value="">{at('válassz')}</option>
                                        <option value="-1">{at('Új felvitel')}</option>
                                        {foreach $partnerlist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.nev} ({$_mk.email})</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="KiPartnervezeteknevEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Vezetéknév')}:</td>
                                <td><input id="KiPartnervezeteknevEdit" name="partnervezeteknev"></td>
                                <td><label for="KiPartnerkeresztnevEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Keresztnév')}:</td>
                                <td colspan="3"><input id="KiPartnerkeresztnevEdit" name="partnerkeresztnev"></td>
                            </tr>
                            <tr>
                                <td><label title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Cím')}:</label></td>
                                <td colspan="7">
                                    <input id="KiPartnerirszamEdit" name="partnerirszam" size="6" maxlength="10">
                                    <input id="KiPartnervarosEdit" name="partnervaros" size="20" maxlength="40">
                                    <input id="KiPartnerutcaEdit" name="partnerutca" size="40" maxlength="60">
                                </td>
                            </tr>
                            <tr>
                                <td class="mattable-important"><label for="KiEmailEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Email')}:</label></td>
                                <td><input id="KiEmailEdit" name="partneremail"></td>
                                <td><label for="KiTelefonEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Telefon')}:</label></td>
                                <td><input id="KiTelefonEdit" name="partnertelefon"></td>
                            </tr>
                            <tr>
                                <td><label for="KiMegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                                <td colspan="7"><textarea id="KiMegjegyzesEdit" name="megjegyzes" rows="1" cols="50"></textarea></td>
                            </tr>
                            <tr>
                                <td><label for="KiSzovegEdit" title="{at('Írd be a saját szavaiddal')}">{at('Mire veszem ki')}:</label></td>
                                <td><input id="KiSzovegEdit" name="tetelszoveg" value=""></td>
                            </tr>
                            <tr>
                                <td class="mattable-important"><label for="KiJogcimEdit" title="{at('Milyen célból veszel ki pénzt')}">{at('Jogcím')}:</label></td>
                                <td>
                                    <select id="KiJogcimEdit" name="teteljogcim" class="mattable-important" required="required">
                                        <option value="">{at('válassz')}</option>
                                        {foreach $jogcimlist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="mattable-important"><label for="KiHivatkozottBizonylatEdit" title="{at('Ha pl. villanyszámlát fizetsz ki, írd ide a költségszámla számát vagy keresd ki')}">{at('Hivatkozott bizonylat')}:</label></td>
                                <td>
                                    <input id="KiHivatkozottBizonylatEdit" name="tetelhivatkozottbizonylat" value="">
                                    <a class="js-kihivatkozottbizonylatbutton">{at('Keresés')}</a>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="KiEsedekessegEdit" title="{at('A hivatkozott bizonylat fizetési határideje')}">{at('Esedékesség')}:</label></td>
                                <td><input id="KiEsedekessegEdit" name="tetelhivatkozottdatum" readonly></td>
                            </tr>
                            <tr>
                                <td><label for="KiOsszegEdit">{at('Összeg')}:</label></td>
                                <td><input id="KiOsszegEdit" name="tetelosszeg" type="number" required="required" step="any"></td>
                            </tr>
                            <tr>
                                <td>
                                    <input id="KiOKButton" type="submit" value="OK">
                                    <a id="KiCancelButton" href="#"><span>Mégsem</span></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">{at('Pénzt TESZEK BE')}</div>
            </div>
            <div class="mainboxinner">
                <form id="BepenztarForm" method="post" action="{$penztarformaction}">
                    <input id="BeIranyEdit" name="irany" value="1" type="hidden">
                    <input name="quick" value="1" type="hidden">
                    <input name="oper" value="add" type="hidden">
                    <table>
                        <tbody>
                        <tr>
                            <td class="mattable-important"><label for="KeltEdit">{at('Mikor')}:</label></td>
                            <td><input id="BeKeltEdit" name="kelt" type="text" size="12" data-datum="{$keltstr}" class="mattable-important" required="required"></td>
                        </tr>
                        <tr>
                            <td><label for="BePenztarEdit">{at('Hova')}:</label></td>
                            <td>
                                <select id="BePenztarEdit" name="penztar" required="required">
                                    <option value="">{at('válassz')}</option>
                                    {foreach $penztarlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="BePartnerEdit">{at('Kitől kaptam')}:</label></td>
                            <td colspan="3">
                                <select id="BePartnerEdit" name="partner" class="mattable-important" required="required">
                                    <option value="">{at('válassz')}</option>
                                    <option value="-1">{at('Új felvitel')}</option>
                                    {foreach $partnerlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.nev} ({$_mk.email})</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="BePartnervezeteknevEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Vezetéknév')}:</td>
                            <td><input id="BePartnervezeteknevEdit" name="partnervezeteknev"></td>
                            <td><label for="BePartnerkeresztnevEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Keresztnév')}:</td>
                            <td colspan="3"><input id="BePartnerkeresztnevEdit" name="partnerkeresztnev"></td>
                        </tr>
                        <tr>
                            <td><label title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Cím')}:</label></td>
                            <td colspan="7">
                                <input id="BePartnerirszamEdit" name="partnerirszam" size="6" maxlength="10">
                                <input id="BePartnervarosEdit" name="partnervaros" size="20" maxlength="40">
                                <input id="BePartnerutcaEdit" name="partnerutca" size="40" maxlength="60">
                            </td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="BeEmailEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Email')}:</label></td>
                            <td><input id="BeEmailEdit" name="partneremail"></td>
                            <td><label for="BeTelefonEdit" title="{at('Akkor töltsd ki, ha új partnert viszel fel vagy változtatnál a partner adatain')}">{at('Telefon')}:</label></td>
                            <td><input id="BeTelefonEdit" name="partnertelefon"></td>
                        </tr>
                        <tr>
                            <td><label for="BeMegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                            <td colspan="7"><textarea id="BeMegjegyzesEdit" name="megjegyzes" rows="1" cols="50"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="BeSzovegEdit" title="{at('Írd be a saját szavaiddal')}">{at('Mire kaptam')}:</label></td>
                            <td><input id="BeSzovegEdit" name="tetelszoveg" value=""></td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="BeJogcimEdit" title="{at('Milyen célból teszel be pénzt')}">{at('Jogcím')}:</label></td>
                            <td>
                                <select id="BeJogcimEdit" name="teteljogcim" class="mattable-important" required="required">
                                    <option value="">{at('válassz')}</option>
                                    {foreach $jogcimlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="BeHivatkozottBizonylatEdit" title="{at('Ha pl. terembérletet teszel be, a számla vagy egyéb mozgás számát írd ide vagy keresd ki')}">{at('Hivatkozott bizonylat')}:</label></td>
                            <td>
                                <input id="BeHivatkozottBizonylatEdit" name="tetelhivatkozottbizonylat" value="">
                                <a class="js-behivatkozottbizonylatbutton">{at('Keresés')}</a>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="BeEsedekessegEdit" title="{at('A hivatkozott bizonylat fizetési határideje')}">{at('Esedékesség')}:</label></td>
                            <td><input id="BeEsedekessegEdit" name="tetelhivatkozottdatum" readonly></td>
                        </tr>
                        <tr>
                            <td><label for="BeOsszegEdit">{at('Összeg')}:</label></td>
                            <td><input id="BeOsszegEdit" name="tetelosszeg" type="number" required="required" step="any"></td>
                        </tr>
                        <tr>
                            <td>
                                <input id="BeOKButton" type="submit" value="OK">
                                <a id="BeCancelButton" href="#"><span>Mégsem</span></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class="clearboth">
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Címletező</div>
            </div>
            <div class="mainboxinner">
                <label for="CimletezoEdit">Írd ide a címletezni kívánt összegeket vesszővel elválasztva:</label>
                <input id="CimletezoEdit" type="text" name="osszegek">
                <a id="CimletezoButton" href="#"><span>Címletez</span></a>
                <table id="CimletezoEredmeny">
                </table>
            </div>
        </div>
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Bérlet lejárat kalkulátor</div>
            </div>
            <div class="mainboxinner">
                <label for="BLKVasarlasDatumEdit">{at('Vásárlás dátuma')}:</label>
                <input id="BLKVasarlasDatumEdit" type="text" name="blkvasarlasdatum" size="12" data-datum="">
                <label for="BLKBerletTipusEdit">Bérlet típusa:</label>
                <select id="BLKBerletTipusEdit" name="blkervenyesseg">
                    <option value="0">válassz</option>
                    <option value="6">4 alkalmas</option>
                    <option value="16">10 alkalmas</option>
                    <option value="8">Nyugdíjas 5 alkalmas</option>
                </select>
                <a id="BLKButton" href="#"><span>Számol</span></a>
                <div id="BLKEredmeny">
                </div>
            </div>
        </div>
    </div>
{/block}