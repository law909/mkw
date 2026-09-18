{extends "base.tpl"}

{block "kozep"}
    <div class="container page-header">
        <div class="row">
            <div class="col">
				{include 'morzsa.tpl'}
                            <i class="icon arrow-right breadcrumb-{$_navi.url}"></i>
                        {else}
                            {$_navi.caption|capitalize}
                        {/if}
                    {/foreach}
                    {/if}
				</span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h1 class="page-header__title" typeof="v:Breadcrumb">
                    <a href="/news/" rel="v:url" property="v:title">
                        {t('Hírek')}
                    </a>
                </h1>
            </div>
        </div>
    </div>
    <div class="container news-list">


        <div class="row">
            <div class="col news-list__items">
                {foreach $children as $_child}
                    <div class="kat news-list__item" data-href="/news/{$_child.slug}">
                        <div class="kattext news-list__item-content">
                            {if ($_child.kepurllarge)}
                                <img src="{$imagepath}{$_child.kepurllarge}" alt="{$_child.kepleiras}" class="news-list__item-image">
                            {/if}
                            <div class="hiralairas news-list__item-date">{$_child.datum}</div>
                            <div class="kattitle news-list__item-title"><a href="/news/{$_child.slug}">{$_child.cim}</a></div>
                            <div class="katcopy news-list__item-lead">{$_child.lead}</div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
{/block}