{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/szabadsagkimutatas.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Szabadság kimutatás')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Szabadság kimutatás')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="szabadsagkimutatas" action="" target="_blank">
                    {mezocsoport cim="Időszak"}
                        {include "comp_idoszak.tpl" comptype="datum" mezo=true}
                        <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                        {include "comp_dolgozoselect.tpl" mezo=true ujsor=true}
                        {mezo szeles=true}
                            <span class="mattkarb-megjegyzes">{at('Dolgozó nélkül minden aktív dolgozó rákerül.')}</span>
                        {/mezo}
                    {/mezocsoport}
                    <div class="arsav-gombok">
                        <a href="/admin/szabadsagkimutatas/get" class="js-okbutton">{at('OK')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}
