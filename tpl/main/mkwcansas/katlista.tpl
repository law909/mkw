{extends "base.tpl"}

{block "script"}
<script src="/js/main/mkwcansas/jquery.blockUI.js"></script>
<script src="/js/main/mkwcansas/bootstrap.min.js"></script>
{/block}

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
<div class="container">
	<div class="row">
		<div class="span10">
			{foreach $children as $_child}
				<div class="kat" data-href="/termekfa/{$_child.slug}">
					<div class="katimage">
					{if ($_child.kiskepurl!='')}<a href="/termekfa/{$_child.slug}"><img src="{$_child.kiskepurl}" alt="{$_child.caption}" title="{$_child.caption}"></a>{/if}
					</div>
					<div class="kattext">
						<div class="kattitle"><a href="/termekfa/{$_child.slug}">{$_child.caption}</a></div>
						<div class="katcopy">{$_child.leiras}</div>
					</div>
				</div>
			{/foreach}
		</div>
		<div class="span2 offset1">
			<div class="row">
				<div class="sectionheader">Kiemelt termékeink</div>
			</div>
		</div>
		<div class="span2 offset1">
			<div class="row">
				<div class="sectionheader">Best Buy</div>
			</div>
			<div class="row">
				<div class="sectionheader">Legfrissebb híreink</div>
			</div>
		</div>
	</div>
</div>
{/block}