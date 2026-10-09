{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/bizomanyosertekesiteslista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
            <h3>{at('Bizományos értékesítés lista')}</h3>
        </div>
        <form id="mattkarb-form" action="" method="post">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {mezocsoport cim="Időszak"}
                    {include "comp_idoszak.tpl" comptype="szamla" mezo=true}
                    <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                    {include "comp_partnerselect.tpl" mezo=true}
                    {include "comp_partnercimkefilter.tpl" mezo=true}
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
                <div class="arsav-gombok">
                    <a href="#" class="js-refresh">{at('Frissít')}</a>
                </div>
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}