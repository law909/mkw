<div id="mattkarb-header">
    <h3>{at('VTSZ')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Szám" for="SzamEdit"}
                    <input id="SzamEdit" name="szam" type="text" size="80" maxlength="255" value="{$egyed.szam}" required="required">
                {/mezo}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}">
                {/mezo}
                {mezo cimke="ÁFA kulcs" for="AfaEdit"}
                    <select id="AfaEdit" name="afa" required="required">
                        {foreach $egyed.afalist as $_o}
                            <option value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="CSK szám" for="CskEdit"}
                    <select id="CskEdit" name="csk">
                        <option value="">{at('válasszon')}</option>
                        {foreach $egyed.csklist as $_o}
                            <option value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="KT kód" for="KtEdit"}
                    <select id="KtEdit" name="kt">
                        <option value="">{at('válasszon')}</option>
                        {foreach $egyed.ktlist as $_o}
                            <option value="{$_o.id}"{if ($_o.selected)} selected="selected"{/if}>{$_o.caption}</option>
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
