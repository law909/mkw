<thead>
    <tr>
        <th></th>
        <th>Item</th>
        <th><div class="textalignright">Unit price</div></th>
        <th><div class="textaligncenter">Qty</div></th>
        <th><div class="textalignright">Price</div></th>
    </tr>
</thead>
<tbody>
{$osszesen=0}
{$dbosszesen=0}
{foreach $tetellista as $tetel}
    {$osszesen=$osszesen+$tetel.bruttohuf}
    {$dbosszesen=$dbosszesen+$tetel.mennyiseg}
    <tr class="clickable" data-href="{$tetel.link}">
        <td><div class="textaligncenter"><img src="{$tetel.kiskepurl}" alt="{$tetel.caption}" title="{$tetel.caption}"></div></td>
        <td><div>{$tetel.caption}</div>
            <div>{foreach $tetel.valtozatok as $valtozat}{$valtozat.ertek}&nbsp;{/foreach}</div>
            {$tetel.cikkszam}</td>
        <td><div class="textalignright">{number_format($tetel.bruttoegysarhuf,0,',',' ')} {$valutanem}</div></td>
        <td>
            <div>
                <div class="textalignright">{number_format($tetel.mennyiseg,0,',','')}</div>
            </div>
        </td>
        <td><div class="textalignright">{number_format($tetel.bruttohuf,0,',',' ')} {$valutanem}</div></td>
    </tr>
{/foreach}
</tbody>
<tfoot>
    <tr>
        <th colspan="3"><div class="textalignright">Summary:</div></th>
        <th><div class="textalignright">{number_format($dbosszesen,0,',',' ')}</div></th>
        <th><div class="textalignright">{number_format($osszesen,0,',',' ')} {$valutanem}</div></th>
    </tr>
</tfoot>
