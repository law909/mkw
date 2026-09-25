<div id="mattkarb-header">
    <h3>{at('ÁFA kulcs')}</h3>
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
                {mezo cimke="ÁFA kulcs" for="ErtekEdit"}
                    <input id="ErtekEdit" name="ertek" type="number" step="any" value="{$egyed.ertek}" required="required"> %
                {/mezo}
                {mezo cimke="NAV case" for="NavcaseEdit"}
                    <select id="NavcaseEdit" name="navcase">
                        {foreach $egyed.navcaselist as $_case}
                            <option value="{$_case.id}"{if ($_case.selected)} selected="selected"{/if}>{$_case.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Magyar ÁFA kulcs" for="MagyarEdit"}
                    <input id="MagyarEdit" name="magyar" type="checkbox"{if ($egyed.magyar)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="RLB kód" for="RlbkodEdit"}
                    <input id="RlbkodEdit" name="rlbkod" type="number" value="{$egyed.rlbkod}">
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
