{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/termekcsoport.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'termekcsoportkarbform.tpl'}
    </div>
{/block}
