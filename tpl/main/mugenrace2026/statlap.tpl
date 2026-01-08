{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg static-page">
	<article itemtype="http://schema.org/Article" itemscope="">

			<div class="container page-header static-page__header">
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
																			<a href="/termekfa/{$_navi.url}" rel="v:url" property="v:title">
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
										{if (isset($_navi.url))}
											<a href="/termekfa/{$_navi.url}" rel="v:url" property="v:title">
													{$statlap.oldalcim|capitalize}
											</a>
										{else}
											<a href="#" rel="v:url" property="v:title">
													{$statlap.oldalcim|capitalize}
											</a>
										{/if}
								</h1>
							</div>
					</div>
			</div>

			<div class="row static-page__content">
				<div class="col">
					{$statlap.szoveg}
				</div>
			</div>
	</article>
</div>
{/block}