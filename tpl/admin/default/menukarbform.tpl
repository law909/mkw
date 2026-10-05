<div id="mattkarb-header">
    <h3>{at('Menüpont')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit" szeles=true}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}" required="required">
                {/mezo}
                {mezo cimke="Menücsoport" for="MenucsoportEdit"}
                    <select id="MenucsoportEdit" name="menucsoport">
                        <option value="">{at('csoport nélkül')}</option>
                        {foreach $egyed.menucsoportlist as $_mcs}
                            <option value="{$_mcs.id}"{if ($_mcs.selected)} selected="selected"{/if}>{$_mcs.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Sorrend" for="SorrendEdit"}
                    <input id="SorrendEdit" name="sorrend" type="number" step="1" value="{$egyed.sorrend}">
                {/mezo}
                {mezo cimke="URL" for="UrlEdit" szeles=true}
                    <input id="UrlEdit" name="url" type="text" size="80" maxlength="255" value="{$egyed.url}">
                {/mezo}
                {mezo cimke="Route név" for="RoutenameEdit" szeles=true}
                    <input id="RoutenameEdit" name="routename" type="text" size="80" maxlength="255" value="{$egyed.routename}">
                {/mezo}
                {mezo cimke="CSS osztály" for="ClassEdit"}
                    <input id="ClassEdit" name="class" type="text" size="40" maxlength="255" value="{$egyed.class}">
                {/mezo}
                {mezo cimke="Jog" for="JogosultsagEdit"}
                    <input id="JogosultsagEdit" name="jogosultsag" type="number" step="1" value="{$egyed.jogosultsag}">
                {/mezo}
                {mezo cimke="Látható" for="LathatoEdit"}
                    <input id="LathatoEdit" name="lathato" type="checkbox"{if ($egyed.lathato)} checked="checked"{/if}>
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
