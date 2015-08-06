{if ($kosar.termekdb)}
    <span>{number_format($kosar.termekdb,0,',',' ')}&nbsp;{t('item')}</span>:&nbsp;<span>{number_format($kosar.osszeg,0,',',' ')} {$kosar.valutanem} in your cart</span>
{else}
    <span>Cart</span>
{/if}