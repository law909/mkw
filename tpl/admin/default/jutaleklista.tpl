{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/chartjs/chart.umd.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/chartvaluelabels.js"></script>
    <script type="text/javascript" src="/js/admin/default/reportchart.js"></script>
    <script type="text/javascript" src="/js/admin/default/reportgrouping.js"></script>
    <script type="text/javascript" src="/js/admin/default/jutaleklista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-baseurl="/admin/jutaleklista" data-decimals="2">
            <h3>{at('Jutalék elszámolás')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Jutalék elszámolás')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="jutalek" action="" target="_blank">
                    {mezocsoport cim="Időszak"}
                        {include "comp_idoszak.tpl" comptype="datum" mezo=true}
                        <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
                        {include "comp_uzletkotoselect.tpl" mezo=true ujsor=true}
                        {if (haveJog(90))}
                            {mezo cimke="Belső üzletkötő elszámolás" for="BelsoEdit"}
                                <input id="BelsoEdit" type="checkbox" name="belso">
                            {/mezo}
                        {/if}
                        {include "comp_partnercimkefilter.tpl" mezo=true}
                        <div class="mattkarb-szakaszcim">{at('Megjelenítés')}</div>
                        {include "comp_csoportositas.tpl" alap2="uzletkoto"}
                    {/mezocsoport}
                    <div class="arsav-gombok">
                        <a href="#" class="js-refresh">{at('Frissít')}</a>
                        <a href="/admin/jutaleklista/get" class="js-okbutton">{at('Részletes lista')}</a>
                        <a href="/admin/jutaleklista/export" class="js-exportbutton">{at('Export')}</a>
                        <a href="#" class="js-pdfbutton">{at('PDF')}</a>
                    </div>
                    <div id="jutalekchartnote" class="arbevetel-chartnote"></div>
                    <div class="arbevetel-chart"><canvas id="jutalekchart"></canvas></div>
                    <div id="eredmeny"></div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}