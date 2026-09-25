{extends "../base.tpl"}

{block "inhead"}
    {include 'ckeditor.tpl'}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    <script type="text/javascript" src="/js/admin/default/termekfalista.js"></script>
{/block}

{block "kozep"}
    <div id="termekfapanel" class="ui-widget ui-widget-content ui-corner-all mattkarb">
        <div class="mattable-titlebar ui-widget-header ui-corner-top ui-helper-clearfix">
            <h3>{at('Termék kategóriák')}</h3>
        </div>
        <div id="termekfa" class="treepanel-body"></div>
    </div>
    <div id="termekfakarb"></div>
{/block}