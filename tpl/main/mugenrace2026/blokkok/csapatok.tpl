{if isset($blokk.lathato) && $blokk.lathato}
    <section id="{$blokk.id}"
             class="featured-collection-slider featured-collection-slider__dark carousel-section {$blokk.cssclass}" {if (isset($blokk.cssstyle) && $blokk.cssstyle)} style="{$blokk.cssstyle}" {/if}>
        <div class="container section-header small row flex-cb">
            <div class="col flex-lc flex-col ta-l">
                <h2>{$blokk.cim}</h2>
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
                            <img src="{$_csapat.kepurl400}" alt="{$_csapat.kepleiras}" class="teams__item-image">
                            <img src="{$_csapat.logourllarge}" alt="{$_csapat.logoleiras}" class="teams__item-logo">
                            <h3 class="teams__item-title"><a href="/teams/{$_csapat.slug}">{$_csapat.nev}</a></h3>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </section>
{/if}