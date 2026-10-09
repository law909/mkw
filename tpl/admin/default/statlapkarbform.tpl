<div id="mattkarb-header">
    <h3>{at('Statikus lap')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Oldalcím" for="NevEdit"}
                    <input id="NevEdit" name="oldalcim" type="text" size="80" maxlength="255" value="{$egyed.oldalcim}">
                {/mezo}
                {mezo cimke="Régi oldalcím" for="OldurlEdit"}
                    <input id="OldurlEdit" name="oldurl" type="text" size="80" maxlength="255" value="{$egyed.oldurl}">
                {/mezo}
                {mezo cimke="Szöveg" for="LeirasEdit" szeles=true}
                    <textarea id="LeirasEdit" name="szoveg" class="js-ckeditor">{$egyed.szoveg}</textarea>
                {/mezo}
                {mezo cimke="META leírás" for="SeoDescriptionEdit" szeles=true}
                    <textarea id="SeoDescriptionEdit" name="seodescription" cols="70">{$egyed.seodescription}</textarea>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Oldalcím" for="NevL1Edit"}
                    <input id="NevL1Edit" name="oldalcim_l1" type="text" size="80" maxlength="255" value="{$egyed.oldalcim_l1}">
                {/mezo}
                {mezo cimke="Szöveg" for="LeirasL1Edit" szeles=true}
                    <textarea id="LeirasL1Edit" name="szoveg_l1" class="js-ckeditor">{$egyed.szoveg_l1}</textarea>
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