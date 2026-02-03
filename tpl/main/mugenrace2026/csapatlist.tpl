{extends "base.tpl"}

{block "kozep"}
    <div class="container whitebg teams">
        <div class="container page-header teams__header">
            <div class="row">
                <div class="col" xmlns:v="http://rdf.data-vocabulary.org/#">
                <span class="page-header__breadcrumb flex-lc" itemprop="breadcrumb ">

                </span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h1 class="page-header__title" typeof="v:Breadcrumb">
                        <a href="#" rel="v:url" property="v:title">
                            {t('Csapatok')}
                        </a>

                    </h1>
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
                            {* <img src="{$imagepath}{$_child.kepurl}" alt="{$_child.cim}" class="teams__item-image"> *}
                            <img src="{$imagepath}{$_csapat.kepurl400}" alt="{$_csapat.kepleiras}" class="teams__item-image">
                            <img src="{$imagepath}{$_csapat.logourlmini}" alt="{$_csapat.logoleiras}" class="teams__item-logo">
                            <h3 class="teams__item-title"><a href="/teams/{$_csapat.slug}">{$_csapat.nev}</a></h3>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
    {* {foreach $csapatlista as $_csapat}
        <div>
            <h3>{$_csapat.nev}</h3>
            {$_csapat.id}
            {$_csapat.nev}
            {$_csapat.slug}
            {$_csapat.logourl}
            {$_csapat.logourlsmall}
            {$_csapat.logourlmini}
            {$_csapat.logoleiras}
            {$_csapat.leiras}
            {$_csapat.kepurl}
            {$_csapat.kepurlsmall}
            {$_csapat.kepurlmini}
            {$_csapat.kepleiras}
            {foreach $_csapat.versenyzok as $_versenyzo}
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
        </div>
    {/foreach} *}
{/block}
