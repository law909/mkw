{extends "../base.tpl"}

{block "inhead"}
    {include 'ckeditor.tpl'}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    <script type="text/javascript" src="/js/admin/default/csapat.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'csapatkarbform.tpl'}
    </div>
{/block}
