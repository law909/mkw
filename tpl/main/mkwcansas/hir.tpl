{extends "base.tpl"}

{block "kozep"}
{include 'morzsa.tpl'}
<div class="container whitebg">
<article class="hir">
		<div class="row">
                    <div class="span10 offset1">
                        <h1>{$hir.cim}</h1>
                        {$hir.szoveg}
                    </div>
                    <div class="hiralairas">
                            {$hir.forras} {$hir.datum}
                    </div>
                </div>
</article>
</div>
{/block}