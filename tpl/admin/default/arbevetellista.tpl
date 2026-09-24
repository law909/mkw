{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/chartjs/chart.umd.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/chartvaluelabels.js"></script>
    <script type="text/javascript" src="/js/admin/default/arbevetellista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}" data-baseurl="/admin/arbevetellista">
            <h3>{at('Árbevétel kimutatás')}</h3>
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
                    <span class="mattkarb-hint">{at('Mindegy: minden bizonylat, forintra átszámolt értékkel. Választott valutanemnél csak az abban kiállított bizonylatok számítanak, a saját pénznemükben (pl. EUR-ban), átszámítás nélkül.')}</span>
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
                    <div>{at('Bizonylattípus')} ({at('ha egy sincs bejelölve: előlegszámla, számla, bolti eladás')}):</div>
                    {include "comp_bizonylattipus.tpl"}
                    <div class="matt-hseparator"></div>
                {/if}
                {include "comp_partnercimkefilter.tpl"}
                <div class="matt-hseparator"></div>
                {include "comp_termekfa.tpl"}
                <div class="matt-hseparator"></div>
                <div class="arbevetel-grouping ui-widget ui-widget-content ui-corner-all">
                    <label for="IdoszakcsoportEdit">{at('Csoportosítás')}:</label>
                    <select id="IdoszakcsoportEdit" name="idoszakcsoport">
                        <option value="">{at('időszak nélkül')}</option>
                        <option value="ev">{at('évente')}</option>
                        <option value="honap" selected="selected">{at('havonta')}</option>
                    </select>
                    <select id="KategoriacsoportEdit" name="kategoriacsoport">
                        <option value="">{at('kategória nélkül')}</option>
                        <option value="fokategoria">{at('főkategóriánként')}</option>
                        <option value="kategoria">{at('termék kategóriánként')}</option>
                    </select>
                    <input id="GyartocsoportEdit" type="checkbox" name="gyartocsoport">
                    <label for="GyartocsoportEdit">{at('gyártónként')}</label>
                    <input id="WebshopcsoportEdit" type="checkbox" name="webshopcsoport">
                    <label for="WebshopcsoportEdit">{at('webshoponként')}</label>
                </div>
                <div class="matt-hseparator"></div>
                <a href="#" class="js-refresh">{at('Frissít')}</a>
                <a href="/admin/arbevetellista/export" class="js-exportbutton">{at('Export')}</a>
                <div class="matt-hseparator"></div>
                <div class="arbevetel-chart"><canvas id="arbevetelchart"></canvas></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
