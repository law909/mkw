{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/template.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{t('Frissítés')}" data-caption="{t('Sablonok')}"></div>
<table id="mattable-table">
<thead>
	<tr>
	<th>{t('Név')}</th>
	</tr>
</thead>
<tbody id="mattable-body"></tbody>
</table>
</div>
<div id="mattkarb">
</div>
{/block}