{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/jogaberlet.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Bérletek')}"></div>
<div id="mattable-filterwrapper">
    <div>
    <label for="vevonevfilter">{at('Partner')}:</label>
    <input id="vevonevfilter" name="vevonevfilter" type="text">
    </div>
    <div class="matt-hseparator"></div>
    <div>
    <label for="TermekEdit" class="mattable-important">{at('Bérlet')}:</label>
    <select id="TermekEdit" name="termekfilter" class="mattable-important" required="required">
        <option value="">{at('válassz')}</option>
        {foreach $termeklist as $_mk}
            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
        {/foreach}
    </select>
    </div>
    <div class="matt-hseparator"></div>
    <div>
        <label for="datumtolfilter">{at('Vásárlás napja')}:</label>
        <input id="datumtolfilter" name="datumtolfilter" type="text" size="12" data-datum="{$datumtolfilter|default}">
        <input id="datumigfilter" name="datumigfilter" type="text" size="12">
    </div>
    <div class="matt-hseparator"></div>
    <div>
        <label for="lejarattolfilter">{at('Lejárat napja')}:</label>
        <input id="lejarattolfilter" name="lejarattolfilter" type="text" size="12" data-datum="{$lejarattolfilter|default}">
        <input id="lejaratigfilter" name="lejaratigfilter" type="text" size="12">
    </div>
    <div class="matt-hseparator"></div>
    <div>
        <label for="utolsohasznalatfilter">{at('Utolsó óralátogatás napja')}</label>
        <input id="utolsohasznalatfilter" name="utolsohasznalatfilter" type="text" size="12" data-datum="{$utolsohasznalatfilter|default}">
        <label>{at('előtt')}</label>
    </div>
    <div class="matt-hseparator"></div>
    <div>
        <label for="lejartfilter">{at('Lejárt')}:</label>
        <select id="lejartfilter" name="lejartfilter">
            <option value="0">{at('Mindegy')}</option>
            <option value="1"{if ($lejartfilter === 1)} selected="selected"{/if}>{at('nem')}</option>
            <option value="2"{if ($lejartfilter === 2)} selected="selected"{/if}>{at('igen')}</option>
        </select>
        <label for="nincsfizetvefilter">{at('Fizetve')}:</label>
        <select id="nincsfizetvefilter" name="nincsfizetvefilter">
            <option value="0">{at('Mindegy')}</option>
            <option value="1"{if ($nincsfizetvefilter === 1)} selected="selected"{/if}>{at('ki van fizetve')}</option>
            <option value="2"{if ($nincsfizetvefilter === 2)} selected="selected"{/if}>{at('nincs kifizetve')}</option>
        </select>
    </div>
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
        <th><input id="maincheckbox" type="checkbox"></th>
        <th>{at('Partner')}</th>
        <th>{at('Termék')}</th>
        <th>{at('Ár')}</th>
        <th>{at('Vásárlás dátuma')}</th>
        <th>{at('Lejárat dátuma')}</th>
        <th></th>
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