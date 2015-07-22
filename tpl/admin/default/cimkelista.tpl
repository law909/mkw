{extends "base.tpl"}

{block "inhead"}
{include "ckeditor.tpl"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
<script type="text/javascript" src="/js/admin/default/ajaxupload.js"></script>
<script type="text/javascript" src="/js/admin/default/{$controllerscript}"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{t('Frissítés')}" data-caption="{t('Címkék')}"></div>
<div id="mattable-filterwrapper">
	<label for="nevfilter">{t('Szűrés')}</label>
	<input id="nevfilter" name="nevfilter" type="text" size="30" maxlength="255">
	<label for="ckfilter">{t('Címkecsoport')}</label>
	<select id="ckfilter" name="ckfilter">
		<option value="">{t('válasszon')}</option>
		{foreach $cimkecsoportlist as $_ck}
		<option value="{$_ck.id}">{$_ck.caption}</option>
		{/foreach}
	</select>
</div>
<div class="mattable-pagerwrapper">
	<div class="mattable-order">
	<label for="cos1">{t('Rendezés')}</label>
	<select id="cos1" class="mattable-orderselect">
		{foreach $orderselect as $_os}
		<option value="{$_os.id}"{if ($_os.selected)} selected="selected"{/if}>{$_os.caption}</option>
		{/foreach}
	</select>
	</div>
</div>
<div class="mattable-batch">
	{t('Csoportos művelet')} <select class="mattable-batchselect">
	<option value="">{t('válasszon')}</option>
	{foreach $batchesselect as $_batch}
	<option value="{$_batch.id}">{$_batch.caption}</option>
	{/foreach}
	</select>
</div>
<table id="mattable-table">
<thead>
	<tr>
	<th><input class="js-maincheckbox" type="checkbox"></th>
	<th>{t('Név')}</th>
	<th>{t('Címkecsoport')}</th>
	<th>{t('Hol látható')}</th>
	</tr>
</thead>
<tbody id="mattable-body"></tbody>
</table>
<div class="mattable-batch">
	{t('Csoportos művelet')} <select class="mattable-batchselect">
	<option value="">{t('válasszon')}</option>
	{foreach $batchesselect as $_batch}
	<option value="{$_batch.id}">{$_batch.caption}</option>
	{/foreach}
	</select>
</div>
<div class="mattable-pagerwrapper ui-corner-bottom">
	<div class="mattable-order">
	<label for="cos1">{t('Rendezés')}</label>
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