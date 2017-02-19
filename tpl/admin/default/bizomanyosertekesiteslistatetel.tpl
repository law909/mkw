<table>
    <thead>
    <tr>
        <th class="headercell">{at('Cikkszám')}</th>
        <th class="headercell">{at('Név')}</th>
        <th class="headercell textalignright">{at('Kiadás')}</th>
        <th class="headercell textalignright">{at('Visszavét')}</th>
        <th class="headercell textalignright">{at('Forgalom')}</th>
        {if ($ertektipus)}
        <th class="headercell textalignright">{at('Kiadás érték')}</th>
        <th class="headercell textalignright">{at('Visszavét érték')}</th>
        <th class="headercell textalignright">{at('Forgalom érték')}</th>
        {/if}
    </tr>
    </thead>
    <tbody>
    {$sbe = 0}
    {$ski = 0}
    {$sbee = 0}
    {$skie = 0}
    {$skul = 0}
    {$skule = 0}
    {foreach $tetelek as $key => $tetel}
        {$sbe = $sbe + $tetel.be}
        {$ski = $ski + $tetel.ki}
        {$sbee = $sbee + $tetel.beertek}
        {$skie = $skie + $tetel.kiertek}
        {$skul = $skul + ($tetel.ki - $tetel.be)}
        {$skule = $skule + ($tetel.kiertek - $tetel.beertek)}
        <tr>
            <td class="datacell">{$tetel.cikkszam}</td>
            <td class="datacell">{$tetel.nev} {$tetel.ertek1} {$tetel.ertek2}</td>
            <td class="datacell textalignright">{bizformat($tetel.ki)}</td>
            <td class="datacell textalignright">{bizformat($tetel.be)}</td>
            <td class="datacell textalignright">{bizformat($tetel.ki - $tetel.be)}</td>
            {if ($ertektipus)}
            <td class="datacell textalignright">{bizformat($tetel.kiertek)}</td>
            <td class="datacell textalignright">{bizformat($tetel.beertek)}</td>
            <td class="datacell textalignright">{bizformat($tetel.kiertek - $tetel.beertek)}</td>
            {/if}
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="datacell">{at('Összesen')}</td>
            <td class="datacell"></td>
            <td class="datacell textalignright">{bizformat($ski)}</td>
            <td class="datacell textalignright">{bizformat($sbe)}</td>
            <td class="datacell textalignright">{bizformat($skul)}</td>
            {if ($ertektipus)}
                <td class="datacell textalignright">{bizformat($skie)}</td>
                <td class="datacell textalignright">{bizformat($sbee)}</td>
                <td class="datacell textalignright">{bizformat($skule)}</td>
            {/if}
        </tr>
    </tfoot>
</table>