<thead>
	<tr>
		<th></th>
		<th>Item</th>
		<th><div class="textalignright">Unit price</div></th>
		<th><div class="textaligncenter">Qty<i class="icon-question-sign cartheader-tooltipbtn hidden-phone js-tooltipbtn" title="A mennyiség módosításához adja meg a kívánt mennyiséget, majd nyomja meg az Enter-t"></i></div></th>
		<th><div class="textalignright">Price</div></th>
		<th></th>
	</tr>
</thead>
<tbody>
	{$osszesen=0}
	{foreach $tetellista as $tetel}
		{$osszesen=$osszesen+$tetel.bruttohuf}
		<tr data-href="{$tetel.link}">
			<td><div>
                    {if ($tetel.noedit)}
                    <img src="{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}" class="szinkep">
                    {else}
                    <a href="{$tetel.link}"><img src="{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}" class="szinkep"></a>
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
			<td><div class="textalignright">{number_format($tetel.bruttoegysar,0,',',' ')} {$valutanem}</div></td>
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
			<td><div id="ertek_{$tetel.id}" class="textalignright">{number_format($tetel.brutto,0,',',' ')} {$valutanem}</div></td>
			<td class="textaligncenter">{if (!$tetel.noedit)}<a class="btn btn-default js-kosardelbtn" href="/kosar/del?id={$tetel.id}" rel="nofollow"><i class="icon-remove-sign"></i>Remove</a>{/if}</td>
		</tr>
	{/foreach}
</tbody>
<tfoot>
	<tr>
		<th colspan="4"><div class="textalignright">Summary:</div></th>
		<th><div id="kosarsum" class="textalignright">{number_format($osszesen,0,',',' ')} {$valutanem}</div></th>
		<th></th>
	</tr>
</tfoot>
