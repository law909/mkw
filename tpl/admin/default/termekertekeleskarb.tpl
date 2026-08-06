{extends "../base.tpl"}

{block "inhead"}
    {include 'ckeditor.tpl'}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/termekertekeles.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'termekertekeleskarbform.tpl'}
    </div>
{/block}