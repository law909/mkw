{extends "../base.tpl"}

{block "inhead"}
    {include 'ckeditor.tpl'}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    <script type="text/javascript" src="/js/admin/default/ajaxupload.js"></script>
    <script type="text/javascript" src="/js/admin/default/termekmenulista.js"></script>
{/block}

{block "kozep"}
    <div id="termekmenu" class="ui-widget-content"></div>
    <div id="termekmenukarb"></div>
{/block}