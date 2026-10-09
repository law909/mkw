{* Az árbevétel és a forgalmi kimutatás közös szűrői (arbevetellista.js); a mezők nevei a JS-é, ne változzanak.
   Paraméterek: baseurl, bizonylattipushint, pivotertek (a forgalmi kimutatás kereszttáblájának cellatartalma) *}
{mezocsoport cim="Időszak és érték"}
    {mezo cimke="Időszak" for="DatumTipusEdit" szeles=true}
        <div class="mattkarb-mezogomb kimutatas-idoszak">
            <select id="DatumTipusEdit" name="datumtipus">
                <option value="kelt" {if ($datumtipus == 'kelt')}selected="selected"{/if}>{at('kelt')}</option>
                <option value="teljesites" {if ($datumtipus == 'teljesites')}selected="selected"{/if}>{at('teljesítés')}</option>
                <option value="esedekesseg" {if ($datumtipus == 'esedekesseg')}selected="selected"{/if}>{at('esedékesség')}</option>
            </select>
            <input id="TolEdit" name="tol" data-datum="{$toldatum}">
            <span>–</span>
            <input id="IgEdit" name="ig" data-datum="{$igdatum}">
        </div>
    {/mezo}
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
    {mezo cimke="Partner" for="PartnerEdit" szeles=true}
        {if ($setup.partnerautocomplete)}
            <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete mattable-important" size=90>
            <input class="js-partnerid" name="partner" type="hidden">
        {else}
            <select id="PartnerEdit" name="partner" class="js-partnerid mattable-important">
                <option value="">{at('válasszon')}</option>
                {foreach $partnerlist as $_mk}
                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                {/foreach}
            </select>
        {/if}
    {/mezo}
    {mezo cimke="Partnertípus" for="PartnertipusEdit" ujsor=true}
        <select id="PartnertipusEdit" name="partnertipus">
            <option value="">{at('válasszon')}</option>
            {foreach $partnertipuslist as $_mk}
                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
            {/foreach}
        </select>
    {/mezo}
    {mezo cimke="Üzletkötő" for="UzletkotoEdit"}
        <select id="UzletkotoEdit" name="uzletkoto" class="mattable-important">
            <option value="">{at('válasszon')}</option>
            {foreach $uklist as $_mk}
                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
            {/foreach}
        </select>
    {/mezo}
    {mezo cimke="Gyártó" for="GyartoEdit" ujsor=true}
        <select id="GyartoEdit" name="gyarto">
            <option value="">{at('válasszon')}</option>
            {foreach $gyartolist as $_mk}
                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
            {/foreach}
        </select>
    {/mezo}
    {mezo cimke="Termék" for="NevEdit" sugo="Termék névben és cikkszámban keres"}
        <input id="NevEdit" name="nev" type="text" size="30" placeholder="{at('név vagy cikkszám')}" title="{at('Termék névben és cikkszámban keres')}">
    {/mezo}
    {mezo cimke="Webshop" for="WebshopnumEdit" ujsor=true}
        <select id="WebshopnumEdit" name="webshopnum">
            {foreach $webshopfilterlist as $_mk}
                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
            {/foreach}
        </select>
    {/mezo}
    {if ($bizonylattipusfilter)}
        {mezo cimke="Bizonylattípus" szeles=true}
            <div class="mattkarb-pipak">
                {foreach $bizonylattipuslist as $bt}
                    <label><input id="bizonylattipuscb{$bt.id}" type="checkbox" name="bizonylattipus[]" value="{$bt.id}"{if (!empty($bizonylattipuschecked[$bt.id]))} checked="checked"{/if}>{$bt.caption}</label>
                {/foreach}
            </div>
            <div class="mattkarb-megjegyzes">{$bizonylattipushint}</div>
        {/mezo}
    {/if}
    {if (!empty($cimkekat))}
        {mezo cimke="Partnercímkék" szeles=true}
            {include "comp_partnercimkefilter.tpl"}
        {/mezo}
    {/if}
    {mezo cimke="Termékkategória" szeles=true}
        {include "comp_termekfa.tpl"}
    {/mezo}

    <div class="mattkarb-szakaszcim">{at('Megjelenítés')}</div>
    {mezo cimke="Csoportosítás" for="Szint1Edit" szeles=true}
        <div class="kimutatas-szintek">
            {for $_i = 1 to $maxszint}
                {if ($_i > 1)}<span class="arbevetel-szintnyil">›</span>{/if}
                <select id="Szint{$_i}Edit" class="js-szint" name="szint[]" title="{$_i}. {at('szint')}">
                    <option value="">{if ($_i == 1)}{at('nincs')}{else}–{/if}</option>
                    {foreach $szintlist as $_szint}
                        <option value="{$_szint.id}" data-dim="{$_szint.dim}"{if ($_i == 1 && $_szint.id == 'honap')} selected="selected"{/if}>{$_szint.caption}</option>
                    {/foreach}
                </select>
            {/for}
        </div>
    {/mezo}
    {mezo cimke="Megjelenítés" for="MegjelenitesEdit" ujsor=true}
        <div class="mattkarb-mezogomb">
            <select id="MegjelenitesEdit" name="megjelenites" title="{at('A kereszttáblához időszak-szint kell: az időszakok lesznek az oszlopok.')}">
                <option value="lista">{at('lista')}</option>
                <option value="kereszttabla">{at('kereszttábla')}</option>
            </select>
            {if ($pivotertek|default:false)}
                <select id="PivotertekEdit" name="pivotertek" title="{at('A kereszttábla celláiban')}">
                    <option value="mennyiseg">{at('mennyiség')}</option>
                    <option value="ertek">{at('érték')}</option>
                </select>
            {/if}
        </div>
    {/mezo}
    {mezo cimke="Mentett nézet" for="NezetEdit"}
        <div class="mattkarb-mezogomb">
            <select id="NezetEdit">
                <option value="">{at('válasszon')}</option>
            </select>
            <a href="#" class="js-nezetsave">{at('Mentés…')}</a>
            <a href="#" class="js-nezetdelete">{at('Törlés')}</a>
        </div>
    {/mezo}
{/mezocsoport}
<div class="arsav-gombok">
    <a href="#" class="js-refresh">{at('Frissít')}</a>
    <a href="{$baseurl}/export" class="js-exportbutton">{at('Export')}</a>
    <a href="#" class="js-pdfbutton">{at('PDF')}</a>
</div>
