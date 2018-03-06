{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/rendezveny.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Rendezvények')}"></div>
<div id="mattable-filterwrapper">
    <div class="matt-hseparator"></div>
    <div>
        <label for="nevfilter">{at('Szűrés')}</label>
        <input id="nevfilter" name="nevfilter" type="text" size="30" maxlength="255">
    </div>
    <div class="matt-hseparator"></div>
    {include "comp_idoszak.tpl" comptype="datum"}
    <div class="matt-hseparator"></div>
    <div>
        <label for="tanarfilter">{at('Tanár')}: </label>
        <select id="tanarfilter" name="tanarfilter">
            <option value="">{at('válasszon')}</option>
            {foreach $tanarlist as $_gyarto}
                <option
                    value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
            {/foreach}
        </select>
    </div>
    <div class="matt-hseparator"></div>
    <div>
        <label for="teremfilter">{at('Hely')}: </label>
        <select id="teremfilter" name="jogateremfilter">
            <option value="">{at('válasszon')}</option>
            {foreach $jogateremlist as $_gyarto}
                <option
                    value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
            {/foreach}
        </select>
    </div>
    <div class="matt-hseparator"></div>
    <div>
        <label for="allapotfilter">{at('Állapot')}: </label>
        <select id="allapotfilter" name="rendezvenyallapotfilter">
            <option value="">{at('válasszon')}</option>
            {foreach $rendezvenyallapotlist as $_gyarto}
                <option
                    value="{$_gyarto.id}"{if ($_gyarto.selected)} selected="selected"{/if}>{$_gyarto.caption}</option>
            {/foreach}
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
	<th><input class="js-maincheckbox" type="checkbox"></th>
	<th>{at('Név')}</th>
	<th>{at('Tanár')}</th>
    <th>{at('Állapot')}</th>
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
<div id="mattkarb"></div>
{/block}