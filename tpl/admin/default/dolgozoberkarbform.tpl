<div id="mattkarb-header">
    <h3>{at('Bér')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {if ($readonly)}
                <p class="rontott">{at('Rontott sor, nem módosítható.')} {at('Rontotta')}: {$egyed.rontottby} {$egyed.rontottonstr}</p>
            {/if}
            <fieldset{if ($readonly)} disabled="disabled"{/if}>
            {mezocsoport}
                {mezo cimke="Dolgozó" for="DolgozoEdit"}
                    <select id="DolgozoEdit" name="dolgozo" required autofocus>
                        <option value="">{at('válasszon')}</option>
                        {foreach $dolgozolist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Dátum" for="DatumEdit"}
                    <input id="DatumEdit" name="datum" type="text" size="12" data-datum="{$egyed.datumstr}" required>
                {/mezo}
                {mezo cimke="Jogcím" for="BerjogcimEdit"}
                    <select id="BerjogcimEdit" name="berjogcim" required>
                        <option value="">{at('válasszon')}</option>
                        {foreach $berjogcimlist as $_jc}
                            <option value="{$_jc.id}"{if ($_jc.selected)} selected="selected"{/if}>{$_jc.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Összeg" for="OsszegEdit"}
                    <div class="mattkarb-mezogomb">
                        <input id="OsszegEdit" name="osszeg" type="number" step="any" value="{$egyed.osszeg}" required> Ft
                    </div>
                {/mezo}
                {mezo cimke="Megjegyzés" for="MegjegyzesEdit"}
                    <input id="MegjegyzesEdit" name="megjegyzes" type="text" size="60" maxlength="255" value="{$egyed.megjegyzes}">
                {/mezo}
                {if ($egyed.id)}
                    {mezo cimke="Rögzítette"}
                        {$egyed.createdby} {$egyed.createdstr}
                    {/mezo}
                    {mezo cimke="Módosította"}
                        {$egyed.updatedby} {$egyed.lastmodstr}
                    {/mezo}
                {/if}
            {/mezocsoport}
            </fieldset>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        {if (!$readonly)}<input id="mattkarb-okbutton" type="submit" value="{at('OK')}">{/if}
        <a id="mattkarb-cancelbutton" href="#">{if ($readonly)}{at('Vissza')}{else}{at('Mégsem')}{/if}</a>
    </div>
</form>
