{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/minkeszletlista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Minimum készlet alatt')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Minimum készlet alatt')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="minkeszlet" action="" target="_blank">
                    {mezocsoport cim="Dátum és raktár"}
                        {include "comp_datum.tpl" mezo=true ujsor=true}
                        {mezo cimke="Raktár" for="RaktarEdit"}
                            <select id="RaktarEdit" name="raktar" class="mattable-important" required="required">
                                <option value="0">{at('Céges készlet')}</option>
                                {foreach $raktarlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        {/mezo}
                        {mezo cimke="Ebből a raktárból kiszolgálható" for="MasikRaktarEdit" ujsor=true}
                            <select id="MasikRaktarEdit" name="masikraktar">
                                <option value="">{at('nem kell')}</option>
                                {foreach $masikraktarlist as $_mk}
                                    <option value="{$_mk.id}">{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        {/mezo}
                        <div class="mattkarb-szakaszcim">{at('Készletszint')}</div>
                        {mezo cimke="Készlet" for="KeszletEdit" szeles=true}
                            <div class="mattkarb-mezogomb">
                                <input id="KeszletEdit" name="keszlet" type="number" step="any" size="8" value="0">
                                <span class="mattkarb-pipacimke">
                                    <input id="KeszletSzamitEdit" name="keszletszamit" type="checkbox" value="1"
                                           {if ($keszletszamit)}checked="checked"{/if}>
                                    <label for="KeszletSzamitEdit">{at('a minimum készlet helyett ezt figyelje')}</label>
                                </span>
                            </div>
                        {/mezo}
                        {mezo cimke="A készletet eddig töltsük fel" szeles=true}
                            <div class="mattkarb-pipak">
                                <span class="mattkarb-pipacimke">
                                    <input id="CelszintMinEdit" name="celszint" type="radio" value="min" checked="checked">
                                    <label for="CelszintMinEdit">{at('minimum készlet')}</label>
                                </span>
                                <span class="mattkarb-pipacimke">
                                    <input id="CelszintOptEdit" name="celszint" type="radio" value="opt">
                                    <label for="CelszintOptEdit">{at('optimális készlet')}</label>
                                </span>
                            </div>
                        {/mezo}
                        <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                        {mezo cimke="Gyártó" for="GyartoEdit" ujsor=true}
                            <select id="GyartoEdit" name="gyarto">
                                <option value="">{at('mindegy')}</option>
                                {foreach $gyartolist as $_gy}
                                    <option value="{$_gy.id}">{$_gy.caption}</option>
                                {/foreach}
                            </select>
                        {/mezo}
                        {include "comp_termekfa.tpl" mezo=true}
                    {/mezocsoport}
                    <input type="hidden" name="fafilter">
                    <div class="arsav-gombok">
                        <a href="/admin/minkeszletlista/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/minkeszletlista/export" class="js-exportbutton">{at('Export')}</a>
                        <a href="/admin/minkeszletlista/exportbizonylat" class="js-exportbizonylatbutton">{at('Export bizonylathoz')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}
