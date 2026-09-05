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
                    <div>
                        <label for="DatumEdit">{at('Dátum')}:</label>
                        <input id="DatumEdit" name="datum" data-datum="{$datum}">
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_raktarselect.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="KeszletEdit">{at('Készlet')}:</label>
                        <select id="KeszletEdit" name="keszlet">
                            <option value="2">{at('ami van')}</option>
                            <option value="1">{at('minden')}</option>
                            <option value="3">{at('fedezetlen')}</option>
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="NevEdit">{at('Termék')}:</label>
                        <input id="NevEdit" type="text" name="nevfilter">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="CsakBecsultEdit">{at('Csak becsült árat tartalmazó')}:</label>
                        <input id="CsakBecsultEdit" type="checkbox" name="csakbecsult">
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_termekfa.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <input type="hidden" name="fafilter">
                        <a href="/admin/keszletertek/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/keszletertek/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
                <div class="matt-hseparator"></div>
                <div>
                    <span id="fifoszamitva">
                        {if $utolsoszamitas}{at('Utolsó számítás')}: {$utolsoszamitas}{else}{at('Még nem futott számítás.')}{/if}
                    </span>
                </div>
                <div>
                    <a href="/admin/keszletertek/recalc" class="js-recalcbutton">{at('Teljes újraszámolás')}</a>
                </div>
                <div id="fifoeredmeny"></div>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}
