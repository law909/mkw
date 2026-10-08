<div id="mattkarb-header">
    <h3>{at('Fizetési mód')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#HatarTab">{at('Rugalmas határok')}</a></li>
            <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}">
                {/mezo}
                {mezo cimke="Leírás" for="LeirasEdit" szeles=true}
                    <textarea id="LeirasEdit" name="leiras" type="text">{$egyed.leiras}</textarea>
                {/mezo}
                {mezo cimke="Típus" for="TipusEdit"}
                    <select id="TipusEdit" name="tipus">
                        <option value="B"{if ($egyed.tipus == 'B')} selected="selected"{/if}>{at('Bank')}</option>
                        <option value="P"{if ($egyed.tipus == 'P')} selected="selected"{/if}>{at('Pénztár')}</option>
                    </select>
                {/mezo}
                {mezo cimke="NAV típus" for="NAVTipusEdit"}
                    <select id="NAVTipusEdit" name="navtipus">
                        <option value="">{at('válasszon')}</option>
                        <option value="TRANSFER"{if ($egyed.navtipus == 'TRANSFER')} selected="selected"{/if}>{at('TRANSFER')}</option>
                        <option value="CASH"{if ($egyed.navtipus == 'CASH')} selected="selected"{/if}>{at('CASH')}</option>
                        <option value="CARD"{if ($egyed.navtipus == 'CARD')} selected="selected"{/if}>{at('CARD')}</option>
                        <option value="VOUCHER"{if ($egyed.navtipus == 'VOUCHER')} selected="selected"{/if}>{at('VOUCHER')}</option>
                        <option value="OTHER"{if ($egyed.navtipus == 'OTHER')} selected="selected"{/if}>{at('OTHER')}</option>
                    </select>
                {/mezo}
                {mezo cimke="Haladék" for="HaladekEdit"}
                    <div class="mattkarb-mezogomb">
                        <input id="HaladekEdit" name="haladek" type="number" value="{$egyed.haladek}"> {at('nap')}
                    </div>
                {/mezo}
                {mezo cimke="Sorrend" for="SorrendEdit"}
                    <input id="SorrendEdit" name="sorrend" type="number" value="{$egyed.sorrend}">
                {/mezo}
                {mezo cimke="Webes" for="WebesEdit"}
                    <input id="WebesEdit" name="webes" type="checkbox"{if ($egyed.webes)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Inaktív" for="InaktivEdit"}
                    <input id="InaktivEdit" name="inaktiv" type="checkbox"{if ($egyed.inaktiv)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Nincs pénzmozgás" for="NincspenzmozgasEdit"}
                    <input id="NincspenzmozgasEdit" name="nincspenzmozgas" type="checkbox"{if ($egyed.nincspenzmozgas)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Rugalmas" for="RugalmasEdit"}
                    <input id="RugalmasEdit" name="rugalmas" type="checkbox"{if ($egyed.rugalmas)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Osztott haladék 1" for="Osztotthaladek1Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Osztotthaladek1Edit" name="osztotthaladek1" type="number" value="{$egyed.osztotthaladek1}"> {at('nap')}
                    </div>
                {/mezo}
                {mezo cimke="Osztott százalék 1" for="Osztottszazalek1Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Osztottszazalek1Edit" name="osztottszazalek1" type="number" step="any" value="{$egyed.osztottszazalek1}"> %
                    </div>
                {/mezo}
                {mezo cimke="Osztott haladék 2" for="Osztotthaladek2Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Osztotthaladek2Edit" name="osztotthaladek2" type="number" value="{$egyed.osztotthaladek2}"> {at('nap')}
                    </div>
                {/mezo}
                {mezo cimke="Osztott százalék 2" for="Osztottszazalek2Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Osztottszazalek2Edit" name="osztottszazalek2" type="number" step="any" value="{$egyed.osztottszazalek2}"> %
                    </div>
                {/mezo}
                {mezo cimke="Osztott haladék 3" for="Osztotthaladek3Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Osztotthaladek3Edit" name="osztotthaladek3" type="number" value="{$egyed.osztotthaladek3}"> {at('nap')}
                    </div>
                {/mezo}
                {mezo cimke="Osztott százalék 3" for="Osztottszazalek3Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Osztottszazalek3Edit" name="osztottszazalek3" type="number" step="any" value="{$egyed.osztottszazalek3}"> %
                    </div>
                {/mezo}
                {mezo cimke="Osztott haladék 4" for="Osztotthaladek4Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Osztotthaladek4Edit" name="osztotthaladek4" type="number" value="{$egyed.osztotthaladek4}"> {at('nap')}
                    </div>
                {/mezo}
                {mezo cimke="Osztott százalék 4" for="Osztottszazalek4Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Osztottszazalek4Edit" name="osztottszazalek4" type="number" step="any" value="{$egyed.osztottszazalek4}"> %
                    </div>
                {/mezo}
                {mezo cimke="Osztott haladék 5" for="Osztotthaladek5Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Osztotthaladek5Edit" name="osztotthaladek5" type="number" value="{$egyed.osztotthaladek5}"> {at('nap')}
                    </div>
                {/mezo}
                {mezo cimke="Osztott százalék 5" for="Osztottszazalek5Edit"}
                    <div class="mattkarb-mezogomb">
                        <input id="Osztottszazalek5Edit" name="osztottszazalek5" type="number" step="any" value="{$egyed.osztottszazalek5}"> %
                    </div>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="HatarTab" class="mattkarb-page" data-visible="visible">
            {foreach $egyed.hatarok as $hatar}
                {include 'fizmodfizmodhatarkarb.tpl'}
            {/foreach}
            <a class="js-hatarnewbutton" href="#" title="{at('Új')}">
                <span class="ui-icon ui-icon-circle-plus"></span>
            </a>
        </div>
        <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevL1Edit"}
                    <input id="NevL1Edit" name="nev_l1" type="text" size="80" maxlength="255" value="{$egyed.nev_l1}">
                {/mezo}
                {mezo cimke="Leírás" for="LeirasL1Edit" szeles=true}
                    <textarea id="LeirasL1Edit" name="leiras_l1" type="text">{$egyed.leiras_l1}</textarea>
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