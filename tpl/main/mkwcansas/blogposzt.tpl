{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
<article itemtype="http://schema.org/Article" itemscope="">
		<div class="row">
                    <div class="span10 offset1">
                        <h2>{$blogposzt.cim}</h2>
                        {$blogposzt.szoveg}
                    </div>
                </div>
</article>
</div>
{/block}