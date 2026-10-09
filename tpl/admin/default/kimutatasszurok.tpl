{* Az árbevétel és a forgalmi kimutatás közös szűrői (arbevetellista.js); a mezők nevei a JS-é, ne változzanak.
   Paraméterek: baseurl, bizonylattipushint, pivotertek (a forgalmi kimutatás kereszttáblájának cellatartalma) *}
{mezocsoport cim="Időszak és érték"}
    {include "comp_idoszak.tpl" comptype="szamla" mezo=true}
    {mezo cimke="Érték" for="ErtekEdit" ujsor=true}
        <select id="ErtekEdit" name="ertektipus">
            <option value="netto">{at('nettó')}</option>
            <option value="brutto">{at('bruttó')}</option>
        </select>
    {/mezo}
    {mezo cimke="Valutanem" for="ValutanemEdit"}
        <select id="ValutanemEdit" name="valutanem">
            <option value="">{at('Mindegy')}</option>
            {foreach $valutanemlist as $_valutanem}
                <option value="{$_valutanem.id}">{$_valutanem.caption}</option>
            {/foreach}
        </select>
    {/mezo}
    {mezo szeles=true}
        <span class="mattkarb-megjegyzes">{$valutanemhint}</span>
    {/mezo}

    <div class="mattkarb-szakaszcim">{at('Szűrők')}</div>
    {include "comp_partnerselect.tpl" mezo=true}
    {include "comp_partnertipusselect.tpl" mezo=true ujsor=true}
    {include "comp_uzletkotoselect.tpl" mezo=true}
    {include "comp_gyartoselect.tpl" mezo=true ujsor=true}
    {mezo cimke="Termék" for="NevEdit" sugo="Termék névben és cikkszámban keres"}
        <input id="NevEdit" name="nev" type="text" size="30" placeholder="{at('név vagy cikkszám')}" title="{at('Termék névben és cikkszámban keres')}">
    {/mezo}
    {include "comp_webshopfilter.tpl" mezo=true ujsor=true}
    {if ($bizonylattipusfilter)}
        {include "comp_bizonylattipus.tpl" mezo=true hint=$bizonylattipushint}
    {/if}
    {include "comp_partnercimkefilter.tpl" mezo=true}
    {include "comp_termekfa.tpl" mezo=true}

    <div class="mattkarb-szakaszcim">{at('Megjelenítés')}</div>
    {include "comp_csoportositas.tpl" pivotertek=$pivotertek|default:false}
{/mezocsoport}
<div class="arsav-gombok">
    <a href="#" class="js-refresh">{at('Frissít')}</a>
    <a href="{$baseurl}/export" class="js-exportbutton">{at('Export')}</a>
    <a href="#" class="js-pdfbutton">{at('PDF')}</a>
</div>
