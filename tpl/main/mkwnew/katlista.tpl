{extends "base.tpl"}

{block "kozep"}
<div class="row">
	<div class="span10 offset1">
		<div>
			{foreach $navigator as $_navi}
				{if ($_navi.url!='')}
					<a href="{$_navi.url}">
						{$_navi.caption}
					</a>
					/
				{else}
					{$_navi.caption}
				{/if}
			{/foreach}
		</div>
		<div class="spg-block-container">
			{foreach $children as $_child}
				<div class="spg-block-container-full" data-href="/termekfa/{$_child.slug}">
					<div class="wrap">
						<dl class="spg-additional">
							<dt>{if ($_child.kiskepurl!='')}<a href="/termekfa/{$_child.slug}">
								<img src="{$_child.kiskepurl}" alt="{$_child.caption}" title="{$_child.caption}">
							</a>{/if}</dt>
							<dd class="title"><a href="/termekfa/{$_child.slug}">{$_child.caption}</a></dd>
							<dd class="copy">{$_child.leiras}</dd>
						</dl>
					</div>
				</div>
			{/foreach}
		</div>
	</div>
</div>
{/block}