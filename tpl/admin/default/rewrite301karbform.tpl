<div id="mattkarb-header">
    <h3>{at('Átirányítás')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Forrás URL" for="FromurlEdit" szeles=true}
                    <textarea id="FromurlEdit" name="fromurl" rows="3" cols="70" required="required">{$egyed.fromurl}</textarea>
                {/mezo}
                {mezo cimke="Cél URL" for="TourlEdit" szeles=true}
                    <textarea id="TourlEdit" name="tourl" rows="3" cols="70" required="required">{$egyed.tourl}</textarea>
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
