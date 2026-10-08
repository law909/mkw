<div id="mattkarb-header">
    <h3>{at('Szállítási mód')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#HatarTab">{at('Összeghatárok')}</a></li>
            <li><a href="#OrszagTab">{at('Összeghatárok országonként')}</a></li>
            <li><a href="#FizmodTab">{at('Fiz.mód növelők')}</a></li>
            <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit" szeles=true}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}">
                {/mezo}
                {mezo cimke="Leírás" for="LeirasEdit" szeles=true}
                    <textarea id="LeirasEdit" name="leiras">{$egyed.leiras}</textarea>
                {/mezo}
                {mezo cimke="Típus" for="TipusEdit" szeles=true}
                    <select id="TipusEdit" name="tipus" class="js-tipus">
                        <option value="">{at('válasszon')}</option>
                        {foreach $tipuslist as $_tipusid => $_tipusnev}
                            <option value="{$_tipusid}"{if ($_tipusid == $egyed.tipus)} selected="selected"{/if}>{$_tipusnev}</option>
                        {/foreach}
                    </select>
                {/mezo}
                {mezo cimke="Terminál típus" for="TerminaltipusEdit" szeles=true}
                    <input id="TerminaltipusEdit" name="terminaltipus" type="text" size="80" maxlength="20" value="{$egyed.terminaltipus}"
                        class="js-terminaltipus"{if ($egyed.tipus == 'glscsomagpont')} readonly="readonly"{/if}>
                {/mezo}
                {mezo cimke="Fizetési módok" for="FizmodEdit" szeles=true}
                    <input id="FizmodEdit" name="fizmodok" type="text" size="80" maxlength="255" value="{$egyed.fizmodok}">
                {/mezo}
                {mezo cimke="Sorrend" for="SorrendEdit"}
                    <input id="SorrendEdit" name="sorrend" type="number" value="{$egyed.sorrend}">
                {/mezo}
                {if ($setup.multishop)}
                    {mezo cimke="Webes" for="WebesEdit" ujsor=true}
                        <input id="WebesEdit" name="webes" type="checkbox"{if ($egyed.webes)} checked="checked"{/if}>
                    {/mezo}
                    {mezo cimke="Webes 2" for="Webes2Edit"}
                        <input id="Webes2Edit" name="webes2" type="checkbox"{if ($egyed.webes2)} checked="checked"{/if}>
                    {/mezo}
                    {mezo cimke="Webes 3" for="Webes3Edit"}
                        <input id="Webes3Edit" name="webes3" type="checkbox"{if ($egyed.webes3)} checked="checked"{/if}>
                    {/mezo}
                    {mezo cimke="Webes 4" for="Webes4Edit"}
                        <input id="Webes4Edit" name="webes4" type="checkbox"{if ($egyed.webes4)} checked="checked"{/if}>
                    {/mezo}
                {else}
                    {mezo cimke="Webes" for="WebesEdit"}
                        <input id="WebesEdit" name="webes" type="checkbox"{if ($egyed.webes)} checked="checked"{/if}>
                    {/mezo}
                {/if}
                {mezo cimke="Van száll.költség" for="VanSzallktgEdit"}
                    <input id="VanSzallktgEdit" name="vanszallitasiktg" type="checkbox"{if ($egyed.vanszallitasiktg)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Szállítási díj a szolgáltatótól jön" for="SzolgaltatoiSzallitasiDijEdit"}
                    <input id="SzolgaltatoiSzallitasiDijEdit" name="szolgaltatoiszallitasidij" type="checkbox"{if ($egyed.szolgaltatoiszallitasidij)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Csomagpont" for="CsomagpontEdit"}
                    <input id="CsomagpontEdit" name="csomagpont" type="checkbox"{if ($egyed.csomagpont)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Kezelési költség" for="TermekSelect"}
                    <div class="mattkarb-mezogomb">
                        {if ($setup.termekautocomplete)}
                            <input id="TermekSelect" type="text" name="termeknev"
                                   class="js-termekselect termekselect mattable-important" value="{$egyed.termeknev}">
                            <input class="js-termekid" name="termek" type="hidden" value="{$egyed.termek}">
                            <button type="button" class="js-termekclear">Töröl</button>
                        {else}
                            <select class="js-termekselectreal js-termekid" name="termek">
                                <option value="">{t('válasszon')}</option>
                                {foreach $egyed.termeklist as $_termekadat}
                                    <option
                                        value="{$_termekadat.id}"{if ($_termekadat.id == $egyed.termek)} selected="selected"{/if}>{$_termekadat.caption}</option>
                                {/foreach}
                            </select>
                        {/if}
                    </div>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="HatarTab" class="mattkarb-page" data-visible="visible">
            {foreach $egyed.hatarok as $hatar}
                {include 'szallitasimodhatarkarb.tpl'}
            {/foreach}
            <a class="js-hatarnewbutton" href="#" title="{at('Új')}">
                <span class="ui-icon ui-icon-circle-plus"></span>
            </a>
        </div>
        <div id="OrszagTab" class="mattkarb-page" data-visible="visible">
            {foreach $egyed.orszagok as $orszag}
                {include 'szallitasimodorszagkarb.tpl'}
            {/foreach}
            <a class="js-orszagnewbutton" href="#" title="{at('Új')}">
                <span class="ui-icon ui-icon-circle-plus"></span>
            </a>
        </div>
        <div id="FizmodTab" class="mattkarb-page" data-visible="visible">
            {foreach $egyed.fizmodnovelok as $fizmod}
                {include 'szallitasimodfizmodnovelokarb.tpl'}
            {/foreach}
            <a class="js-fizmodnewbutton" href="#" title="{at('Új')}">
                <span class="ui-icon ui-icon-circle-plus"></span>
            </a>
        </div>
        <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Név" for="NevL1Edit"}
                    <input id="NevL1Edit" name="nev_l1" type="text" size="80" maxlength="255" value="{$egyed.nev_l1}">
                {/mezo}
                {mezo cimke="Leírás" for="LeirasL1Edit" szeles=true}
                    <textarea id="LeirasL1Edit" name="leiras_l1">{$egyed.leiras_l1}</textarea>
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