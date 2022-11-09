<article class="termek-box" itemscope itemtype="http://schema.org/Product">
    <div class="termek-innerbox">
        <section class="termek-fokep">
            <a href="/termek/{$_termek.slug}">
                <img
                    itemprop="image"
                    src="{$imagepath}{$_termek.nagykepurl}"
                    title="{$_termek.caption}"
                    alt="{$_termek.caption}"
                    class="termek-img"
                    onerror="this.src = '/themes/main/mugenrace2021/noprodimg.jpg';"
                ></a>
        </section>
        {if ($_termek.kepek|default)}
            <div class="termek-valtozatslider">
                {foreach $_termek.mindenvaltozat as $_v}
                    <span>{$_v}</span>
                {/foreach}
            </div>
        {/if}
        <section class="termek-infobox">
            <div class="termek-nev">
                <a href="/termek/{$_termek.slug}" itemprop="url"><span itemprop="name">{$_termek.caption}</span></a>
            </div>
            <div class="termek-cikkszam">
                <a href="/termek/{$_termek.slug}">{$_termek.cikkszam}</a>
            </div>
            <div class="termek-rovidleiras">
                <span>{$_termek.rovidleiras}</span>
            </div>
            <div class="termek-ar-row">
                {if ($_termek.ertekelesdb)}
                    <div class="termek-ertekeles" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                        <div class="c-rating" data-rating-value="{$_termek.ertekelesatlag}">
                            <button>1</button>
                            <button>2</button>
                            <button>3</button>
                            <button>4</button>
                            <button>5</button>
                        </div>
                        <span itemprop="reviewCount"> ({$_termek.ertekelesdb})</span>
                    </div>
                {else}
                    <div class="termek-ertekeles"></div>
                {/if}
                <div class="termek-ar" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="price">{number_format($_termek.brutto,0,',',' ')} {$_termek.valutanemnev}</span>
                </div>
            </div>
        </section>
    </div>
</article>
