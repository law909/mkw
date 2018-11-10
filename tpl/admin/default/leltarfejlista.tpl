{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
<script type="text/javascript" src="/js/admin/default/leltarfej.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Leltárak')}"></div>
<div id="mattable-filterwrapper">
	<div class="matt-hseparator"></div>
	<div>
        <label for="nevfilter">{at('Név')}:</label>
        <input id="nevfilter" name="nevfilter" type="text" maxlength="255">
	</div>
	<div class="matt-hseparator"></div>
    <div>
        <label for="raktarfilter">{at('Raktár')}: </label>
        <select id="raktarfilter" name="raktarfilter">
            <option value="">{at('válasszon')}</option>
            {foreach $raktarlist as $_gyarto}
                <option
                    value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
            {/foreach}
        </select>
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
<div class="mattable-batch">
	{at('Csoportos művelet')} <select class="mattable-batchselect">
	<option value="">{at('válasszon')}</option>
	{foreach $batchesselect as $_batch}
	<option value="{$_batch.id}">{$_batch.caption}</option>
	{/foreach}
	</select>
    <a href="#" class="mattable-batchbtn">{at('Futtat')}</a>
</div>
<table id="mattable-table">
<thead>
	<tr>
        <th><input class="js-maincheckbox" type="checkbox"></th>
        <th>{at('Név')}</th>
        <th>{at('Nyitás dátuma')}</th>
        <th>{at('Zárás dátuma')}</th>
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
    <div id="zarasdatumform" class="hidden">
        {include 'comp_datum.tpl'}
    </div>
{/block}