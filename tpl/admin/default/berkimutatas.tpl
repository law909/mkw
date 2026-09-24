{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/chartjs/chart.umd.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/chartvaluelabels.js"></script>
    <script type="text/javascript" src="/js/admin/default/reportchart.js"></script>
    <script type="text/javascript" src="/js/admin/default/berkimutatas.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Bér kimutatás')}</h3>
        </div>
        <form id="berkimutatas" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div class="matt-hseparator"></div>
                {include "comp_idoszak.tpl" comptype="datum"}
                <div class="matt-hseparator"></div>
                {include "comp_dolgozoselect.tpl"}
                <div class="matt-hseparator"></div>
                <div class="arbevetel-grouping ui-widget ui-widget-content ui-corner-all">
                    <label for="IdoszakcsoportEdit">{at('Csoportosítás')}:</label>
                    <select id="IdoszakcsoportEdit" name="idoszakcsoport">
                        <option value="">{at('időszak nélkül')}</option>
                        <option value="ev">{at('évente')}</option>
                        <option value="honap" selected="selected">{at('havonta')}</option>
                    </select>
                    <input id="DolgozocsoportEdit" type="checkbox" name="dolgozocsoport" checked="checked">
                    <label for="DolgozocsoportEdit">{at('dolgozónként')}</label>
                    <input id="BerjogcimcsoportEdit" type="checkbox" name="berjogcimcsoport">
                    <label for="BerjogcimcsoportEdit">{at('jogcímenként')}</label>
                </div>
                <div class="matt-hseparator"></div>
                <a href="#" class="js-refresh">{at('Frissít')}</a>
                <a href="/admin/berkimutatas/export" class="js-exportbutton">{at('Export')}</a>
                <div class="matt-hseparator"></div>
                <div id="berkimutatasnote" class="arbevetel-chartnote"></div>
                <div class="arbevetel-chart"><canvas id="berkimutataschart"></canvas></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
