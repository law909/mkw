{extends "../base.tpl"}

{block "inhead"}
{include "ckeditor.tpl"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/darshan/jogareszvetelkarb.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Óra látogatások')}"></div>
<div id="mattable-filterwrapper">
    <div>
        <label for="datumtipusfilter">{at('Dátum')}:</label>
        <input id="datumtolfilter" name="datumtolfilter" type="text" size="12" data-datum="{$datumtolfilter|default}">
        <input id="datumigfilter" name="datumigfilter" type="text" size="12">
    </div>
    <div class="matt-hseparator"></div>
    <div>
        <label for="partnernevfilter">{at('Résztvevő')}:</label>
        <input id="partnernevfilter" name="partnernevfilter" type="text">
        <label for="partneremailfilter">{at('Résztvevő email')}:</label>
        <input id="partneremailfilter" name="partneremailfilter" type="text">
    </div>
    <div class="matt-hseparator"></div>
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
<div class="mattable-batch">
	{at('Csoportos művelet')} <select class="mattable-batchselect">
	<option value="">{at('válasszon')}</option>
	{foreach $batchesselect as $_batch}
	<option value="{$_batch.id}">{$_batch.caption}</option>
	{/foreach}
	</select>
</div>
<table id="mattable-table">
<thead>
	<tr>
        <th><input class="js-maincheckbox" type="checkbox"></th>
        <th>{at('Dátum')}</th>
        <th>{at('Tanár')}</th>
        <th>{at('Óra')}</th>
        <th>{at('Résztvevő')}</th>
        <th>{at('Bérlet/jegy')}</th>
        <th>{at('Ár')}</th>
        <th>{at('Jutalék')}</th>
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