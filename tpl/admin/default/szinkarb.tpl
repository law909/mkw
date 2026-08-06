{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    <script type="text/javascript" src="/js/admin/default/szin.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'szinkarbform.tpl'}
    </div>
{/block}