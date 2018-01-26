<table>
    <thead>
    <tr>
        <th class="headercell">{at('Tag neve')}</th>
        <th class="headercell">{at('Email')}</th>
        <th class="headercell">{at('Tanár neve')}</th>
        <th class="headercell">{at('Tanár egyéb')}</th>
        <th class="headercell">{at('Helyszín')}</th>
        <th class="headercell">{at('Mikor')}</th>
        <th class="headercell textalignright">{at('Óraszám')}</th>
    </tr>
    </thead>
    <tbody>
    {$cnt = count($tetelek)}
    {$i = 0}
    {while $i < $cnt}
        {$sum = 0}
        {$tetel = $tetelek[$i]}
        {$partnerid = $tetel.id}
        {while ($partnerid == $tetelek[$i].id) && ($i < $cnt)}
            {$tetel = $tetelek[$i]}
            {$sum = $sum + $tetel.oraszam}
            <tr>
                <td class="datacell">{$tetel.nev}</td>
                <td class="datacell">{$tetel.email}</td>
                <td class="datacell">{$tetel.tanar}</td>
                <td class="datacell">{$tetel.tanaregyeb}</td>
                <td class="datacell">{$tetel.helyszin}</td>
                <td class="datacell">{$tetel.mikor}</td>
                <td class="datacell textalignright">{$tetel.oraszam}</td>
            </tr>
            {$i = $i + 1}
        {/while}
        {if ($sum)}
            <tr class="italic bold">
                <td colspan="5" class="cell"></td>
                <td class="datacell">{$tetel.nev} összesen</td>
                <td class="datacell textalignright nowrap">{$sum}</td>
            </tr>
        {/if}
    {/while}
    </tbody>
</table>