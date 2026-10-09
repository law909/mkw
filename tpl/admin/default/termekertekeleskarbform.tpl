<div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
    <h3>{at('Termék értékelés')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/termekertekeles/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo szeles=true}
                    <span>Elutasítva: </span><input type="checkbox" name="elutasitva"{if ($egyed.elutasitva)} checked="checked"{/if}>
                {/mezo}
                {mezo szeles=true}
                    <span>Anonim: </span><input type="checkbox" name="anonim"{if ($egyed.anonim)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Vevő" for="PartnerEdit" szeles=true class="mezo-fontos"}
                    {if ($setup.partnerautocomplete)}
                        {if ($oper === 'add')}
                        <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete mattable-important" value="{$egyed.partnernev}" size=90 required="required">
                        {else}
                        {$egyed.partnernev}
                        {/if}
                        <input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partner}">
                    {else}
                        <select id="PartnerEdit" name="partner" class="js-partnerid mattable-important" required="required">
                            <option value="">{at('válasszon')}</option>
                            <option value="-1">{at('Új felvitel')}</option>
                            {foreach $partnerlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    {/if}
                {/mezo}
                {mezo cimke="Termék" for="TermekSelect" szeles=true class="mezo-fontos"}
                    {if ($setup.termekautocomplete)}
                        {if ($oper === 'add')}
                        <input id="TermekSelect" type="text" name="termeknev" class="js-termekselect termekselect mattable-important" value="{$egyed.termeknev}" required="required">
                        {else}
                        {$egyed.termeknev}
                        {/if}
                        <input class="js-termekid" name="termek" type="hidden" value="{$egyed.termek}">
                    {else}
                        <select class="js-termekselectreal js-termekid" name="termek" required="required">
                            <option value="">{t('válasszon')}</option>
                            {foreach $termeklist as $_termekadat}
                                <option value="{$_termekadat.id}"{if ($_termekadat.id == $egyed.termek)} selected="selected"{/if}>{$_termekadat.caption}</option>
                            {/foreach}
                        </select>
                    {/if}
                {/mezo}
                {mezo cimke="Értékelés" for="ErtekelesEdit"}
                    <input id="ErtekelesEdit" name="ertekeles" type="number" size="5" value="{$egyed.ertekeles}" required>
                {/mezo}
                {mezo cimke="Szöveg" for="SzovegEdit" szeles=true}
                    <textarea id="SzovegEdit" name="szoveg">{$egyed.szoveg}</textarea>
                {/mezo}
                {mezo cimke="Előny" for="ElonyEdit" szeles=true}
                    <textarea id="ElonyEdit" name="elony">{$egyed.elony}</textarea>
                {/mezo}
                {mezo cimke="Hátrány" for="HatranyEdit" szeles=true}
                    <textarea id="HatranyEdit" name="hatrany">{$egyed.hatrany}</textarea>
                {/mezo}
                {mezo cimke="Válasz" for="ValaszEdit" szeles=true}
                    <textarea id="ValaszEdit" name="valasz">{$egyed.valasz}</textarea>
                {/mezo}
            {/mezocsoport}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
