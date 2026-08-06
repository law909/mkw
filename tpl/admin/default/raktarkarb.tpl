{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/raktar.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        {include 'raktarkarbform.tpl'}
    </div>
{/block}
