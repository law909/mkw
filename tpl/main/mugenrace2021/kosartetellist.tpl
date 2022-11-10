<tbody>
	{$osszesen=0}
	{foreach $tetellista as $tetel}
		{$osszesen=$osszesen+$tetel.bruttohuf}
		<tr class="clickable" data-href="{$tetel.link}">
			<td><div class="textaligncenter">
                    {if ($tetel.noedit)}
                    <img src="{$imagepath}{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}">
                    {else}
                    <a href="{$tetel.link}"><img src="{$imagepath}{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}"></a>
                    {/if}
                </div></td>
			<td>
				<div class="cart-termeknev">
                    {if ($tetel.noedit)}
                    {$tetel.caption}
                    {else}
                    <a href="{$tetel.link}">{$tetel.caption}</a>
                    {/if}
                </div>
				<div class="cart-cikkszam">{$tetel.cikkszam}</div>
				<div>{foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}</div>
			</td>
			<td><div class="textalignright">{number_format($tetel.bruttoegysarhuf,0,',',' ')} {$valutanemnev}</div></td>
			<td>
				<div class="textaligncenter">
					<form class="kosarform" action="{$tetel.editlink}">
						<div>{if ($tetel.noedit)}
                            {number_format($tetel.mennyiseg,0,'','')}
                            {else}
                            <input id="mennyedit_{$tetel.id}" class="span1" type="number" min="1" step="any" name="mennyiseg" value="{number_format($tetel.mennyiseg,0,'','')}" data-org="{$tetel.mennyiseg}">
                            {/if}
                        </div>
						<input type="hidden" name="id" value="{$tetel.id}">
					</form>
				</div>
			</td>
			<td><div id="ertek_{$tetel.id}" class="textalignright">{number_format($tetel.bruttohuf,0,',',' ')} {$valutanemnev}</div></td>
			<td class="textaligncenter">{if (!$tetel.noedit)}<a class="btn js-kosardelbtn" href="/kosar/del?id={$tetel.id}" rel="nofollow"><i class="icon-remove-sign"></i>{t('Töröl')}</a>{/if}</td>
		</tr>
	{/foreach}
</tbody>
<tfoot>
	<tr>
		<th colspan="4"><div class="textalignright">{t('Összesen')}:</div></th>
		<th><div id="kosarsum" class="textalignright">{number_format($osszesen,0,',',' ')} {$valutanemnev}</div></th>
		<th></th>
	</tr>
</tfoot>
