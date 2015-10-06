<thead>
	<tr>
		<th></th>
		<th>Item</th>
		<th><div class="textalignright">Retail price</div></th>
		<th><div class="textaligncenter">Discount %</div></th>
		<th><div class="textalignright">Unit price</div></th>
		<th><div class="textaligncenter">Qty<i class="icon-question-sign cartheader-tooltipbtn hidden-phone js-tooltipbtn" title="A mennyiség módosításához adja meg a kívánt mennyiséget, majd nyomja meg az Enter-t"></i></div></th>
		<th><div class="textalignright">Price</div></th>
		<th></th>
	</tr>
</thead>
<tbody>
	{$osszesen=0}
    {$dbosszesen=0}
	{foreach $tetellista as $tetel}
		{$osszesen=$osszesen+$tetel.brutto}
        {$dbosszesen=$dbosszesen+$tetel.mennyiseg}
		<tr data-href="{$tetel.link}">
			<td><div>
                    {if ($tetel.noedit)}
                    <img src="{$tetel.minikepurl}" alt="{$tetel.caption}" title="{$tetel.caption}" class="szinkep">
                    {else}
                    <a href="{$tetel.link}"><img src="{$tetel.minikepurl}" alt="{$tetel.caption}" title="{$tetel.caption}" class="szinkep"></a>
                    {/if}
                </div></td>
			<td>
                {$tetel.cikkszam}
                <div>
                    {if ($tetel.noedit)}
                    {$tetel.caption}
                    {else}
                    <a href="{$tetel.link}">{$tetel.caption}</a>
                    {/if}
                </div>
				<div>{foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}</div>
			</td>
			<td><div class="textalignright">{number_format($tetel.ebruttoegysar, 2, ',', ' ')} {$valutanem}</div></td>
            <td class="kosar-discounttd">
                <div class="textaligncenter">
                    <form class="kosarformk" action="{$tetel.editlink}">
                        <div>{if ($tetel.noedit)}
                                {number_format($tetel.kedvezmeny, 0, ',', ' ')} %
                            {else}
                                <input id="kedvezmenyedit_{$tetel.id}" class="form-control" type="number" min="1" step="any" name="kedvezmeny" value="{$tetel.kedvezmeny * 1}" data-org="{$tetel.kedvezmeny}">
                            {/if}
                        </div>
                        <input type="hidden" name="id" value="{$tetel.id}">
                    </form>
                </div>
            </td>
			<td><div id="egysegar_{$tetel.id}" class="textalignright">{number_format($tetel.bruttoegysar, 2, ',', ' ')} {$valutanem}</div></td>
			<td class="kosar-qtytd">
				<div class="textaligncenter">
					<form class="kosarform" action="{$tetel.editlink}">
						<div>{if ($tetel.noedit)}
                            {number_format($tetel.mennyiseg, 0, ',', ' ')}
                            {else}
                            <input id="mennyedit_{$tetel.id}" class="form-control" type="number" min="1" step="any" name="mennyiseg" value="{$tetel.mennyiseg}" data-org="{$tetel.mennyiseg}">
                            {/if}
                        </div>
						<input type="hidden" name="id" value="{$tetel.id}">
					</form>
				</div>
			</td>
			<td><div id="ertek_{$tetel.id}" class="textalignright">{number_format($tetel.brutto, 2, ',', ' ')} {$valutanem}</div></td>
			<td class="textaligncenter">{if (!$tetel.noedit)}<a class="btn btn-default js-kosardelbtn" href="/kosar/del?id={$tetel.id}" rel="nofollow"><i class="icon-remove-sign"></i>Remove</a>{/if}</td>
		</tr>
	{/foreach}
</tbody>
<tfoot>
	<tr>
		<th colspan="5"><div class="textalignright">Summary:</div></th>
		<th><div id="mennyisegsum" class="textalignright">{number_format($dbosszesen, 0, ',', ' ')}</div></th>
		<th><div id="kosarsum" class="textalignright">{number_format($osszesen, 2, ',', ' ')} {$valutanem}</div></th>
		<th></th>
	</tr>
</tfoot>
