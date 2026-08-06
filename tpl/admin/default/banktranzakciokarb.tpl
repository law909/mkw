{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/banktranzakcio.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'banktranzakciokarbform.tpl'}
    </div>
{/block}