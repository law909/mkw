{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/bizonylattetellista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Bizonylattétel lista')}</h3>
        </div>
        <form id="bizonylattetel" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div class="matt-hseparator"></div>
                {include "comp_idoszak.tpl" comptype="hataridos"}
                <div class="matt-hseparator"></div>
                {include "comp_partnerselect.tpl"}
                <div class="matt-hseparator"></div>
                <div class="balra termekforgalmibal">
                    <div>
                        <label for="RaktarEdit">{at('Raktár')}:</label>
                        <select id="RaktarEdit" name="raktar">
                            <option value="0">{at('válasszon')}</option>
                            {foreach $raktarlista as $raktar}
                                <option value="{$raktar.id}">{$raktar.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_uzletkotoselect.tpl"}
                    <div class="matt-hseparator"></div>
                    {include "comp_gyartoselect.tpl"}
                    <div class="matt-hseparator"></div>
                    {include "comp_fizmodselect.tpl"}
                    <div class="matt-hseparator"></div>
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
                    <div class="matt-hseparator"></div>
                    {include "comp_arsavselect.tpl"}
                    {/if}
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="NevEdit">{at('Termék')}:</label>
                        <input id="NevEdit" type="text" name="nevfilter">
                    </div>
                    {if ($setup.multilang)}
                        <div class="matt-hseparator"></div>
                        {include "comp_nyelvselect.tpl"}
                    {/if}
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="CsoportositasEdit">{at('Csoportosítás')}:</label>
                        <select id="CsoportositasEdit" name="csoportositas">
                            <option value="1">{at('termékenként')}</option>
                            <option value="2">{at('partnerenként/termékenként')}</option>
                            <option value="3">{at('üzletkötőnként/partnerenként')}</option>
                            <option value="4">{at('bizonylatonként')}</option>
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="KeszletkellEdit">{at('Készlet kell')}:</label>
                        <input id="KeszletkellEdit" type="checkbox" name="keszletkell">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="CsakfoglalasEdit">{at('Csak foglalás')}:</label>
                        <input id="CsakfoglalasEdit" type="checkbox" name="csakfoglalas">
                    </div>
                </div>
                <div>
                    {include "comp_bizonylattipus.tpl"}
                    <div class="matt-hseparator"></div>
                    {include "comp_bizonylatstatusz.tpl"}
                    <div class="matt-hseparator"></div>
                    {include "comp_bizonylatstatuszcsoport.tpl"}
                    <div class="matt-hseparator"></div>
                </div>
                <div class="matt-hseparator clearboth"></div>
                {include "comp_partnercimkefilter.tpl"}
                <div class="matt-hseparator"></div>
                {include "comp_termekfa.tpl"}
                <div class="matt-hseparator"></div>
                <input id="FaFilter" type="hidden" name="fafilter[]">
                <input id="PartnerCimkeFilter" type="hidden" name="partnercimkefilter[]">
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