<div id="mattkarb-header">
    <h3>{at('Szabadság')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Dolgozó" for="DolgozoEdit"}
                    <select id="DolgozoEdit" name="dolgozo" required autofocus>
                        <option value="">{at('válasszon')}</option>
                        {foreach $dolgozolist as $_mk}
                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Időszak" for="DatumtolEdit"}
                    <div class="mattkarb-mezogomb">
                        <input id="DatumtolEdit" name="datumtol" type="text" size="12" data-datum="{$egyed.datumtolstr}" required>
                        <input id="DatumigEdit" name="datumig" type="text" size="12" data-datum="{$egyed.datumigstr}">
                    </div>
                    <span>{at('Egy napra elég a kezdő dátum.')}</span>
                {/mezo}
                {mezo cimke="Típus" for="TipusEdit"}
                    <select id="TipusEdit" name="tipus" required>
                        {foreach $tipuslist as $_tip}
                            <option value="{$_tip.id}"{if ($_tip.selected)} selected="selected"{/if}>{$_tip.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Megjegyzés" for="MegjegyzesEdit"}
                    <input id="MegjegyzesEdit" name="megjegyzes" type="text" size="60" maxlength="255" value="{$egyed.megjegyzes}">
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
