{extends "base.tpl"}

{block "kozep"}
	<div class="container whitebg static-page">
		<article itemtype="http://schema.org/Article" itemscope="">

				<div class="container page-header static-page__header">
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
											{if (isset($_navi.url))}
												<a href="/categories/{$_navi.url}" rel="v:url" property="v:title">
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