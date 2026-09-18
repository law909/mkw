<div class="carousel-item product-list-item spanmkw3 gtermek">
<div class="gtermekinner">
    <div class="gtermekinnest product-list-item__inner">
        <div class="textaligncenter product-list-item__image-container">
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

                {if (isset($termek.ajanlott) && $termek.ajanlott)}
                    <div class="flag featured">{t('Ajánlott')}</div>
                {/if}

                {* {if (isset($termek.top10) && $termek.top10)}
                    <div class="flag sale-product">{t('Top 10')}</div>
                {/if} *}
            </div>
            {* a kártyakép a 250 px-es származék: a _400 és a _2000 csak az újabb feltöltésekhez
               készült el, a régebbi képeknél 404 lenne *}
            {if (is_array($termek.szinkepek))}
                {$_kartyakep = $termek.szinkepek[0].kepurlmedium}{$_kartyakepnagy = $termek.szinkepek[0].kepurllarge}
            {else}
                {$_kartyakep = $termek.kozepeskepurl}{$_kartyakepnagy = $termek.kepurl}
            {/if}
            <a href="/product/{$termek.slug}/{$termek.szin_id}"><img class="product-list-item__image"
                                                                     src="{$imagepath}{$_kartyakep}"
                                                                     srcset="{$imagepath}{$_kartyakep} 250w, {$imagepath}{$_kartyakepnagy} 1000w"
                                                                     sizes="(max-width: 768px) 45vw, 320px"
                                                                     title="{$termek.caption}" alt="{$termek.caption}"></a>
        </div>
        <div class="textaligncenter product-list-item__content product-list-item__title">
            <a href="/product/{$termek.slug}/{$termek.szin_id}"><span class="gtermekcaption">{$termek.caption|lower|capitalize}{if ($termek.szin)} ({$termek.szin}){/if}</span></a>
        </div>
        <div class="textaligncenter product-list-item__content product-list-item__code">
            <a href="/product/{$termek.slug}/{$termek.szin_id}">{$termek.cikkszam}</a>
        </div>
        <div class="textaligncenter product-list-item__content">
            {if ( isset($termek.szallitasiido) && $termek.szallitasiido && isset($termek.nemkaphato) && !$termek.nemkaphato)}
                <div class="textaligncenter"><span class="bold">{t('Szállítási idő')}: </span>{$termek.szallitasiido} {t('munkanap')}</div>
            {/if}
            {if ($hidecart) != 1 && ($termek.szinek|default)}
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
        <div class="flex-tb flex-col product-list-item__price-block">
            {* a kártyán nincs Product/Offer jelölés: a lista ItemList-et ad, az árat a terméklap mondja meg *}
            <div class="termekprice pull-left">
                {if ((isset($termek.eredetibrutto) && $termek.eredetibrutto>0))}
                    <span class="akciosarszoveg">{t('Eredeti ár')}:&nbsp;<span
                            class="akciosar">{number_format($termek.eredetibrutto,0,',',' ')} {$termek.valutanemnev}</span></span>
                {/if}
                <span class="product-list-item__price">{number_format($termek.brutto,0,',',' ')}
                    {$termek.valutanemnev}
                        </span>
            </div>
            <div class="product-list-item__button-block">
                {if (!$hidecart)}
                    {if (isset($termek.nemkaphato) && $termek.nemkaphato)}
                        <a href="#" rel="nofollow" class="js-termekertesitobtn btn graybtn pull-right" data-termek="{$termek.id}">
                            {t('Elfogyott')}
                        </a>
                    {else}
                        {if ($termek.brutto > 0)}
                            <button type="button" data-url="/kosar/add?id={$termek.id}"
                                    class="js-kosarbaszinvaltozat button primary full-width cartbtn pull-right" data-termek="{$termek.id}">
                                {t('Kosárba')}
                            </button>
                        {/if}
                    {/if}
                {/if}
            </div>

        </div>
        {if ($detailsbutton)}
            <a href="{$termek.link}" class="button bordered okbtn">{t('Részletek')}</a>
        {/if}
    </div>
</div>
</div>