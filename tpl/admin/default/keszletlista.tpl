{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/keszletlista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{t('Készlet')}</h3>
        </div>
        <div{if ($setup.editstyle=='tab')} id="mattkarb-tabs"{/if}>
            {if ($setup.editstyle=='tab')}
                <ul>
                    <li><a href="#DefaTab">{t('Készlet')}</a></li>
                </ul>
            {/if}
            {if ($setup.editstyle=='dropdown')}
                <div class="mattkarb-titlebar" data-caption="{t('Készlet')}" data-refcontrol="#DefaTab"></div>
            {/if}
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="keszlet" action="" target="_blank">
                    <div>
                        <label for="DatumEdit">{t('Dátum')}:</label>
                        <input id="DatumEdit" name="datum" data-datum="{$datum}">
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_raktarselect.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="KeszletEdit">{t('Készlet')}:</label>
                        <select id="KeszletEdit" name="keszlet">
                            <option value="1">minden</option>
                            <option value="2">ami van</option>
                            <option value="3">ami nincs</option>
                            <option value="4">ami negatív</option>
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="NevEdit">{t('Termék')}:</label>
                        <input id="NevEdit" type="text" name="nevfilter">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="FoglalasEdit">{t('Foglalás számít')}:</label>
                        <input id="FoglalasEdit" type="checkbox" name="foglalasszamit">
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_termekfa.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <input type="hidden" name="fafilter">
                        <a href="/admin/keszletlista/get" class="js-okbutton">OK</a>
                        <a href="/admin/keszletlista/export" class="js-exportbutton">Export</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}