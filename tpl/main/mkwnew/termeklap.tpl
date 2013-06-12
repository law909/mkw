{extends "base.tpl"}
{block "css"}
<link type="text/css" rel="stylesheet" href="/themes/main/mkwnew/lightbox.css">
{/block}
{block "script"}
<script src="/js/main/mkwnew/jquery.blockUI.js"></script>
<script src="/js/main/mkwnew/lightbox.js"></script>
<script src="/js/main/mkwnew/bootstrap-tab.js"></script>
{/block}
{block "kozep"}
<div class="row hataroltSor">
	<div class="span12">
	{foreach $navigator as $_navi}
		{if ($_navi.url!='')}
			<a href="/termekfa/{$_navi.url}">
				{$_navi.caption}
			</a>
			/
		{else}
			{$_navi.caption}
		{/if}
	{/foreach}
	</div>
</div>
<article itemtype="http://schema.org/Product" itemscope="">
	<div class="row hataroltSor">
		<div class="span7">
			<div class="row hataroltSor">
				<div class="span7 textAlignCenter"><h3>{$termek.caption}</h3></div>
			</div>
			<div class="row hataroltSor">
				<div class="span7 textAlignCenter">
					<a href="{$termek.kepurl}" rel="lightbox[termekkep]" title="{$termek.caption}">
						<img src="{$termek.kozepeskepurl}" itemprop="image" alt="{$termek.caption}" title="{$termek.caption}">
					</a>
				</div>
			</div>
			<div class="row">
				<div class="span7 textAlignCenter">
					{foreach $termek.kepek as $_kep}
						<a href="{$_kep.kepurl}" rel="lightbox[termekkep]" title="{$_kep.leiras}">
						<img src="{$_kep.kiskepurl}" alt="{$_kep.leiras}" title="{$_kep.leiras}">
						</a>
					{/foreach}
				</div>
			</div>
		</div>
		<div class="span5 hatter">
			<div class="korbepadding">
				<div class="row hataroltSor">
					<div class="span5">
						<div class="pull-left">{t('Cikkszám')}: {$termek.cikkszam}</div>
					</div>
				</div>
				<div class="row hataroltSor">
					<div class="span5">
						<ul class="simalista">
						{foreach $termek.cimkeakciodobozban as $_jelzo}
							<li>{if ($_jelzo.kiskepurl!='')}<img src="{$_jelzo.kiskepurl}" alt="{$_jelzo.caption}" title="{$_jelzo.caption}"> {/if}{$_jelzo.caption}</li>
						{/foreach}
						</ul>
					</div>
				</div>
				<div class="row hataroltSor">
					<div class="span5">
						<ul class="simalista pull-left">
							{foreach $termek.valtozatok['tipus'] as $_ertek}
								{if ($_ertek)}
									<li>
									{$_ertek}
									{$_adat=$termek.valtozatok['adat']}
									<select class="valtozatEdit" data-oszlop="{$_ertek@index+1}" data-id="{$_ertek@key}" data-termek="{$termek.id}">
										<option value="">{t('Válasszon')}</option>
										{foreach $_adat[$_ertek@key] as $_v}
											<option value="{$_v}">{$_v}</option>
										{/foreach}
									</select>
									</li>
								{/if}
							{/foreach}
						</ul>
					</div>
				</div>
				<div class="row hataroltSor">
					<h3 class="itemPrice"><span class="pull-right">{number_format($termek.bruttohuf,0,',',' ')} Ft</span></h3>
				</div>
				<div class="row">
					<a href="/kosar/add?id={$termek.id}" rel="nofollow" class="kosarba btn btn-large btn-success pull-right" data-termek="{$termek.id}">
						<i class="icon-shopping-cart icon-white"></i>
						{t('Megveszem')}
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row hataroltSor">
		<div class="span12">
			<div id="termekTabbable" class="tabbable">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#leirasTab" data-toggle="tab">{t('Leírás')}</a></li>
					<li><a href="#tulajdonsagTab" data-toggle="tab">{t('Tulajdonságok')}</a></li>
					{if (count($termek.kapcsolodok)!=0)}<li><a href="#kapcsolodoTab" data-toggle="tab">{t('Kapcsolódó termékek')}</a></li>{/if}
					<li><a href="#ertekelesTab" data-toggle="tab">{t('Értékelések')}</a></li>
					<li><a href="#hasonlotermekTab" data-toggle="tab">{t('Hasonló termékek')}</a></li>
				</ul>
				<div class="tab-content keret">
					<div id="leirasTab" class="tab-pane active">
						<p>{$termek.leiras}</p>
					</div>
					<div id="tulajdonsagTab" class="tab-pane">
						<div class="span6 nincsbalmargo">
						<table class="table table-striped table-condensed"><tbody>
							{foreach $termek.cimkelapon as $_cimke}
								<tr><td>{$_cimke.kategorianev}</td><td>{if ($_cimke.kiskepurl!='')}<img src="{$_cimke.kiskepurl}" alt="{$_cimke.caption}" title="{$_cimke.caption}"> {/if}{$_cimke.caption}</td></tr>
							{/foreach}
						</tbody></table>
						</div>
					</div>
					{if (count($termek.kapcsolodok)!=0)}
					<div id="kapcsolodoTab" class="tab-pane">
						<table><tr>
						{foreach $termek.kapcsolodok as $kapcsolodo}
							<td>
								<table>
									<tr><td class="textAlignCenter">
									{if ($kapcsolodo.kiskepurl!='')}<a href="/termek/{$kapcsolodo.slug}"><img src="{$kapcsolodo.kiskepurl}" title="{$kapcsolodo.caption}" alt="{$kapcsolodo.caption}"></a>{/if}
									</td></tr>
									<tr><td class="textAlignCenter">
									<a href="/termek/{$kapcsolodo.slug}"><h4>{$kapcsolodo.caption}</h4></a>
									</td></tr>
									<tr><td class="textAlignCenter">
										<h3 class="itemPrice">{number_format($kapcsolodo.bruttohuf,0,',',' ')} Ft</h3>
									</td></tr>
								</table>
							</td>
							<td>
							</td>
						{/foreach}
						</tr></table>
					</div>
					{/if}
					<div id="ertekelesTab" class="tab-pane">
						<p>mindjart itt lesznek az ertekelesek</p>
					</div>
					<div id="hasonlotermekTab" class="tab-pane">
						<p>mindjart itt lesznek a hasonlo termekek</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</article>
{/block}