{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/mptszekcio.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'mptszekciokarbform.tpl'}
    </div>
{/block}
