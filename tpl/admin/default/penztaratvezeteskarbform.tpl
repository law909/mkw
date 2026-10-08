<div id="mattkarb-header">
    <h3>{$pagetitle}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Kelt" for="KeltEdit" class="mezo-fontos"}
                    <input id="KeltEdit" name="kelt" type="text" size="12" data-datum="{$egyed.keltstr}" class="mattable-important" required="required">
                {/mezo}
                {mezo cimke="Honnan" for="HonnanPenztarEdit" ujsor=true class="mezo-fontos"}
                    <select id="HonnanPenztarEdit" name="honnanpenztar" class="mattable-important" required="required">
                        <option value="">{at('válasszon')}</option>
                        {foreach $penztarlist as $_mk}
                            <option value="{$_mk.id}" data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Hová" for="HovaPenztarEdit" class="mezo-fontos"}
                    <select id="HovaPenztarEdit" name="hovapenztar" class="mattable-important" required="required">
                        <option value="">{at('válasszon')}</option>
                        {foreach $penztarlist as $_mk}
                            <option value="{$_mk.id}" data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Valutanem" for="ValutanemEdit" ujsor=true}
                    <select id="ValutanemEdit" name="valutanemselect" disabled="disabled">
                        <option value="">{at('válasszon')}</option>
                        {foreach $valutanemlist as $_mk}
                            <option value="{$_mk.id}">{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Árfolyam" for="ArfolyamEdit"}
                    <input id="ArfolyamEdit" name="arfolyam" type="text" value="{$egyed.arfolyam}">
                {/mezo}
                {mezo cimke="Partner" for="PartnerEdit" szeles=true}
                    <select id="PartnerEdit" name="partner">
                        <option value="">{at('válasszon')}</option>
                        {foreach $partnerlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {if ($showerbizonylatszam)}
                    {mezo cimke="Eredeti biz.szám" for="ErbizonylatszamEdit"}
                        <input id="ErbizonylatszamEdit" name="erbizonylatszam" type="text" value="{$egyed.erbizonylatszam}">
                    {/mezo}
                {/if}
                {mezo cimke="Megjegyzés" for="MegjegyzesEdit" szeles=true}
                    <textarea id="MegjegyzesEdit" name="megjegyzes" rows="1" cols="100">{$egyed.megjegyzes}</textarea>
                {/mezo}
            {/mezocsoport}
            <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                {mezocsoport}
                    {mezo cimke="Szöveg" for="SzovegEdit"}
                        <input id="SzovegEdit" name="szoveg" size="60" value="{$egyed.szoveg}">
                    {/mezo}
                    {mezo cimke="Jogcím" for="JogcimEdit" class="mezo-fontos"}
                        <select id="JogcimEdit" name="jogcim" class="mattable-important" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $jogcimlist as $_mk}
                                <option value="{$_mk.id}">{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {mezo cimke="Összeg" for="OsszegEdit" class="mezo-fontos"}
                        <input id="OsszegEdit" name="osszeg" type="number" step="any" required="required" value="{$egyed.osszeg}">
                    {/mezo}
                {/mezocsoport}
            </div>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
