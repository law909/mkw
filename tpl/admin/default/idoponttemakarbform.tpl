<div id="mattkarb-header">
    <h3>{at('Időpont téma')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/idoponttema/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#KerdoivTab">{at('Kérdőív')}{if ($egyed.kerdoivkerdesdb)} ({$egyed.kerdoivkerdesdb}){/if}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required autofocus>
                {/mezo}
                {mezo cimke="Webcím" for="UrlEdit"}
                    <input id="UrlEdit" name="url" type="text" size="80" maxlength="255" value="{$egyed.url}">
                {/mezo}
                {mezo cimke="Leírás" for="LeirasEdit" szeles=true}
                    <textarea id="LeirasEdit" name="leiras" rows="5" cols="80">{$egyed.leiras}</textarea>
                {/mezo}
                {mezo cimke="Inaktív" for="InaktivEdit"}
                    <input id="InaktivEdit" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)} checked="checked"{/if}>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="KerdoivTab" class="mattkarb-page" data-visible="visible">
            {include 'idopontkerdoivszerkeszto.tpl' kerdoivjson=$egyed.kerdoivjson
                kerdoivhint={at('A téma kérdőíve az időpontba másolódik, amikor ott a témát kiválasztod (meglévő kérdések esetén rákérdez); a már létrehozott időpontok kérdőívét magától nem változtatja meg.')}}
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
