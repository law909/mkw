{extends "base.tpl"}

{block "kozep"}
    <div class="container page-header">
        <div class="row">
            <div class="col">
				{include 'morzsa.tpl'}
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h1 class="page-header__title">{$hir.cim}</h1>
            </div>
            <div class="col flex-cr">
                <a href="/news/" class="button bordered">{t('Vissza a hírekhez')}</a>
            </div>
        </div>
    </div>
    <div class="container-sm  news-datasheet">
        <article class="news-datasheet__article">
            <div class="row">
                <div class="col ">
                    
                    <div class="news-datasheet__meta">
                        <div class="news-datasheet__date">
                            {$hir.datum}
                        </div>
                        {if (isset($hir.forras) && $hir.forras)}
                            <div class="news-datasheet__source">
                                {$hir.forras}
                            </div>
                        {/if}
                    </div>
                    <div class="news-datasheet__content">
                        {if ($hir.kepurllarge)}
                            <img src="{$imagepath}{$hir.kepurllarge}" class="news-datasheet__image" alt="{$hir.kepleiras}">
                        {/if}
                        {$hir.szoveg|demoteh1}
                    </div>
                </div>
            </div>
        </article>
    </div>
{/block}