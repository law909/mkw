{extends "../base.tpl"}

{block "inhead"}
{include 'ckeditor.tpl'}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/{$controllerscript}"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header"><h3><a class="mattable-tablerefresh" href="#" title="{at('Frissítés')}">{at('Felhasználók')}</a></h3></div>
<div id="mattable-filterwrapper">
	<label for="nevfilter">{at('Szűrés')}</label>
	<input id="nevfilter" name="nevfilter" type="text" size="30" maxlength="255">
</div>
<div class="mattable-pagerwrapper">
	<div class="mattable-order">
	<label for="cos1">{at('Rendezés')}</label>
	<select id="cos1" class="mattable-orderselect">
		{foreach $orderselect as $_os}
		<option value="{$_os.id}"{if ($_os.selected)} selected="selected"{/if}>{$_os.caption}</option>
		{/foreach}
	</select>
	</div>
</div>
<table id="mattable-table">
<thead>
	<tr>
	<th><input id="maincheckbox" type="checkbox"></th>
	<th>{at('Név')}</th>
	<th>{at('Felhasználónév')}</th>
	</tr>
</thead>
<tbody id="mattable-body"></tbody>
</table>
<div class="mattable-pagerwrapper ui-corner-bottom">
	<div class="mattable-order">
	<label for="cos1">{at('Rendezés')}</label>
	<select id="cos1" class="mattable-orderselect">
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