{extends "base.tpl"}

{block "precss"}
    <link type="text/css" rel="stylesheet" href="/themes/main/mugenrace2021/splide/splide-sea-green.min.css?v=1">
{/block}

{block "prescript"}
    <script src="/themes/main/mugenrace2021/splide/splide.min.js?v=1"></script>
{/block}
{block "script"}
    <script src="/js/main/mugenrace2021/termeklap.js?v=9"></script>
{/block}

{block "body"}
    <div class="tl-nav-spacer"></div>
    <div class="tl-nav-decor hide-on-desktop"></div>
    <article class="tl-container" x-data="{ imagepath: '{$imagepath}', termekid: {$termek.id} }">
        <div class="tl-termek-imageslider hide-on-mobile">
            <section class="tl-splide-desktop splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide">
                            <img
                                src="{$imagepath}{$termek.kepurl}"
                                alt="{$termek.caption}"
                                data-url="{$imagepath}{$termek.kepurl}"
                                class="tl-termek-imageslide"
                            >
                        </li>
                        {foreach $termek.kepek as $_k}
                            <li class="splide__slide">
                                <img
                                    src="{$imagepath}{$_k.kepurl}"
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
            <div class="tl-termek-rovidleiras hide-on-desktop">
                <span>{$termek.rovidleiras}</span>
            </div>
            <div class="tl-termek-ar hide-on-desktop" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <span itemprop="price">{number_format($termek.brutto,0,',',' ')} {$termek.valutanemnev}</span>
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
                <div class="tl-termek-cikkszam">
                    <span>{$termek.cikkszam}</span>
                </div>
                {if ($termek.ertekelesdb)}
                    <div class="tl-termek-ertekeles" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                        <div class="c-rating" data-rating-value="{$termek.ertekelesatlag}">
                            <button>1</button>
                            <button>2</button>
                            <button>3</button>
                            <button>4</button>
                            <button>5</button>
                        </div>
                        <span itemprop="reviewCount"> ({$termek.ertekelesdb})</span>
                    </div>
                {else}
                    <div class="tl-termek-ertekeles"></div>
                {/if}
                <div class="tl-termek-rovidleiras hide-on-mobile">
                    <span>{$termek.rovidleiras}</span>
                </div>
                <div class="tl-termek-ar hide-on-mobile" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="price">{number_format($termek.brutto,0,',',' ')} {$termek.valutanemnev}</span>
                </div>
                <div class="tl-color-select-container hide-on-mobile">
                    <div>{t('VÁLASSZ SZÍNT')}</div>
                    <div class="tl-color-select">
                        <template x-for="(color, index) in colors" :key="index">
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
                <div class="tl-color-input-container hide-on-desktop">
                    <select x-model="selectedColorIndex">
                        <option value="">{t('VÁLASSZ SZÍNT')}</option>
                        <template x-for="(color, index) in colors" :key="index">
                            <option :value="index" x-text="color.value"></option>
                        </template>
                    </select>
                </div>
                <div class="tl-size-select-container hide-on-mobile">
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
                <div class="tl-size-input-container hide-on-desktop">
                    <select x-model="selectedSizeIndex">
                        <option value="">{t('VÁLASSZ MÉRETET')}</option>
                        <template x-for="(size, index) in sizes" :key="index">
                            <option :value="index" x-text="size.value"></option>
                        </template>
                    </select>
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
            </section>
        </div>
    </article>
{/block}