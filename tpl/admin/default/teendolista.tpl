{extends "../base.tpl"}

{block "inhead"}
{include "ckeditor.tpl"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/teendo.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Teendők')}"></div>
<div id="mattable-filterwrapper">
	<label for="bejegyzesfilter">{at('Szűrés')}</label>
	<input id="bejegyzesfilter" name="bejegyzesfilter" type="text" size="30" maxlength="255">
	<label for="dtfilter">{at('Dátum')}</label>
	<input id="dtfilter" name="dtfilter" type="text" size="12"> -
	<input id="difilter" name="difilter" type="text" size="12">
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
	<th><input class="js-maincheckbox" type="checkbox"></th>
	<th>{at('Teendő')}</th>
	<th>{at('Partner')}</th>
	<th>{at('Esedékes')}</th>
	<th>{at('Elvégezve')}</th>
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