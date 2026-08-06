{extends "../base.tpl"}

{block "inhead"}
    {include 'ckeditor.tpl'}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/teendo.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'teendokarbform.tpl'}
    </div>
{/block}