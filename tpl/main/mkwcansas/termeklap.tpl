{extends "base.tpl"}

{block "meta"}
    <meta property="og:title" content="{$pagetitle|default}"/>
    <meta property="og:url" content="{$serverurl}/termek/{$termek.slug}"/>
    <meta property="og:description" content="{$termek.rovidleiras}"/>
    <meta property="og:image" content="{$termek.fullkepurl}"/>
    <meta property="og:type" content="product"/>
    <meta name='itemId' content='{$termek.id}'>
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
            <div class="span9">
                <div class="row">
                    <div class="span6">
                        <div class="textaligncenter"><h1 itemprop="name" class="termeknev">{$termek.caption}</h1></div>
                        <div class="termekimagecontainer textaligncenter">
                            <a id="termekkeplink{$termek.id}" href="{$termek.kepurl}" class="js-lightbox" title="{$termek.caption}">
                                <img id="termekkep{$termek.id}" itemprop="image" src="{$termek.kozepeskepurl}" alt="{$termek.caption}" title="{$termek.caption}">
                            </a>
                        </div>
                        {if ($termek.marka)}<span itemprop="brand" content="{$termek.marka}"></span>{/if}
                        {$kcnt=count($termek.kepek)}
                        {if ($kcnt>0)}
                        <div class="js-termekimageslider termekimageslider termekimagecontainer textaligncenter royalSlider contentSlider rsDefaultInv">
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
                        {/if}
                    </div>
                    <div class="span3 hatter">
                        <div class="korbepadding">
                            {if ($termek.ertekelesdb)}
                                <div itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                                    <div class="c-rating" data-rating-value="{$termek.ertekelesatlag}">
                                        <button>1</button>
                                        <button>2</button>
                                        <button>3</button>
                                        <button>4</button>
                                        <button>5</button>
                                        <span class="c-rating-value" itemprop="ratingValue">{$termek.ertekelesatlag}</span>
                                    </div>
                                    <div itemprop="reviewCount"> <a href="#ertekelesTab" class="js-showertekeles">{$termek.ertekelesdb} értékelésből</a></div>
                                </div>
                            {/if}
                            <div><span class="bold">Cikkszám:</span> <span itemprop="sku">{$termek.cikkszam}</span></div>
                            <div><span class="bold">Kapható hűségpont:</span> {$termek.husegpont}</div>
                            {if ($termek.me)}
                            <div><span class="bold">Kiszerelés:</span> {$termek.me}</div>
                            {/if}
                            {if ($termek.szallitasiido && (!$termek.nemkaphato))}
                                <div><span class="bold">Szállítási idő: </span>max. <span id="termekszallitasiido{$termek.id}">{if ($termek.minszallitasiido)}{$termek.minszallitasiido} - {/if}{$termek.szallitasiido}</span> munkanap</div>
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
                                    <link itemprop="availability" href="http://schema.org/OutOfStock">
                                {else}
                                    <link itemprop="availability" href="http://schema.org/InStock">
                                {/if}
                                <link href="/termek/{$termek.slug}" itemprop="url">
                                <span itemprop="price" content="{number_format($termek.bruttohuf,0,'','')}">{number_format($termek.bruttohuf,0,',',' ')} <span itemprop="priceCurrency" content="HUF"> Ft</span></span>
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
                                {elseif ($termek.akciotipus == 2)}{$termek.akciostop}-ig<br>vagy a készlet erejéig tart
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
                                {if (count($termek.cimkelapon) != 0)}<li><a href="#tulajdonsagTab" data-toggle="tab">{t('Tulajdonságok')}</a></li>{/if}
                                {if (count($termek.kapcsolodok) != 0)}<li><a href="#kapcsolodoTab" data-toggle="tab">{t('Kapcsolódó termékek')}</a></li>{/if}
                                {if (count($termek.hasonlotermekek) != 0)}<li><a href="#hasonloTermekTab" data-toggle="tab">{t('Hasonló termékek')}</a></li>{/if}
                                {if (count($termek.ertekelesek) != 0)}<li><a href="#ertekelesTab" id="ertekelesa" data-toggle="tab">{t('Értékelések')}</a></li>{/if}
                                <!--li><a href="#hasonlotermekTab" data-toggle="tab">{t('Hasonló termékek')}</a></li-->
                                {if ($szallitasifeltetelsablon)}
                                <li><a href="#szallitasTab" data-toggle="tab">{t('Szállítás és fizetés')}</a></li>
                                {/if}
                            </ul>
                            <div class="tab-content termektabbablekeret">
                                <div id="leirasTab" class="tab-pane active">
                                    <span itemprop="description">{$termek.leiras}</span>
                                </div>
                                {if (count($termek.cimkelapon)!=0)}
                                <div id="tulajdonsagTab" class="tab-pane">
                                    <div class="span6 nincsbalmargo">
                                    <table class="table table-striped table-condensed"><tbody>
                                        {foreach $termek.cimkelapon as $_cimke}
                                            <tr>
                                                <td>{$_cimke.kategorianev}</td>
                                                <td>{if ($_cimke.ismarka)}<a href="{$_cimke.termeklisturl}">{/if}
                                                    {if ($_cimke.kiskepurl!='')}<img src="{$_cimke.kiskepurl}" alt="{$_cimke.caption}" title="{$_cimke.caption}"> {/if}
                                                    {if (!$_cimke.dontshowcaption || $_cimke.kiskepurl=='')}{$_cimke.caption}{/if}
                                                    {if ($_cimke.ismarka)}</a>{/if}
                                                    {if ($_cimke.leiras)}<i class="icon-question-sign tooltipbtn hidden-phone js-tooltipbtn" title="{$_cimke.leiras}"></i>{/if}
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </tbody></table>
                                    </div>
                                </div>
                                {/if}
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
                                {if (count($termek.hasonlotermekek)!=0)}
                                <div id="hasonloTermekTab" class="tab-pane">
                                {$lntcnt=count($termek.hasonlotermekek)}
                                {$step=4}
                                {for $i=0 to $lntcnt-1 step $step}
                                    <div>
                                    {for $j=0 to $step-1}
                                        {if ($i+$j<$lntcnt)}
                                        {$_hasonlo=$termek.hasonlotermekek[$i+$j]}
                                        <div class="textaligncenter pull-left" style="width:{100/$step}%">
                                            <div class="kapcsolodoTermekInner">
                                                <a href="{$_hasonlo.link}">
                                                    <div class="kapcsolodoImageContainer">
                                                        <img src="{$_hasonlo.minikepurl}" title="{$_hasonlo.caption}" alt="{$_hasonlo.caption}">
                                                    </div>
                                                    <div>{$_hasonlo.caption}</div>
                                                    <h5>
                                                        {if ($_hasonlo.akcios)}
                                                        <span><span class="akciosar">{number_format($_hasonlo.eredetibruttohuf,0,',',' ')} Ft</span> helyett {number_format($_hasonlo.bruttohuf,0,',',' ')} Ft</span>
                                                        {else}
                                                        <span>{number_format($_hasonlo.bruttohuf,0,',',' ')} Ft</span>
                                                        {/if}
                                                    </h5>
                                                    <a href="{$_hasonlo.link}" class="btn okbtn">Részletek</a>
                                                </a>
                                            </div>
                                        </div>
                                        {/if}
                                    {/for}
                                    </div>
                                {/for}
                                </div>
                                {/if}
                                {if (count($termek.ertekelesek) != 0)}
                                    <div id="ertekelesTab" class="tab-pane">
                                        {$ertcikl = 0}
                                        {foreach $termek.ertekelesek as $ertekeles}
                                            {$ertcikl = $ertcikl + 1}
                                            <div class="ratinglist{if ($ertcikl mod 2 == 0)} ratinglist-odd{/if}">
                                                <div><strong>{$ertekeles.partnernev} - {$ertekeles.datum}</strong></div>
                                                <div>
                                                <div class="c-rating" data-rating-value="{$ertekeles.ertekeles}">
                                                    <button>1</button>
                                                    <button>2</button>
                                                    <button>3</button>
                                                    <button>4</button>
                                                    <button>5</button>
                                                </div>
                                                </div>
                                                <p>{$ertekeles.szoveg}</p>
                                                <div><strong>Előnyök</strong></div>
                                                <p>{$ertekeles.elony}</p>
                                                <div><strong>Hátrányok</strong></div>
                                                <p>{$ertekeles.hatrany}</p>
                                            </div>
                                        {/foreach}
                                    </div>
                                {/if}
                                <!--div id="ertekelesTab" class="tab-pane">
                                    <p>mindjart itt lesznek az ertekelesek</p>
                                </div>
                                <div id="hasonlotermekTab" class="tab-pane">
                                    <p>mindjart itt lesznek a hasonlo termekek</p>
                                </div-->
                                {if ($szallitasifeltetelsablon)}
                                <div id="szallitasTab" class="tab-pane">
                                    {$szallitasifeltetelsablon}
                                </div>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
                {if (count($termek.blogposztok)>0)}
                <div class="row">
                    <div class="span9">
                        <h3>Kapcsolódó blogbejegyzések</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="span9">
                        {$i=0}
                        {foreach $termek.blogposztok as $_child}
                            {if ($_child.lathato && ($i<$blogposztdb))}
                                {$i=$i+1}
                                <div class="kat" data-href="/blogposzt/{$_child.slug}">
                                    <div class="kattext">
                                        <div class="blogkivonatkep"><a href="/blogposzt/{$_child.slug}" rel="nofollow"><img src="{$_child.kepurlsmall}"</a></div>
                                        <div class="kattitle"><a href="/blogposzt/{$_child.slug}" rel="nofollow">{$_child.cim}</a></div>
                                        <div>{$_child.megjelenesdatumstr}</div>
                                        <div class="katcopy">{$_child.kivonat}</div>
                                    </div>
                                </div>
                            {/if}
                        {/foreach}
                    </div>
                </div>
                {/if}
                {if (count($hozzavasarolttermekek)>0)}
                <div class="row">
                    <div class="span9">
                    <div class="blockHeader">
                        <h2 class="main">{t('Ehhez a termékhez vásárolták még')}</h2>
                    </div>
                    <div id="hozzavasarolttermekslider" class="royalSlider contentSlider rsDefaultInv termekSlider">
                        {$lntcnt=count($hozzavasarolttermekek)}
                        {$step=3}
                        {for $i=0 to $lntcnt-1 step $step}
                            <div>
                            {for $j=0 to $step-1}
                                {if ($i+$j<$lntcnt)}
                                {$_termek=$hozzavasarolttermekek[$i+$j]}
                                <div class="textaligncenter pull-left" style="width:{100/$step}%">
                                    <div class="termekSliderTermekInner">
                                        <a href="/termek/{$_termek.slug}">
                                            <div class="termekSliderImageContainer">
                                                <img src="{$_termek.minikepurl}" title="{$_termek.caption}" alt="{$_termek.caption}">
                                            </div>
                                            <div>{$_termek.caption}</div>
                                            <h5 class="main"><span>{number_format($_termek.bruttohuf,0,',',' ')} Ft</span></h5>
                                        </a>
                                    </div>
                                </div>
                                {/if}
                            {/for}
                            </div>
                        {/for}
                    </div>
                    </div>
                </div>
                {/if}
            </div>
            <div class="span3">
                <h4 class="textaligncenter">Legnépszerűbb termékeink</h4>
                {foreach $legnepszerubbtermekek as $_nepszeru}
                <div class="textaligncenter">
                    <div class="kapcsolodoTermekInner">
                        <a href="{$_nepszeru.link}">
                            <div class="kapcsolodoImageContainer">
                                <img src="{$_nepszeru.minikepurl}" title="{$_nepszeru.caption}" alt="{$_nepszeru.caption}">
                            </div>
                            <div>{$_nepszeru.caption}</div>
                            <h5>
                                {if ($_nepszeru.akcios)}
                                <span><span class="akciosar">{number_format($_nepszeru.eredetibruttohuf,0,',',' ')} Ft</span> helyett {number_format($_nepszeru.bruttohuf,0,',',' ')} Ft</span>
                                {else}
                                <span>{number_format($_nepszeru.bruttohuf,0,',',' ')} Ft</span>
                                {/if}
                            </h5>
                        </a>
                    </div>
                </div>
                {/foreach}
            </div>
        </div>
	</article>
</div>
{include 'termekertesitomodal.tpl'}
{/block}
