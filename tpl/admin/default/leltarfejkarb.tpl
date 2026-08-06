{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/leltarfej.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'leltarfejkarbform.tpl'}
    </div>
{/block}