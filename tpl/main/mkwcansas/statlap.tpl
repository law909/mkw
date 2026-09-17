{extends "base.tpl"}

{block "kozep"}
{include 'morzsa.tpl'}
<div class="container whitebg">
	<article itemtype="http://schema.org/Article" itemscope="">
			<div class="row">
				<div class="span12">
					{$statlap.szoveg}
				</div>
			</div>
	</article>
</div>
{/block}