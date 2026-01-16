{extends "base.tpl"}

{block "meta"}
    <meta property="og:title" content="{$globaltitle}">
    <meta property="og:url" content="http://www.mugenrace.com">
    <meta property="og:image" content="{$logo}">
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="{$seodescription}">
{/block}

{block "body"}
    <div id="fb-root"></div>
    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
{/block}

{block "kozep"}
    <div class="container-full whitebg">
{* <pre>
{var_dump($csapatlista)}
<pre>
</pre>
{var_dump($versenyzolista)}
</pre> *}
        <div id="MainContent">
            <section class="hero-section video">
                {* <img src="/themes/main/mugenrace2026/img/pages/mugenrace-home-1.jpg" alt="Hero"> *}
                <video autoplay muted loop class="hero-video">
                    <source src="https://phpstack-333569-6090507.cloudwaysapps.com/mugenrace-main-video.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>

                <div class="hero-content hero-content__inverse flex-col flex-cc">
                    <h1>{t('Új kollekció 2025')}</h1>
                    <p>{t('Ismerd meg legújabb termékeinket')}</p>
                    <a href="#" class="button bordered inverse">{t('Részletek')}</a>
                </div>
            </section>

            {if ( $legujabbtermekek && count($legujabbtermekek)>0 )}
            <section class="featured-collection-slider featured-collection-slider__dark carousel-section">
                <div class="container section-header small row flex-cb">
                    <div class="col flex-lc flex-col ta-l">
                        <h2>{t('Akciós termékeink')}</h2>
                        {* <p>{t('Válogatás a legújabb kollekciónkból')}</p> *}
                        <p></p>
                    </div>
                    <div class="col flex-cr">
                        <div class="carousel-controls">
                            <button class="carousel-btn carousel-prev" aria-label="Preview">‹</button>
                            <button class="carousel-btn carousel-next" aria-label="Next">›</button>
                        </div>
                    </div>
                </div>

                <div class="carousel-container">
                    <div class="carousel-wrapper product-list">
                        {$lntcnt=count($legujabbtermekek)}
                        {$step=3}
                        {for $i=0 to $lntcnt-1 step $step}
                        {for $j=0 to $step-1}
                        {if ($i+$j<$lntcnt)}
                        {$_termek=$legujabbtermekek[$i+$j]}

                        <div class="carousel-item product-list-item spanmkw3 gtermek itemscope itemtype=" http:
                        //schema.org/Product">
                        <div class="gtermekinner">
                            <div class="gtermekinnest product-list-item__inner">
                                <div class="textaligncenter product-list-item__image-container">
                                    <div class="flags">
                                        {if (isset($_termek.ujtermek) && $_termek.ujtermek)}
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
                                    <a href="/product/{$_termek.slug}"><img class="product-list-item__image" itemprop="image" src="{$imagepath}{$_termek.kepurl}"
                                        title="{$_termek.caption}" alt="{$_termek.caption}"></a>
                                </div>
                                <div class="textaligncenter product-list-item__content product-list-item__title">
                                    <a itemprop="url" href="/product/{$_termek.slug}"><span class="gtermekcaption"
                                                                                           itemprop="name">{$_termek.caption|lower|capitalize}</span></a>
                                </div>
                                <div class="textaligncenter product-list-item__content product-list-item__code">
                                    <a href="/product/{$_termek.slug}">{$_termek.cikkszam}</a>
                                </div>
                                <div class="textaligncenter product-list-item__content">
                                    {if ($_termek.szallitasiido && (!$_termek.nemkaphato))}
                                        <div class="textaligncenter"><span class="bold">Szállítási idő: </span>{$_termek.szallitasiido} munkanap</div>
                                    {/if}
                                    {if ($_termek.szinek|default)}
                                        <div class="js-valtozatbox product-list-item__variations-container">
                                            {* {$_termek.szinek|@count} {t('szín')} *}
                                            <div class="pull-left gvaltozatcontainer product-list-item__variations">
                                                <div class="pull-left gvaltozatnev termekvaltozat">{t('Szín')}:</div>
                                                <div class="pull-left gvaltozatselect">

                                                    <div class="option-selector color-selector" data-termek="{$_termek.id}">
                                                        {foreach $_termek.szinek as $_v}
                                                            <div class="select-option {$_v|lower|replace:'/':'-'}" data-value="{$_v}" title="{$_v}"></div>
                                                        {/foreach}
                                                    </div>

                                                    <select class="js-szinvaltozatedit custom-select valtozatselect" data-termek="{$_termek.id}">
                                                        <option value="">{t('Válasszon')}</option>
                                                        {foreach $_termek.szinek as $_v}
                                                            <option value="{$_v}">{$_v}</option>
                                                        {/foreach}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                </div>
                                <div class="flex-tb ">
                                    <div class="termekprice pull-left" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        {if ($_termek.akcios)}
                                            <span class="akciosarszoveg">Eredeti ár: <span
                                                    class="akciosar">{number_format($_termek.eredetibruttohuf,0,',',' ')} {$_termek.valutanemnev}</span></span>
                                        {/if}
                                        {if ($_termek.nemkaphato)}
                                            <link itemprop="availability" href="http://schema.org/OutOfStock" content="Nem kapható">
                                        {else}
                                            <link itemprop="availability" href="http://schema.org/InStock" content="Kapható">
                                        {/if}
                                        <span class="product-list-item__price" itemprop="price">{number_format($_termek.bruttohuf,0,',',' ')}
                                            {$_termek.valutanemnev}
                                            </span>
                                    </div>
                                    <div class="pull-right">
                                        {if ($_termek.nemkaphato)}
                                            <a href="#" rel="nofollow" class="js-termekertesitobtn btn graybtn pull-right" data-termek="{$_termek.id}">
                                                {t('Elfogyott')}
                                            </a>
                                        {else}
                                            {if ($_termek.bruttohuf > 0)}
                                                <a href="/kosar/add?id={$_termek.id}" rel="nofollow"
                                                   class="js-kosarbaszinvaltozat button bordered small cartbtn pull-right" data-termek="{$_termek.id}">
                                                    {t('Kosárba')}
                                                </a>
                                            {/if}
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {/if}
                    {/for}
                    {/for}

                </div>
                {* <div class="carousel-dots" id="carouselDots"></div> *}
        </div>
        </section>
        {/if}

        <section class="content-grid">
            <div class="grid-item">
                <img src="/themes/main/mugenrace2026/img/pages/mugenrace-home-2.jpg" alt="Kategória 1">
                <div class="grid-content inverse flex-cc flex-col">
                    <h3>{t('Szponzorált versenyzők')}</h3>
                    <p>lorem ipsum dolor sit amet </p>
                    <a href="/riders" class="button bordered inverse">{t('Tovább')}</a>
                </div>
            </div>
            <div class="grid-item">
                <img src="/themes/main/mugenrace2026/img/pages/mugenrace-home-3.jpg" alt="Kategória 2">
                <div class="grid-content inverse flex-cc flex-col">
                    <h3>{t('Csapatok')}</h3>
                    <p>lorem ipsum dolor sit amet </p>
                    <a href="/teams" class="button bordered inverse">{t('Tovább')}</a>
                </div>
            </div>
        </section>

        <section class="featured-collection-slider featured-collection-slider__dark carousel-section">
            <div class="container section-header small row flex-cb">
                <div class="col flex-lc flex-col ta-l">
                    <h2>{t('Csapatok')}</h2>
                    <p></p>
                </div>
                <div class="col flex-cr">
                    <div class="carousel-controls">
                        <button class="carousel-btn carousel-prev" aria-label="Preview">‹</button>
                        <button class="carousel-btn carousel-next" aria-label="Next">›</button>
                    </div>
                </div>
            </div>

            <div class="carousel-container teams__list">
                <div class="carousel-wrapper teams__items ">
                    {foreach $csapatlista as $_csapat}
                        <div class="kat carousel-item teams__item" data-href="/teams/{$_csapat.slug}">
                            <div class="kattext teams__item-content">
                                {* <img src="{$imagepath}{$_child.kepurl}" alt="{$_child.cim}" class="teams__item-image"> *}
                                <img src="{$_csapat.kepurl}" alt="{$_csapat.kepleiras}" class="teams__item-image">
                                <img src="{$_csapat.logourl}" alt="{$_csapat.logoleiras}" class="teams__item-logo">
                                <h3 class="teams__item-title"><a href="/teams/{$_csapat.slug}">{$_csapat.nev}</a></h3>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </section>

        <section class="full-banner left inverse">
            <img src="/themes/main/mugenrace2026/img/pages/mugenrace-home-5.jpg" alt="Banner">
            <div class="banner-content flex-cc flex-col">
                <h2>{t('Új Kollekció')}</h2>
                <p>{t('Fedezd fel legújabb termékeinket')}</p>
                <a href="#" class="button bordered inverse">{t('Tudj Meg Többet')}</a>
            </div>
        </section>


        <section class="featured-collection-slider featured-collection-slider__dark carousel-section">
            <div class="container section-header small row flex-cb">
                <div class="col flex-lc flex-col ta-l">
                    <h2>{t('Szponzorált versenyzők')}</h2>
                    <p></p>
                </div>
                <div class="col flex-cr">
                    <div class="carousel-controls">
                        <button class="carousel-btn carousel-prev" aria-label="Preview">‹</button>
                        <button class="carousel-btn carousel-next" aria-label="Next">›</button>
                    </div>
                </div>
            </div>

            <div class="carousel-container sponsored-riders__list">
                <div class="carousel-wrapper sponsored-riders__items ">
                    {foreach $versenyzolista as $_versenyzo}
                        <div class="kat carousel-item sponsored-riders__item" data-href="/riders/{$_versenyzo.slug}">
                            <div class="kattext sponsored-riders__item-content">
                                {* <img src="{$imagepath}{$_child.kepurl}" alt="{$_child.cim}" class="sponsored-riders__item-image"> *}
                                <img src="{$_versenyzo.kepurl}" alt="{$_versenyzo.nev}" class="sponsored-riders__item-image">
                                <div class="sponsored-riders__item-category">{$_versenyzo.versenysorozat}</div>
                                <div class="sponsored-riders__item-title"><a href="/riders/{$_versenyzo.slug}">{$_versenyzo.nev}</a></div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </section>

        <section class="full-banner right inverse">
            <img src="/themes/main/mugenrace2026/img/pages/mugenrace-home-4.jpg" alt="Banner">
            <div class="banner-content flex-cc flex-col">
                <h2>{t('Új Kollekció')}</h2>
                <p>{t('Fedezd fel legújabb termékeinket')}</p>
                <a href="#" class="button bordered inverse">{t('Tudj Meg Többet')}</a>
            </div>
        </section>


        <section class="featured-collection-slider featured-collection-slider__dark carousel-section">
            <div class="container section-header small row flex-cb">
                <div class="col flex-lc flex-col ta-l">
                    <h2>{t('Hírek')}</h2>
                    {* <p>{t('Válogatás a legújabb kollekciónkból')}</p> *}
                    <p></p>
                </div>
                <div class="col flex-cr">
                    <div class="carousel-controls">
                        <button class="carousel-btn carousel-prev" aria-label="Preview">‹</button>
                        <button class="carousel-btn carousel-next" aria-label="Next">›</button>
                    </div>
                </div>
            </div>

            <div class="carousel-container">
                <div class="carousel-wrapper news-list__items">
                    {foreach $hirek as $_child}
                        <div class="carousel-item kat news-list__item" data-href="/news/{$_child.slug}">
                            <div class="kattext news-list__item-content">
                                <img src="{$_child.kepurl}" alt="{$_child.cim}" class="news-list__item-image">
                                {* <img src="{$imagepath}{$_child.kepurl}" alt="{$_child.cim}" class="news-list__item-image"> *}
                                {* <img src="https://picsum.photos/500/400" alt="{$_child.cim}" class="news-list__item-image"> *}
                                <div class="hiralairas news-list__item-date">{$_child.datum}</div>
                                <div class="kattitle news-list__item-title"><a href="/news/{$_child.slug}">{$_child.cim}</a></div>
                                <div class="katcopy news-list__item-lead">{$_child.lead}</div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </section>
    </div>
{/block}
