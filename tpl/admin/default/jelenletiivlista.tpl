{extends "base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/jelenletiiv.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{t('Frissítés')}" data-caption="{t('Jelenléti ívek')}"></div>
<div id="mattable-filterwrapper">
<table><tbody>
	<tr><td>
	<label for="tolfilter">{t('Időszak')}:</label>
	<input id="tolfilter" name="tolfilter" type="text" size="12">
	<input id="igfilter" name="igfilter" type="text" size="12">
	</td></tr>
	<tr><td>
	<label>{t('Dolgozó')}:</label>
	<select id="dolgozofilter" name="dolgozofilter">
	<option value="">{t('válasszon')}</option>
	{foreach $dolgozolist as $_mk}
	<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
	{/foreach}
	</select>
	<label>{t('Jelenlét típus')}:</label>
	<select id="jelenlettipusfilter" name="jelenlettipusfilter">
	<option value="">{t('válasszon')}</option>
	{foreach $jelenlettipuslist as $_mk}
	<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
	{/foreach}
	</select>
	</td></tr>
</tbody></table>
</div>
<div class="mattable-titlebar ui-widget-header ui-helper-clearfix">
	<label>{t('Jelenlét típus')}:</label>
	<select id="genjelenlettipus" name="genjelenlettipus">
	<option value="">{t('válasszon')}</option>
	{foreach $jelenlettipuslist as $_mk}
	<option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
	{/foreach}
	</select>
	<label for="gendatum">Dátum:</label><input id="gendatum" name="gendatum" type="text" size="12">
	<a id="GeneralBtn" href="">{t('Napi jelenlét generálás')}</a>
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
	<th>{t('Dolgozó')}</th>
	<th>{t('Dátum')}</th>
	<th>{t('Jelenlét')}</th>
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