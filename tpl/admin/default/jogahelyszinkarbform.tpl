<div id="mattkarb-header">
    <h3>{at('Helyszín')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/jogahelyszin/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required autofocus>
                {/mezo}
                {mezo cimke="Irányítószám" for="IrszamEdit"}
                    <input id="IrszamEdit" name="irszam" type="text" size="10" maxlength="10" value="{$egyed.irszam}">
                {/mezo}
                {mezo cimke="Város" for="VarosEdit"}
                    <input id="VarosEdit" name="varos" type="text" size="40" maxlength="255" value="{$egyed.varos}">
                {/mezo}
                {mezo cimke="Utca" for="UtcaEdit"}
                    <input id="UtcaEdit" name="utca" type="text" size="40" maxlength="255" value="{$egyed.utca}">
                {/mezo}
                {mezo cimke="Házszám" for="HazszamEdit"}
                    <input id="HazszamEdit" name="hazszam" type="text" size="15" maxlength="50" value="{$egyed.hazszam}">
                {/mezo}
                {mezo cimke="Webcím" for="UrlEdit"}
                    <input id="UrlEdit" name="url" type="text" size="80" maxlength="255" value="{$egyed.url}">
                {/mezo}
                {mezo cimke="Inaktív" for="InaktivEdit"}
                    <input id="InaktivEdit" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Helyszín szövege a levelekben" for="LeirasEdit" szeles=true}
                    <textarea id="LeirasEdit" name="emailsablon" class="emailtemplateleiras">{$egyed.emailsablon}</textarea>
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
