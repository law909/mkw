<div id="mattkarb-header">
    <h3>{at('Óratípus')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required="required">
                {/mezo}
                {mezo cimke="Leírás" for="LeirasEdit" szeles=true}
                    <textarea id="LeirasEdit" name="leiras" rows="3" cols="70">{$egyed.leiras}</textarea>
                {/mezo}
                {mezo cimke="Szín" for="SzinEdit"}
                    <input id="SzinEdit" name="szin" type="text" size="80" maxlength="7" value="{$egyed.szin}">
                {/mezo}
                {mezo cimke="Árnövelő" for="ArnoveloEdit"}
                    <input id="ArnoveloEdit" name="arnovelo" type="number" step="any" value="{$egyed.arnovelo}">
                {/mezo}
                {mezo cimke="Inaktív" for="InaktivEdit"}
                    <input id="InaktivEdit" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="URL" for="UrlEdit"}
                    <input id="UrlEdit" name="url" type="text" size="80" maxlength="255" value="{$egyed.url}">
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
