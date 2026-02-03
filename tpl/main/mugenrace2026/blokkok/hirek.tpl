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

        <div class="carousel-container">
            <div class="carousel-wrapper news-list__items">
                {foreach $hirek as $_child}
                    <div class="carousel-item kat news-list__item" data-href="/news/{$_child.slug}">
                        <div class="kattext news-list__item-content">
                            <img src="{$imagepath}{$_child.kepurllarge}" alt="{$_child.cim}" class="news-list__item-image">
                            <div class="hiralairas news-list__item-date">{$_child.datum}</div>
                            <div class="kattitle news-list__item-title"><a href="/news/{$_child.slug}">{$_child.cim}</a></div>
                            <div class="katcopy news-list__item-lead">{$_child.lead}</div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </section>
{/if}