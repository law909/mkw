{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
    <div class="row">
        <div class="span12">
            <a href="\blog"><h1>Mindentkapni Blog</h1></a>
        </div>
    </div>
    <article itemtype="http://schema.org/Article" itemscope="">
		<div class="row">
                    <div class="span10 offset1">
                        <h2>{$blogposzt.cim}</h2>
                        <div>{$blogposzt.megjelenesdatumstr}</div>
                        {$blogposzt.szoveg}
                    </div>
                </div>
    </article>
</div>
{/block}