{extends "base.tpl"}

{block "kozep"}
    {$csapatjsonld|default}
    <div class="container page-header">
        <div class="row">
            <div class="col">
                {include 'morzsa.tpl'}
            </div>
        </div>
    </div>
    <div class="teams-datasheet">
        <article class="teams-datasheet__article">
            <div class="row">
                <div class="col ">
                    <div class="teams-datasheet__image-wrapper">
                        <img src="{$imagepath}{$csapat.kepurl2000}" alt="{if ($csapat.kepleiras)}{$csapat.kepleiras|escape}{else}{$csapat.nev|escape}{/if}"
                             class="teams-datasheet__image" fetchpriority="high">
                    </div>
                    <div class="teams-datasheet__meta">
                        {if ($csapat.logourlmini)}
                            <img src="{$imagepath}{$csapat.logourlmini}" alt="{$csapat.nev|escape}" class="teams-datasheet__logo">
                        {/if}
                        <h1 class="teams-datasheet__title">{$csapat.nev}</h1>
                        <div class="teams-datasheet__lead">
                            {$csapat.leiras}
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
    <div class="container sponsored-riders__list">
        <div class="row">
            <div class="col">
                <h2 class="sponsored-riders__list-title">{t('Képgaléria')}</h2>
                <div class="divider"></div>
            </div>
        </div>
        <div class="row">
            <div class="col sponsored-riders__items gallery-grid">
                {foreach $csapat.kepek as $_kep}
                    <div class=" sponsored-riders__item gallery">
                        <div class="sponsored-riders__item-content"><img src="{$imagepath}{$_kep.urllarge}" data-image-large="{$imagepath}{$_kep.url2000}"
                                                                         alt="{$_kep.leiras}"
                                                                         class=" gallery-image sponsored-riders__item-image"></div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
    {if ($csapat.versenyzok|@count gt 0)}
        <div class="container sponsored-riders__list">
            <div class="row">
                <div class="col">
                    <h2 class="sponsored-riders__list-title">{t('Szponzorált versenyzők')}</h2>
                    <div class="divider"></div>
                </div>
            </div>
            <div class="row">
                <div class="col sponsored-riders__items">
                    {foreach $csapat.versenyzok as $_versenyzo}
                        <div class="kat sponsored-riders__item" data-href="/riders/{$_versenyzo.slug}/">
                            <div class="kattext sponsored-riders__item-content">
                                <img src="{$imagepath}{$_versenyzo.kepurl400}" alt="{$_versenyzo.nev|escape}" class="sponsored-riders__item-image">
                                {if ($_versenyzo.versenysorozat)}
                                    <div class="sponsored-riders__item-category">{$_versenyzo.versenysorozat}</div>
                                {/if}
                                <div class="sponsored-riders__item-title"><a href="/riders/{$_versenyzo.slug}/">{$_versenyzo.nev}</a></div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
    {/if}
    {if ($menu1[0].children[0].slug|default)}
        <div class="container teams-datasheet__cta textaligncenter">
            <a href="/categories/{$menu1[0].children[0].slug}" class="button bordered">{t('Nézd meg a Mugen Race felszereléseket')}</a>
        </div>
    {/if}
    <div id="lightbox" class="lightbox hidden">
        <div class="lightbox-backdrop"></div>
        <button class="lightbox-nav lightbox-prev">‹</button>
        <button class="lightbox-nav lightbox-next">›</button>
        <img id="lightboxImage" class="lightbox-image" alt="">
        <div class="lightbox-close">×</div>
    </div>
{/block}