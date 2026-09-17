{extends "base.tpl"}

{block "kozep"}
<div class="container whitebg">
    <div class="row">
        <div class="span12">
            <h1>Mindent Kapni Blog</h1>
        </div>
    </div>
	<div class="row js-blog">
		<div class="span12">
            <div class="lapozo">
                <form class="lapozoform" action="/blog" method="post" data-url="/blog" data-pageno="{$lapozo.pageno}">
                    <table><tbody><tr>
                            <td class="lapozooldalak">
                                {include 'lapozolinkek.tpl' lapozourl='/blog'}
                            </td>
                        </tr></tbody></table>
                </form>
            </div>

			{foreach $children as $_child}
				<div class="kat" data-href="/blogposzt/{$_child.slug}">
					<div class="kattext">
                        <div class="blogkivonatkep"><a href="/blogposzt/{$_child.slug}"><img src="{$_child.kepurlsmall}"</a></div>
						<div class="kattitle"><a href="/blogposzt/{$_child.slug}">{$_child.cim}</a></div>
                        <div>{$_child.megjelenesdatumstr}</div>
						<div class="katcopy">{$_child.kivonat}</div>
					</div>
				</div>
			{/foreach}

            <div class="lapozo">
                <form class="lapozoform" action="/blog" method="post" data-url="/blog" data-pageno="{$lapozo.pageno}">
                    <table><tbody><tr>
                            <td class="lapozooldalak">
                                {include 'lapozolinkek.tpl' lapozourl='/blog'}
                            </td>
                        </tr></tbody></table>
                </form>
            </div>

		</div>
	</div>
</div>
{/block}