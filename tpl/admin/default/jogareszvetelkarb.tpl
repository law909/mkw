{extends "../base.tpl"}

{block "inhead"}
{include 'ckeditor.tpl'}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/darshan/jogareszvetelkarb.js"></script>
{/block}

{block "kozep"}
<div id="mattkarb">
{include 'jogareszvetelkarbform.tpl'}
</div>
{/block}