{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/unastermekimport.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('UNAS termékimport')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Import')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {if ($figyelmeztetes)}
                    <div class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin-bottom:5px;">
                        {$figyelmeztetes}
                    </div>
                {/if}
                {if ($fut)}
                    <div class="matt-messagecenter ui-widget ui-state-highlight" style="padding:5px;margin-bottom:5px;">
                        {at('Jelenleg fut egy import. Ha biztosan elakadt, a zárolás feloldható.')}
                        <button type="button" id="unasstop" class="ui-button ui-widget ui-state-default ui-corner-all">{at('Zárolás feloldása')}</button>
                    </div>
                {/if}

                <div>
                    <button type="button" id="unasteszt" class="ui-button ui-widget ui-state-default ui-corner-all">{at('Kapcsolat teszt')}</button>
                    <span id="unastesztvalasz"></span>
                </div>
                <div class="matt-hseparator"></div>

                <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                    <form id="unasgetproduct" method="post" action="/admin/unastermekimport/getproduct">
                        <div>
                            <label for="GetproductCikkszamEdit">{at('Termék lekérdezése (getProduct)')}:</label>
                            <input id="GetproductCikkszamEdit" name="cikkszam" type="text" placeholder="{at('cikkszám')}" style="width:14em;">
                            <input id="GetproductUnasidEdit" name="unasid" type="text" placeholder="{at('UNAS azonosító')}" style="width:14em;">
                            <select id="GetproductContenttypeEdit" name="contenttype">
                                <option value="full">full</option>
                                <option value="normal">normal</option>
                                <option value="short">short</option>
                                <option value="minimal">minimal</option>
                            </select>
                            <select id="GetproductStateEdit" name="state">
                                <option value="live">{at('létező')}</option>
                                <option value="deleted">{at('törölt')}</option>
                            </select>
                            <button type="submit" class="ui-button ui-widget ui-state-default ui-corner-all">{at('Lekérdezés')}</button>
                            <span id="unasgetproductvalasz"></span>
                        </div>
                        <div>
                            <span>{at('Elég az egyik mező, és ha az UNAS azonosító ki van töltve, a cikkszám nem számít (az UNAS figyelmen kívül hagyja). Cikkszámból vesszővel több is megadható, de a több termékes hívásból PREMIUM csomagon óránként csak 30 van; UNAS azonosítóból egyszerre csak egy kérdezhető le. Semmit nem ír, csak megmutatja, mit ad az UNAS – például van-e a terméknek változata.')}</span>
                        </div>
                    </form>
                    <div id="unasgetproducteredmeny"></div>
                </div>
                <div class="matt-hseparator"></div>

                <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                    <form id="unastermekimport" method="post" action="/admin/unastermekimport/letoltes"
                          data-kotegurl="/admin/unastermekimport/koteg"
                          data-riporturl="/admin/unastermekimport/riport"
                          data-hibauzenet="{at('A feldolgozás nem fejeződött be. Nézze meg a hibanaplót, majd próbálja újra.')}">
                        <div>
                            <label for="SzarazfutasEdit">{at('Száraz futás (csak párosítás, semmit nem ír)')}:</label>
                            <input id="SzarazfutasEdit" name="szarazfutas" type="checkbox" checked="checked">
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="LimitnumEdit">{at('Lekért termékek darabszáma')}:</label>
                            <input id="LimitnumEdit" name="limitnum" type="number" value="0" min="0">
                            <span>{at('0 = az egész katalógus')}</span>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="UnasidkihagyEdit">{at('Az UNAS azonosító alapján megtalált tételeket hagyja békén')}:</label>
                            <input id="UnasidkihagyEdit" name="unasidkihagy" type="checkbox" checked="checked">
                            <span>{at('Amit egy korábbi menet már párosított, azon nem ír mezőt, nem párosít változatot és nem tölt képet. Kikapcsolva a már ismert termékek adatai is frissülnek – inkrementális menetben ezért magától kikapcsol.')}</span>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="InkrementalisEdit">{at('Inkrementális (csak a legutóbbi import óta módosultak)')}:</label>
                            <input id="InkrementalisEdit" name="inkrementalis" type="checkbox">
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="UjraletoltesEdit">{at('Fájl újraletöltése az UNAS-tól')}:</label>
                            <input id="UjraletoltesEdit" name="ujraletoltes" type="checkbox">
                            <span>
                            {if ($utolsoletoltes)}
                                {at('Utolsó letöltés')}: {$utolsoletoltes.ido} ({$utolsoletoltes.fajl}).
                            {/if}
                        </span>
                        </div>
                        <div class="matt-hseparator"></div>
                        {if ($multilang)}
                            <div>
                                <label for="NyelvsuffixEdit">{at('Nyelv')}:</label>
                                <select id="NyelvsuffixEdit" name="nyelvsuffix">
                                    <option value="">{at('alapmezők')} ({$nyelv})</option>
                                    <option value="_l1">_l1 {at('mezők')} ({$nyelvl1})</option>
                                </select>
                            </div>
                            <div class="matt-hseparator"></div>
                        {else}
                            <input type="hidden" name="nyelvsuffix" value="">
                        {/if}
                        <div>
                            <label for="EditmezokEdit">{at('Szöveges mezők felülírása')}:</label>
                            <input id="EditmezokEdit" name="editmezok" type="checkbox">
                            <span>{at('Leírás, rövid leírás, SEO mezők és a kép ALT. Üres UNAS érték egyiket sem törli.')}</span>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="KepekEdit">{at('Képek letöltése')}:</label>
                            <input id="KepekEdit" name="kepek" type="checkbox">
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="KepekujraEdit">{at('Meglévő képek újratöltése')}:</label>
                            <input id="KepekujraEdit" name="kepekujra" type="checkbox">
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="KepforrasEdit">{at('Képek forrása')}:</label>
                            <select id="KepforrasEdit" name="kepforras">
                                <option value="auto">{at('automatikus (a fejléc alapján)')}</option>
                                <option value="csv">{at('a termékadatbázis "Kép link" oszlopa')}</option>
                                <option value="getproduct">getProduct</option>
                            </select>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label for="SortolEdit">{at('Sorok tól-ig (0-0 = az egész fájl egyben)')}:</label>
                            <input id="SortolEdit" name="sortol" type="number" value="1" min="0" style="width:6em;">
                            &ndash;
                            <input id="SorigEdit" name="sorig" type="number" value="250" min="0" style="width:6em;">
                            <span>{at('A futás végén a mezők a következő szakaszra ugranak, és az UNAS-tól nem kérünk új fájlt.')}</span>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label>{at('Riportok')}:</label>
                            <button type="button" id="unasriporttorles" class="ui-button ui-widget ui-state-default ui-corner-all"
                                    data-kerdes="{at('Biztosan törli a riportokat és a lista-CSV-ket?')}">{at('Riportok törlése')}</button>
                            <span
                                id="unasriporttorlesvalasz">{at('A következő menet nulláról kezdi a riportot. A letöltött termékadatbázis megmarad, a félbehagyott sorablakos menet folytatható.')}</span>
                        </div>
                        <div class="matt-hseparator"></div>
                        <div class="admin-form-footer">
                            <input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" value="{at('Import indítása')}">
                        </div>
                    </form>
                </div>
                <div id="unashaladas" style="display:none;margin:5px 0;">
                    <div id="unashaladasszoveg"></div>
                    <div class="ui-widget ui-widget-content ui-corner-all" style="height:16px;">
                        <div id="unashaladascsik" class="ui-widget-header ui-corner-all" style="height:16px;width:0;"></div>
                    </div>
                </div>

                <div id="unaseredmeny"></div>
            </div>
        </div>
    </div>
{/block}
