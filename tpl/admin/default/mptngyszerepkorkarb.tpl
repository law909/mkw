{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/mptngyszerepkor.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'mptngyszerepkorkarbform.tpl'}
    </div>
{/block}
