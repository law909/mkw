{extends "base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.flyout.js"></script>
<script type="text/javascript" src="/js/admin/default/bizonylathelper.js"></script>
<script type="text/javascript" src="/js/admin/default/{$controllerscript}"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{t('Frissítés')}" data-caption="{$pagetitle}"></div>
<div id="mattable-filterwrapper">
	<label for="idfilter">{t('Sorszám')}:</label>
	<input id="idfilter" name="idfilter" type="text" size="20" maxlength="20">
    <label for="vevonevfilter">Vevőnév:</label>
    <input id="vevonevfilter" name="vevonevfilter" type="text">
    <label for="vevoemailfilter">Vevő email:</label>
    <input id="vevoemailfilter" name="vevoemailfilter" type="text">
    <div>
        <label for="szallitasiirszamfilter">Szállítási cím:</label>
        <input id="szallitasiirszamfilter" name="szallitasiirszamfilter" type="text" size="8">
        <input id="szallitasivarosfilter" name="szallitasivarosfilter" type="text">
        <input id="szallitasiutcafilter" name="szallitasiutcafilter" type="text">
    </div>
    <div>
        <label for="szamlazasiirszamfilter">Számlázási cím:</label>
        <input id="szamlazasiirszamfilter" name="szamlazasiirszamfilter" type="text" size="8">
        <input id="szamlazasivarosfilter" name="szamlazasivarosfilter" type="text">
        <input id="szamlazasiutcafilter" name="szamlazasiutcafilter" type="text">
    </div>
    <div>
        <label for="datumtipusfilter">Dátum:</label>
        <select id="datumtipusfilter" name="datumtipusfilter">
            <option value="1">kelt</option>
            <option value="2">teljesítés</option>
            <option value="3">esedékesség</option>
        </select>
        <input id="datumtolfilter" name="datumtolfilter" type="text" size="12" data-datum="{$datumtolfilter|default}">
        <input id="datumigfilter" name="datumigfilter" type="text" size="12">
    </div>
    {if ($showbizonylatstatuszeditor)}
    <div>
        <label for="bizonylatstatuszfilter">Státusz:</label>
        <select id="bizonylatstatuszfilter" name="bizonylatstatuszfilter">
            <option value="">Mindegy</option>
            {foreach $bizonylatstatuszlist as $_role}
            <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
            {/foreach}
        </select>
    </div>
    {/if}
    <div>
        <label for="fizmodfilter">Fiz.mód:</label>
        <select id="fizmodfilter" name="fizmodfilter">
            <option value="">Mindegy</option>
            {foreach $fizmodlist as $_role}
            <option value="{$_role.id}">{$_role.caption}</option>
            {/foreach}
        </select>
    </div>
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
	<th><input id="maincheckbox" type="checkbox"></th>
    {if ($showbizonylatstatuszeditor)}
    <th></th>
    {/if}
	<th></th>
	<th></th>
	<th></th>
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