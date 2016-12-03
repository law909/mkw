{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/kintlevoseglista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{t('Kintlevőség')}</h3>
        </div>
        <div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
            {if ($setup.editstyle=='tab')}
                <ul>
                    <li><a href="#DefaTab">{t('Kintlevőség')}</a></li>
                </ul>
            {/if}
            {if ($setup.editstyle=='dropdown')}
                <div class="mattkarb-titlebar" data-caption="{t('Kintlevőség')}" data-refcontrol="#DefaTab"></div>
            {/if}
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="kintlevoseg" action="" target="_blank">
                    {include "comp_idoszak.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="BefEdit">{t('A befizetéseket')}</label>
                        <input id="BefEdit" name="befdatum" data-datum="{$toldatum}">
                        <span>{t('-ig kell figyelembe venni.')}</span>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="LejartFilterEdit">{t('Lejárat')}:</label>
                        <select id="LejartFilterEdit" name="lejartfilter">
                            <option value="1">mind</option>
                            <option value="2">lejárt</option>
                            <option value="3">nem lejárt</option>
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="ReszletesSumCB">{t('Részletes összesítő kell')}</label>
                        <input id="ReszletesSumCB" name="reszletessum" type="checkbox">
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_partnerselect.tpl"}
                    <div class="matt-hseparator"></div>
                    {include "comp_uzletkotoselect.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="SorrendEdit">{t('Sorrend')}:</label>
                        <select id="SorrendEdit" name="sorrend">
                            <option value="1">kelt</option>
                            <option value="2">esedékesség</option>
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_partnercimkefilter.tpl"}
                    <div class="matt-hseparator"></div>

                    <div>
                        <a href="/admin/kintlevoseglista/get" class="js-okbutton">OK</a>
                        <a href="/admin/kintlevoseglista/export" class="js-exportbutton">Export</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}