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
                        <label for="LimitnumEdit">{at('Teszt limit (0 = az egész katalógus)')}:</label>
                        <input id="LimitnumEdit" name="limitnum" type="number" value="0" min="0">
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
                        <label for="EditleirasEdit">{at('Leírás és rövid leírás felülírása')}:</label>
                        <input id="EditleirasEdit" name="editleiras" type="checkbox">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="EditseoEdit">{at('SEO mezők és kép ALT felülírása')}:</label>
                        <input id="EditseoEdit" name="editseo" type="checkbox">
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
                        <label for="RiportujraEdit">{at('Riport újrakezdése')}:</label>
                        <input id="RiportujraEdit" name="riportujra" type="checkbox">
                        <span>{at('Ugyanazon a fájlon nulláról indítja a riportot és a lista-CSV-ket, a megadott sortól folytatva.')}</span>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div class="admin-form-footer">
                        <input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" value="{at('Import indítása')}">
                    </div>
                </form>

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
