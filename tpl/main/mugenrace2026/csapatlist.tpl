{extends "base.tpl"}

{block "kozep"}
    <div class="container whitebg teams">
        <div class="container page-header teams__header">
            <div class="row">
                <div class="col">
                {include 'morzsa.tpl'}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h1 class="page-header__title">{t('Csapatok')}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container teams__list">
        <div class="row">
            <div class="col teams__items">
                {foreach $csapatlista as $_csapat}
                    <div class="kat teams__item" data-href="/teams/{$_csapat.slug}">
                        <div class="kattext teams__item-content">
                            <img src="{$imagepath}{$_csapat.kepurl400}" alt="{$_csapat.kepleiras}" class="teams__item-image">
                            <img src="{$imagepath}{$_csapat.logourlmini}" alt="{$_csapat.logoleiras}" class="teams__item-logo">
                            <h3 class="teams__item-title"><a href="/teams/{$_csapat.slug}">{$_csapat.nev}</a></h3>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
{/block}
