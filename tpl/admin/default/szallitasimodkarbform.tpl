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
            {if ($setup.multilang)}
                <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
            {/if}
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td colspan="5"><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Leírás')}:</label></td>
                    <td colspan="5"><textarea id="LeirasEdit" name="leiras">{$egyed.leiras}</textarea></td>
                </tr>
                <tr>
                    <td><label for="TerminaltipusEdit">{at('Terminál típus')}:</label></td>
                    <td colspan="5"><input id="TerminaltipusEdit" name="terminaltipus" type="text" size="80" maxlength="20" value="{$egyed.terminaltipus}"></td>
                </tr>
                <tr>
                    <td><label for="FizmodEdit">{at('Fizetési módok')}:</label></td>
                    <td colspan="5"><input id="FizmodEdit" name="fizmodok" type="text" size="80" maxlength="255" value="{$egyed.fizmodok}"></td>
                </tr>
                <tr>
                    <td><label for="WcidEdit">{at('WooCommerce ID')}:</label></td>
                    <td><input id="WcidEdit" name="wcid" type="text" size="80" maxlength="255" value="{$egyed.wcid}"></td>
                </tr>
                <tr>
                    <td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
                    <td colspan="5"><input id="SorrendEdit" name="sorrend" type="number" value="{$egyed.sorrend}"></td>
                </tr>
                {if ($setup.multishop)}
                    <tr>
                        <td><label for="WebesEdit">{at('Webes')}:</label></td>
                        <td><input id="WebesEdit" name="webes" type="checkbox"{if ($egyed.webes)} checked="checked"{/if}></td>
                        <td><label for="Webes2Edit">{at('Webes 2')}:</label></td>
                        <td><input id="Webes2Edit" name="webes2" type="checkbox"{if ($egyed.webes2)} checked="checked"{/if}></td>
                        <td><label for="Webes3Edit">{at('Webes 3')}:</label></td>
                        <td><input id="Webes3Edit" name="webes3" type="checkbox"{if ($egyed.webes3)} checked="checked"{/if}></td>
                        <td><label for="Webes4Edit">{at('Webes 4')}:</label></td>
                        <td><input id="Webes4Edit" name="webes4" type="checkbox"{if ($egyed.webes4)} checked="checked"{/if}></td>
                    </tr>
                {else}
                    <tr>
                        <td><label for="WebesEdit">{at('Webes')}:</label></td>
                        <td><input id="WebesEdit" name="webes" type="checkbox"{if ($egyed.webes)} checked="checked"{/if}></td>
                    </tr>
                {/if}
                <tr>
                    <td><label for="VanSzallktgEdit">{at('Van száll.költség')}:</label></td>
                    <td><input id="VanSzallktgEdit" name="vanszallitasiktg" type="checkbox"{if ($egyed.vanszallitasiktg)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="TermekSelect">{at('Kezelési költség')}:</label></td>
                    <td>
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
                    </td>
                </tr>
                </tbody>
            </table>
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
        {if ($setup.multilang)}
            <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
                {foreach $egyed.translations as $translation}
                    {include 'translationkarb.tpl'}
                {/foreach}
                <a class="js-translationnewbutton" href="#" title="{at('Új')}">
                    <span class="ui-icon ui-icon-circle-plus"></span>
                </a>
            </div>
        {/if}
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>