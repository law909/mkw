{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/keszletertek.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Készletérték')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Készletérték')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="keszletertek" action="" target="_blank">
                    {mezocsoport cim="Dátum és raktár"}
                        {include "comp_datum.tpl" mezo=true ujsor=true}
                        {include "comp_raktarselect.tpl" mezo=true}
                        <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                        {mezo cimke="Készlet" for="KeszletEdit" ujsor=true}
                            <select id="KeszletEdit" name="keszlet">
                                <option value="2">{at('ami van')}</option>
                                <option value="1">{at('minden')}</option>
                                <option value="3">{at('fedezetlen')}</option>
                            </select>
                        {/mezo}
                        {mezo cimke="Termék" for="NevEdit"}
                            <input id="NevEdit" type="text" name="nevfilter">
                        {/mezo}
                        {mezo cimke="Csak becsült árat tartalmazó" for="CsakBecsultEdit" ujsor=true}
                            <input id="CsakBecsultEdit" type="checkbox" name="csakbecsult">
                        {/mezo}
                        {include "comp_termekfa.tpl" mezo=true}
                    {/mezocsoport}
                    <input type="hidden" name="fafilter">
                    <div class="arsav-gombok">
                        <a href="/admin/keszletertek/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/keszletertek/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
                <div class="mattkarb-szakaszcim">{at('FIFO készletérték számítás')}</div>
                <div>
                    <span id="fifoszamitva">
                        {if $utolsoszamitas}{at('Utolsó számítás')}: {$utolsoszamitas}{else}{at('Még nem futott számítás.')}{/if}
                    </span>
                </div>
                <div class="arsav-gombok">
                    <a href="/admin/keszletertek/recalc" class="js-recalcbutton">{at('Teljes újraszámolás')}</a>
                </div>
                <div id="fifoeredmeny"></div>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}
