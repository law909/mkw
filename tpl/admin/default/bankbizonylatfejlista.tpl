{extends "base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
<script type="text/javascript" src="/js/admin/default/bankbizonylat.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{t('Frissítés')}" data-caption="{$pagetitle}"></div>
<div id="mattable-filterwrapper">
	<label for="idfilter">{t('Sorszám')}:</label>
	<input id="idfilter" name="idfilter" type="text" size="20" maxlength="20">
    <label for="vevonevfilter">Vevőnév:</label>
    <input id="vevonevfilter" name="vevonevfilter" type="text">
	<div class="matt-hseparator"></div>
    <div>
        <label for="datumtipusfilter">Dátum:</label>
        <input id="datumtolfilter" name="datumtolfilter" type="text" size="12" data-datum="{$datumtolfilter|default}">
        <input id="datumigfilter" name="datumigfilter" type="text" size="12">
    </div>
	<div class="matt-hseparator"></div>
    <div>
        <label for="bizonylatrontottfilter">Rontott:</label>
        <select id="bizonylatrontottfilter" name="bizonylatrontottfilter">
            <option value="0">Mindegy</option>
            <option value="1"{if ($bizonylatrontottfilter === 1)} selected="selected"{/if}>nem rontott</option>
            <option value="2"{if ($bizonylatrontottfilter === 2)} selected="selected"{/if}>rontott</option>
        </select>
    </div>
    {if ($showerbizonylatszam)}
	<div class="matt-hseparator"></div>
    <div>
        <label for="erbizonylatszamfilter">Er.biz.szám:</label>
        <input id="erbizonylatszamfilter" name="erbizonylatszamfilter" type="text" size="20">
    </div>
    {/if}
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
    <a href="#" class="mattable-batchbtn">Futtat</a>
</div>
<table id="mattable-table">
<thead>
	<tr>
	<th><input id="maincheckbox" type="checkbox"></th>
	<th></th>
	<th></th>
	<th></th>
	</tr>
</thead>
<tbody id="mattable-body"></tbody>
</table>
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