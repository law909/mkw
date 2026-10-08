<div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
    <h3>{at('Bank tranzakció')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            {mezocsoport}
                {mezo cimke="Azonosító" for="AzonEdit"}
                    <input id="AzonEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.azonosito}" disabled>
                {/mezo}
                {mezo cimke="Könyvelés dátuma" for="KonyvelesdatumEdit"}
                    <input id="KonyvelesdatumEdit" name="konyvelesdatum" type="text" value="{$egyed.konyvelesdatumstr}" disabled>
                {/mezo}
                {mezo cimke="Értéknap" for="ErteknapEdit"}
                    <input id="ErteknapEdit" name="erteknapdatum" type="text" value="{$egyed.erteknapstr}" disabled>
                {/mezo}
                {mezo cimke="Összeg" for="OsszegEdit"}
                    <input id="OsszegEdit" name="osszeg" type="text" value="{$egyed.osszeg}" disabled>
                {/mezo}
                {mezo cimke="Közlemény 1" for="Kozl1Edit"}
                    <input id="Kozl1Edit" name="kozlemeny1" type="text" value="{$egyed.kozlemeny1}" disabled>
                {/mezo}
                {mezo cimke="Közlemény 2" for="Kozl2Edit"}
                    <input id="Kozl2Edit" name="kozlemeny2" type="text" value="{$egyed.kozlemeny2}" disabled>
                {/mezo}
                {mezo cimke="Közlemény 3" for="Kozl3Edit"}
                    <input id="Kozl3Edit" name="kozlemeny3" type="text" value="{$egyed.kozlemeny3}" disabled>
                {/mezo}
                {mezo cimke="Bizonylatszámok" for="BizonylatszamokEdit"}
                    <input id="BizonylatszamokEdit" name="bizonylatszamok" type="text" value="{$egyed.bizonylatszamok}">
                {/mezo}
                {mezo cimke="Partner" for="PartnerEdit" szeles=true class="mezo-fontos"}
                    {if ($setup.partnerautocomplete)}
                        <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete mattable-important"
                               value="{$egyed.partnernev}" size=90>
                        <input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partner}">
                    {else}
                        <select id="PartnerEdit" name="partner" class="js-partnerid mattable-important">
                            <option value="">{at('válasszon')}</option>
                            {foreach $partnerlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    {/if}
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