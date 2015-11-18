{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
<script type="text/javascript" src="/js/admin/default/bankbizonylat.js"></script>
{/block}

{block "kozep"}
<div id="mattkarb">
{include 'bankbizonylatfejkarbform.tpl'}
</div>
{/block}