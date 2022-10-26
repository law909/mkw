<table>
    <thead>
    <tr>
        <th class="headercell">{at('Tanár')}</th>
        <th class="headercell textalignright">{at('Jutalék')}</th>
        <th class="headercell textalignright">{at('Havi levonás')}</th>
        <th class="headercell textalignright">{at('Napi levonás')}</th>
        <th class="headercell textalignright">{at('Fizetendő')}</th>
        <th class="headercell">{at('Fiz.mód')}</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    {$sum = 0}
    {$fsum = 0}
    {foreach $tetelek as $tetel}
        {$sum = $sum + $tetel.jutalek}
        {$fsum = $fsum + $tetel.jutalek - $tetel.havilevonas - $tetel.napilevonas}
        <tr>
            <td class="datacell">{$tetel.nev}</td>
            <td class="datacell textalignright">{bizformat($tetel.jutalek)}</td>
            <td class="datacell textalignright">{bizformat($tetel.havilevonas)}</td>
            <td class="datacell textalignright">{bizformat($tetel.napilevonas)}</td>
            <td class="datacell textalignright">{bizformat($tetel.jutalek-$tetel.havilevonas-$tetel.napilevonas)}</td>
            <td class="datacell">{$tetel.fizmodnev}</td>
            <td class="datacell"><a href="\admin\tanarelszamolas\reszletezo?id={$tetel.id}&tol={$tol}&ig={$ig}" target="_blank">{at('Részletezés')}</a></td>
            <td class="datacell"><a href="\admin\tanarelszamolas\email?id={$tetel.id}&tol={$tol}&ig={$ig}" target="_blank">{at('Email')}</a></td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="datacell">{at('Összesen')}</td>
            <td class="datacell textalignright">{bizformat($sum)}</td>
            <td></td>
            <td></td>
            <td class="datacell textalignright">{bizformat($fsum)}</td>
        </tr>
    </tfoot>
</table>