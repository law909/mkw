{extends "base.tpl"}

{block "kozep"}
    <div class="container page-header">
        <div class="row">
            <div class="col" xmlns:v="http://rdf.data-vocabulary.org/#">
				<span class="page-header__breadcrumb flex-lc" itemprop="breadcrumb ">
						{if ($navigator|default)}
                            <a href="/" rel="v:url" property="v:title">
										{t('Home')}
								</a>
                            <i class="icon arrow-right"></i>



{foreach $navigator as $_navi}
                            {if ($_navi.url|default)}
                                <span typeof="v:Breadcrumb">
														<a href="/categories/{$_navi.url}" rel="v:url" property="v:title">
																{$_navi.caption|capitalize}
														</a>
												</span>
                                <i class="icon arrow-right"></i>
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
            <div class="col flex-cr">
                <a href="/news/" class="button bordered">{t('Vissza a hírekhez')}</a>
            </div>
        </div>
    </div>
    <div class="container-sm  news-datasheet">
        <article itemtype="http://schema.org/Article" itemscope="">
            <div class="row">
                <div class="col ">
                    <h2 class="news-datasheet__title">{$hir.cim}</h2>
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
                        {if ($hir.kepurl)}
                            <img src="{$hir.kepurl}" class="news-datasheet__image" alt="{$hir.kepleiras}">
                        {/if}
                        {$hir.szoveg}
                    </div>
                </div>
            </div>
        </article>
    </div>
{/block}