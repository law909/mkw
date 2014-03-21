{extends "base.tpl"}

{block "meta"}
    <meta property="og:title" content="{$pagetitle|default}"/>
    <meta property="og:url" content="{$serverurl}/termek/{$termek.slug}"/>
    <meta property="og:description" content="{$termek.rovidleiras}"/>
    <meta property="og:image" content="{$termek.fullkepurl}"/>
    <meta property="og:type" content="product"/>
{/block}

{block "script"}
    <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
{/block}

{block "kozep"}
<div class="container whitebg">
    <div class="container morzsa">
        <div class="row">
            <div class="span12 morzsaszoveg" xmlns:v="http://rdf.data-vocabulary.org/#">
                        <b>Ön itt áll: </b>
            <span itemprop="breadcrumb">
                {if ($navigator|default)}
            {foreach $navigator as $_navi}
                {if ($_navi.url!='')}
                    <span typeof="v:Breadcrumb">
                    <a href="/termekfa/{$_navi.url}" rel="v:url" property="v:title">
                        {$_navi.caption}
                    </a>
                    </span>
                    /
                {else}
                    {$_navi.caption}
                {/if}
            {/foreach}
            {/if}
            </span>
            </div>
        </div>
    </div>
	<article itemtype="http://schema.org/Product" itemscope="">
		<div class="row">
			<div class="span6">
                <div class="textaligncenter"><h1 itemprop="name" class="termeknev">{$termek.caption}</h1></div>
				<div class="termekimagecontainer textaligncenter">
                    <a href="{$termek.kepurl}" class="js-lightbox" title="{$termek.caption}">
                        <img itemprop="image" src="{$termek.kozepeskepurl}" alt="{$termek.caption}" title="{$termek.caption}">
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
                <div>
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" style="cursor:pointer"></a>
<a class="addthis_button_facebook" style="cursor:pointer"></a>
<g:plusone size="small"></g:plusone>
<a class="addthis_button_iwiw" style="cursor:pointer"></a>
<a class="addthis_button_twitter" style="cursor:pointer"></a>
<a class="addthis_button_email" style="cursor:pointer"></a>
<a class="addthis_button_pinterest_pinit" style="cursor:pointer"></a>
</div>
<script type="text/javascript">var addthis_config = { "data_track_clickback":true };</script>
<script type="text/javascript">var addthis_config = { "data_track_addressbar":true };</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=quixoft"></script>
<!-- AddThis Button END -->
                </div>
			</div>
			<div class="span3 hatter">
				<div class="korbepadding">
                    <div><span class="bold">Cikkszám:</span> <span itemprop="productID">{$termek.cikkszam}</span></div>
                    <div><span class="bold">Kapható hűségpont:</span> {$termek.husegpont}</div>
                    {if ($termek.szallitasiido && (!$termek.nemkaphato))}
                    <div><span class="bold">Szállítási idő:</span> max. {$termek.szallitasiido} munkanap</div>
                    {/if}
                    <div>
                        <ul class="simalista">
                        {foreach $termek.cimkeakciodobozban as $_jelzo}
                            <li>{$_jelzo.caption}</li>
                        {/foreach}
                        </ul>
                    </div>
                    <div>
                        {if ($termek.ujtermek)}<img src="{$ujtermekjelolourl}" title="Új termék" alt="Új termék">{/if}
                        {if ($termek.akcios)}<img src="{$akciosjelolourl}" title="Akciós termék" alt="Akciós termék">{/if}
                        {if ($termek.top10)}<img src="{$top10jelolourl}" title="Top 10 termék" alt="Top 10 termék">{/if}
                        {if ($termek.ingyenszallitas)}<img src="{$ingyenszallitasjelolourl}" title="Ingyenes szállítás" alt="Ingyenes szállítás">{/if}
                    </div>
					{$_kosarbaclass="js-kosarba"}
					{if ($termek.valtozatok)}
					{$_kosarbaclass="js-kosarbamindenvaltozat"}
					<div class="row">
						<div class="span2 kosarbacontainer">
                            {foreach $termek.valtozatok as $_valtozat}
                            <div class="bold">{$_valtozat.name}</div>
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
                    <div class="kosarbacontainer">
                    {if ($termek.akcios)}
                    <div class="akciosarszoveg akciosarszovegtermeklap textalignright">Eredeti ár: <span class="akciosar">{number_format($termek.eredetibruttohuf,0,',',' ')} Ft</span></div>
                    {/if}
                    <div id="termekprice{$termek.id}" class="itemPrice textalignright" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
    					{if ($termek.nemkaphato)}
                            <link itemprop="availability" href="http://schema.org/OutOfStock" content="Nem kapható">
                        {else}
                            <link itemprop="availability" href="http://schema.org/InStock" content="Kapható">
                        {/if}
                        <span itemprop="price">{number_format($termek.bruttohuf,0,',',' ')} Ft</span>
                    </div>
					{if ($termek.nemkaphato)}
					<div class="textalignright">
						<a href="#" rel="nofollow" class="js-termekertesitobtn btn btn-large graybtn" data-termek="{$termek.id}" data-id="{$termek.id}">
							{t('Elfogyott')}
						</a>
					</div>
					{else}
					<div class="textalignright">
						<a href="/kosar/add?id={$termek.id}" rel="nofollow" class="{$_kosarbaclass} btn btn-large cartbtn" data-termek="{$termek.id}" data-id="{$termek.id}">
							{t('Kosárba')}
						</a>
					</div>
					{/if}
                    {if ($termek.akcios)}
                    <div class="textalignright">Az akció {if ($termek.akciotipus == 1)}{$termek.akciostart} - {$termek.akciostop}-ig tart
                        {elseif ($termek.akciotipus == 2)}{$termek.akciostop}-ig tart
                        {else}a készlet erejéig tart
                        {/if}
                    </div>
					{/if}
                    </div>
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
							<span itemprop="description">{$termek.leiras}</span>
						</div>
						<div id="tulajdonsagTab" class="tab-pane">
							<div class="span6 nincsbalmargo">
							<table class="table table-striped table-condensed"><tbody>
								{foreach $termek.cimkelapon as $_cimke}
									<tr><td>{$_cimke.kategorianev}</td><td>{if ($_cimke.kiskepurl!='')}<img src="{$_cimke.kiskepurl}" alt="{$_cimke.caption}" title="{$_cimke.caption}"> {/if}{if (!$_cimke.dontshowcaption || $_cimke.kiskepurl=='')}{$_cimke.caption}{/if}</td></tr>
								{/foreach}
							</tbody></table>
							</div>
						</div>
						{if (count($termek.kapcsolodok)!=0)}
						<div id="kapcsolodoTab" class="tab-pane">
                        {$lntcnt=count($termek.kapcsolodok)}
                        {$step=4}
                        {for $i=0 to $lntcnt-1 step $step}
                            <div>
                            {for $j=0 to $step-1}
                                {if ($i+$j<$lntcnt)}
                                {$_kapcsolodo=$termek.kapcsolodok[$i+$j]}
                                <div class="textaligncenter pull-left" style="width:{100/$step}%">
                                    <div class="kapcsolodoTermekInner">
                                        <a href="{$_kapcsolodo.link}">
                                            <div class="kapcsolodoImageContainer">
                                                <img src="{$_kapcsolodo.minikepurl}" title="{$_kapcsolodo.caption}" alt="{$_kapcsolodo.caption}">
                                            </div>
                                            <div>{$_kapcsolodo.caption}</div>
                                            <h5>
                                                {if ($_kapcsolodo.akcios)}
                                                <span><span class="akciosar">{number_format($_kapcsolodo.eredetibruttohuf,0,',',' ')} Ft</span> helyett {number_format($_kapcsolodo.bruttohuf,0,',',' ')} Ft</span>
                                                {else}
                                                <span>{number_format($_kapcsolodo.bruttohuf,0,',',' ')} Ft</span>
                                                {/if}
                                            </h5>
                                            <a href="{$_kapcsolodo.link}" class="btn okbtn">Részletek</a>
                                        </a>
                                    </div>
                                </div>
                                {/if}
                            {/for}
                            </div>
                        {/for}
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
{include 'termekertesitomodal.tpl'}
{/block}
