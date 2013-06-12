{extends "base.tpl"}

{block "inhead"}
<link rel="stylesheet" type="text/css" media="screen" href="/themes/admin/{$theme}/ui.jqgrid.css" />
<script type="text/javascript" src="/js/admin/default/grid.locale-hu.js"></script>
<script type="text/javascript">
$.jgrid.useJSON=true;
</script>
<script type="text/javascript" src="/js/admin/default/jqgrid.js"></script>
<script type="text/javascript" src="/js/admin/default/szamlatukorlista.js"></script>
{/block}

{block "kozep"}
<table id="szamlatukorgrid"></table>
<div id="szamlatukorgridpager"></div>
{/block}