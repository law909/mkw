{extends "base.tpl"}

{block "precss"}
    <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2021/splide/splide-sea-green.min.css?v=1">
{/block}

{block "prescript"}
    <script src="/themes/main/mugenrace2021/splide/splide.min.js?v=1"></script>
    <script defer src="/js/alpine/cdn.min.js"></script>
{/block}
{block "script"}
    <script src="/js/main/mugenrace2021/termeklap.js?v=3"></script>
{/block}

{block "body"}
    <div class="tl-nav-spacer"></div>
    <article class="tl-container" x-data="{ imagepath: '{$imagepath}', termekid: {$termek.id} }">
        <div class="tl-termek-imageslider hide-on-mobile">
            <section class="tl-splide-desktop splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        {foreach $termek.kepek as $_k}
                            <li class="splide__slide">
                                <img
                                    src="{$imagepath}{$_k.kozepeskepurl}"
                                    alt="{$_k.leiras}"
                                    data-url="{$imagepath}{$_k.kepurl}"
                                    class="tl-termek-imageslide"
                                >
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </section>
        </div>
        <div class="tl-termek-innerbox" x-data="termeklap" x-init="getLists">
            <div class="tl-termek-nev hide-on-desktop">
                <span itemprop="name">{$termek.caption}</span>
            </div>
            <div class="tl-termek-cikkszam hide-on-desktop">
                <span>{$termek.cikkszam}</span>
            </div>
            <section class="tl-termek-fokep hide-on-mobile">
                <img
                    itemprop="image"
                    src="{$imagepath}{$termek.kepurl}"
                    title="{$termek.caption}"
                    alt="{$termek.caption}"
                    class="tl-termek-img"
                >
            </section>
            <section class="tl-splide-mobile splide hide-on-desktop">
                <div class="splide__track">
                    <ul class="splide__list">
                        {foreach $termek.kepek as $_k}
                            <li class="splide__slide">
                                <img
                                    src="{$imagepath}{$_k.kepurl}"
                                    alt="{$_k.leiras}"
                                    class="tl-termek-imageslide"
                                >
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </section>
            <section class="tl-termek-infobox">
                <div class="tl-termek-nev hide-on-mobile">
                    <span itemprop="name">{$termek.caption}</span>
                </div>
                <div class="tl-termek-cikkszam hide-on-mobile">
                    <span>{$termek.cikkszam}</span>
                </div>
                <div class="tl-termek-rovidleiras">
                    <span>{$termek.rovidleiras}</span>
                </div>
                <div class="tl-termek-ar" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="price">{number_format($termek.brutto,0,',',' ')} {$termek.valutanemnev}</span>
                </div>
                <div class="tl-color-select-container">
                    <div>{t('VÁLASSZ SZÍNT')}</div>
                    <div class="tl-color-select">
                        <template x-for="(color, index) in colors">
                            <a
                                href="#"
                                class="tl-color-selector"
                                :class="selectedColor === color ? 'selected' : ''"
                                @click.prevent="selectColor(color)"
                            >
                                <img
                                    :src="imagepath + color.kepurl"
                                    :alt="color.value"
                                    :title="color.value"
                                    class="tl-color-selector-img"
                                >
                            </a>
                        </template>
                    </div>
                </div>
                <div class="tl-size-select-container">
                    <div>{t('VÁLASSZ MÉRETET')}</div>
                    <div class="tl-size-select">
                        <template x-for="(size, index) in sizes">
                            <a
                                href="#"
                                class="tl-size-selector"
                                x-text="size.value"
                                :class="selectedSize === size ? 'selected' : ''"
                                @click.prevent="selectSize(size)"
                            ></a>
                        </template>
                    </div>
                </div>
                <button
                    class="tl-add-to-cart-button"
                    :disabled="!canAddToCart"
                    @click.prevent="addToCart"
                >{t("KOSÁRBA")}</button>
                <div class="tl-details">
                    <div>{t('RÉSZLETEK')}</div>
                    <div class="tl-details-text">{$termek.leiras}</div>
                </div>
                <div class="tl-termek-ertekeles"></div>
            </section>
        </div>
    </article>
{/block}