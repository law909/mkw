{extends "base.tpl"}

{block "body"}
    <div class="tl-nav-spacer"></div>
    <div class="tl-container">
        <div class="tl-termek-imageslider">
            {foreach $termek.kepek as $_k}
                <div><img src="{$imagepath}{$_k.kozepeskepurl}" alt="{$_k.leiras}"></div>
            {/foreach}
        </div>
        <div class="tl-termek-innerbox">
            <div class="tl-termek-fokep">
                <img
                    itemprop="image"
                    src="{$imagepath}{$termek.kepurl}"
                    title="{$termek.caption}"
                    alt="{$termek.caption}"
                    class="tl-termek-img"
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
                <div class="tl-color-select-container">
                    <div>{t('VÁLASSZ SZÍNT')}</div>
                    <div class="tl-color-select">
                        {foreach $termek.szinek as $szin}
                            <a href="#" class="tl-color-selector"><img src="{$imagepath}{$szin[1]}" alt="{$szin[0]}" class="tl-color-selector-img"></a>
                        {/foreach}
                    </div>
                </div>
                <div class="tl-size-select-container">
                    <div>{t('VÁLASSZ MÉRETET')}</div>
                    <div class="tl-size-select">
                        {foreach $termek.meretek as $meret}
                            <a href="#" class="tl-size-selector">{$meret}</a>
                        {/foreach}
                    </div>
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