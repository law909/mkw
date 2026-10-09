{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/bizonylattetellista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
            <h3>{at('Bizonylattétel lista')}</h3>
        </div>
        <form id="bizonylattetel" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {mezocsoport cim="Időszak és bizonylat"}
                    {include "comp_idoszak.tpl" comptype="hataridos" mezo=true}
                    {include "comp_bizonylatstatusz.tpl" mezo=true ujsor=true}
                    {include "comp_bizonylatstatuszcsoport.tpl" mezo=true}
                    {include "comp_fizmodselect.tpl" mezo=true ujsor=true}
                    {mezo cimke="Raktár" for="RaktarEdit"}
                        <select id="RaktarEdit" name="raktar">
                            <option value="0">{at('válasszon')}</option>
                            {foreach $raktarlista as $raktar}
                                <option value="{$raktar.id}">{$raktar.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {include "comp_webshopfilter.tpl" mezo=true ujsor=true}
                    {include "comp_bizonylattipus.tpl" mezo=true hint=at('Ha egy sincs bejelölve: mind.')}

                    <div class="mattkarb-szakaszcim">{at('Partner')}</div>
                    {include "comp_partnerselect.tpl" mezo=true}
                    {include "comp_partnertipusselect.tpl" mezo=true ujsor=true}
                    {include "comp_uzletkotoselect.tpl" mezo=true}
                    {include "comp_partnercimkefilter.tpl" mezo=true}

                    <div class="mattkarb-szakaszcim">{at('Termék')}</div>
                    {mezo cimke="Termék" for="NevEdit" ujsor=true}
                        <input id="NevEdit" type="text" name="nevfilter">
                    {/mezo}
                    {include "comp_gyartoselect.tpl" mezo=true}
                    {if ($setup.multilang)}
                        {include "comp_nyelvselect.tpl" mezo=true ujsor=true}
                    {/if}
                    {mezo cimke="Készlet kell" for="KeszletkellEdit" ujsor=true}
                        <input id="KeszletkellEdit" type="checkbox" name="keszletkell">
                    {/mezo}
                    {mezo cimke="Csak foglalás" for="CsakfoglalasEdit"}
                        <input id="CsakfoglalasEdit" type="checkbox" name="csakfoglalas">
                    {/mezo}
                    {include "comp_termekfa.tpl" mezo=true}

                    <div class="mattkarb-szakaszcim">{at('Megjelenítés')}</div>
                    {mezo cimke="Csoportosítás" for="CsoportositasEdit" ujsor=true}
                        <select id="CsoportositasEdit" name="csoportositas">
                            <option value="1">{at('termékenként')}</option>
                            <option value="2">{at('partnerenként/termékenként')}</option>
                            <option value="3">{at('üzletkötőnként/partnerenként')}</option>
                            <option value="4">{at('bizonylatonként')}</option>
                        </select>
                    {/mezo}
                    {mezo cimke="Érték" for="ErtekEdit"}
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
                        {include "comp_arsavselect.tpl" mezo=true ujsor=true}
                    {/if}
                {/mezocsoport}
                <div class="arsav-gombok">
                    <a href="#" class="js-refresh">{at('Frissít')}</a>
                    <a href="/admin/bizonylattetellista/export" class="js-exportbutton">{at('Export')}</a>
                    <a href="/admin/bizonylattetellista/print" class="js-print">{at('Nyomtat')}</a>
                </div>
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}