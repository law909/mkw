{extends "base.tpl"}
{block "script"}
<script src="/js/main/mkwcansas/jquery.blockUI.js"></script>
<script src="/js/main/mkwcansas/bootstrap.min.js"></script>
{/block}

{block "kozep"}
<div class="container">
	<article itemtype="http://schema.org/Article" itemscope="">
			<div class="row">
				<div class="span16">
					{$statlap.szoveg}
				</div>
			</div>
	</article>
</div>
{/block}