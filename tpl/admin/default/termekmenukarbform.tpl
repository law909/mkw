<div id="menukarb-header">
    <h3>{$egyed.nev}</h3>
</div>
<form id="menukarb-form" method="post" action="/admin/termekmenu/save" data-id="{$egyed.id}">
    <div id="menukarb-tabs">
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
            {/mezocsoport}
            {include 'termekmenuimagekarb.tpl'}
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
            <input id="InaktivCheck" name="inaktiv" type="checkbox" {if ($egyed.inaktiv)}checked="checked"{/if}/>{at('Inaktív')}
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
        <input id="menukarb-okbutton" type="submit" value="{at('OK')}">
        <a id="menukarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
