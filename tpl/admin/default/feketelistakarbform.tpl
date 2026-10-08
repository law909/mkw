<div id="mattkarb-header">
    <h3>{at('Feketelista')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/feketelista/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Email/IP cím" for="EmailEdit"}
                    <input id="EmailEdit" name="email" type="text" size="80" maxlength="255" value="{$egyed.email}">
                {/mezo}
                {mezo cimke="OK" for="OkEdit"}
                    <textarea id="OkEdit" name="ok">{$egyed.ok}</textarea>
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