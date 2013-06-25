{extends "base.tpl"}

{block "inhead"}
{include 'ckeditor.tpl'}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/esemeny.js"></script>
{/block}

{block "kozep"}
<div id="mattkarb">
{include 'esemenykarbform.tpl'}
</div>
{/block}