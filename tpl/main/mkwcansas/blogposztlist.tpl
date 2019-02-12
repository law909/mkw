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
                                {if ($lapozo.pageno>1)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno-1}">< {t('Előző')}</a>{/if}
                                {for $i=1 to $lapozo.pagecount} {if ($i==$lapozo.pageno)}<span class="aktualislap">{$i}</span>{else}<a href="#" class="pageedit" data-pageno="{$i}">{$i}</a>{/if}{/for}
                                {if ($lapozo.pageno<$lapozo.pagecount)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno+1}">{t('Következő')} ></a>{/if}
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
                                {if ($lapozo.pageno>1)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno-1}">< {t('Előző')}</a>{/if}
                                {for $i=1 to $lapozo.pagecount} {if ($i==$lapozo.pageno)}<span class="aktualislap">{$i}</span>{else}<a href="#" class="pageedit" data-pageno="{$i}">{$i}</a>{/if}{/for}
                                {if ($lapozo.pageno<$lapozo.pagecount)}<a href="#" class="pageedit" data-pageno="{$lapozo.pageno+1}">{t('Következő')} ></a>{/if}
                            </td>
                        </tr></tbody></table>
                </form>
            </div>

		</div>
	</div>
</div>
{/block}