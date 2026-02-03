{extends "base.tpl"}

{block "kozep"}
    <div class="teams-datasheet">
        <article itemtype="http://schema.org/Article" itemscope="">
            <div class="row">
                <div class="col ">
                    <div class="teams-datasheet__image-wrapper">
                        <img src="{$imagepath}{$csapat.kepurl2000}" alt="{$csapat.kepleiras}" class="teams-datasheet__image">
                    </div>
                    <div class="teams-datasheet__meta">
                        {if ($csapat.logourlmini)}
                            <img src="{$imagepath}{$csapat.logourlmini}" alt="" class="teams-datasheet__logo">
                        {/if}
                        <h2 class="teams-datasheet__title">{$csapat.nev}</h2>
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
                                <img src="{$imagepath}{$_versenyzo.kepurl400}" alt="" class="sponsored-riders__item-image">
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
    <div id="lightbox" class="lightbox hidden">
        <div class="lightbox-backdrop"></div>
        <button class="lightbox-nav lightbox-prev">‹</button>
        <button class="lightbox-nav lightbox-next">›</button>
        <img id="lightboxImage" class="lightbox-image" src="" alt="">
        <div class="lightbox-close">×</div>
    </div>
    {* <div>
        <h3>{$csapat.nev}</h3>
        {$csapat.id}
        {$csapat.nev}
        {$csapat.slug}
        {$csapat.logourl}
        {$csapat.logourlsmall}
        {$csapat.logourlmini}
        {$csapat.logoleiras}
        {$csapat.leiras}
        {$csapat.kepurl}
        {$csapat.kepurlsmall}
        {$csapat.kepurlmini}
        {$csapat.kepleiras}
        {foreach $csapat.versenyzok as $_versenyzo}
            <p>{$_versenyzo.nev}</p>
            {$_versenyzo.id}
            {$_versenyzo.nev}
            {$_versenyzo.slug}
            {$_versenyzo.versenysorozat}
            {$_versenyzo.rovidleiras}
            {$_versenyzo.leiras}
            {$_versenyzo.kepurl}
            {$_versenyzo.kepurlsmall}
            {$_versenyzo.kepurlmini}
            {$_versenyzo.kepleiras}
            {$_versenyzo.kepurl1}
            {$_versenyzo.kepurl1small}
            {$_versenyzo.kepleiras1}
            {$_versenyzo.kepurl2}
            {$_versenyzo.kepurl2small}
            {$_versenyzo.kepleiras2}
            {$_versenyzo.kepurl3}
            {$_versenyzo.kepurl3small}
            {$_versenyzo.kepleiras3}
            {$_versenyzo.csapatid}
            {$_versenyzo.csapatnev}

        {/foreach}
    </div> *}
{/block}