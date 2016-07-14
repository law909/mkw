<table>
    <thead>
    <tr>
        <th class="headercell">Cikkszám</th>
        <th class="headercell">Név</th>
        <th class="headercell textalignright">Mennyiség</th>
        {if ($ertektipus)}
        <th class="headercell textalignright">Érték</th>
        {/if}
    </tr>
    </thead>
    <tbody>
    {$snyito = 0}
    {$snyitoe = 0}
    {foreach $tetelek as $key => $tetel}
        {$snyito = $snyito + $tetel.mennyiseg}
        {$snyitoe = $snyitoe + $tetel.ertek}
        <tr>
            <td class="datacell">{$tetel.cikkszam}</td>
            <td class="datacell">{$tetel.nev} {$tetel.ertek1} {$tetel.ertek2}</td>
            <td class="datacell textalignright">{bizformat($tetel.mennyiseg)}</td>
            {if ($ertektipus)}
            <td class="datacell textalignright">{bizformat($tetel.ertek)}</td>
            {/if}
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="datacell">Összesen</td>
            <td class="datacell"></td>
            <td class="datacell textalignright">{bizformat($snyito)}</td>
            {if ($ertektipus)}
                <td class="datacell textalignright">{bizformat($snyitoe)}</td>
            {/if}
        </tr>
    </tfoot>
</table>