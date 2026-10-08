<div id="mattkarb-header">
    <h3>{at('Valutanem')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="6" value="{$egyed.nev}" required="required">
                {/mezo}
                {mezo cimke="Kerekít" for="KerekitEdit"}
                    <input id="KerekitEdit" name="kerekit" type="checkbox"{if ($egyed.kerekit)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Hivatalos" for="HivatalosEdit"}
                    <input id="HivatalosEdit" name="hivatalos" type="checkbox"{if ($egyed.hivatalos)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Legkisebb címlet" for="MincimletEdit"}
                    <input id="MincimletEdit" name="mincimlet" type="number" step="1" value="{$egyed.mincimlet}">
                {/mezo}
                {mezo cimke="Bankszámla" for="BankszamlaEdit"}
                    <select id="BankszamlaEdit" name="bankszamla">
                        <option value="">{at('válasszon')}</option>
                        {foreach $egyed.bankszamlalist as $_o}
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
