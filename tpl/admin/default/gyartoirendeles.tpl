{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/gyartoirendeles.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Gyártói rendelés')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Gyártói rendelés')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="gyartoirendeles" action="" target="_blank">
                    <div>
                        <label for="DatumEdit">{at('Dátum')}:</label>
                        <input id="DatumEdit" name="datum" data-datum="{$datum}">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="GyartoEdit">{at('Gyártó')}:</label>
                        <select id="GyartoEdit" name="gyarto" class="mattable-important" required="required">
                            <option value="">{at('válassz')}</option>
                            {foreach $gyartolist as $_gy}
                                <option value="{$_gy.id}">{$_gy.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="RaktarEdit">{at('Raktár')}:</label>
                        <select id="RaktarEdit" name="raktar">
                            <option value="0">{at('Céges készlet')}</option>
                            {foreach $raktarlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_termekfa.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <input type="hidden" name="fafilter">
                        <a href="/admin/gyartoirendeles/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/gyartoirendeles/export" class="js-exportbutton">{at('Export')}</a>
                        <a href="/admin/gyartoirendeles/createbizonylat" class="js-bizonylatbutton">{at('Szállítói megrendelés')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}
