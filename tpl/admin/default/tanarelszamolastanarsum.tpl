<table>
    <thead>
    <tr>
        <th class="headercell">{at('Tanár')}</th>
        <th class="headercell textalignright">{at('Jutalék')}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    {$sum = 0}
    {foreach $tetelek as $tetel}
        {$sum = $sum + $tetel.jutalek}
        <tr>
            <td class="datacell">{$tetel.nev}</td>
            <td class="datacell textalignright">{bizformat($tetel.jutalek)}</td>
            <td class="datacell"><a href="\admin\tanarelszamolas\reszletezo?id={$tetel.id}&tol={$tol}&ig={$ig}" target="_blank">{at('Részletezés')}</a></td>
            <td class="datacell"><a href="\admin\tanarelszamolas\email?id={$tetel.id}&tol={$tol}&ig={$ig}" target="_blank">{at('Email')}</a></td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="datacell">{at('Összesen')}</td>
            <td class="datacell textalignright">{bizformat($sum)}</td>
        </tr>
    </tfoot>
</table>