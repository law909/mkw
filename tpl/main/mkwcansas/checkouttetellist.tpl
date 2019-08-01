<thead>
	<tr>
		<th><div class="textaligncenter">{t('Termék')}</div></th>
		<th>{t('Megnevezés, cikkszám')}</th>
		<th><div class="textalignright">{t('Egységár')}</div></th>
		<th><div class="textaligncenter">{t('Mennyiség')}</div></th>
		<th><div class="textalignright">{t('Érték')}</div></th>
	</tr>
</thead>
<tbody>
{$osszesen=0}
{foreach $tetellista as $tetel}
	{$osszesen=$osszesen+$tetel.bruttohuf}
	<tr class="clickable" data-href="{$tetel.link}">
		<td><div class="textaligncenter"><img src="{$tetel.minikepurl}" alt="{$tetel.caption}" title="{$tetel.caption}"></div></td>
		<td><div>{$tetel.caption}</div>
			<div>{foreach $tetel.valtozatok as $valtozat}{$valtozat.nev}: {$valtozat.ertek}&nbsp;{/foreach}</div>
			{$tetel.cikkszam}</td>
		<td><div class="textalignright">{number_format($tetel.bruttoegysarhuf,0,',',' ')} Ft</div></td>
		<td>
			<div class="textaligncenter">
				<div>{number_format($tetel.mennyiseg,0,',','')}</div>
			</div>
		</td>
		<td><div class="textalignright">{number_format($tetel.bruttohuf,0,',',' ')} Ft</div></td>
	</tr>
{/foreach}
</tbody>
<tfoot>
	<tr>
		<th colspan="4"><div class="textalignright">{t('Összesen')}:</div></th>
		<th><div class="textalignright">{number_format($osszesen,0,',',' ')} Ft</div></th>
	</tr>
    <tr>
        <th colspan="4"><div class="textalignright">{t('Szállítási idő')}:</div></th>
        <th><div class="textalignright">{$szallitasiido} nap</div></th>
    </tr>
</tfoot>
