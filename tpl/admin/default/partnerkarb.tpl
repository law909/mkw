{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/dokumentumtar.js"></script>
    <script type="text/javascript" src="/js/admin/default/partner.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'partnerkarbform.tpl'}
    </div>
{/block}