<table>
    <thead>
    <tr>
        <th class="headercell">{at('Cikkszám')}</th>
        <th class="headercell">{at('Név')}</th>
        <th class="headercell textalignright">{at('Nyitó')}</th>
        <th class="headercell textalignright">{at('Be')}</th>
        <th class="headercell textalignright">{at('Ki')}</th>
        <th class="headercell textalignright">{at('Záró')}</th>
        {if ($ertektipus)}
        <th class="headercell textalignright">{at('Nyitó érték')}</th>
        <th class="headercell textalignright">{at('Be érték')}</th>
        <th class="headercell textalignright">{at('Ki érték')}</th>
        <th class="headercell textalignright">{at('Záró érték')}</th>
        {/if}
    </tr>
    </thead>
    <tbody>
    {$snyito = 0}
    {$sbe = 0}
    {$ski = 0}
    {$szaro = 0}
    {$snyitoe = 0}
    {$sbee = 0}
    {$skie = 0}
    {$szaroe = 0}
    {foreach $tetelek as $key => $tetel}
        {$snyito = $snyito + $tetel.nyito}
        {$sbe = $sbe + $tetel.be}
        {$ski = $ski + $tetel.ki}
        {$szaro = $szaro + $tetel.zaro}
        {$snyitoe = $snyitoe + $tetel.nyitoertek}
        {$sbee = $sbee + $tetel.beertek}
        {$skie = $skie + $tetel.kiertek}
        {$szaroe = $szaroe + $tetel.zaroertek}
        <tr>
            <td class="datacell">{$tetel.cikkszam}</td>
            <td class="datacell">{$tetel.nev} {$tetel.ertek1} {$tetel.ertek2}</td>
            <td class="datacell textalignright">{bizformat($tetel.nyito)}</td>
            <td class="datacell textalignright">{bizformat($tetel.be)}</td>
            <td class="datacell textalignright">{bizformat($tetel.ki)}</td>
            <td class="datacell textalignright">{bizformat($tetel.zaro)}</td>
            {if ($ertektipus)}
            <td class="datacell textalignright">{bizformat($tetel.nyitoertek)}</td>
            <td class="datacell textalignright">{bizformat($tetel.beertek)}</td>
            <td class="datacell textalignright">{bizformat($tetel.kiertek)}</td>
            <td class="datacell textalignright">{bizformat($tetel.zaroertek)}</td>
            {/if}
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="datacell">{at('Összesen')}</td>
            <td class="datacell"></td>
            <td class="datacell textalignright">{bizformat($snyito)}</td>
            <td class="datacell textalignright">{bizformat($sbe)}</td>
            <td class="datacell textalignright">{bizformat($ski)}</td>
            <td class="datacell textalignright">{bizformat($szaro)}</td>
            {if ($ertektipus)}
                <td class="datacell textalignright">{bizformat($snyitoe)}</td>
                <td class="datacell textalignright">{bizformat($sbee)}</td>
                <td class="datacell textalignright">{bizformat($skie)}</td>
                <td class="datacell textalignright">{bizformat($szaroe)}</td>
            {/if}
        </tr>
    </tfoot>
</table>