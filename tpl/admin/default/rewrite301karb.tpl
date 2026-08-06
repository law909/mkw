{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/rewrite301.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'rewrite301karbform.tpl'}
    </div>
{/block}
