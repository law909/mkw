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
                    {mezocsoport cim="Rendelés"}
                        {include "comp_datum.tpl" mezo=true ujsor=true}
                        {mezo cimke="Gyártó" for="GyartoEdit"}
                            <select id="GyartoEdit" name="gyarto" class="mattable-important" required="required">
                                <option value="">{at('válassz')}</option>
                                {foreach $gyartolist as $_gy}
                                    <option value="{$_gy.id}">{$_gy.caption}</option>
                                {/foreach}
                            </select>
                        {/mezo}
                        {mezo cimke="Raktár" for="RaktarEdit" ujsor=true}
                            <select id="RaktarEdit" name="raktar">
                                <option value="0">{at('Céges készlet')}</option>
                                {foreach $raktarlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        {/mezo}
                        {include "comp_termekfa.tpl" mezo=true}
                    {/mezocsoport}
                    <input type="hidden" name="fafilter">
                    <div class="arsav-gombok">
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
