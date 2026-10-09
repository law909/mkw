{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/penzbelista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
            <h3>{at('Beérkezett pénz')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Beérkezett pénz')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="penzbe" action="" target="_blank">
                    {mezocsoport cim="Időszak"}
                        {include "comp_idoszak.tpl" comptype="datum" mezo=true}
                        <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                        {include "comp_bankszamlaselect.tpl" mezo=true ujsor=true}
                        {include "comp_partnerselect.tpl" mezo=true}
                        {include "comp_partnercimkefilter.tpl" mezo=true}
                    {/mezocsoport}
                    <div class="arsav-gombok">
                        <a href="/admin/penzbelista/get" class="js-okbutton">{at('OK')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}