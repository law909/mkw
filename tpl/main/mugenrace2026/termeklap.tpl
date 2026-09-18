{extends "base.tpl"}
{block "script"}
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
    {$termekjsonld|default}
    <div class="container whitebg product-datasheet">

        <article class="product-datasheet__article">
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

                                {* a galéria a szerverválaszban van: a fő kép így az LCP-elem, és a
                                   termék képei bekerülnek az indexbe. A bélyegképek sorrendje azonos
                                   az alábbi images tömbbel, mert a JS az index alapján vált képet. *}
                                <div class="thumbs" id="thumbs">
                                    <img src="{$imagepath}{$termek.kepurl400}" class="active" alt="{$termek.caption|escape}"
                                         title="{$termek.caption|escape}">
                                    {foreach $termek.kepek as $_kep}
                                        <img src="{$imagepath}{$_kep.kepurl400}" alt="{if ($_kep.leiras)}{$_kep.leiras|escape}{else}{$termek.caption|escape}{/if}"
                                             title="{if ($_kep.leiras)}{$_kep.leiras|escape}{else}{$termek.caption|escape}{/if}">
                                    {/foreach}
                                </div>

                                <div class="main-image-wrapper">

                                    <img id="mainImage" class="main-image" src="{$imagepath}{$termek.kepurl}"
                                         srcset="{$imagepath}{$termek.kepurl400} 400w, {$imagepath}{$termek.kepurl} 1000w"
                                         sizes="(max-width: 768px) 100vw, 640px"
                                         alt="{$termek.caption|escape}" title="{$termek.caption|escape}" fetchpriority="high">

                                    <div class="nav-btn-container flex-cr">
                                        <div class="nav-btn nav-left" id="prevBtn">⟨</div>
                                        <div class="nav-btn nav-right" id="nextBtn">⟩</div>
                                    </div>
                                </div>

                                <div id="lightbox" class="lightbox hidden">
                                    <div class="lightbox-backdrop"></div>
                                    <button class="lightbox-nav lightbox-prev">‹</button>
                                    <button class="lightbox-nav lightbox-next">›</button>
                                    {* src nélkül: az üres src-t a böngésző a saját oldal URL-jével tölti le *}
                                    <img id="lightboxImage" class="lightbox-image" alt="">
                                    <div class="lightbox-close">×</div>
                                </div>
                            </div>
                            <script>
                                const images = [
                                    "{$imagepath}{$termek.kepurl}"{foreach $termek.kepek as $_kep},
                                    "{$imagepath}{$_kep.kepurl}"{/foreach}
                                ];
                            </script>
                        </div>

                        <div class="col product-datasheet__details-column">
                            <div class="korbepadding">

                                {include 'morzsa.tpl'}
                                <div class="textaligncenter product-datasheet__title"><h1 class="termeknev">{$termek.caption|lower|capitalize}</h1></div>
                                <div>
                                    <span class="bold">{t('Cikkszám')}:</span> <span class="termekcikkszam">{$termek.cikkszam}</span>
                                </div>
                                {if ($termek.me)}
                                    <div><span class="bold">{t('Kiszerelés')}:</span> {$termek.me}</div>
                                {/if}
                                {if ($termek.szallitasiido && (!$termek.nemkaphato))}
                                    <div><span class="bold">{t('Szállítási idő')}:</span> max. <span
                                            id="termekszallitasiido{$termek.id}">{$termek.szallitasiido}</span> {t('munkanap')}</div>
                                {/if}
                                {* az ár és a készlet strukturált adata a JSON-LD-ben van, itt csak a látható szöveg *}
                                <div id="termekprice{$termek.id}" class="itemPrice product-datasheet__price textalignright">
                                    {if (isset($termek.eredetibrutto) && $termek.eredetibrutto>0)}
                                        <span class="akciosarszoveg"><strong>{t('Eredeti ár')}:</strong>&nbsp;<span
                                                class="akciosar">{number_format($termek.eredetibrutto,0,',',' ')} {$termek.valutanemnev}</span></span>
                                    {/if}
                                    <span class="termekar">{number_format($termek.brutto,0,',',' ')} {$valutanemnev}</span>
                                </div>
                                <div>
                                    <ul class="simalista">
                                        {foreach $termek.cimkeakciodobozban as $_jelzo}
                                            <li>{$_jelzo.caption}</li>
                                        {/foreach}
                                    </ul>
                                </div>
                                {$_kosarbaclass="js-kosarba"}
                                {if ($hidecart != 1)}
                                    {$_kosarbaclass="js-kosarbaszinvaltozat"}
                                    <div class="row  product-datasheet__cart-container flex-col">
                                        <div class="js-valtozatbox kosarbacontainer ">
                                            <div class="pull-left gvaltozatcontainer">
                                                <div class="pull-left gvaltozatnev termekvaltozat">{t('Szín')}:</div>
                                                <div class="pull-left gvaltozatselect">
                                                    <div class="option-selector color-selector" data-termek="{$termek.id}">
                                                        {foreach from=$termek.szinek item=$_v key=$_k}
                                                            <div class="select-option {$_v|lower|replace:'/':'-'}" data-value="{$_k}" title="{$_v}"></div>
                                                        {/foreach}
                                                    </div>

                                                    <select class="js-szinvaltozatedit custom-select valtozatselect" data-termek="{$termek.id}">
                                                        <option value="">{t('Válasszon')}</option>
                                                        {foreach from=$termek.szinek item=$_v key=$_k}
                                                            <option value="{$_k}"{if ($_k===$szin_id)} selected="selected"{/if}>{$_v}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {/if}
                                <div class="kosarbacontainer">
                                    {if ($termek.nemkaphato)}
                                        <div class="textalignright">
                                            <a href="#" rel="nofollow" class="js-termekertesitobtn button bordered graybtn" data-termek="{$termek.id}"
                                               data-id="{$termek.id}">
                                                {t('Elfogyott')}
                                            </a>
                                        </div>
                                    {else}
                                        {if ($hidecart != 1) && ($termek.brutto > 0)}
                                            <div class="textalignright">
                                                <button type="button" class="{$_kosarbaclass} button primary full-width cartbtn" data-url="/kosar/add?id={$termek.id}"
                                                        data-termek="{$termek.id}" data-id="{$termek.id}" data-price="{number_format($termek.brutto,0,',',' ')}"
                                                        data-currency="{$valutanemnev}" data-name="{$termek.caption|escape:'javascript'}">
                                                    {t('Kosárba')}
                                                </button>
                                            </div>
                                        {/if}
                                    {/if}
                                </div>
                                <div class="accordion">
                                    <div class="accordion-item">
                                        <div class="accordion-header">{t('Leírás')}<span class="arrow"></span></div>
                                        <div class="accordion-content">
                                            {$termek.leiras}
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
        </article>
    </div>
    <div id="MainContent">
        <div class="container whitebg product-datasheet">
            {if (count($termek.hasonlotermekek)!=0)}
                {include 'blokkok/termekcarousel.tpl' termeklista=$termek.hasonlotermekek fejlecszoveg=t('Hasonló termékek') hatterszin="light"} {* Carousel *}
            {/if}

            <hr>

            {if (count($hozzavasarolttermekek)>0)}
                {include 'blokkok/termekcarousel.tpl' termeklista=$hozzavasarolttermekek fejlecszoveg=t('Ehhez a termékhez vásárolták még') hatterszin="light"} {* Carousel *}
            {/if}

            <hr>

            {if (count($legnepszerubbtermekek)>0)}
                {include 'blokkok/termekcarousel.tpl' termeklista=$legnepszerubbtermekek fejlecszoveg=t('Legnépszerűbb termékeink') hatterszin="light"} {* Carousel *}
            {/if}

        </div>
    </div>
    {include 'termekertesitomodal.tpl'}
{/block}
