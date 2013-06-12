{extends "base.tpl"}

{block "kozep"}
<article itemtype="http://schema.org/Article" itemscope="">
		<div class="row hataroltSor">
			<div class="span10 offset1">
			<div>
				<h3>{$hir.cim}</h3>
			</div>
			<div class="span12">
				{$hir.szoveg}
			</div>
			<div class="hiralairas">
				{$hir.forras} {$hir.datum}
			</div>
			</div>
		</div>
</article>
{/block}