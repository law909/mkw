{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/tanarelszamolas.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Tanár elszámolás')}</h3>
        </div>
        <form id="tanarelszamolas" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {mezocsoport cim="Időszak"}
                    {include "comp_idoszak.tpl" comptype="datum" mezo=true}
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