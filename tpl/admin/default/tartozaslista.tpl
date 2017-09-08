{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/tartozaslista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Tartozás')}</h3>
        </div>
        <div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
            {if ($setup.editstyle=='tab')}
                <ul>
                    <li><a href="#DefaTab">{at('Kintlevőség')}</a></li>
                </ul>
            {/if}
            {if ($setup.editstyle=='dropdown')}
                <div class="mattkarb-titlebar" data-caption="{at('Tartozás')}" data-refcontrol="#DefaTab"></div>
            {/if}
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="tartozas" action="" target="_blank">
                    {include "comp_idoszak.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="BefEdit">{at('A befizetéseket')}</label>
                        <input id="BefEdit" name="befdatum" data-datum="{$toldatum}">
                        <span>{at('-ig kell figyelembe venni.')}</span>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="LejartFilterEdit">{at('Lejárat')}:</label>
                        <select id="LejartFilterEdit" name="lejartfilter">
                            <option value="1">{at('mind')}</option>
                            <option value="2">{at('lejárt')}</option>
                            <option value="3">{at('nem lejárt')}</option>
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="ReszletesSumCB">{at('Részletes összesítő kell')}</label>
                        <input id="ReszletesSumCB" name="reszletessum" type="checkbox">
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_partnerselect.tpl"}
                    <div class="matt-hseparator"></div>
                    {include "comp_dolgozoselect.tpl"}
                    <div class="matt-hseparator"></div>
                    {include "comp_uzletkotoselect.tpl"}
                    <div class="matt-hseparator"></div>
                    {include "comp_fizmodselect.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="SorrendEdit">{at('Sorrend')}:</label>
                        <select id="SorrendEdit" name="sorrend">
                            <option value="1">{at('kelt')}</option>
                            <option value="2">{at('esedékesség')}</option>
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_partnercimkefilter.tpl"}
                    <div class="matt-hseparator"></div>

                    <div>
                        <a href="/admin/tartozaslista/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/tartozaslista/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}