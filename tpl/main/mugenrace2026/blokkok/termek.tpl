<div class="carousel-item product-list-item spanmkw3 gtermek itemscope itemtype=" http://schema.org/Product">
<div class="gtermekinner">
    <div class="gtermekinnest product-list-item__inner">
        <div class="textaligncenter product-list-item__image-container">
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

                {if (isset($termek.ajanlott) && $termek.ajanlott)}
                    <div class="flag featured">{t('Ajánlott')}</div>
                {/if}

                {* {if (isset($termek.top10) && $termek.top10)}
                    <div class="flag sale-product">{t('Top 10')}</div>
                {/if} *}
            </div>
            <a href="/product/{$termek.slug}"><img class="product-list-item__image" itemprop="image" src="{$imagepath}{$termek.kepurl}"
                                                   title="{$termek.caption}" alt="{$termek.caption}"></a>
        </div>
        <div class="textaligncenter product-list-item__content product-list-item__title">
            <a itemprop="url" href="/product/{$termek.slug}"><span class="gtermekcaption"
                                                                   itemprop="name">{$termek.caption|lower|capitalize}</span></a>
        </div>
        <div class="textaligncenter product-list-item__content product-list-item__code">
            <a href="/product/{$termek.slug}">{$termek.cikkszam}</a>
        </div>
        <div class="textaligncenter product-list-item__content">
            {if ($termek.szallitasiido && (!$termek.nemkaphato))}
                <div class="textaligncenter"><span class="bold">{t('Szállítási idő')}: </span>{$termek.szallitasiido} {t('munkanap')}</div>
            {/if}
            {if false} {*if ($termek.szinek|default)*}
                <div class="js-valtozatbox product-list-item__variations-container">
                    <div class="pull-left gvaltozatcontainer product-list-item__variations">
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
            {/if}
        </div>
        <div class="flex-tb ">
            <div class="termekprice pull-left" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                {if ((isset($termek.eredetibrutto) && $termek.eredetibrutto>0))}
                    <span class="akciosarszoveg">{t('Eredeti ár')}:&nbsp;<span
                            class="akciosar">{number_format($termek.eredetibrutto,0,',',' ')} {$termek.valutanemnev}</span></span>
                {/if}
                {if ($termek.nemkaphato)}
                    <link itemprop="availability" href="http://schema.org/OutOfStock" content="Nem kapható">
                {else}
                    <link itemprop="availability" href="http://schema.org/InStock" content="Kapható">
                {/if}
                <span class="product-list-item__price" itemprop="price">{number_format($termek.brutto,0,',',' ')}
                    {$termek.valutanemnev}
                        </span>
            </div>
            <div class="pull-right">
                {if ($termek.nemkaphato)}
                    <a href="#" rel="nofollow" class="js-termekertesitobtn btn graybtn pull-right" data-termek="{$termek.id}">
                        {t('Elfogyott')}
                    </a>
                {else}
                    {if ($termek.brutto > 0)}
                        <a href="/kosar/add?id={$termek.id}" rel="nofollow"
                           class="js-kosarbaszinvaltozat button bordered small cartbtn pull-right" data-termek="{$termek.id}">
                            {t('Kosárba')}
                        </a>
                    {/if}
                {/if}
            </div>
        </div>
    </div>
</div>
</div>