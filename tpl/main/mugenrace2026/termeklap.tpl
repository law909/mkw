{extends "base.tpl"}

{block "meta"}
    <meta property="og:title" content="{$pagetitle|default}"/>
    <meta property="og:url" content="{$serverurl}/product/{$termek.slug}"/>
    <meta property="og:description" content="{$termek.rovidleiras}"/>
    <meta property="og:image" content="{$termek.fullkepurl}"/>
    <meta property="og:type" content="product"/>
{/block}

{block "script"}
    <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
    <script>
        // document.addEventListener('DOMContentLoaded', function () {
        //     if (typeof fbq === 'function') {
        fbq('track', 'ViewContent', {
            content_ids: ['{$termek.id}'],
            content_name: '{$termek.caption|escape:"javascript"}',
            content_type: 'product',
            value: {number_format($termek.bruttohuf,0,',','.')},
            currency: '{$valutanemnev}'
        });
        //     }
        // });
    </script>
{/block}

{block "kozep"}
    <div class="container whitebg product-datasheet">
        {* <div class="container morzsa">
            <div class="row">
                <div class="col" xmlns:v="http://rdf.data-vocabulary.org/#">
                    <span class="page-header__breadcrumb flex-lc" itemprop="breadcrumb ">
                        {if ($navigator|default)}
                            <a href="/" rel="v:url" property="v:title">
                                {t('Home')}
                            </a>
                            <i class="icon arrow-right"></i>
                            {foreach $navigator as $_navi}
                                {if ($_navi.url|default)}
                                    <span typeof="v:Breadcrumb">
                                        <a href="/termekfa/{$_navi.url}" rel="v:url" property="v:title">
                                            {$_navi.caption|capitalize}
                                        </a>
                                    </span>
                                    <i class="icon arrow-right"></i>
                                {else}
                                    {$_navi.caption|capitalize}
                                {/if}
                            {/foreach}
                        {/if}
                    </span>
                </div>
            </div>
        </div> *}

        <article itemtype="http://schema.org/Product" itemscope="">
            <div class="row product-datasheet__content">
                <div class="col">
                    <div class="row">
                        <div class="col product-datasheet__image-column flex-tr">


                            <div class="product-carousel-container">
                                <div class="flags">
                                    {if (isset($termek.uj) && $termek.uj)}
                                        <div class="flag new-product">{t('Új')}</div>
                                    {/if}

                                    {* {if (isset($termek.akcios) && $termek.akcios)}
                                        <div class="flag sale-product">{t('Akciós')}</div>
                                    {/if} *}

                                    {if (isset($termek.kiemelt) && $termek.kiemelt)}
                                        <div class="flag featured">{t('Kiemelt')}</div>
                                    {/if}
                                    {* {if (isset($termek.top10) && $termek.top10)}
                                        <div class="flag sale-product">{t('Top 10')}</div>
                                    {/if} *}
                                </div>

                                <div class="thumbs" id="thumbs"></div>

                                <div class="main-image-wrapper">

                                    <img id="mainImage" class="main-image" src=""/>

                                    <div class="nav-btn-container flex-cr">
                                        <div class="nav-btn nav-left" id="prevBtn">⟨</div>
                                        <div class="nav-btn nav-right" id="nextBtn">⟩</div>
                                    </div>
                                </div>

                                <div id="lightbox" class="lightbox hidden">
                                    <div class="lightbox-backdrop"></div>
                                    <button class="lightbox-nav lightbox-prev">‹</button>
                                    <button class="lightbox-nav lightbox-next">›</button>
                                    <img id="lightboxImage" class="lightbox-image" src="" alt="">
                                    <div class="lightbox-close">×</div>
                                </div>
                            </div>
                            <script>
                                const images = [
                                    "{$imagepath}{$termek.kepurl}",

                                    {$kcnt=count($termek.kepek)}
                                    {if ($kcnt>0)}
                                    {$step=4}
                                    {for $i=0 to $kcnt-1 step $step}
                                    {for $j=0 to $step-1}
                                    {if ($i+$j<$kcnt)}
                                    {$_kep=$termek.kepek[$i+$j]}
                                    "{$imagepath}{$_kep.kepurl}",
                                    {/if}
                                    {/for}
                                    {/for}
                                    {/if}

                                ];
                            </script>
                        </div>

                        <div class="col product-datasheet__details-column">
                            <div class="korbepadding">

                            <span class="page-header__breadcrumb flex-lc" itemprop="breadcrumb ">
                                {if ($navigator|default)}
                                    <a href="/" rel="v:url" property="v:title">
                                        {t('Home')}
                                    </a>
                                    <i class="icon arrow-right"></i>



