<div id="fakarb-header">
    <h3>{$egyed.nev}</h3>
</div>
<form id="fakarb-form" method="post" action="/admin/termekfa/save" data-id="{$egyed.id}">
    <div id="fakarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
            <li><a href="#WebTab">{at('Webes adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit" szeles=true}
                    <input id="NevEdit" name="nev" type="text" size="83" maxlength="255" value="{$egyed.nev}" required autofocus>
                {/mezo}
                <input id="ParentIdEdit" name="parentid" type="hidden" value="{$egyed.parentid}">
                {mezo cimke="Sorrend" for="SorrendEdit"}
                    <input id="SorrendEdit" name="sorrend" type="number" size="10" maxlength="10" value="{$egyed.sorrend}">
                {/mezo}
                {mezo cimke="Árukereső id" for="ArukeresoidEdit"}
                    <input id="ArukeresoidEdit" name="arukeresoid" type="text" value="{$egyed.arukeresoid}">
                {/mezo}
                {mezo cimke="GS1 termékbesorolás (GPC)" for="GpcEdit"}
                    <input id="GpcEdit" name="gpc" type="text" maxlength="20" value="{$egyed.gpc}"
                        title="{at('Ha üres, a fölérendelt kategória besorolása érvényes.')}">
                {/mezo}
                {if ($setup.szinmode === 'fix')}
                    {mezo cimke="Változat cikkszám színkóddal, méretkóddal" for="SzinmeretcikkszamEdit"}
                        <select id="SzinmeretcikkszamEdit" name="szinmeretcikkszam">
                            <option value=""{if ($egyed.szinmeretcikkszam === null)} selected="selected"{/if}>{at('örökli')} ({if ($egyed.szinmeretcikkszamoroklott)}{at('igen')}{else}{at('nem')}{/if})</option>
                            <option value="1"{if ($egyed.szinmeretcikkszam === true)} selected="selected"{/if}>{at('igen')}</option>
                            <option value="0"{if ($egyed.szinmeretcikkszam === false)} selected="selected"{/if}>{at('nem')}</option>
                        </select>
                    {/mezo}
                {/if}
            {/mezocsoport}
            {include 'termekfaimagekarb.tpl'}
            {mezocsoport}
                {mezo cimke="Sketchfab model id" for="SketchfabEdit"}
                    <input id="SketchfabEdit" name="sketchfabmodelid" type="text" value="{$egyed.sketchfabmodelid}">
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevL1Edit"}
                    <input id="NevL1Edit" name="nev_l1" type="text" size="83" maxlength="255" value="{$egyed.nev_l1}">
                {/mezo}
                {mezo cimke="Rövid leírás" for="RovidleirasL1Edit"}
                    <input id="RovidleirasL1Edit" name="rovidleiras_l1" type="text" size="100" maxlength="255" value="{$egyed.rovidleiras_l1}">
                {/mezo}
                {mezo cimke="Leírás" for="LeirasL1Edit" szeles=true}
                    <textarea id="LeirasL1Edit" name="leiras_l1" class="js-ckeditor">{$egyed.leiras_l1}</textarea>
                {/mezo}
                {mezo cimke="Leírás 2" for="Leiras2L1Edit" szeles=true}
                    <textarea id="Leiras2L1Edit" name="leiras2_l1" class="js-ckeditor">{$egyed.leiras2_l1}</textarea>
                {/mezo}
                {mezo cimke="Leírás 3" for="Leiras3L1Edit" szeles=true}
                    <textarea id="Leiras3L1Edit" name="leiras3_l1" class="js-ckeditor">{$egyed.leiras3_l1}</textarea>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="WebTab" class="mattkarb-page">
            <input id="InaktivCheck" name="inaktiv" type="checkbox" {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}</input>
            <input id="Menu1LathatoCheck" name="menu1lathato" type="checkbox" {if ($egyed.menu1lathato)}checked="checked"{/if}>{at('Főmenü')}</input>
            <input id="Menu2LathatoCheck" name="menu2lathato" type="checkbox" {if ($egyed.menu2lathato)}checked="checked"{/if}>{at('Főmenü lenyíló')}</input>
            <input id="Menu3LathatoCheck" name="menu3lathato" type="checkbox" {if ($egyed.menu3lathato)}checked="checked"{/if}>{at('Top kategória')}</input>
            <input id="Menu4LathatoCheck" name="menu4lathato" type="checkbox" {if ($egyed.menu4lathato)}checked="checked"{/if}>{at('Kategória lista')}</input>
            <div>
                <input id="LathatoCheck" name="lathato" type="checkbox"
                       {if ($egyed.lathato)}checked="checked"{/if}>{at('Látható')} {$webshop1name}
                {if ($setup.multishop)}
                    {for $cikl = 2 to $enabledwebshops}
                        <input id="Lathato{$cikl}Check" name="lathato{$cikl}" type="checkbox"
                               {if ($egyed["lathato$cikl"])}checked="checked"{/if}>{at('Látható')} {$webshop{$cikl}name}
                    {/for}
                {/if}
            </div>
            {mezocsoport}
                {mezo cimke="Lap címe" for="OldalCimEdit"}
                    <input id="OldalCimEdit" name="oldalcim" type="text" size="100" maxlength="255" value="{$egyed.oldalcim}">
                {/mezo}
                {mezo cimke="Rövid leírás" for="RovidleirasEdit"}
                    <input id="RovidleirasEdit" name="rovidleiras" type="text" size="100" maxlength="255" value="{$egyed.rovidleiras}">
                {/mezo}
                {mezo cimke="Leírás" for="LeirasEdit" szeles=true}
                    <textarea id="LeirasEdit" name="leiras" class="js-ckeditor">{$egyed.leiras}</textarea>
                {/mezo}
                {mezo cimke="Leírás 2" for="Leiras2Edit" szeles=true}
                    <textarea id="Leiras2Edit" name="leiras2" class="js-ckeditor">{$egyed.leiras2}</textarea>
                {/mezo}
                {mezo cimke="Leírás 3" for="Leiras3Edit" szeles=true}
                    <textarea id="Leiras3Edit" name="leiras3" class="js-ckeditor">{$egyed.leiras3}</textarea>
                {/mezo}
                {mezo cimke="META leírás" for="SeoDescriptionEdit" szeles=true}
                    <textarea id="SeoDescriptionEdit" name="seodescription" cols="70">{$egyed.seodescription}</textarea>
                {/mezo}
            {/mezocsoport}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="fakarb-okbutton" type="submit" value="{at('OK')}">
        <a id="fakarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
