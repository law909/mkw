{extends "base.tpl"}

{block "kozep"}
	<div class="container whitebg static-page">
		<article class="static-page__article">

				<div class="container page-header static-page__header">
					<div class="row">
						<div class="col">
										{include 'morzsa.tpl'}
						</div>
					</div>
						<div class="row">
								<div class="col">
									<h1 class="page-header__title">{$statlap.oldalcim|capitalize}</h1>
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