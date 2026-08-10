{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    {if ($pos|default)}
        <script type="text/javascript" src="/js/admin/default/bizonylatpos.js"></script>
    {/if}
    <script type="text/javascript" src="/js/admin/default/bizonylathelper.js?v=5"></script>
    <script type="text/javascript" src="/js/admin/default/{$controllerscript}"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'bizonylatfejkarbform.tpl'}
    </div>
{/block}