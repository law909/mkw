{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/rendbevlista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
            <h3>{at('Rendelt / beérkezett kimutatás')}</h3>
        </div>
        <form id="mattkarb-form" action="" method="post">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {mezocsoport cim="Időszak"}
                    {include "comp_idoszak.tpl" comptype="datum" mezo=true}
                    {mezo szeles=true}
                        <span class="mattkarb-megjegyzes">Az időszak a szállítói megrendelésre vonatkozik. Minden hozzá kapcsolt bevételezés a listán lesz a teljesítésétől függetlenül.</span>
                    {/mezo}
                    <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                    {include "comp_partnerselect.tpl" mezo=true}
                {/mezocsoport}
                <div class="arsav-gombok">
                    <a href="#" class="js-refresh">{at('Frissít')}</a>
                    <a href="/admin/rendbevlista/export" class="js-exportbutton">{at('Export')}</a>
                </div>
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
