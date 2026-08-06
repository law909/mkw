{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/raktar.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'raktarkarbform.tpl'}
    </div>
{/block}
