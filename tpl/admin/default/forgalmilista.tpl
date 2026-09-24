{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/chartjs/chart.umd.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/chartvaluelabels.js"></script>
    <script type="text/javascript" src="/js/admin/default/reportchart.js"></script>
    <script type="text/javascript" src="/js/admin/default/arbevetellista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}" data-baseurl="/admin/forgalmilista" data-decimals="2">
            <h3>{at('Forgalmi kimutatás')}</h3>
        </div>
        <form id="arbevetel" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div class="matt-hseparator"></div>
                {include "comp_idoszak.tpl" comptype="szamla"}
                <div class="matt-hseparator"></div>
                <div>
                    <label for="ErtekEdit">{at('Érték')}:</label>
                    <select id="ErtekEdit" name="ertektipus">
                        <option value="netto">{at('nettó')}</option>
                        <option value="brutto">{at('bruttó')}</option>
                    </select>
                    <label for="ValutanemEdit">{at('Valutanem')}:</label>
                    <select id="ValutanemEdit" name="valutanem">
                        <option value="">{at('Mindegy')}</option>
                        {foreach $valutanemlist as $_valutanem}
                            <option value="{$_valutanem.id}">{$_valutanem.caption}</option>
                        {/foreach}
                    </select>
                    <span class="mattkarb-hint">{at('Mindegy: minden bizonylat, forintra átszámolt értékkel. Választott valutanemnél csak az abban kiállított bizonylatok számítanak, a saját pénznemükben (pl. EUR-ban), átszámítás nélkül; a mennyiség is csak ezekből adódik össze.')}</span>
                </div>
                <div class="matt-hseparator"></div>
                {include "comp_partnerselect.tpl"}
                <div class="matt-hseparator"></div>
                {include "comp_partnertipusselect.tpl"}
                <div class="matt-hseparator"></div>
                {include "comp_uzletkotoselect.tpl"}
                <div class="matt-hseparator"></div>
                {include "comp_gyartoselect.tpl"}
                <div class="matt-hseparator"></div>
                <div>
                    <label for="NevEdit">{at('Név')}:</label>
                    <input id="NevEdit" name="nev" type="text" size="30" title="{at('Termék névben és cikkszámban keres')}">
                </div>
                <div class="matt-hseparator"></div>
                {include "comp_webshopfilter.tpl"}
                <div class="matt-hseparator"></div>
                {if ($bizonylattipusfilter)}
                    <div>{at('Bizonylattípus')} ({at('ha egy sincs bejelölve: számla, bolti eladás; előlegszámla itt nem számít')}):</div>
                    {include "comp_bizonylattipus.tpl"}
                    <div class="matt-hseparator"></div>
                {/if}
                {include "comp_partnercimkefilter.tpl"}
                <div class="matt-hseparator"></div>
                {include "comp_termekfa.tpl"}
                <div class="matt-hseparator"></div>
                <div class="arbevetel-grouping ui-widget ui-widget-content ui-corner-all">
                    <label for="Szint1Edit">{at('Csoportosítás')}:</label>
                    {for $_i = 1 to $maxszint}
                        {if ($_i > 1)}<span class="arbevetel-szintnyil">›</span>{/if}
                        <select id="Szint{$_i}Edit" class="js-szint" name="szint[]" title="{$_i}. {at('szint')}">
                            <option value="">{if ($_i == 1)}{at('nincs')}{else}–{/if}</option>
                            {foreach $szintlist as $_szint}
                                <option value="{$_szint.id}" data-dim="{$_szint.dim}"{if ($_i == 1 && $_szint.id == 'honap')} selected="selected"{/if}>{$_szint.caption}</option>
                            {/foreach}
                        </select>
                    {/for}
                    <label for="MegjelenitesEdit" class="arbevetel-megjelenites">{at('Megjelenítés')}:</label>
                    <select id="MegjelenitesEdit" name="megjelenites" title="{at('A kereszttáblához időszak-szint kell: az időszakok lesznek az oszlopok.')}">
                        <option value="lista">{at('lista')}</option>
                        <option value="kereszttabla">{at('kereszttábla')}</option>
                    </select>
                    <select id="PivotertekEdit" name="pivotertek" title="{at('A kereszttábla celláiban')}">
                        <option value="mennyiseg">{at('mennyiség')}</option>
                        <option value="ertek">{at('érték')}</option>
                    </select>
                </div>
                <div class="matt-hseparator"></div>
                <a href="#" class="js-refresh">{at('Frissít')}</a>
                <a href="/admin/forgalmilista/export" class="js-exportbutton">{at('Export')}</a>
                <div class="matt-hseparator"></div>
                <div id="arbevetelchartnote" class="arbevetel-chartnote"></div>
                <div class="arbevetel-chart"><canvas id="arbevetelchart"></canvas></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
