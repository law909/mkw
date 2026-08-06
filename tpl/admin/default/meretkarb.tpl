{extends "../base.tpl"}

{block "inhead"}
    {include 'ckeditor.tpl'}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
    <script type="text/javascript" src="/js/admin/default/meret.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'meretkarbform.tpl'}
    </div>
{/block}