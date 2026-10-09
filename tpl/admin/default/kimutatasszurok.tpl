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
