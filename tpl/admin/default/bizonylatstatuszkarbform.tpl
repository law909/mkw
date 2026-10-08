<div id="mattkarb-header">
    <h3>{at('')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}">
                {/mezo}
                {mezo cimke="Bizonylattípus" for="BizonylattipusEdit"}
                    <select id="BizonylattipusEdit" name="bizonylattipus">
                        <option value="">{at('mindegyik')}</option>
                        <option value="kozos"{if ($egyed.kozos)} selected="selected"{/if}>{at('közös')}</option>
                        {foreach $bizonylattipuslist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Csoport" for="CsoportEdit"}
                    <input id="CsoportEdit" name="csoport" type="text" size="80" maxlength="255" value="{$egyed.csoport}">
                {/mezo}
                {mezo cimke="Nem értékelhető" for="NemertekelhetoEdit"}
                    <input id="NemertekelhetoEdit" name="nemertekelheto" type="checkbox"{if ($egyed.nemertekelheto)} checked="checked"{/if}">
                {/mezo}
                {if ($setup.foglalas)}
                    {mezo cimke="Foglal" for="FoglalEdit"}
                        <input id="FoglalEdit" name="foglal" type="checkbox"{if ($egyed.foglal)} checked="checked"{/if}">
                    {/mezo}
                {/if}
                {mezo cimke="Mozgat" for="MozgatEdit"}
                    <input id="MozgatEdit" name="mozgat" type="checkbox"{if ($egyed.mozgat)} checked="checked"{/if}">
                {/mezo}
                {mezo cimke="Érkezik" for="ErkezikEdit"}
                    <input id="ErkezikEdit" name="erkezik" type="checkbox"{if ($egyed.erkezik)} checked="checked"{/if}">
                {/mezo}
                {mezo cimke="Sorrend" for="SorrendEdit"}
                    <input id="SorrendEdit" name="sorrend" type="text" size="80" maxlength="255" value="{$egyed.sorrend}">
                {/mezo}
                {mezo cimke="Email sablon" for="EmailEdit" szeles=true}
                    <select id="EmailEdit" name="emailtemplate">
                        <option value="">{at('válasszon')}</option>
                        {foreach $emailtemplatelist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Fizetési mód" for="FizmodEdit"}
                    <select id="FizmodEdit" name="fizmod">
                        <option value="">{at('válasszon')}</option>
                        {foreach $fizmodlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Szállítási mód" for="SzallitasimodEdit"}
                    <select id="SzallitasimodEdit" name="szallitasimod">
                        <option value="">{at('válasszon')}</option>
                        {foreach $szallitasimodlist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
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