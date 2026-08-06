{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/elallas.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'elallaskarbform.tpl'}
    </div>
{/block}
