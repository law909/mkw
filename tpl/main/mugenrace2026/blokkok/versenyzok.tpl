{if isset($blokk.lathato) && $blokk.lathato}
<section  id="{$blokk.id}" class="featured-collection-slider featured-collection-slider__dark carousel-section {$blokk.cssclass}" {if (isset($blokk.cssstyle) && $blokk.cssstyle)} style="{$blokk.cssstyle}" {/if}>
    <div class="container section-header small row flex-cb">
        <div class="col flex-lc flex-col ta-l">
            <h2>{$blokk.nev}</h2>
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
{/if}