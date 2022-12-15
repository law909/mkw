<tbody>
	{$osszesen=0}
	{foreach $tetellista as $tetel}
		{$osszesen=$osszesen+$tetel.bruttohuf}
		<tr{if ($tetel.noedit)} class="cart-row-noedit"{/if} data-href="{$tetel.link}">
			<td>
				{if ($tetel.kiskepurl)}
					{if ($tetel.noedit)}
					<img src="{$imagepath}{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}">
					{else}
					<a href="{$tetel.link}" class="cart-table-link"><img src="{$imagepath}{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}"></a>
					{/if}
				{/if}
			</td>
			<td class="cart-table-cell">
				<div>
                    {if ($tetel.noedit)}
                    {$tetel.caption}
                    {else}
                    <a href="{$tetel.link}">{$tetel.caption}</a>
                    {/if}
                </div>
				<div class="termek-cikkszam">{$tetel.cikkszam}</div>
				<div class="co-termek-tul">{foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}</div>
			</td>
			<td class="cart-table-cell">
				<div class="text-align-right co-termek-ar">{number_format($tetel.bruttoegysarhuf,0,',',' ')} {$valutanemnev}</div>
			</td>
			<td class="cart-table-cell">
				<div class="text-align-center">
					<form class="kosarform" action="{$tetel.editlink}">
						<div class="co-termek-ar">
							{if ($tetel.noedit)}
                            	{number_format($tetel.mennyiseg,0,'','')} {t('db')}
                            {else}
                            	<input id="mennyedit_{$tetel.id}" class="cart-mennyiseg-input" type="number" min="1" step="any" name="mennyiseg" value="{number_format($tetel.mennyiseg,0,'','')}" data-org="{$tetel.mennyiseg}">
                            {/if}
                        </div>
						<input type="hidden" name="id" value="{$tetel.id}">
					</form>
				</div>
			</td>
			<td class="cart-table-cell">
				<div id="ertek_{$tetel.id}" class="text-align-right co-termek-ar font-bold">{number_format($tetel.bruttohuf,0,',',' ')} {$valutanemnev}</div>
			</td>
			<td class="cart-table-cell text-align-center">
				{if (!$tetel.noedit)}
					<button class="btn btn-secondary" onclick="location = '/kosar/del?id={$tetel.id}'" rel="nofollow"><i class="icon-remove-sign"></i>{t('Töröl')}</button>
				{/if}
			</td>
		</tr>
	{/foreach}
</tbody>
<tfoot>
	<tr>
		<th class="cart-table-cell" colspan="4"><div class="text-align-right">{t('Összesen')}:</div></th>
		<th class="cart-table-cell"><div id="kosarsum" class="text-align-right co-termek-ar font-bold">{number_format($osszesen,0,',',' ')} {$valutanemnev}</div></th>
		<th></th>
	</tr>
</tfoot>
