{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/chartjs/chart.umd.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/chartvaluelabels.js"></script>
    <script type="text/javascript" src="/js/admin/default/reportchart.js"></script>
    <script type="text/javascript" src="/js/admin/default/reportgrouping.js"></script>
    <script type="text/javascript" src="/js/admin/default/arbevetellista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}" data-baseurl="/admin/arbevetellista">
            <h3>{at('Árbevétel kimutatás')}</h3>
        </div>
        <form id="arbevetel" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {include "kimutatasszurok.tpl" baseurl="/admin/arbevetellista"
                    valutanemhint=at('Mindegy: minden bizonylat, forintra átszámolt értékkel. Választott valutanemnél csak az abban kiállított bizonylatok számítanak, a saját pénznemükben (pl. EUR-ban), átszámítás nélkül.')
                    bizonylattipushint=at('Ha egy sincs bejelölve: előlegszámla, számla, bolti eladás.')}
                <div id="arbevetelchartnote" class="arbevetel-chartnote"></div>
                <div class="arbevetel-chart"><canvas id="arbevetelchart"></canvas></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
