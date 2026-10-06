{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/bizonylattetellista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
            <h3>{at('Bizonylattétel lista')}</h3>
        </div>
        <form id="bizonylattetel" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div class="szuropanel">
                    <fieldset class="mattkarb-doboz szurodoboz szurodoboz-fo">
                        <legend>{at('Időszak és bizonylat')}</legend>
                        {include "comp_idoszak.tpl" comptype="hataridos"}
                        {include "comp_bizonylatstatusz.tpl"}
                        {include "comp_bizonylatstatuszcsoport.tpl"}
                        {include "comp_fizmodselect.tpl"}
                        <div>
                            <label for="RaktarEdit">{at('Raktár')}:</label>
                            <select id="RaktarEdit" name="raktar">
                                <option value="0">{at('válasszon')}</option>
                                {foreach $raktarlista as $raktar}
                                    <option value="{$raktar.id}">{$raktar.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        {include "comp_webshopfilter.tpl"}
                        <div class="szurodoboz-alcim">{at('Bizonylattípus')} ({at('ha egy sincs bejelölve: mind')}):</div>
                        {include "comp_bizonylattipus.tpl"}
                    </fieldset>
                    <fieldset class="mattkarb-doboz szurodoboz">
                        <legend>{at('Partner')}</legend>
                        {include "comp_partnerselect.tpl"}
                        {include "comp_partnertipusselect.tpl"}
                        {include "comp_uzletkotoselect.tpl"}
                    </fieldset>
                    <fieldset class="mattkarb-doboz szurodoboz">
                        <legend>{at('Termék')}</legend>
                        <div>
                            <label for="NevEdit">{at('Termék')}:</label>
                            <input id="NevEdit" type="text" name="nevfilter">
                        </div>
                        {include "comp_gyartoselect.tpl"}
                        {if ($setup.multilang)}
                            {include "comp_nyelvselect.tpl"}
                        {/if}
                        <div>
                            <label for="KeszletkellEdit">{at('Készlet kell')}:</label>
                            <input id="KeszletkellEdit" type="checkbox" name="keszletkell">
                        </div>
                        <div>
                            <label for="CsakfoglalasEdit">{at('Csak foglalás')}:</label>
                            <input id="CsakfoglalasEdit" type="checkbox" name="csakfoglalas">
                        </div>
                    </fieldset>
                    <fieldset class="mattkarb-doboz szurodoboz">
                        <legend>{at('Megjelenítés')}</legend>
                        <div>
                            <label for="CsoportositasEdit">{at('Csoportosítás')}:</label>
                            <select id="CsoportositasEdit" name="csoportositas">
                                <option value="1">{at('termékenként')}</option>
                                <option value="2">{at('partnerenként/termékenként')}</option>
                                <option value="3">{at('üzletkötőnként/partnerenként')}</option>
                                <option value="4">{at('bizonylatonként')}</option>
                            </select>
                        </div>
                        <div>
                            <label for="ErtekEdit">{at('Érték')}:</label>
                            <select id="ErtekEdit" name="ertektipus">
                                <option value="0">{at('nincs')}</option>
                                <option value="1">{at('bizonylaton szereplő nettó')}</option>
                                <option value="2">{at('bizonylaton szereplő bruttó')}</option>
                                <option value="3">{at('bizonylaton szereplő nettó HUF')}</option>
                                <option value="4">{at('bizonylaton szereplő bruttó HUF')}</option>
                                {if ($setup.arsavok)}
                                    <option value="5">{at('választott ársáv nettó')}</option>
                                    <option value="6">{at('választott ársáv bruttó')}</option>
                                {else}
                                    <option value="7">{at('eladási ár nettó')}</option>
                                    <option value="8">{at('eladási ár bruttó')}</option>
                                {/if}
                            </select>
                        </div>
                        {if ($setup.arsavok)}
                            {include "comp_arsavselect.tpl"}
                        {/if}
                    </fieldset>
                    {if ($cimkekat|default)}
                        <fieldset class="mattkarb-doboz szurodoboz szurodoboz-szeles">
                            <legend>{at('Partnercímkék')}</legend>
                            {include "comp_partnercimkefilter.tpl"}
                        </fieldset>
                    {/if}
                    <fieldset class="mattkarb-doboz szurodoboz szurodoboz-szeles">
                        <legend>{at('Termék kategóriák')}</legend>
                        {include "comp_termekfa.tpl"}
                    </fieldset>
                </div>
                <div class="matt-hseparator"></div>
                <a href="#" class="js-refresh">{at('Frissít')}</a>
                <a href="/admin/bizonylattetellista/export" class="js-exportbutton">{at('Export')}</a>
                <a href="/admin/bizonylattetellista/print" class="js-print">{at('Nyomtat')}</a>
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}