{foreach $navigator as $_navi}
                                    {if ($_navi.url|default)}
                                        <span typeof="v:Breadcrumb" class="breadcrumb-{$_navi.url}">
                                                <a href="/categories/{$_navi.url}" rel="v:url" property="v:title">
                                                    {$_navi.caption|lower|capitalize}
                                                </a>
                                            </span>
                                        <i class="icon arrow-right breadcrumb-{$_navi.url}"></i>
                                    {else}
                                        {$_navi.caption|lower|capitalize}
                                    {/if}
                                {/foreach}
                                {/if}
                            </span>
                                {* Breadcrumb *}
                                <div class="textaligncenter product-datasheet__title"><h1 itemprop="name"
                                                                                          class="termeknev">{$termek.caption|lower|capitalize}</h1></div>
                                {* Title  *}

                                
                                <div>
                                <span class="bold">{t('Cikkszám')}:</span> <span itemprop="productID">{$termek.cikkszam}</span>
                                </div>
                                {* SKU  *}
                                
                                {if ($termek.me)}
                                    <div><span class="bold">{t('Kiszerelés')}:</span> {$termek.me}</div>
                                {/if}
                                {* Packaging  *}
                                
                                {if ($termek.szallitasiido && (!$termek.nemkaphato))}
                                    <div><span class="bold">{t('Szállítási idő')}:</span> max. <span
                                            id="termekszallitasiido{$termek.id}">{$termek.szallitasiido}</span> {t('munkanap')}</div>
                                {/if}
                                {* Delivery time  *}

                                <div id="termekprice{$termek.id}" class="itemPrice product-datasheet__price textalignright" itemprop="offers" itemscope
                                     itemtype="http://schema.org/Offer">
                                    {if (isset($termek.eredetibrutto) && $termek.eredetibrutto>0)}
                                        <span class="akciosarszoveg"><strong>{t('Eredeti ár')}:</strong>&nbsp;<span
                                                class="akciosar">{number_format($termek.eredetibrutto,0,',',' ')} {$termek.valutanemnev}</span></span>
                                    {/if}
                                    {if ($termek.nemkaphato)}
                                        <link itemprop="availability" href="http://schema.org/OutOfStock" content="{t('Nem kapható')}">
                                    {else}
                                        <link itemprop="availability" href="http://schema.org/InStock" content="{t('Kapható')}">
                                    {/if}
                                    <span itemprop="price">{number_format($termek.brutto,0,',',' ')} {$valutanemnev}</span>
                                </div>
                                {* Price  *}

                                

                                <div>
                                    <ul class="simalista">
                                        {foreach $termek.cimkeakciodobozban as $_jelzo}
                                            <li>{$_jelzo.caption}</li>
                                        {/foreach}
                                    </ul>
                                </div>
                                {* Labels  *}

                                {$_kosarbaclass="js-kosarba"}

                                {* Colors  *}
                                {if false} {*if ($termek.szinek)*}
                                    {$_kosarbaclass="js-kosarbaszinvaltozat"}
                                    <div class="row  product-datasheet__cart-container flex-col">
                                        <div class="js-valtozatbox kosarbacontainer ">
                                            <div class="pull-left gvaltozatcontainer">
                                                <div class="pull-left gvaltozatnev termekvaltozat">{t('Szín')}:</div>
                                                <div class="pull-left gvaltozatselect">
                                                    <div class="option-selector color-selector" data-termek="{$termek.id}">
                                                        {foreach $termek.szinek as $_v}
                                                            <div class="select-option {$_v|lower|replace:'/':'-'}" data-value="{$_v}" title="{$_v}"></div>
                                                        {/foreach}
                                                    </div>

                                                    <select class="js-szinvaltozatedit custom-select valtozatselect" data-termek="{$termek.id}">
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
                                {* Colors *}

                                <div class="kosarbacontainer">
                                    {* <div id="termekprice{$termek.id}" class="itemPrice textalignright" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        {if ($termek.nemkaphato)}
                                            <link itemprop="availability" href="http://schema.org/OutOfStock" content="Nem kapható">
                                        {else}
                                            <link itemprop="availability" href="http://schema.org/InStock" content="Kapható">
                                        {/if}
                                        <span itemprop="price">{number_format($termek.bruttohuf,0,',',' ')} {$valutanemnev}</span>
                                    </div> *}
                                    {if ($termek.nemkaphato)}
                                        <div class="textalignright">
                                            <a href="#" rel="nofollow" class="js-termekertesitobtn button bordered graybtn" data-termek="{$termek.id}"
                                               data-id="{$termek.id}">
                                                {t('Elfogyott')}
                                            </a>
                                        </div>
                                    {else}
                                        {if ($termek.brutto > 0)}
                                            {* <div class="textalignright">
                                                <a href="/kosar/add?id={$termek.id}" rel="nofollow" class="{$_kosarbaclass} button primary full-width cartbtn" data-termek="{$termek.id}" data-id="{$termek.id}" data-price="{number_format($termek.bruttohuf,0,',',' ')}" data-currency="{$valutanemnev}" data-name="{$termek.caption|escape:'javascript'}">
                                                    {t('Kosárba')}
                                                </a>
                                            </div> *}
                                        {/if}
                                    {/if}
                                </div>
                                {* Add to cart  *}

                                <div class="accordion">
                                    <div class="accordion-item">
                                        <div class="accordion-header">{t('Leírás')}<span class="arrow"></span></div>
                                        <div class="accordion-content">
                                            <span itemprop="description">{$termek.leiras}</span>
                                        </div>
                                    </div>

                                    {if (count($termek.cimkelapon)!=0)}
                                        <div class="accordion-item">
                                            <div class="accordion-header">{t('Tulajdonságok')}<span class="arrow"></span></div>
                                            <div class="accordion-content">
                                                <table class="table table-striped table-condensed">
                                                    <tbody>
                                                    {foreach $termek.cimkelapon as $_cimke}
                                                        <tr>
                                                            <td>{$_cimke.kategorianev}</td>
                                                            <td>{if ($_cimke.ismarka)}<a href="{$_cimke.termeklisturl}">{/if}
                                                                    {if ($_cimke.kiskepurl!='')}<img src="{$imagepath}{$_cimke.kiskepurl}"
                                                                                                     alt="{$_cimke.caption}" title="{$_cimke.caption}"> {/if}
                                                                    {if (!$_cimke.dontshowcaption || $_cimke.kiskepurl=='')}{$_cimke.caption}{/if}
                                                                    {if ($_cimke.ismarka)}</a>{/if}
                                                                {if ($_cimke.leiras)}<i class="icon-question-sign tooltipbtn hidden-phone js-tooltipbtn"
                                                                                        title="{$_cimke.leiras}"></i>{/if}
                                                            </td>
                                                        </tr>
                                                    {/foreach}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    {/if}
                                    {if (count($termek.kapcsolodok)!=0)}
                                        <div class="accordion-item product-datasheet__related-products">
                                            <div class="accordion-header">{t('Kapcsolódó termékek')}<span class="arrow"></span></div>
                                            <div class="accordion-content">
                                                {$lntcnt=count($termek.kapcsolodok)}
                                                {$step=4}
                                                {for $i=0 to $lntcnt-1 step $step}
                                                    <div>
                                                        {for $j=0 to $step-1}
                                                            {if ($i+$j<$lntcnt)}
                                                                {$_kapcsolodo=$termek.kapcsolodok[$i+$j]}
                                                                {include 'blokkok/termek.tpl' termek=$_kapcsolodo detailsbutton=true}
                                                            {/if}
                                                        {/for}
                                                    </div>
                                                {/for}
                                            </div>
                                        </div>
                                    {/if}

                                    {if (isset($szallitasifeltetelsablon))}
                                        <div class="accordion-item">
                                            <div class="accordion-header">{t('Szállítás és fizetés')}<span class="arrow"></span></div>
                                            <div class="accordion-content">
                                                {$szallitasifeltetelsablon}
                                            </div>
                                        </div>
                                    {/if}
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="span9">
                        </div>
                    </div>
                </div>
            </div>



            {if (count($termek.hasonlotermekek)!=0)}
                {include 'blokkok/termekcarousel.tpl' termeklista=$termek.hasonlotermekek fejlecszoveg={t('Hasonló termékek..')} hatterszin="light"} {* Carousel *}
                {* <div class="accordion-item product-datasheet__similar-products flex-col">
                    <div class="col">
                        <h2 class="main">{t('Hasonló termékek')}</h2>
                    </div>
                    <div class=" col product-datasheet__recommended-products-list">
                        {$lntcnt=count($termek.hasonlotermekek)}
                        {$step=4}
                        {for $i=0 to $lntcnt-1 step $step}
                            {for $j=0 to $step-1}
                                {if ($i+$j<$lntcnt)}
                                    {$_hasonlo=$termek.hasonlotermekek[$i+$j]}
                                    {include 'blokkok/termek.tpl' termek=$_hasonlo detailsbutton=true}
                                {/if}
                            {/for}
                        {/for}
                    </div>
                </div> *}
            {/if}


            {if (count($hozzavasarolttermekek)>0)}
                {include 'blokkok/termekcarousel.tpl' termeklista=$hozzavasarolttermekek fejlecszoveg={t('Ehhez a termékhez vásárolták még')} hatterszin="light"} {* Carousel *}
                {* <div class="row product-datasheet__recommended-products flex-col">
                    <div class="col">
                        <h2 class="main">{t('Ehhez a termékhez vásárolták még')}</h2>
                    </div>
                    <div class=" col product-datasheet__recommended-products-list">
                        {$lntcnt=count($hozzavasarolttermekek)}
                        {$step=3}
                        {for $i=0 to $lntcnt-1 step $step}
                            {for $j=0 to $step-1}
                                {if ($i+$j<$lntcnt)}
                                    {$_termek=$hozzavasarolttermekek[$i+$j]}
                                    {include 'blokkok/termek.tpl' termek=$_termek detailsbutton=true}
                                {/if}
                            {/for}
                        {/for}
                    </div>
                </div> *}
            {/if}
            <hr>

            {if (count($legnepszerubbtermekek)>0)}  
            {include 'blokkok/termekcarousel.tpl' termeklista=$legnepszerubbtermekek fejlecszoveg={t('Legnépszerűbb termékeink  ')} hatterszin="light"} {* Carousel *}
            {/if}
            {* <div class="row product-datasheet__popular-products flex-col">
                <div class="col">
                    <h4 class="textaligncenter">{t('Legnépszerűbb termékeink')}</h4>
                </div>
                <div class="col product-datasheet__popular-products-list">
                    {foreach $legnepszerubbtermekek as $_nepszeru}
                        {include 'blokkok/termek.tpl' termek=$_nepszeru detailsbutton=true}
                    {/foreach}
                </div>
            </div> *}
        </article>
    </div>
    {include 'termekertesitomodal.tpl'}
{/block}
