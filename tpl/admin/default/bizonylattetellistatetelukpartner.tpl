<table>
    <thead>
    <tr>
        <th class="headercell">{at('Partner')}</th>
        {if ($ertektipus)}
        <th class="headercell textalignright">{at('Érték')}</th>
        {/if}
    </tr>
    </thead>
    <tbody>
    {$osszesen = 0}
    {$cnt = count($tetelek)}
    {$i = 0}
    {while $i < $cnt}
        {$tetel = $tetelek[$i]}
        {$ukid = $tetel.uzletkoto_id}
        {$ukertek = 0}
        <tr class="italic bold">
            <td colspan="2" class="cell">
                {$tetel.uzletkotonev}
            </td>
        </tr>
        {while ($ukid == $tetelek[$i].uzletkoto_id) && ($i < $cnt)}
            {$tetel = $tetelek[$i]}
            {$ukertek = $ukertek + $tetel.ertek}
            {$osszesen = $osszesen + $tetel.ertek}
            <tr>
                <td class="datacell">{$tetel.partnernev}</td>
                <td class="datacell">{$tetel.partnerirszam} {$tetel.partnervaros} {$tetel.partnerutca}</td>
                {if ($ertektipus)}
                <td class="datacell textalignright">{bizformat($tetel.ertek)}</td>
                {/if}
            </tr>
            {$i = $i + 1}
        {/while}
        <tr class="italic bold">
            <td colspan="2" class="cell">{$tetel.uzletkotonev} {at('összesen')}</td>
            {if ($ertektipus)}
            <td class="datacell textalignright">{bizformat($ukertek)}</td>
            {/if}
        </tr>
    {/while}
    </tbody>
    <tfoot>
        <tr>
            <td class="datacell">{at('Összesen')}</td>
            <td class="datacell"></td>
            {if ($ertektipus)}
                <td class="datacell textalignright">{bizformat($osszesen)}</td>
            {/if}
        </tr>
    </tfoot>
</table>