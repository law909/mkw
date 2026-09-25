{extends "../base.tpl"}

{block "inhead"}
    {include 'ckeditor.tpl'}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    <script type="text/javascript" src="/js/admin/default/termekmenulista.js"></script>
{/block}

{block "kozep"}
    <div id="termekmenupanel" class="ui-widget ui-widget-content ui-corner-all mattkarb">
        <div class="mattable-titlebar ui-widget-header ui-corner-top ui-helper-clearfix">
            <h3>{at('Termékmenük')}</h3>
        </div>
        <div class="termekmenufa-sav">
            <label for="TermekMenuFaEdit">{at('Menü')}:</label>
            <select id="TermekMenuFaEdit">
                {foreach $termekmenufalist as $_fa}
                    <option value="{$_fa.id}"{if ($_fa.selected)} selected="selected"{/if}>{$_fa.caption|escape}</option>
                {/foreach}
            </select>
            <a href="#" class="js-termekmenufanew">{at('Új menü')}</a>
            <a href="#" class="js-termekmenufarename">{at('Átnevez')}</a>
            <a href="#" class="js-termekmenufacopy">{at('Másol')}</a>
            <a href="#" class="js-termekmenufadelete">{at('Töröl')}</a>
            <span class="mattkarb-hint">{at('Hogy melyik webshop melyik menüt mutatja, a Beállításokban, a webshop „Menü forrása” mezőjében állítható.')}</span>
        </div>
        <div id="termekmenu" data-fa="{$termekmenufa}"></div>
    </div>
    <div id="termekmenukarb"></div>
{/block}
