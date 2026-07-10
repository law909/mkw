{$osszesen=0}
{foreach $tetellista as $tetel}
    {$osszesen=$osszesen+$tetel.bruttohuf}
    <div class="checkout-order-list-item flex-tb clickable" data-href="{$tetel.link}">
        <div class="checkout-order-list-item__image">
            <img src="{$imagepath}{$tetel.minikepurl}" alt="{$tetel.caption}" title="{$tetel.caption}">
            <div class="checkout-order-list-item__quantity textaligncenter">
                {number_format($tetel.mennyiseg,0,'','')}
            </div>
        </div>
        <div class="checkout-order-list-item__details">
            <div class="checkout-order-list-item__caption">{$tetel.caption}</div>
            <div class="checkout-order-list-item__variants">
                {foreach $tetel.valtozatok as $valtozat}{t($valtozat.nev)}: {$valtozat.ertek}&nbsp;{/foreach}
            </div>
            <div class="checkout-order-list-item__sku">{$tetel.cikkszam}</div>
        </div>
        <div class="checkout-order-list-item__price">
            <div class="checkout-order-list-item__total-price">
                {number_format($tetel.bruttohuf,0,',',' ')} {$valutanemnev}
            </div>
        </div>
    </div>
{/foreach}

<div class="checkout-order-list__total flex-cb">
    <div class="checkout-order-list__total-label">{t('Összesen')}:</div>
    <div class="checkout-order-list__total-value">{number_format($osszesen,0,',',' ')} {$valutanemnev}</div>
</div>
