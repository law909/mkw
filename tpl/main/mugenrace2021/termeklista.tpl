{extends "base.tpl"}

{block "body"}
    <div class="termek-filter-m">
        <div class="termek-filter-inner-m">
            <div class="category-filter-m">
                <div>CATEGORY</div>
                {foreach $categoryfilter as $cat}
                    <div><a href="/termekfa/{$cat.slug}">{$cat.caption}</a></div>
                {/foreach}
            </div>
            <div>FILTERS</div>
        </div>
    </div>
    <div class="nav-spacer"></div>
    <div class="termekfa-kep" id="termekfa-kep">
        {if ($sketchfabmodelid)}
            <iframe title="Mugen overal 3D model"
                    class="c-viewer__iframe"
                    src="https://sketchfab.com/models/{$sketchfabmodelid}/embed?autostart=1&amp;autospin=1&amp;internal=1&amp;tracking=0&amp;ui_infos=0&amp;ui_snapshots=1&amp;ui_controls=0&amp;ui_stop=0&amp;ui_theme=dark&amp;ui_watermark=0"
                    id="object-frame"
                    allow="autoplay; fullscreen; xr-spatial-tracking"
                    xr-spatial-tracking="true"
                    execution-while-out-of-viewport="true"
                    execution-while-not-rendered="true"
                    web-share="false">
            </iframe>
        {else}
            <img src="{$imagepath}{$kepurl}"/>
        {/if}
    </div>
    <div class="termek-screen">
        <div class="termek-filter-d">
            <div class="termek-filter-inner-d">
                <div class="category-filter-d">
                    <div>CATEGORY</div>
                    {foreach $categoryfilter as $cat}
                        <div><a href="/termekfa/{$cat.slug}">{$cat.caption}</a></div>
                    {/foreach}
                </div>
            </div>
        </div>
        <div class="termek-grid">
            {if ($lapozo.elemcount>0)}
            {$termekcnt=count($termekek)}
            {for $i=0 to $termekcnt-1}
                {$_termek=$termekek[$i]}
                {if ($_termek)}
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
                                    {foreach $_termek.valtozatok as $_v}
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
                {/if}
            {/for}
            {else}
                {t('Nincs ilyen termék')}
            {/if}
        </div>
    </div>
{/block}
{block "endscript"}
    <script>
        let isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
        if (isMobile) {
            document.getElementById('termekfa-kep').remove();
        }
    </script>
{/block}
