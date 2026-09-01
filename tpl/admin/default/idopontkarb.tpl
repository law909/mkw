{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/clipboard.min.js"></script>
    <script type="text/javascript" src="/js/admin/default/idopont.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'idopontkarbform.tpl'}
    </div>
{/block}
