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

                                {if (isset($termek.akcios) && $termek.akcios)}
                                    <div class="flag sale-product">{t('Akciós')}</div>
                                {/if}
                                
                                {if (isset($termek.kiemelt) && $termek.kiemelt)}
                                    <div class="flag featured">{t('Kiemelt')}</div>
                                {/if}
                                {* {if (isset($termek.top10) && $termek.top10)}
                                    <div class="flag sale-product">{t('Top 10')}</div>
                                {/if} *}
                            </div>

                            <div class="thumbs" id="thumbs"></div>

                            <div class="main-image-wrapper">

                                <img id="mainImage" class="main-image" src="" />

                                <div class="nav-btn-container flex-cr">
                                    <div class="nav-btn nav-left" id="prevBtn">⟨</div>
                                    <div class="nav-btn nav-right" id="nextBtn">⟩</div>
                                </div>
                            </div>

                            <div id="lightbox" class="lightbox hidden">
                                <div class="lightbox-backdrop"></div>
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
                        // ########################
                        // Product profile carousel
                        // ########################                           
                        const thumbsContainer = document.getElementById("thumbs");
                        const mainImage = document.getElementById("mainImage");
                        mainImage.addEventListener("click", () => {
                        openLightbox(mainImage.src);
                        });
                        

                        let currentIndex = 0;


                        // Render thumbs only once
                        images.forEach((src, index) => {
                        const img = document.createElement("img");
                        img.src = src;
                        img.dataset.index = index;
                        if (index === 0) img.classList.add("active");
                        img.onclick = () => changeImage(index, true);
                        thumbsContainer.appendChild(img);
                        });

                        const preloaded = images.map(src => {
                            const img = new Image();
                            img.src = src;
                            return img;
                        });

                        function setActiveThumb(index) {
                            const all = thumbsContainer.querySelectorAll("img");
                            all.forEach(t => t.classList.remove("active"));

                            const activeThumb = all[index];
                            activeThumb.classList.add("active");

                            const containerTop = thumbsContainer.scrollTop;
                            const containerBottom = containerTop + thumbsContainer.clientHeight;

                            const thumbTop = activeThumb.offsetTop;
                            const thumbBottom = thumbTop + activeThumb.offsetHeight;

                            if (thumbTop < containerTop) {
                                // kilóg felül
                                thumbsContainer.scrollTo({
                                top: thumbTop - 10,
                                behavior: "smooth"
                                });
                            } else if (thumbBottom > containerBottom) {
                                // kilóg alul
                                thumbsContainer.scrollTo({
                                top: thumbBottom - thumbsContainer.clientHeight + 10,
                                behavior: "smooth"
                                });
                            }
                        }


                        let isAnimating = false;

                        function changeImage(newIndex) {
                            if (newIndex === currentIndex) return;
                            if (isAnimating) return;

                            isAnimating = true;

                            const wrapper = document.querySelector(".main-image-wrapper");
                            const oldImage = wrapper.querySelector(".main-image");

                            const direction = newIndex > currentIndex ? 1 : -1;
                            const wrapperWidth = wrapper.clientWidth;

                            const newImg = document.createElement("img");
                            newImg.src = images[newIndex];
                            newImg.className = "main-image";
                            newImg.style.position = "absolute";
                            newImg.style.transform =
                                "translateX(" + (direction > 0 ? wrapperWidth : -wrapperWidth) + "px)";
                            newImg.style.transition = "transform 0.15s ease";

                            wrapper.appendChild(newImg);

                            // 👉 lightbox click
                            newImg.addEventListener("click", () => {
                                openLightbox(newImg.src);
                            });

                            newImg.onload = () => {
                                newImg.getBoundingClientRect();

                                oldImage.style.transition = "transform 0.15s ease";
                                oldImage.style.transform =
                                "translateX(" + (direction > 0 ? -wrapperWidth : wrapperWidth) + "px)";

                                newImg.style.transform = "translateX(0)";

                                setTimeout(() => {
                                    oldImage.remove();
                                    newImg.style.position = "";
                                    newImg.style.transform = "";
                                    newImg.style.transition = "";
                                    newImg.style.top = "";

                                    isAnimating = false; // 🔓 unlock
                                }, 10);
                            };

                            currentIndex = newIndex;
                            setActiveThumb(newIndex);
                        }



                        document.getElementById("prevBtn").onclick = () => {
                            const newIndex = (currentIndex - 1 + images.length) % images.length;
                            changeImage(newIndex);
                        };


                        document.getElementById("nextBtn").onclick = () => {
                            const newIndex = (currentIndex + 1) % images.length;
                            changeImage(newIndex);
                        };


                        // Init
                        mainImage.src = images[0];

                        // ########
                        // Lightbox
                        // ########
                        const lightbox = document.getElementById("lightbox");
                        const lightboxImage = document.getElementById("lightboxImage");
                        const lightboxClose = document.querySelector(".lightbox-close");
                        const lightboxBackdrop = document.querySelector(".lightbox-backdrop");

                        function openLightbox(src) {
                        lightboxImage.src = src;
                        lightbox.classList.remove("hidden");
                        document.body.style.overflow = "hidden"; // scroll lock
                        }

                        function closeLightbox() {
                        lightbox.classList.add("hidden");
                        lightboxImage.src = "";
                        document.body.style.overflow = "";
                        }

                        lightboxClose.addEventListener("click", closeLightbox);
                        lightboxBackdrop.addEventListener("click", closeLightbox);

                        document.addEventListener("keydown", (e) => {
                        if (e.key === "Escape" && !lightbox.classList.contains("hidden")) {
                            closeLightbox();
                        }
                        });

                        </script>


                        {* <div class="termekimagecontainer textaligncenter">
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
                        {/if} *}
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
                            <div class="textaligncenter product-datasheet__title"><h1 itemprop="name" class="termeknev">{$termek.caption|lower|capitalize}</h1></div>
                            {* Title  *}

                            <div id="termekprice{$termek.id}" class="itemPrice product-datasheet__price textalignright" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                 {if (isset($termek.eredetibrutto) && $termek.eredetibrutto>0)}
                                    <span class="akciosarszoveg">{t('Eredeti ár')}: <span class="akciosar">{number_format($termek.eredetibrutto,0,',',' ')} {$termek.valutanemnev}</span></span>
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
                                <span class="bold">{t('Cikkszám')}:</span> <span itemprop="productID">{$termek.cikkszam}</span>
                            </div>
                            {* SKU  *}

                            {if ($termek.me)}
                                <div><span class="bold">{t('Kiszerelés')}:</span> {$termek.me}</div>
                            {/if}
                            {* Packaging  *}

                            {if ($termek.szallitasiido && (!$termek.nemkaphato))}
                                <div><span class="bold">{t('Szállítási idő')}:</span> max. <span id="termekszallitasiido{$termek.id}">{$termek.szallitasiido}</span> {t('munkanap')}</div>
                            {/if}
                            {* Delivery time  *}

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
                            {if ($termek.szinek)}
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
                                        <a href="#" rel="nofollow" class="js-termekertesitobtn button bordered graybtn" data-termek="{$termek.id}" data-id="{$termek.id}">
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
                                                <div class="textaligncenter pull-left product-datasheet__list-item">{* style="width:{100/$step}%" *}
                                                    <div class="kapcsolodoTermekInner">
                                                        <a href="{$_kapcsolodo.link}">
                                                            <div class="flags">
                                                                {if (isset($_kapcsolodo.uj) && $_kapcsolodo.uj)}
                                                                    <div class="flag new-product">{t('Új')}</div>
                                                                {/if}

                                                                {if (isset($_kapcsolodo.akcios) && $_kapcsolodo.akcios)}
                                                                    <div class="flag sale-product">{t('Akciós')}</div>
                                                                {/if}
                                                                
                                                                {if (isset($_kapcsolodo.kiemelt) && $_kapcsolodo.kiemelt)}
                                                                    <div class="flag featured">{t('Kiemelt')}</div>
                                                                {/if}
                                                                {* {if (isset($_kapcsolodo.top10) && $_kapcsolodo.top10)}
                                                                    <div class="flag sale-product">{t('Top 10')}</div>
                                                                {/if} *}
                                                            </div>

                                                            <div class="kapcsolodoImageContainer product-datasheet__list-item-image">
                                                                <img src="{$imagepath}{$_kapcsolodo.minikepurl}" title="{$_kapcsolodo.caption}" alt="{$_kapcsolodo.caption}">
                                                            </div>
                                                            <div class="product-datasheet__list-item-caption">{$_kapcsolodo.caption|lower|capitalize}</div>
                                                            <div class="product-datasheet__list-item-sku">{$_kapcsolodo.cikkszam}</div>
                                                            {if ((isset($_kapcsolodo.eredetibrutto) && $_kapcsolodo.eredetibrutto>0))}
                                                                <div class="termekprice" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                                                    <span class="akciosarszoveg"><span class="akciosar">{number_format($_kapcsolodo.eredetibrutto,0,',',' ')} {$_kapcsolodo.valutanemnev}</span></span>
                                                                </div>
                                                            {/if}
                                                            <h5>
                                                                <span>{number_format($_kapcsolodo.brutto,0,',',' ')} {$valutanemnev}</span>
                                                            </h5>
                                                            <a href="{$_kapcsolodo.link}" class="button bordered okbtn">{t('Részletek')}</a>
                                                        </a>
                                                    </div>
                                                </div>
                                                {/if}
                                            {/for}
                                            </div>
                                        {/for}
                                    </div>
                                </div>
                                {/if}

                                {if (count($termek.hasonlotermekek)!=0)}
                                <div class="accordion-item product-datasheet__similar-products">
                                    <div class="accordion-header">{t('Hasonló termékek')}<span class="arrow"></span></div>
                                    <div class="accordion-content">
                                        {$lntcnt=count($termek.hasonlotermekek)}
                                        {$step=4}
                                        {for $i=0 to $lntcnt-1 step $step}
                                            <div>
                                            {for $j=0 to $step-1}
                                                {if ($i+$j<$lntcnt)}
                                                {$_hasonlo=$termek.hasonlotermekek[$i+$j]}
                                                <div class="textaligncenter pull-left product-datasheet__list-item">{* style="width:{100/$step}%" *}
                                                    <div class="kapcsolodoTermekInner">
                                                        <a href="{$_hasonlo.link}">
                                                            <div class="flags">
                                                                {if (isset($_hasonlo.uj) && $_hasonlo.uj)}
                                                                    <div class="flag new-product">{t('Új')}</div>
                                                                {/if}

                                                                {if (isset($_hasonlo.akcios) && $_hasonlo.akcios)}
                                                                    <div class="flag sale-product">{t('Akciós')}</div>
                                                                {/if}
                                                                
                                                                {if (isset($_hasonlo.kiemelt) && $_hasonlo.kiemelt)}
                                                                    <div class="flag featured">{t('Kiemelt')}</div>
                                                                {/if}
                                                                {* {if (isset($_hasonlo.top10) && $_hasonlo.top10)}
                                                                    <div class="flag sale-product">{t('Top 10')}</div>
                                                                {/if} *}
                                                            </div>
                                                            <div class="kapcsolodoImageContainer product-datasheet__list-item-image">
                                                                <img src="{$imagepath}{$_hasonlo.kozepeskepurl}" title="{$_hasonlo.caption}" alt="{$_hasonlo.caption}">
                                                            </div>
                                                            <div class="product-datasheet__list-item-caption">{$_hasonlo.caption|lower|capitalize}</div>
                                                            <div class="product-datasheet__list-item-sku">{$_hasonlo.cikkszam}</div>
                                                            {if ((isset($_hasonlo.eredetibrutto) && $_hasonlo.eredetibrutto>0))}
                                                                <div class="termekprice" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                                                    <span class="akciosarszoveg"><span class="akciosar">{number_format($_hasonlo.eredetibrutto,0,',',' ')} {$_hasonlo.valutanemnev}</span></span>
                                                                </div>
                                                            {/if}
                                                            <h5>
                                                                <span>{number_format($_hasonlo.brutto,0,',',' ')} {$valutanemnev}</span>
                                                            </h5>
                                                            <a href="{$_hasonlo.link}" class="button bordered okbtn">{t('Részletek')}</a>
                                                        </a>
                                                    </div>
                                                </div>
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
                        {* <div id="termekTabbable" class="tabbable">
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
                        </div> *}
                    </div>
                </div>
            </div>
        </div>

        {if (count($hozzavasarolttermekek)>0)}
        <div class="row product-datasheet__recommended-products flex-col">
            <div class="col">
                <h2 class="main">{t('Ehhez a termékhez vásárolták még')}</h2>
            </div>
            <div  class=" col product-datasheet__recommended-products-list">
            {* royalSlider contentSlider rsDefaultInv termekSlider *}
            {* id="hozzavasarolttermekslider" *}
                {$lntcnt=count($hozzavasarolttermekek)}
                {$step=3}
                {for $i=0 to $lntcnt-1 step $step}
                    {* <div> *}
                    {for $j=0 to $step-1}
                        {if ($i+$j<$lntcnt)}
                        {$_termek=$hozzavasarolttermekek[$i+$j]}
                        <div class="textaligncenter pull-left product-datasheet__list-item">
                            {* style="width:{100/$step}%" *}
                            
                            <div class="termekSliderTermekInner">
                                <a href="/product/{$_termek.slug}">
                                    <div class="flags">
                                        {if (isset($_termek.uj) && $_termek.uj)}
                                            <div class="flag new-product">{t('Új')}</div>
                                        {/if}

                                        {if (isset($_termek.akcios) && $_termek.akcios)}
                                            <div class="flag sale-product">{t('Akciós')}</div>
                                        {/if}
                                        
                                        {if (isset($_termek.kiemelt) && $_termek.kiemelt)}
                                            <div class="flag featured">{t('Kiemelt')}</div>
                                        {/if}
                                        {* {if (isset($_termek.top10) && $_termek.top10)}
                                            <div class="flag sale-product">{t('Top 10')}</div>
                                        {/if} *}
                                    </div>
                                    <div class="termekSliderImageContainer  product-datasheet__list-item-image">
                                        <img src="{$imagepath}{$_termek.kozepeskepurl}" title="{$_termek.caption}" alt="{$_termek.caption}">
                                    </div>
                                    <div class="product-datasheet__list-item-caption">{$_termek.caption|lower|capitalize}</div>
                                    <div class="product-datasheet__list-item-sku">{$_termek.cikkszam}</div>
                                    {if ((isset($_termek.eredetibrutto) && $_termek.eredetibrutto>0))}
                                        <div class="termekprice" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                            <span class="akciosarszoveg"><span class="akciosar">{number_format($_termek.eredetibrutto,0,',',' ')} {$_termek.valutanemnev}</span></span>
                                        </div>
                                    {/if}
                                    <h5 class="main"><span>{number_format($_termek.brutto,0,',',' ')} {$valutanemnev}</span></h5>
                                    <a href="{$_termek.link}" class="button bordered okbtn">{t('Részletek')}</a>
                                </a>
                            </div>
                        </div>
                        {/if}
                    {/for}
                    {* </div> *}
                {/for}
            </div>
        </div>
        {/if}
        <hr>
        <div class="row product-datasheet__popular-products flex-col">
            <div class="col">
                <h4 class="textaligncenter">{t('Legnépszerűbb termékeink')}</h4>
            </div>
            <div class="col product-datasheet__popular-products-list">
                {foreach $legnepszerubbtermekek as $_nepszeru}
                <div class="textaligncenter product-datasheet__list-item">
                    <div class="kapcsolodoTermekInner">
                        <a href="{$_nepszeru.link}">
                            <div class="flags">
                                {if (isset($_nepszeru.uj) && $_nepszeru.uj)}
                                    <div class="flag new-product">{t('Új')}</div>
                                {/if}

                                {if (isset($_nepszeru.akcios) && $_nepszeru.akcios)}
                                    <div class="flag sale-product">{t('Akciós')}</div>
                                {/if}
                                
                                {if (isset($_nepszeru.kiemelt) && $_nepszeru.kiemelt)}
                                    <div class="flag featured">{t('Kiemelt')}</div>
                                {/if}

                                {if (isset($_termek.ajanlott) && $_termek.ajanlott)}
                                    <div class="flag featured">{t('Ajánlott')}</div>
                                {/if}
                                {* {if (isset($_nepszeru.top10) && $_nepszeru.top10)}
                                    <div class="flag sale-product">{t('Top 10')}</div>
                                {/if} *}
                            </div>
                            <div class="kapcsolodoImageContainer  product-datasheet__list-item-image">
                                <img src="{$imagepath}{$_nepszeru.kozepeskepurl}" title="{$_nepszeru.caption}" alt="{$_nepszeru.caption}">
                            </div>
                            <div class="product-datasheet__list-item-caption">{$_nepszeru.caption|lower|capitalize}</div>
                            <div class="product-datasheet__list-item-sku">{$_nepszeru.cikkszam}</div>
                            {if ((isset($_nepszeru.eredetibrutto) && $_nepszeru.eredetibrutto>0))}
                                <div class="termekprice" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <span class="akciosarszoveg"><span class="akciosar">{number_format($_nepszeru.eredetibrutto,0,',',' ')} {$_nepszeru.valutanemnev}</span></span>
                                </div>
                            {/if}
                            <h5>
                                <span>{number_format($_nepszeru.brutto,0,',',' ')} {$valutanemnev}</span>
                            </h5>
                            <a href="{$_nepszeru.link}" class="button bordered okbtn">{t('Részletek')}</a>
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
