{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/chartjs/chart.umd.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/chartvaluelabels.js"></script>
    <script type="text/javascript" src="/js/admin/default/arbevetellista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}" data-baseurl="/admin/arbevetellista" data-unit="Ft">
            <h3>{at('Árbevétel kimutatás')}</h3>
        </div>
        <form id="arbevetel" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <p>{at('Előlegszámlák, számlák és bolti eladások tételei forintban; a stornó levonódik, a bolti eladásból képzett számla nem számít még egyszer.')}</p>
                <div class="matt-hseparator"></div>
                {include "comp_idoszak.tpl" comptype="szamla"}
                <div class="matt-hseparator"></div>
                <div>
                    <label for="ErtekEdit">{at('Érték')}:</label>
                    <select id="ErtekEdit" name="ertektipus">
                        <option value="netto">{at('nettó HUF')}</option>
                        <option value="brutto">{at('bruttó HUF')}</option>
                    </select>
                </div>
                <div class="matt-hseparator"></div>
                {include "comp_partnerselect.tpl"}
                <div class="matt-hseparator"></div>
                {include "comp_partnertipusselect.tpl"}
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
                <div>
                    <label for="IdoszakcsoportEdit">{at('Csoportosítás')}:</label>
                    <select id="IdoszakcsoportEdit" name="idoszakcsoport">
                        <option value="">{at('időszak nélkül')}</option>
                        <option value="ev">{at('évente')}</option>
                        <option value="honap" selected="selected">{at('havonta')}</option>
                    </select>
                    <input id="GyartocsoportEdit" type="checkbox" name="gyartocsoport">
                    <label for="GyartocsoportEdit">{at('gyártónként')}</label>
                    <input id="WebshopcsoportEdit" type="checkbox" name="webshopcsoport">
                    <label for="WebshopcsoportEdit">{at('webshoponként')}</label>
                </div>
                <div class="matt-hseparator"></div>
                {include "comp_termekfa.tpl"}
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
