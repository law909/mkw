{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/keszletlista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Készlet')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Készlet')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="keszlet" action="" target="_blank">
                    {mezocsoport cim="Dátum és raktár"}
                        {include "comp_datum.tpl" mezo=true ujsor=true}
                        {include "comp_raktarselect.tpl" mezo=true}
                        <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                        {mezo cimke="Készlet" for="KeszletEdit" ujsor=true}
                            <select id="KeszletEdit" name="keszlet">
                                <option value="1">{at('minden')}</option>
                                <option value="2">{at('ami van')}</option>
                                <option value="3">{at('ami nincs')}</option>
                                <option value="4">{at('ami negatív')}</option>
                            </select>
                        {/mezo}
                        {mezo cimke="Termék" for="NevEdit"}
                            <input id="NevEdit" type="text" name="nevfilter">
                        {/mezo}
                        {include "comp_termekfa.tpl" mezo=true}
                        <div class="mattkarb-szakaszcim">{at('Megjelenítés')}</div>
                        {mezo cimke="Foglalás számít" for="FoglalasEdit" ujsor=true}
                            <input id="FoglalasEdit" type="checkbox" name="foglalasszamit">
                        {/mezo}
                        {mezo cimke="Minimum készlet számít" for="MinkeszletEdit"}
                            <input id="MinkeszletEdit" type="checkbox" name="minkeszletszamit">
                        {/mezo}
                        {include "comp_nyelvselect.tpl" mezo=true ujsor=true}
                        {mezo cimke="Ársáv" for="ArsavEdit"}
                            <div class="mattkarb-mezogomb">
                                <select id="ArsavEdit" name="arsav" class="mattable-important">
                                    <option value="">{at('mindegy')}</option>
                                    {foreach $arsavlist as $_mk}
                                        <option
                                            value="{$_mk.id}_{$_mk.valutanemid}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption} {$_mk.valutanem}</option>
                                    {/foreach}
                                </select>
                                <select id="NettoBruttoEdit" name="nettobrutto">
                                    <option value="netto">{at('nettó')}</option>
                                    <option value="brutto">{at('bruttó')}</option>
                                </select>
                            </div>
                        {/mezo}
                    {/mezocsoport}
                    <input type="hidden" name="fafilter">
                    <div class="arsav-gombok">
                        <a href="/admin/keszletlista/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/keszletlista/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}