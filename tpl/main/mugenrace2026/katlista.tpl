{extends "base.tpl"}

{block "kozep"}
<div class="container morzsa">
	<div class="row">
		<div class="span12 morzsaszoveg">
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
	</div>
</div>
<div class="container whitebg">
	<div class="row">
		<div class="span12">
			{foreach $children as $_child}
				<div class="kat" data-href="/categories/{$_child.slug}">
					<div class="katimage">
					{if ($_child.kiskepurl!='')}<a href="/categories/{$_child.slug}"><img src="{$imagepath}{$_child.kiskepurl}" alt="{$_child.caption}" title="{$_child.caption}"></a>{/if}
					</div>
					<div class="kattext">
						<div class="kattitle"><a href="/categories/{$_child.slug}">{$_child.caption}</a></div>
						<div class="katcopy">{$_child.leiras}</div>
					</div>
				</div>
			{/foreach}
		</div>
	</div>
</div>
{/block}