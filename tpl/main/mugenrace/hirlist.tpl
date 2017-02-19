{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
	<div class="row">
		<div class="span12">
			{foreach $children as $_child}
				<div class="kat" data-href="/hir/{$_child.slug}">
					<div class="kattext">
						<div class="kattitle"><a href="/hir/{$_child.slug}">{$_child.cim}</a></div>
						<div class="katcopy">{$_child.lead}</div>
                        <div class="hiralairas">{$_child.datum}</div>
					</div>
				</div>
			{/foreach}
		</div>
	</div>
</div>
{/block}