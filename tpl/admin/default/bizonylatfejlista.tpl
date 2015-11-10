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
	<div class="matt-hseparator"></div>
    <div>
        <label for="szallitasiirszamfilter">Szállítási cím:</label>
        <input id="szallitasiirszamfilter" name="szallitasiirszamfilter" type="text" size="8">
        <input id="szallitasivarosfilter" name="szallitasivarosfilter" type="text">
        <input id="szallitasiutcafilter" name="szallitasiutcafilter" type="text">
    </div>
	<div class="matt-hseparator"></div>
    <div>
        <label for="szamlazasiirszamfilter">Számlázási cím:</label>
        <input id="szamlazasiirszamfilter" name="szamlazasiirszamfilter" type="text" size="8">
        <input id="szamlazasivarosfilter" name="szamlazasivarosfilter" type="text">
        <input id="szamlazasiutcafilter" name="szamlazasiutcafilter" type="text">
    </div>
	<div class="matt-hseparator"></div>
    <div>
        <label for="datumtipusfilter">Dátum:</label>
        <select id="datumtipusfilter" name="datumtipusfilter">
            <option value="1">kelt</option>
            <option value="2">teljesítés</option>
            {if ($showesedekesseg)}
            <option value="3">esedékesség</option>
            {/if}
        </select>
        <input id="datumtolfilter" name="datumtolfilter" type="text" size="12" data-datum="{$datumtolfilter|default}">
        <input id="datumigfilter" name="datumigfilter" type="text" size="12">
    </div>
	<div class="matt-hseparator"></div>
    <div>
        {if ($showbizonylatstatuszeditor)}
        <label for="bizonylatstatuszfilter">Státusz:</label>
        <select id="bizonylatstatuszfilter" name="bizonylatstatuszfilter">
            <option value="">Mindegy</option>
            {foreach $bizonylatstatuszlist as $_role}
            <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
            {/foreach}
        </select>
        <label for="bizonylatstatuszcsoportfilter">Státusz csoport:</label>
        <select id="bizonylatstatuszcsoportfilter" name="bizonylatstatuszcsoportfilter">
            <option value="">Mindegy</option>
            {foreach $bizonylatstatuszcsoportlist as $_role}
            <option value="{$_role.id}"{if ($_role.selected)} selected="selected"{/if}>{$_role.caption}</option>
            {/foreach}
        </select>
        {/if}
        <label for="bizonylatrontottfilter">Rontott:</label>
        <select id="bizonylatrontottfilter" name="bizonylatrontottfilter">
            <option value="0">Mindegy</option>
            <option value="1"{if ($bizonylatrontottfilter === 1)} selected="selected"{/if}>nem rontott</option>
            <option value="2"{if ($bizonylatrontottfilter === 2)} selected="selected"{/if}>rontott</option>
        </select>
        <label for="bizonylatstornofilter">Stornó:</label>
        <select id="bizonylatstornofilter" name="bizonylatstornofilter">
            <option value="0">Mindegy</option>
            <option value="1"{if ($bizonylatstornofilter === 1)} selected="selected"{/if}>nem stornózott</option>
            <option value="2"{if ($bizonylatstornofilter === 2)} selected="selected"{/if}>stornózott</option>
        </select>
    </div>
	<div class="matt-hseparator"></div>
    <div>
        <label for="fizmodfilter">Fiz.mód:</label>
        <select id="fizmodfilter" name="fizmodfilter">
            <option value="">Mindegy</option>
            {foreach $fizmodlist as $_role}
            <option value="{$_role.id}">{$_role.caption}</option>
            {/foreach}
        </select>
        <label for="szallitasimodfilter">Szállítási mód:</label>
        <select id="szallitasimodfilter" name="szallitasimodfilter">
            <option value="">Mindegy</option>
            {foreach $szallitasimodlist as $_role}
            <option value="{$_role.id}">{$_role.caption}</option>
            {/foreach}
        </select>
        <label for="uzletkotofilter">Üzletkötő:</label>
        <select id="uzletkotofilter" name="uzletkotofilter">
            <option value="">Mindegy</option>
            {foreach $uzletkotolist as $_role}
            <option value="{$_role.id}">{$_role.caption}</option>
            {/foreach}
        </select>
    </div>
    <div class="matt-hseparator"></div>
    <div>
        <label for="valutanemfilter">Valutanem:</label>
        <select id="valutanemfilter" name="valutanemfilter">
            <option value="">Mindegy</option>
            {foreach $valutanemlist as $_role}
                <option value="{$_role.id}">{$_role.caption}</option>
            {/foreach}
        </select>
    </div>
    {if ($showfuvarlevelszam)}
	<div class="matt-hseparator"></div>
    <div>
        <label for="fuvarlevelszamfilter">Fuvarlevélszám:</label>
        <input id="fuvarlevelszamfilter" name="fuvarlevelszamfilter" type="text" size="20">
    </div>
    {/if}
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
        {if ($showbizonylatstatuszeditor)}
        <th></th>
        {/if}
        <th></th>
        <th></th>
        <th class="js-sumcol"></th>
        {if ($setup.osztottfizmod)}
        <th></th>
        {/if}
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