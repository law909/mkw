{extends "base.tpl"}

{block "kozep"}
    <div class="container whitebg sponsored-riders">
        <div class="container page-header sponsored-riders__header">
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
                            {t('Szponzorált versenyzők')}
                        </a>

                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container sponsored-riders__list">
        <div class="row">
            <div class="col sponsored-riders__items">
                {foreach $versenyzolista as $_versenyzo}
                    <div class="kat sponsored-riders__item" data-href="/riders/{$_versenyzo.slug}">
                        <div class="kattext sponsored-riders__item-content">
                            {* <img src="{$imagepath}{$_child.kepurl}" alt="{$_child.cim}" class="sponsored-riders__item-image"> *}
                            <img src="{$imagepath}{$_versenyzo.kepurl400}" alt="" class="sponsored-riders__item-image">
                            <div class="sponsored-riders__item-category">{$_versenyzo.versenysorozat}</div>
                            <div class="sponsored-riders__item-title"><a href="/riders/{$_versenyzo.slug}/">{$_versenyzo.nev}</a></div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
    {* {foreach $versenyzolista as $_versenyzo}
        <div>
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

        </div>
    {/foreach} *}
{/block}