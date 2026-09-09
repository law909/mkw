{if ($lapozo|default) && ($lapozo.pagecount > 1)}
    {assign var="qs" value="&order=`$order|default`&keresett=`$keresett|default|escape:'url'`"}
    <nav class="lapozo" aria-label="{t('Lapozó')}">
        {if ($lapozo.pageno > 1)}
            <a href="{$url}?pageno={$lapozo.pageno-1}{$qs}" rel="prev">‹ {t('Előző')}</a>
        {/if}
        <span class="lapszam">{$lapozo.pageno} / {$lapozo.pagecount}</span>
        {if ($lapozo.pageno < $lapozo.pagecount)}
            <a href="{$url}?pageno={$lapozo.pageno+1}{$qs}" rel="next">{t('Következő')} ›</a>
        {/if}
    </nav>
{/if}
