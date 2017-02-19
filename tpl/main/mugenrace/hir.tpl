{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
<article itemtype="http://schema.org/Article" itemscope="">
		<div class="row">
                    <div class="span10 offset1">
                        <h2>{$hir.cim}</h2>
                        {$hir.szoveg}
                    </div>
                    <div class="hiralairas">
                            {$hir.forras} {$hir.datum}
                    </div>
                </div>
</article>
</div>
{/block}