{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/termekforgalmilista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
            <h3>{at('Termékforgalmi lista')}</h3>
        </div>
        <form id="termekforgalmi" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div class="matt-hseparator"></div>
                {include "comp_idoszak.tpl" comptype="szamla"}
                <div class="matt-hseparator"></div>
                {include "comp_partnerselect.tpl"}
                <div class="matt-hseparator"></div>
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
                {include "comp_gyartoselect.tpl"}
                <div class="matt-hseparator"></div>
                <div>
                    <label for="KeszletEdit">{at('Készlet')}:</label>
                    <select id="KeszletEdit" name="keszletfilter">
                        <option value="0">{at('mindegy')}</option>
                        <option value="1">{at('ami időszak végén van')}</option>
                        <option value="2">{at('ami időszak végén nincs')}</option>
                        <option value="3">{at('ami időszak végén negatív')}</option>
                    </select>
                </div>
                <div class="matt-hseparator"></div>
                <div>
                    <label for="ForgalomEdit">{at('Forgalom')}:</label>
                    <select id="ForgalomEdit" name="forgalomfilter">
                        <option value="0">{at('mindegy')}</option>
                        <option value="1">{at('ami az időszakban mozgott')}</option>
                        <option value="2">{at('ami az időszakban nem mozgott')}</option>
                    </select>
                </div>
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
                {include "comp_partnercimkefilter.tpl"}
                <div class="matt-hseparator"></div>
                {include "comp_termekfa.tpl"}
                <div class="matt-hseparator"></div>
                <input id="FaFilter" type="hidden" name="fafilter[]">
                <input id="PartnerCimkeFilter" type="hidden" name="partnercimkefilter[]">
                <a href="#" class="js-refresh">{at('Frissít')}</a>
                <a href="/admin/termekforgalmilista/export" class="js-exportbutton">{at('Export')}</a>
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}