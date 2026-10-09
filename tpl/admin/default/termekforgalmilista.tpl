{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/termekforgalmilista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
            <h3>{at('Termékforgalmi lista')}</h3>
        </div>
        <form id="termekforgalmi" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {mezocsoport cim="Időszak"}
                    {include "comp_idoszak.tpl" comptype="szamla" mezo=true}
                    <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                    {include "comp_partnerselect.tpl" mezo=true}
                    {include "comp_partnertipusselect.tpl" mezo=true ujsor=true}
                    {include "comp_webshopfilter.tpl" mezo=true}
                    {mezo cimke="Raktár" for="RaktarEdit" ujsor=true}
                        <select id="RaktarEdit" name="raktar">
                            <option value="0">{at('válasszon')}</option>
                            {foreach $raktarlista as $raktar}
                                <option value="{$raktar.id}">{$raktar.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {include "comp_gyartoselect.tpl" mezo=true}
                    {mezo cimke="Termék" for="NevEdit" ujsor=true}
                        <input id="NevEdit" type="text" name="nevfilter">
                    {/mezo}
                    {if ($setup.multilang)}
                        {include "comp_nyelvselect.tpl" mezo=true}
                    {/if}
                    {mezo cimke="Készlet" for="KeszletEdit" ujsor=true}
                        <select id="KeszletEdit" name="keszletfilter">
                            <option value="0">{at('mindegy')}</option>
                            <option value="1">{at('ami időszak végén van')}</option>
                            <option value="2">{at('ami időszak végén nincs')}</option>
                            <option value="3">{at('ami időszak végén negatív')}</option>
                        </select>
                    {/mezo}
                    {mezo cimke="Forgalom" for="ForgalomEdit"}
                        <select id="ForgalomEdit" name="forgalomfilter">
                            <option value="0">{at('mindegy')}</option>
                            <option value="1">{at('ami az időszakban mozgott')}</option>
                            <option value="2">{at('ami az időszakban nem mozgott')}</option>
                        </select>
                    {/mezo}
                    {include "comp_partnercimkefilter.tpl" mezo=true}
                    {include "comp_termekfa.tpl" mezo=true}
                    <div class="mattkarb-szakaszcim">{at('Megjelenítés')}</div>
                    {mezo cimke="Érték" for="ErtekEdit" ujsor=true}
                        <select id="ErtekEdit" name="ertektipus">
                            <option value="0">{at('nincs')}</option>
                            <option value="1">{at('bizonylaton szereplő nettó')}</option>
                            <option value="2">{at('bizonylaton szereplő bruttó')}</option>
                            <option value="3">{at('bizonylaton szereplő nettó HUF')}</option>
                            <option value="4">{at('bizonylaton szereplő bruttó HUF')}</option>
                            {if ($setup.arsavok)}
                                <option value="5">{at('választott ársáv nettó')}</option>
                                <option value="6">{at('választott ársáv bruttó')}</option>
                            {else}
                                <option value="7">{at('eladási ár nettó')}</option>
                                <option value="8">{at('eladási ár bruttó')}</option>
                            {/if}
                        </select>
                    {/mezo}
                    {if ($setup.arsavok)}
                        {include "comp_arsavselect.tpl" mezo=true}
                    {/if}
                {/mezocsoport}
                <input id="FaFilter" type="hidden" name="fafilter[]">
                <input id="PartnerCimkeFilter" type="hidden" name="partnercimkefilter[]">
                <div class="arsav-gombok">
                    <a href="#" class="js-refresh">{at('Frissít')}</a>
                    <a href="/admin/termekforgalmilista/export" class="js-exportbutton">{at('Export')}</a>
                </div>
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}