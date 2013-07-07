{extends "base.tpl"}

{block "kozep"}
<div class="container">
<article itemtype="http://schema.org/Article" itemscope="">
		<div class="row">
			<div class="span14 offset1">
			<div>
				<h3>{$hir.cim}</h3>
			</div>
			<div class="span16">
				{$hir.szoveg}
			</div>
			<div class="hiralairas">
				{$hir.forras} {$hir.datum}
			</div>
			</div>
		</div>
</article>
</div>
{/block}