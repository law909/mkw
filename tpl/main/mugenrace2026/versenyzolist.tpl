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
                            <img src="{$imagepath}{$_versenyzo.kepurl400}" alt="" class="sponsored-riders__item-image">
                            <div class="sponsored-riders__item-category">{$_versenyzo.versenysorozat}</div>
                            <div class="sponsored-riders__item-title"><a href="/riders/{$_versenyzo.slug}/">{$_versenyzo.nev}</a></div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
{/block}