<div id="mattkarb-header">
    <h3>{at('Elállás a szerződéstől')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/elallas/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#NaploTab">{at('Napló')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}">
                {/mezo}
                {mezo cimke="Email" for="EmailEdit"}
                    <input id="EmailEdit" name="email" type="text" size="80" maxlength="255" value="{$egyed.email}">
                {/mezo}
                {mezo cimke="Bizonylat" for="BizonylatEdit"}
                    <input id="BizonylatEdit" name="bizonylat" type="text" size="30" maxlength="30" value="{$egyed.bizonylat}">
                {/mezo}
                {mezo cimke="Szöveg" for="SzovegEdit" szeles=true}
                    <textarea id="SzovegEdit" name="szoveg" rows="6" cols="80">{$egyed.szoveg}</textarea>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="NaploTab" class="mattkarb-page" data-visible="visible">
            {foreach $naplok as $naplo}
                {include 'elallaselallasnaplokarb.tpl'}
            {/foreach}
            <a class="js-naplonewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
