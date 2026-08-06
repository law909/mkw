{extends "../base.tpl"}

{block "inhead"}
    {include 'ckeditor.tpl'}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/emailtemplate.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'emailtemplatekarbform.tpl'}
    </div>
{/block}