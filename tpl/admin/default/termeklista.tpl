{extends "base.tpl"}

{block "inhead"}
{include 'ckeditor.tpl'}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
<script type="text/javascript" src="/js/admin/default/ajaxupload.js"></script>
<script type="text/javascript" src="/js/admin/default/termek.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{t('Frissítés')}" data-caption="{t('Termékek')}"></div>
<div id="mattable-filterwrapper">
	<label for="nevfilter">{t('Szűrés')}</label>
	<input id="nevfilter" name="nevfilter" type="text" size="30" maxlength="255">
    <label for="lathatofilter">
	<div class="matt-hseparator"></div>
	<div id="termekfa" class="mattable-filterwrapper ui-widget-content"></div>
	<div class="matt-hseparator"></div>
	<div id="cimkefiltercontainer">
	<div id="cimkefiltercontainerhead"><a id="cimkefiltercollapse" href="#" data-visible="visible">{t('Kinyit/becsuk')}</a></div>
	{foreach $cimkekat as $_cimkekat}
	<div class="mattedit-titlebar ui-widget-header ui-helper-clearfix js-cimkefiltercloseupbutton" data-refcontrol="#{$_cimkekat.sanitizedcaption}">
		<a href="#" class="mattedit-titlebar-close" >
			<span class="ui-icon ui-icon-circle-triangle-n"></span>
		</a>
		<span>{$_cimkekat.caption}</span>
	</div>
	<div id="{$_cimkekat.sanitizedcaption}" class="accordpage cimkelista" data-visible="visible">
		{foreach $_cimkekat.cimkek as $_cimke}
		<a class="js-cimkefilter" href="#" data-id="{$_cimke.id}">{$_cimke.caption}</a>&nbsp;&nbsp;
		{/foreach}
	</div>
	{/foreach}
	</div>
</div>
<div class="mattable-pagerwrapper">
	<div class="mattable-order">
	<label for="tos1">{t('Rendezés')}</label>
	<select id="tos1" class="mattable-orderselect">
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
	<th>{t('Címkék')}</th>
	<th>{t('Jellemzők')}</th>
	{if ($setup.grideditbutton=='big')}
	<th></th>
	{/if}
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
	<label for="tos2">{t('Rendezés')}</label>
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
<div id="termekfakarb"></div>
{/block}