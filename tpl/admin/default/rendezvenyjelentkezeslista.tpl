{extends "../base.tpl"}

{block "inhead"}
<script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
<script type="text/javascript" src="/js/admin/default/jquery.mattable.js"></script>
<script type="text/javascript" src="/js/admin/default/rendezvenyjelentkezes.js"></script>
{/block}

{block "kozep"}
<div id="mattable-select" data-theme="{$theme}">
<div id="mattable-header" data-title="{at('Frissítés')}" data-caption="{at('Rendezvény jelentkezések')}"></div>
<div id="mattable-filterwrapper">
	<label for="partnernevfilter">{at('Résztvevő név')}</label>
	<input id="partnernevfilter" name="partnernevfilter" type="text" size="30" maxlength="255">
    <label for="partneremailfilter">{at('Email')}:</label>
    <input id="partneremailfilter" name="partneremailfilter" type="text">
    <div class="matt-hseparator"></div>
    <div>
        <label for="datumtolfilter">{at('Dátum')}:</label>
        <input id="datumtolfilter" name="datumtolfilter" type="text" size="12" data-datum="{$datumtolfilter|default}">
        <input id="datumigfilter" name="datumigfilter" type="text" size="12">
    </div>
    <div class="matt-hseparator"></div>
    <div>
        <label for="fizmodfilter">{at('Fiz.mód')}:</label>
        <select id="fizmodfilter" name="fizmodfilter">
            <option value="">{at('Mindegy')}</option>
            {foreach $fizmodlist as $_role}
                <option value="{$_role.id}">{$_role.caption}</option>
            {/foreach}
        </select>
    </div>
    <div class="matt-hseparator"></div>
    <div>
        <label for="rendezvenyfilter">{at('Rendezvény')}:</label>
        <select id="rendezvenyfilter" name="rendezvenyfilter">
            <option value="">{at('Mindegy')}</option>
            {foreach $rendezvenylist as $_role}
                <option value="{$_role.id}">{$_role.caption}</option>
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
        <th>{at('Résztvevő')}</th>
        <th>{at('Rendezvény')}</th>
        <th>{at('Állapot')}</th>
        <th>{at('Akciók')}</th>
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

    <form id="fizetveform" class="hidden">
        <div>
            <label for="afizetvefizmodedit">{at('Fizetési mód')}:</label>
            <select id="afizetvefizmodedit" name="afizetvefizmod">
                <option value="0">{at('válasszon')}</option>
                {foreach $fizmodlist as $_fm}
                    <option value="{$_fm.id}">{$_fm.caption}</option>
                {/foreach}
            </select>
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="afizetvejogcimedit">{at('Jogcím')}:</label>
            <select id="afizetvejogcimedit" name="afizetvejogcim">
                <option value="0">{at('válasszon')}</option>
                {foreach $jogcimlist as $_fm}
                    <option value="{$_fm.id}">{$_fm.caption}</option>
                {/foreach}
            </select>
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="afizetvepenztaredit">{at('Pénztár')}:</label>
            <select id="afizetvepenztaredit" name="afizetvepenztar">
                <option value="0">{at('válasszon')}</option>
                {foreach $penztarlist as $_fm}
                    <option value="{$_fm.id}">{$_fm.caption}</option>
                {/foreach}
            </select>
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="afizetvebankszamlaedit">{at('Bankszámla')}:</label>
            <select id="afizetvebankszamlaedit" name="afizetvebankszamla">
                <option value="0">{at('válasszon')}</option>
                {foreach $bankszamlalist as $_fm}
                    <option value="{$_fm.id}">{$_fm.caption}</option>
                {/foreach}
            </select>
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="afizetvedatumedit">{at('Dátum')}:</label>
            <input id="afizetvedatumedit" name="afizetvedatum" type="text" size="12">
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="afizetveosszegedit">{at('Összeg')}:</label>
            <input id="afizetveosszegedit" name="afizetveosszeg" type="text">
        </div>
    </form>

    <form id="szamlazvaform" class="hidden">
        <div>
            <label for="aszamlazvabiztipusedit">{at('Bizonylattípus')}:</label>
            <input id="aszamlazvabiztipusedit" name="aszamlazvabiztipus" type="radio" value="szamla" checked="checked">Számla
            <input name="aszamlazvabiztipus" type="radio" value="egyeb">Egyéb mozgás
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="aszamlazvakeltedit">{at('Kelt')}:</label>
            <input id="aszamlazvakeltedit" name="aszamlazvakelt" type="text" size="12">
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="aszamlazvateljesitesedit">{at('Teljesítés')}:</label>
            <input id="aszamlazvateljesitesedit" name="aszamlazvateljesites" type="text" size="12">
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="aszamlazvaosszegedit">{at('Összeg')}:</label>
            <input id="aszamlazvaosszegedit" name="aszamlazvaosszeg" type="text">
        </div>
    </form>

    <form id="lemondvaform" class="hidden">
        <div>
            <label for="alemondasdatumedit">{at('Dátum')}:</label>
            <input id="alemondasdatumedit" name="alemondasdatum" type="text" size="12">
        </div>
        <div class="matt-hseparator"></div>
        <div>
            <label for="alemondasokaedit">{at('Lemondás oka')}:</label>
            <textarea id="alemondasokaedit" name="alemondasoka"></textarea>
        </div>
    </form>
{/block}