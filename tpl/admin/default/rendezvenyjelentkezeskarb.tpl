{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/rendezvenyjelentkezes.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'rendezvenyjelentkezeskarbform.tpl'}
    </div>
{/block}