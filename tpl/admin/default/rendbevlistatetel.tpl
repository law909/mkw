<table>
    <thead>
    <tr>
        <th class="headercell">{at('Cikkszám')}</th>
        <th class="headercell">{at('Név')}</th>
        <th class="headercell">{at('Változat')}</th>
        <th class="headercell textalignright">{at('Rendelt')}</th>
        <th class="headercell textalignright">{at('Beérkezett')}</th>
        <th class="headercell textalignright">{at('Még érkezni fog')}</th>
    </tr>
    </thead>
    <tbody>
    {$srendelt = 0}
    {$sbeerkezett = 0}
    {$skulonbozet = 0}
    {foreach $tetelek as $tetel}
        {$srendelt = $srendelt + $tetel.rendelt}
        {$sbeerkezett = $sbeerkezett + $tetel.beerkezett}
        {$skulonbozet = $skulonbozet + $tetel.kulonbozet}
        <tr>
            <td class="datacell">{$tetel.cikkszam}</td>
            <td class="datacell">{$tetel.nev}</td>
            <td class="datacell">{$tetel.valtozat}</td>
            <td class="datacell textalignright">{bizformat($tetel.rendelt)}</td>
            <td class="datacell textalignright">{bizformat($tetel.beerkezett)}</td>
            <td class="datacell textalignright">{bizformat($tetel.kulonbozet)}</td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        <td class="datacell">{at('Összesen')}</td>
        <td class="datacell"></td>
        <td class="datacell"></td>
        <td class="datacell textalignright">{bizformat($srendelt)}</td>
        <td class="datacell textalignright">{bizformat($sbeerkezett)}</td>
        <td class="datacell textalignright">{bizformat($skulonbozet)}</td>
    </tr>
    </tfoot>
</table>
