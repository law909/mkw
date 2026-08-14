{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/minkeszletlista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Minimum készlet alatt')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Minimum készlet alatt')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="minkeszlet" action="" target="_blank">
                    <div>
                        <label for="DatumEdit">{at('Dátum')}:</label>
                        <input id="DatumEdit" name="datum" data-datum="{$datum}">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="RaktarEdit">{at('Raktár')}:</label>
                        <select id="RaktarEdit" name="raktar" class="mattable-important" required="required">
                            <option value="0">{at('Céges készlet')}</option>
                            {foreach $raktarlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="MasikRaktarEdit">{at('Ebből a raktárból kiszolgálható')}:</label>
                        <select id="MasikRaktarEdit" name="masikraktar">
                            <option value="">{at('nem kell')}</option>
                            {foreach $masikraktarlist as $_mk}
                                <option value="{$_mk.id}">{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="KeszletEdit">{at('Készlet')}:</label>
                        <input id="KeszletEdit" name="keszlet" type="number" step="any" size="8" value="0">
                        <label for="KeszletSzamitEdit">{at('a minimum készlet helyett ezt figyelje')}:</label>
                        <input id="KeszletSzamitEdit" name="keszletszamit" type="checkbox" value="1"
                               {if ($keszletszamit)}checked="checked"{/if}>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="GyartoEdit">{at('Gyártó')}:</label>
                        <select id="GyartoEdit" name="gyarto">
                            <option value="">{at('mindegy')}</option>
                            {foreach $gyartolist as $_gy}
                                <option value="{$_gy.id}">{$_gy.caption}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_termekfa.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <input type="hidden" name="fafilter">
                        <a href="/admin/minkeszletlista/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/minkeszletlista/export" class="js-exportbutton">{at('Export')}</a>
                        <a href="/admin/minkeszletlista/exportbizonylat" class="js-exportbizonylatbutton">{at('Export bizonylathoz')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}
