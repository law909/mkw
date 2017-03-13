{if ($kosar.termekdb)}
    <span data-empty="0">{number_format($kosar.termekdb, 0, ',', ' ')}&nbsp;{t('item')}</span>:&nbsp;<span>{number_format($kosar.netto, 2, ',', ' ')} {$kosar.valutanem} in your cart</span>
{else}
    <span data-empty="1">Cart</span>
{/if}