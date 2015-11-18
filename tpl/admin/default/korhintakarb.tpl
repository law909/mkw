{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/ajaxupload.js"></script>
<script type="text/javascript" src="/js/admin/default/korhinta.js"></script>
{/block}

{block "kozep"}
<div id="mattkarb">
{include 'korhintakarbform.tpl'}
</div>
{/block}