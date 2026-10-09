<div id="mattkarb-header">
    <h3>{at('Helyettesítés')}</h3>
    <h4>{$egyed.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/orarendhelyettesites/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            <input id="ElmaradCheck" name="elmarad" type="checkbox"
                   {if ($egyed.elmarad)}checked="checked"{/if}>{at('Elmarad')}
            {mezocsoport}
                {if ($oper !== 'edit')}
                    {mezo cimke="Dátum" for="DatumEdit" ujsor=true}
                        <input id="DatumEdit" name="datum" type="text" required>
                    {/mezo}
                {else}
                    {mezo cimke="Dátum"}
                        {$egyed.datum}
                    {/mezo}
                {/if}
                {if ($oper !== 'edit')}
                    {mezo cimke="Óra" for="OrarendEdit" ujsor=true}
                        <select id="OrarendEdit" name="orarend" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $orarendlist as $_tcs}
                                <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                {else}
                    {mezo cimke="Óra"}
                        {$egyed.oranev}
                    {/mezo}
                {/if}
                {mezo cimke="Helyettesítő" for="HelyettesitoEdit"}
                    <select id="HelyettesitoEdit" name="helyettesito">
                        <option value="">{at('válasszon')}</option>
                        {foreach $helyettesitolist as $_tcs}
                            <option value="{$_tcs.id}"{if ($_tcs.selected)} selected="selected"{/if}>{$_tcs.caption}</option>
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