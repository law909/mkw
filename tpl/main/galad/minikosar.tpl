{if ($kosar.termekdb)}
    <span data-empty="0">{number_format($kosar.termekdb, 0, ',', ' ')}&nbsp;{t('tétel')}</span>:&nbsp;<span>{number_format($kosar.brutto, 2, ',', ' ')} {$kosar.valutanem} a kosárban</span>
{else}
    <span data-empty="1">Kosár</span>
{/if}