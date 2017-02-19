{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
<script type="text/javascript" src="/js/admin/default/uzletkoto.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Üzletkötők')}"></div>
<div id="mattable-filterwrapper">
	<div>
	<label for="nevfilter">{at('Szűrés')}</label>
	<input id="nevfilter" name="nevfilter" type="text" size="30" maxlength="255">
	</div>
</div>
<div class="mattable-pagerwrapper">
	<div class="mattable-order">
	<label for="tos1">{at('Rendezés')}</label>
	<select id="tos1" class="mattable-orderselect">
		{foreach $orderselect as $_os}
		<option value="{$_os.id}"{if ($_os.selected)} selected="selected"{/if}>{$_os.caption}</option>
		{/foreach}
	</select>
	</div>
</div>
<table id="mattable-table">
<thead>
	<tr>
	<th>{at('Név')}</th>
	<th>{at('Cím')}</th>
	<th>{at('Elérhetőségek')}</th>
	</tr>
</thead>
<tbody id="mattable-body"></tbody>
</table>
<div class="mattable-pagerwrapper ui-corner-bottom">
	<div class="mattable-order">
	<label for="tos2">{at('Rendezés')}</label>
	<select id="tos2" class="mattable-orderselect">
		{foreach $orderselect as $_os}
		<option value="{$_os.id}"{if ($_os.selected)} selected="selected"{/if}>{$_os.caption}</option>
		{/foreach}
	</select>
	</div>
</div>
</div>
<div id="mattkarb">
</div>
{/block}