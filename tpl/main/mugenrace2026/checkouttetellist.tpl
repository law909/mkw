{* <thead>
	<tr>
		<th><div class="textaligncenter">{t('Termék')}</div></th>
		<th>{t('Megnevezés, cikkszám')}</th>
		<th><div class="textalignright">{t('Egységár')}</div></th>
		<th><div class="textaligncenter">{t('Mennyiség')}</div></th>
		<th><div class="textalignright">{t('Érték')}</div></th>
	</tr>
</thead>
<tbody> *}
{$osszesen=0}
{foreach $tetellista as $tetel}
	{$osszesen=$osszesen+$tetel.bruttohuf}
	<div class="checkout-order-list-item flex-tb clickable" data-href="{$tetel.link}">
		<div class="checkout-order-list-item__image">
			<img src="//shop.mugenrace.com/{$imagepath}{$tetel.minikepurl}" alt="{$tetel.caption}" title="{$tetel.caption}">
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
			{* <div class="checkout-order-list-item__unit-price">
				{number_format($tetel.bruttoegysarhuf,0,',',' ')} {$valutanemnev}
			</div> *}
			<div class="checkout-order-list-item__total-price">
				{number_format($tetel.bruttohuf,0,',',' ')} {$valutanemnev}
			</div>
		</div>
	</div>

	{* <tr class="clickable" data-href="{$tetel.link}">
		<td><div class="textaligncenter"><img src="{$imagepath}{$tetel.minikepurl}" alt="{$tetel.caption}" title="{$tetel.caption}"></div></td>
		<td><div>{$tetel.caption}</div>
			<div>{foreach $tetel.valtozatok as $valtozat}{t($valtozat.nev)}: {$valtozat.ertek}&nbsp;{/foreach}</div>
			{$tetel.cikkszam}</td>
		<td><div class="textalignright">{number_format($tetel.bruttoegysarhuf,0,',',' ')} {$valutanemnev}</div></td>
		<td>
			<div class="textaligncenter">
				<div>{number_format($tetel.mennyiseg,0,',','')}</div>
			</div>
		</td>
		<td><div class="textalignright">{number_format($tetel.bruttohuf,0,',',' ')} {$valutanemnev}</div></td>
	</tr> *}
{/foreach}
{* </tbody>
<tfoot>
	<tr>
		<th colspan="4"><div class="textalignright">{t('Összesen')}:</div></th>
		<th><div class="textalignright">{number_format($osszesen,0,',',' ')} {$valutanemnev}</div></th>
	</tr>
</tfoot> *}

<div class="checkout-order-list__total flex-cb">
		<div class="checkout-order-list__total-label">{t('Összesen')}:</div>
		<div class="checkout-order-list__total-value">{number_format($osszesen,0,',',' ')} {$valutanemnev}</div>
</div>
