{if ($kosar.termekdb)}
    <span data-empty="0">{number_format($kosar.termekdb, 0, ',', ' ')}&nbsp;{t('termék')}</span>:&nbsp;<span>{number_format($kosar.brutto, 0, ',', ' ')} {$kosar.valutanem} {t('a kosarában')}</span>
{else}
    <span data-empty="1">{t('Kosár')}</span>
{/if}