{extends "base.tpl"}

{block "kozep"}
<div class="container">
	<div class="row">
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
		<div class="row">
			<div class="span6">
                <div class="textaligncenter"><h3>{$termek.caption}</h3></div>
				<div class="termekimagecontainer textaligncenter">
                    <a href="{$termek.kepurl}" class="js-lightbox" title="{$termek.caption}">
                        <img src="{$termek.kozepeskepurl}" itemprop="image" alt="{$termek.caption}" title="{$termek.caption}">
                    </a>
				</div>
				<div class="js-termekimageslider termekimageslider termekimagecontainer textaligncenter royalSlider contentSlider rsDefaultInv">
                    {$kcnt=count($termek.kepek)}
                    {$step=4}
                    {for $i=0 to $kcnt-1 step $step}
                        <div>
                        {for $j=0 to $step-1}
                            {if ($i+$j<$kcnt)}
                                {$_kep=$termek.kepek[$i+$j]}
                                <a href="{$_kep.kepurl}" class="js-lightbox" title="{$_kep.leiras}">
                                    <img class="termeksmallimage" src="{$_kep.minikepurl}" alt="{$_kep.leiras}" title="{$_kep.leiras}">
                                </a>
                            {/if}
                        {/for}
                        </div>
                    {/for}
				</div>
			</div>
			<div class="span3 hatter">
				<div class="korbepadding">
					<div>{t('Cikkszám')}: {$termek.cikkszam}</div>
                    <div>
                        <ul class="simalista">
                        {foreach $termek.cimkeakciodobozban as $_jelzo}
                            <li>{if ($_jelzo.kiskepurl!='')}<img src="{$_jelzo.kiskepurl}" alt="{$_jelzo.caption}" title="{$_jelzo.caption}"> {/if}{$_jelzo.caption}</li>
                        {/foreach}
                        </ul>
                    </div>
					{$_kosarbaclass="js-kosarba"}
					{if ($termek.valtozatok)}
					{$_kosarbaclass="js-kosarbamindenvaltozat"}
					<div class="row">
						<div class="span2">
                            {foreach $termek.valtozatok as $_valtozat}
                            <div>{$_valtozat.name}</div>
                            <div>
                                <select class="js-mindenvaltozatedit valtozatselect" data-id="{$termek.id}" data-termek="{$termek.id}" data-tipusid="{$_valtozat.tipusid}">
                                    <option value="">{t('Válasszon')}</option>
                                    {foreach $_valtozat.value as $_v}
                                        <option value="{$_v}">{$_v}</option>
                                    {/foreach}
                                </select>
                            </div>
                            {/foreach}
						</div>
					</div>
					{/if}
					<h3 id="termekprice{$termek.id}" class="itemPrice textalignright">{number_format($termek.bruttohuf,0,',',' ')} Ft</h3>
					{if ($termek.nemkaphato)}
					<div class="textalignright">
						<a href="#" rel="nofollow" class="js-termekertesitobtn btn btn-large graybtn" data-termek="{$termek.id}" data-id="{$termek.id}">
							{t('Elfogyott')}
						</a>
					</div>
					{else}
					<div class="textalignright">
						<a href="/kosar/add?id={$termek.id}" rel="nofollow" class="{$_kosarbaclass} btn btn-large cartbtn" data-termek="{$termek.id}" data-id="{$termek.id}">
							<i class="icon-shopping-cart icon-white"></i>
							{t('Kosárba')}
						</a>
					</div>
					{/if}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span9">
				<div id="termekTabbable" class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#leirasTab" data-toggle="tab">{t('Leírás')}</a></li>
						<li><a href="#tulajdonsagTab" data-toggle="tab">{t('Tulajdonságok')}</a></li>
						{if (count($termek.kapcsolodok)!=0)}<li><a href="#kapcsolodoTab" data-toggle="tab">{t('Kapcsolódó termékek')}</a></li>{/if}
						<!--li><a href="#ertekelesTab" data-toggle="tab">{t('Értékelések')}</a></li>
						<li><a href="#hasonlotermekTab" data-toggle="tab">{t('Hasonló termékek')}</a></li-->
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
										<tr><td class="textaligncenter">
										{if ($kapcsolodo.kiskepurl!='')}<a href="/termek/{$kapcsolodo.slug}"><img src="{$kapcsolodo.kiskepurl}" title="{$kapcsolodo.caption}" alt="{$kapcsolodo.caption}"></a>{/if}
										</td></tr>
										<tr><td class="textaligncenter">
										<a href="/termek/{$kapcsolodo.slug}"><h4>{$kapcsolodo.caption}</h4></a>
										</td></tr>
										<tr><td class="textaligncenter">
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
						<!--div id="ertekelesTab" class="tab-pane">
							<p>mindjart itt lesznek az ertekelesek</p>
						</div>
						<div id="hasonlotermekTab" class="tab-pane">
							<p>mindjart itt lesznek a hasonlo termekek</p>
						</div-->
					</div>
				</div>
			</div>
		</div>
	</article>
</div>
{include 'termekertesitomodal.tpl'};
{/block}