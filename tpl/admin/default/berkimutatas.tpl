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
                <div class="matt-hseparator"></div>
                {include "comp_idoszak.tpl" comptype="datum"}
                <div class="matt-hseparator"></div>
                {include "comp_dolgozoselect.tpl"}
                <div class="matt-hseparator"></div>
                <div class="arbevetel-grouping ui-widget ui-widget-content ui-corner-all">
                    <label for="Szint1Edit">{at('Csoportosítás')}:</label>
                    {for $_i = 1 to $maxszint}
                        {if ($_i > 1)}<span class="arbevetel-szintnyil">›</span>{/if}
                        <select id="Szint{$_i}Edit" class="js-szint" name="szint[]" title="{$_i}. {at('szint')}">
                            <option value="">{if ($_i == 1)}{at('nincs')}{else}–{/if}</option>
                            {foreach $szintlist as $_szint}
                                <option value="{$_szint.id}" data-dim="{$_szint.dim}"{if (($_i == 1 && $_szint.id == 'honap') || ($_i == 2 && $_szint.id == 'dolgozo'))} selected="selected"{/if}>{$_szint.caption}</option>
                            {/foreach}
                        </select>
                    {/for}
                    <label for="MegjelenitesEdit" class="arbevetel-megjelenites">{at('Megjelenítés')}:</label>
                    <select id="MegjelenitesEdit" name="megjelenites" title="{at('A kereszttáblához időszak-szint kell: az időszakok lesznek az oszlopok.')}">
                        <option value="lista">{at('lista')}</option>
                        <option value="kereszttabla">{at('kereszttábla')}</option>
                    </select>
                    <div class="arbevetel-nezet">
                        <label for="NezetEdit">{at('Mentett nézet')}:</label>
                        <select id="NezetEdit">
                            <option value="">{at('válasszon')}</option>
                        </select>
                        <a href="#" class="js-nezetsave">{at('Mentés…')}</a>
                        <a href="#" class="js-nezetdelete">{at('Törlés')}</a>
                    </div>
                </div>
                <div class="matt-hseparator"></div>
                <a href="#" class="js-refresh">{at('Frissít')}</a>
                <a href="/admin/berkimutatas/export" class="js-exportbutton">{at('Export')}</a>
                <a href="#" class="js-pdfbutton">{at('PDF')}</a>
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
