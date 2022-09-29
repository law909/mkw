{extends "base.tpl"}

{block "body"}
    <div class="tl-nav-spacer"></div>
    <div class="tl-container">
        <div class="tl-termek-valtozatslider">
            {foreach $termek.kepek as $_k}
                <div><img src="{$imagepath}{$_k.kozepeskepurl}" alt="{$_k.leiras}"></div>
            {/foreach}
        </div>
        <div class="tl-termek-innerbox">
            <div class="tl-termek-fokep">
                <img
                    itemprop="image"
                    src="{$imagepath}{$termek.nagykepurl}"
                    title="{$termek.caption}"
                    alt="{$termek.caption}"
                    class="tl-termek-img"
                    onerror="this.src = '/themes/main/mugenrace2021/noprodimg.jpg';"
                >
            </div>
            <div class="tl-termek-infobox">
                <div class="tl-termek-nev">
                    <span itemprop="name">{$termek.caption}</span>
                </div>
                <div class="tl-termek-cikkszam">
                    <span>{$termek.cikkszam}</span>
                </div>
                <div class="tl-termek-rovidleiras">
                    <span>{$termek.rovidleiras}</span>
                </div>
                <div class="tl-termek-ar" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="price">{number_format($termek.brutto,0,',',' ')} {$termek.valutanemnev}</span>
                </div>
                <div class="tl-color-select">
                    <div>{t('VÁLASSZ SZÍNT')}</div>

                </div>
                <div class="tl-size-select">
                    <div>{t('VÁLASSZ MÉRETET')}</div>

                </div>
                <div class="tl-details">
                    <div>{t('RÉSZLETEK')}</div>
                    <div class="tl-details-text">{$termek.leiras}</div>
                </div>
                <div class="tl-termek-ertekeles"></div>
            </div>
        </div>
    </div>
{/block}