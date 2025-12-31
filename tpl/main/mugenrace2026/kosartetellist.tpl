<thead>
	<tr>
		<th><div class="textaligncenter">{t('Termék')}</div></th>
		<th>{t('Megnevezés, cikkszám, változat')}</th>
		<th><div class="textalignright">{t('Egységár')}</div></th>
		<th><div class="textaligncenter">{t('Mennyiség')}<i class="icon-question-sign cartheader-tooltipbtn hidden-phone js-tooltipbtn" title="{t('A mennyiség módosításához adja meg a kívánt mennyiséget, majd nyomja meg az Enter-t')}"></i></div></th>
		<th><div class="textalignright">{t('Érték')}</div></th>
		<th></th>
	</tr>
</thead>
<tbody>
	{$osszesen=0}
	{foreach $tetellista as $tetel}
		{$osszesen=$osszesen+$tetel.bruttohuf}
		<tr class="clickable cart-item" data-href="{$tetel.link}">
			<td><div class="textaligncenter">
                    {if ($tetel.noedit)}
                    <img class="cart-item__image" src="{$imagepath}{$tetel.kiskepurl}" alt="{$tetel.caption|lower|capitalize}" title="{$tetel.caption|lower|capitalize}">
                    {else}
                    <a href="{$tetel.link}"><img class="cart-item__image" src="{$imagepath}{$tetel.kiskepurl}" alt="{$tetel.caption|lower|capitalize}" title="{$tetel.caption|lower|capitalize}"></a>
                    {/if}
                </div></td>
			<td><div>
                    {if ($tetel.noedit)}
                    {$tetel.caption|lower|capitalize}
                    {else}
                    <a href="{$tetel.link}" class="cart-item__caption">{$tetel.caption|lower|capitalize}</a>
                    {/if}
                </div>
				<div class="cart-item__variants">{foreach $tetel.valtozatok as $valtozat}{t($valtozat.nev)}: {$valtozat.ertek}&nbsp;{/foreach}</div>
				<div class="cart-item__sku">
					{$tetel.cikkszam}
				</div>
				<div class="textalignright cart-item__itemprice">{t('Ár')} {number_format($tetel.bruttoegysarhuf,0,',',' ')} {$valutanemnev}</div>
			</td>
			<td><div class="textalignright cart-item__itemprice">{number_format($tetel.bruttoegysarhuf,0,',',' ')} {$valutanemnev}</div></td>
			<td>
				<div class="textaligncenter">
					<form class="kosarform" action="{$tetel.editlink}">
						<div>{if ($tetel.noedit)}
								{number_format($tetel.mennyiseg,0,'','')}
								{else}
								<input id="mennyedit_{$tetel.id}" class="cart-item__quantity" type="number" min="1" step="any" name="mennyiseg" value="{number_format($tetel.mennyiseg,0,'','')}" data-org="{$tetel.mennyiseg}">
								{/if}
						</div>
						<input type="hidden" name="id" value="{$tetel.id}">
					</form>
				</div>
			</td>
			<td><div id="ertek_{$tetel.id}" class="textalignright">{number_format($tetel.bruttohuf,0,',',' ')} {$valutanemnev}</div></td>
			<td>{if (!$tetel.noedit)}<div class="flex-cr "><a class="button bordered js-kosardelbtn" href="/kosar/del?id={$tetel.id}" rel="nofollow"><i class="icon trash icon__click"></i><span>{t('Töröl')}</span></a></div>{/if}</td>
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
