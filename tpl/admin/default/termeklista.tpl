{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    <script type="text/javascript" src="/js/admin/default/termek.js"></script>
{/block}

{block "kozep"}
    <div id="mattable-select" data-theme="{$theme}">
        <div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Termékek')}"></div>
        <div id="mattable-filterwrapper" class="listaszuro">
            <div class="listaszuro-sor">
                <div class="listaszuro-mezo listaszuro-szeles">
                    <label for="nevfilter">{at('Név, cikkszám, vonalkód')}</label>
                    <input id="nevfilter" name="nevfilter" type="text" maxlength="255">
                </div>
                <div class="listaszuro-mezo">
                    <label for="idfilter">{at('Id')}</label>
                    <input id="idfilter" name="idfilter" type="text" size="10" maxlength="1000" placeholder="12, 34"
                           title="{at('Pontos egyezés, több termék vesszővel elválasztva')}">
                </div>
                <div class="listaszuro-mezo">
                    <label for="egyediazonositofilter">{at('Egyedi azonosító')}</label>
                    <input id="egyediazonositofilter" name="egyediazonositofilter" type="text" size="16" maxlength="255"
                           title="{at('Azok a termékek, amelyek bizonylatán (tételben vagy munkalap fejben) szerepel')}">
                </div>
                <div class="listaszuro-mezo">
                    <label for="kepurlfilter">{at('Főkép url')}</label>
                    <input id="kepurlfilter" name="kepurlfilter" type="text" size="16" maxlength="255">
                </div>
                <div class="listaszuro-mezo">
                    <label for="gyartofilter">{at('Gyártó')}</label>
                    <select id="gyartofilter" name="gyartofilter">
                        <option value="">{at('válasszon')}</option>
                        {foreach $gyartolist as $_gyarto}
                            <option value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="listaszuro-sor">
                <div class="listaszuro-mezo">
                    <label for="lathatofilter">{at('Látható')} {$webshop1name}</label>
                    <select id="lathatofilter" name="lathatofilter">
                        {if ($maintheme == 'mkwcansas')}
                            <option value="1">{at('Igen')}</option>
                            <option value="0">{at('Nem')}</option>
                            <option value="9">{at('Mindegy')}</option>
                        {else}
                            <option value="9">{at('Mindegy')}</option>
                            <option value="1">{at('Igen')}</option>
                            <option value="0">{at('Nem')}</option>
                        {/if}
                    </select>
                </div>
                {if ($setup.multishop)}
                    {for $cikl = 2 to $enabledwebshops}
                        <div class="listaszuro-mezo">
                            <label for="lathato{$cikl}filter">{at('Látható')} {$webshop{$cikl}name}</label>
                            <select id="lathato{$cikl}filter" name="lathato{$cikl}filter">
                                <option value="9">{at('Mindegy')}</option>
                                <option value="1">{at('Igen')}</option>
                                <option value="0">{at('Nem')}</option>
                            </select>
                        </div>
                    {/for}
                {/if}
                <div class="listaszuro-mezo">
                    <label for="inaktivfilter">{at('Aktív')}</label>
                    <select id="inaktivfilter" name="inaktivfilter">
                        <option value="0">{at('Igen')}</option>
                        <option value="1">{at('Nem')}</option>
                        <option value="9">{at('Mindegy')}</option>
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="nemkaphatofilter">{at('Kapható')}</label>
                    <select id="nemkaphatofilter" name="nemkaphatofilter">
                        <option value="9">{at('Mindegy')}</option>
                        <option value="0">{at('Igen')}</option>
                        <option value="1">{at('Nem')}</option>
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="fuggobenfilter">{at('Függőben')}</label>
                    <select id="fuggobenfilter" name="fuggobenfilter">
                        <option value="9">{at('Mindegy')}</option>
                        <option value="1">{at('Igen')}</option>
                        <option value="0">{at('Nem')}</option>
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="akciosfilter">{at('Akciós')}</label>
                    <select id="akciosfilter" name="akciosfilter">
                        <option value="9">{at('Mindegy')}</option>
                        <option value="1">{at('Igen')}</option>
                        <option value="0">{at('Nem')}</option>
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="ajanlottfilter">{at('Ajánlott')}</label>
                    <select id="ajanlottfilter" name="ajanlottfilter">
                        <option value="9">{at('Mindegy')}</option>
                        <option value="1">{at('Igen')}</option>
                        <option value="0">{at('Nem')}</option>
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="kiemeltfilter">{at('Kiemelt')}</label>
                    <select id="kiemeltfilter" name="kiemeltfilter">
                        <option value="9">{at('Mindegy')}</option>
                        <option value="1">{at('Igen')}</option>
                        <option value="0">{at('Nem')}</option>
                    </select>
                </div>
                <div class="listaszuro-mezo">
                    <label for="keszletfilter">{at('Készlet')}</label>
                    <span class="listaszuro-csoport">
                        <select id="keszletmezofilter" name="keszletmezofilter" title="{at('Melyik készletre szűrjön')}">
                            <option value="keszlet">{at('Készlet')}</option>
                            <option value="szabad">{at('Szabad készlet')}</option>
                            <option value="foglalt">{at('Foglalt')}</option>
                            <option value="erkezik">{at('Érkezik')}</option>
                        </select>
                        <select id="keszletfilter" name="keszletfilter">
                            <option value="">{at('Mindegy')}</option>
                            <option value="van">{at('Van')}</option>
                            <option value="nulla">{at('Nulla')}</option>
                            <option value="negativ">{at('Negatív')}</option>
                        </select>
                        <select id="keszletraktarfilter" name="keszletraktarfilter" title="{at('Raktár')}">
                            <option value="0">{at('Céges szint')}</option>
                            {foreach $raktarlist as $_r}
                                <option value="{$_r.id}">{$_r.caption}</option>
                            {/foreach}
                        </select>
                    </span>
                </div>
                <div class="listaszuro-mezo">
                    <label for="cimkefilternincs">{at('Címkék')}</label>
                    <select id="cimkefilternincs" name="cimkefilternincs">
                        <option value="">{at('van valamelyik kiválasztott címkéje')}</option>
                        <option value="1">{at('nincs egyik kiválasztott címkéje sem')}</option>
                    </select>
                </div>
            </div>
            <div class="listaszuro-fak">
                <div id="termekfa" class="mattable-filterwrapper ui-widget-content"></div>
                <div>
                    {if (count($termekmenufalist) > 1)}
                        <div class="listaszuro-mezo listaszuro-menufa">
                            <label for="TermekMenuFaFilterEdit">{at('Menü')}</label>
                            <select id="TermekMenuFaFilterEdit" class="js-termekmenufafilter">
                                {foreach $termekmenufalist as $_fa}
                                    <option value="{$_fa.id}">{$_fa.caption|escape}</option>
                                {/foreach}
                            </select>
                        </div>
                    {/if}
                    <div id="termekmenu" class="mattable-filterwrapper ui-widget-content"
                         data-url="/admin/termekmenu/jsonlist?fa={$termekmenufalist[0].id|default:''}"></div>
                </div>
            </div>
            <div id="cimkefiltercontainer">
                <div id="cimkefiltercontainerhead"><a id="cimkefiltercollapse" href="#"
                                                      data-visible="visible">{at('Kinyit/becsuk')}</a></div>
                {foreach $cimkekat as $_cimkekat}
                    <div class="mattedit-titlebar ui-widget-header ui-helper-clearfix js-cimkefiltercloseupbutton"
                         data-refcontrol="#{$_cimkekat.sanitizedcaption}">
                        <a href="#" class="mattedit-titlebar-close">
                            <span class="ui-icon ui-icon-circle-triangle-n"></span>
                        </a>
                        <span>{$_cimkekat.caption}</span>
                    </div>
                    <div id="{$_cimkekat.sanitizedcaption}" class="accordpage cimkelista" data-visible="visible">
                        {foreach $_cimkekat.cimkek as $_cimke}
                            <a class="js-cimkefilter" href="#" data-id="{$_cimke.id}">{$_cimke.caption}</a>
                            &nbsp;&nbsp;
                        {/foreach}
                    </div>
                {/foreach}
            </div>
        </div>
        <div class="mattable-pagerwrapper">
            <div class="mattable-order">
                <label for="tos1">{at('Rendezés')}</label>
                <select id="tos1" class="mattable-orderselect">
                    {foreach $orderselect as $_os}
                        <option value="{$_os.id}"{if ($_os.selected)} selected="selected"{/if}>{$_os.caption}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="mattable-batch">
            {at('Csoportos művelet')} <select class="mattable-batchselect">
                <option value="">{at('válasszon')}</option>
                {foreach $batchesselect as $_batch}
                    <option value="{$_batch.id}">{$_batch.caption}</option>
                {/foreach}
            </select>
            <a href="#" class="mattable-batchbtn">{at('Futtat')}</a>
            {if ($arazasimport|default:false)}
                <a href="#" class="js-arazasimport">{at('Árazás import')}</a>
            {/if}
        </div>
        <table id="mattable-table">
            <thead>
            <tr>
                <th><input class="js-maincheckbox" type="checkbox" autocomplete="off"></th>
                <th>{at('Termék')}</th>
                <th>{at('Adatok')}</th>
                <th>{at('Készlet')}</th>
                <th>{at('Jellemzők')}</th>
            </tr>
            </thead>
            <tbody id="mattable-body"></tbody>
        </table>
        <div class="mattable-pagerwrapper ui-corner-bottom">
            <div class="mattable-order">
                <label for="tos2">{at('Rendezés')}</label>
                <select id="tos2" class="mattable-orderselect">
                    {foreach $orderselect as $_os}
                        <option value="{$_os.id}"{if ($_os.selected)} selected="selected"{/if}>{$_os.caption}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    </div>
    <div id="mattkarb"></div>
    <div id="termekfakarb"></div>
    {if ($arazasimport|default:false)}
        <div id="arazasimport" class="hidden">
            <p>
                <label for="ArazasImportFile">{at('Importálandó fájl')}:</label>
                <input id="ArazasImportFile" type="file" accept=".xlsx,.xls">
            </p>
            <p>Az XLSX első sora a fejléc. A terméket a kod (termék ID), a vonalkod vagy a cikkszam oszlop azonosítja,
                ebben a sorrendben. Frissíthető: nev (nev_EN stb. más nyelvre), cikkszam, vonalkod, vtsz, és az árak
                netto_HUF_&lt;ársáv neve&gt; / brutto_HUF_&lt;ársáv neve&gt; alakú oszlopokból. A nem talált sor kimarad.</p>
        </div>
    {/if}
    <div id="cimkeset" class="hidden">
        <label>{at('Címke')}: </label>
        <select class="js-cimkeset">
            {include "../partials/termekcimke.options.tpl"}
        </select>
    </div>
{/block}