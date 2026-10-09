{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/kintlevoseglista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
            <h3>{at('Pénzügyi lista')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Pénzügyi lista')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="kintlevoseg" action="" target="_blank">
                    {mezocsoport cim="Időszak"}
                        {include "comp_idoszak.tpl" mezo=true}
                        {mezo cimke="Befizetések" for="BefEdit" szeles=true}
                            <div class="mattkarb-mezogomb kimutatas-idoszak">
                                <input id="BefEdit" name="befdatum" data-datum="{$toldatum}">
                                <span>{at('-ig kell figyelembe venni.')}</span>
                            </div>
                        {/mezo}
                        <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                        {mezo cimke="Lejárat" for="LejartFilterEdit" ujsor=true}
                            <select id="LejartFilterEdit" name="lejartfilter">
                                <option value="1">{at('mind')}</option>
                                <option value="2">{at('lejárt')}</option>
                                <option value="3">{at('nem lejárt')}</option>
                            </select>
                        {/mezo}
                        {mezo cimke="Nézet" for="EgyenlegFilterEdit"}
                            <select id="EgyenlegFilterEdit" name="egyenlegfilter">
                                <option value="1">{at('egyenleg')}</option>
                                <option value="2">{at('csak kintlevőség')}</option>
                                <option value="3">{at('csak tartozás')}</option>
                            </select>
                        {/mezo}
                        {include "comp_partnerselect.tpl" mezo=true}
                        {include "comp_dolgozoselect.tpl" mezo=true ujsor=true}
                        {include "comp_uzletkotoselect.tpl" mezo=true}
                        {include "comp_fizmodselect.tpl" mezo=true ujsor=true}
                        {include "comp_partnercimkefilter.tpl" mezo=true}
                        <div class="mattkarb-szakaszcim">{at('Megjelenítés')}</div>
                        {mezo cimke="Sorrend" for="SorrendEdit" ujsor=true}
                            <select id="SorrendEdit" name="sorrend">
                                <option value="1">{at('kelt')}</option>
                                <option value="2">{at('esedékesség')}</option>
                            </select>
                        {/mezo}
                        {mezo cimke="Részletes összesítő" for="ReszletesSumCB"}
                            <input id="ReszletesSumCB" name="reszletessum" type="checkbox">
                        {/mezo}
                    {/mezocsoport}
                    <div class="arsav-gombok">
                        <a href="/admin/kintlevoseglista/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/kintlevoseglista/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}