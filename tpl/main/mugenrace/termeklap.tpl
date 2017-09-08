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
                        <b>{t('Ön itt áll')}: </b>
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
                            <a id="termekkeplink{$termek.id}" href="{$imagepath}{$termek.kepurl}" class="js-lightbox" title="{$termek.caption}">
                                <img id="termekkep{$termek.id}" class="zoom" data-magnify-src="{$imagepath}{$termek.eredetikepurl}" itemprop="image" src="{$imagepath}{$termek.kepurl}" alt="{$termek.caption}" title="{$termek.caption}">
                            </a>
                        </div>
                        {$kcnt=count($termek.kepek)}
                        {if ($kcnt>0)}
                        <div class="js-termekimageslider termekimageslider termekimagecontainer textaligncenter royalSlider contentSlider rsDefaultInv">
                            {$step=4}
                            {for $i=0 to $kcnt-1 step $step}
                                <div>
                                {for $j=0 to $step-1}
                                    {if ($i+$j<$kcnt)}
                                        {$_kep=$termek.kepek[$i+$j]}
                                        <a href="{$imagepath}{$_kep.kepurl}" class="js-lightbox" title="{$_kep.leiras}">
                                            <img class="termeksmallimage" src="{$imagepath}{$_kep.minikepurl}" alt="{$_kep.leiras}" title="{$_kep.leiras}">
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
                            <div><span class="bold">{t('Cikkszám')}:</span> <span itemprop="productID">{$termek.cikkszam}</span></div>
                            {if ($termek.me)}
                            <div><span class="bold">{t('Kiszerelés')}:</span> {$termek.me}</div>
                            {/if}
                            {if ($termek.szallitasiido && (!$termek.nemkaphato))}
                                <div><span class="bold">{t('Szállítási idő')}:</span> max. <span id="termekszallitasiido{$termek.id}">{$termek.szallitasiido}</span> {t('munkanap')}</div>
                            {/if}
                            <div>
                                <ul class="simalista">
                                {foreach $termek.cimkeakciodobozban as $_jelzo}
                                    <li>{$_jelzo.caption}</li>
                                {/foreach}
                                </ul>
                            </div>
                            {$_kosarbaclass="js-kosarba"}
                            {if ($termek.szinek)}
                            {$_kosarbaclass="js-kosarbaszinvaltozat"}
                            <div class="row">
                                <div class="js-valtozatbox span2 kosarbacontainer">
                                    <div class="pull-left gvaltozatcontainer">
                                        <div class="pull-left gvaltozatnev termekvaltozat">{t('Szín')}:</div>
                                        <div class="pull-left gvaltozatselect">
                                            <select class="js-szinvaltozatedit valtozatselect" data-termek="{$termek.id}">
                                                <option value="">{t('Válasszon')}</option>
                                                {foreach $termek.szinek as $_v}
                                                    <option value="{$_v}">{$_v}</option>
                                                {/foreach}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {/if}
                            <div class="kosarbacontainer">
                            <div id="termekprice{$termek.id}" class="itemPrice textalignright" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                {if ($termek.nemkaphato)}
                                    <link itemprop="availability" href="http://schema.org/OutOfStock" content="Nem kapható">
                                {else}
                                    <link itemprop="availability" href="http://schema.org/InStock" content="Kapható">
                                {/if}
                                <span itemprop="price">{number_format($termek.bruttohuf,0,',',' ')} {$valutanemnev}</span>
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="span9">
                        <div id="termekTabbable" class="tabbable">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#leirasTab" data-toggle="tab">{t('Leírás')}</a></li>
                                {if (count($termek.cimkelapon)!=0)}<li><a href="#tulajdonsagTab" data-toggle="tab">{t('Tulajdonságok')}</a></li>{/if}
                                {if (count($termek.kapcsolodok)!=0)}<li><a href="#kapcsolodoTab" data-toggle="tab">{t('Kapcsolódó termékek')}</a></li>{/if}
                                {if (count($termek.hasonlotermekek)!=0)}<li><a href="#hasonloTermekTab" data-toggle="tab">{t('Hasonló termékek')}</a></li>{/if}
                                {if ($szallitasifeltetelsablon)}
                                <li><a href="#szallitasTab" data-toggle="tab">{t('Szállítás és fizetés')}</a></li>
                                {/if}
                            </ul>
                            <div class="tab-content keret">
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
                                                    {if ($_cimke.kiskepurl!='')}<img src="{$imagepath}{$_cimke.kiskepurl}" alt="{$_cimke.caption}" title="{$_cimke.caption}"> {/if}
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
                                                        <img src="{$imagepath}{$_kapcsolodo.minikepurl}" title="{$_kapcsolodo.caption}" alt="{$_kapcsolodo.caption}">
                                                    </div>
                                                    <div>{$_kapcsolodo.caption}</div>
                                                    <h5>
                                                        <span>{number_format($_kapcsolodo.bruttohuf,0,',',' ')} {$valutanemnev}</span>
                                                    </h5>
                                                    <a href="{$_kapcsolodo.link}" class="btn okbtn">{t('Részletek')}</a>
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
                                                        <img src="{$imagepath}{$_hasonlo.minikepurl}" title="{$_hasonlo.caption}" alt="{$_hasonlo.caption}">
                                                    </div>
                                                    <div>{$_hasonlo.caption}</div>
                                                    <h5>
                                                        <span>{number_format($_hasonlo.bruttohuf,0,',',' ')} {$valutanemnev}</span>
                                                    </h5>
                                                    <a href="{$_hasonlo.link}" class="btn okbtn">{t('Részletek')}</a>
                                                </a>
                                            </div>
                                        </div>
                                        {/if}
                                    {/for}
                                    </div>
                                {/for}
                                </div>
                                {/if}
                                {if ($szallitasifeltetelsablon)}
                                <div id="szallitasTab" class="tab-pane">
                                    {$szallitasifeltetelsablon}
                                </div>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
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
                                                <img src="{$imagepath}{$_termek.minikepurl}" title="{$_termek.caption}" alt="{$_termek.caption}">
                                            </div>
                                            <div>{$_termek.caption}</div>
                                            <h5 class="main"><span>{number_format($_termek.bruttohuf,0,',',' ')} {$valutanemnev}</span></h5>
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
                <h4 class="textaligncenter">{t('Legnépszerűbb termékeink')}</h4>
                {foreach $legnepszerubbtermekek as $_nepszeru}
                <div class="textaligncenter">
                    <div class="kapcsolodoTermekInner">
                        <a href="{$_nepszeru.link}">
                            <div class="kapcsolodoImageContainer">
                                <img src="{$imagepath}{$_nepszeru.minikepurl}" title="{$_nepszeru.caption}" alt="{$_nepszeru.caption}">
                            </div>
                            <div>{$_nepszeru.caption}</div>
                            <h5>
                                <span>{number_format($_nepszeru.bruttohuf,0,',',' ')} {$valutanemnev}</span>
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
