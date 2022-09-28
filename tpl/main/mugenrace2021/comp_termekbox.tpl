<div class="termek-box" itemscope itemtype="http://schema.org/Product">
    <div class="termek-innerbox">
        <div class="termek-fokep">
            <a href="/termek/{$_termek.slug}">
                <img
                    itemprop="image"
                    src="{$imagepath}{$_termek.nagykepurl}"
                    title="{$_termek.caption}"
                    alt="{$_termek.caption}"
                    class="termek-img"
                    onerror="this.src = '/themes/main/mugenrace2021/noprodimg.jpg';"
                ></a>
        </div>
        {if ($_termek.kepek|default)}
            <div class="termek-valtozatslider">
                {foreach $_termek.mindenvaltozat as $_v}
                    <span>{$_v}</span>
                {/foreach}
            </div>
        {/if}
        <div class="termek-infobox">
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
                <div class="termek-ertekeles"></div>
                <div class="termek-ar" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="price">{number_format($_termek.brutto,0,',',' ')} {$_termek.valutanemnev}</span>
                </div>
            </div>
        </div>
    </div>
</div>
