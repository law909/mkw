{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/chartjs/chart.umd.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/chartvaluelabels.js"></script>
    <script type="text/javascript" src="/js/admin/default/reportchart.js"></script>
    <script type="text/javascript" src="/js/admin/default/reportgrouping.js"></script>
    <script type="text/javascript" src="/js/admin/default/berkimutatas.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-baseurl="/admin/berkimutatas">
            <h3>{at('Bér kimutatás')}</h3>
        </div>
        <form id="berkimutatas" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {mezocsoport cim="Időszak"}
                    {include "comp_idoszak.tpl" comptype="datum" mezo=true}
                    <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                    {include "comp_dolgozoselect.tpl" mezo=true ujsor=true}
                    <div class="mattkarb-szakaszcim">{at('Megjelenítés')}</div>
                    {include "comp_csoportositas.tpl" alap2="dolgozo"}
                {/mezocsoport}
                <div class="arsav-gombok">
                    <a href="#" class="js-refresh">{at('Frissít')}</a>
                    <a href="/admin/berkimutatas/export" class="js-exportbutton">{at('Export')}</a>
                    <a href="#" class="js-pdfbutton">{at('PDF')}</a>
                </div>
                <div id="berkimutatasnote" class="arbevetel-chartnote"></div>
                <div class="arbevetel-chart"><canvas id="berkimutataschart"></canvas></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
