<table>
    <thead>
    <tr>
        <th class="headercell">{at('Cikkszám')}</th>
        <th class="headercell">{at('Név')}</th>
        <th class="headercell textalignright">{at('Mennyiség')}</th>
        {if ($ertektipus)}
        <th class="headercell textalignright">{at('Érték')}</th>
        {/if}
        {if ($keszletkell)}
            {foreach $raktarlista as $raktar}
                <th class="headercell">{$raktar}</th>
            {/foreach}
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
            {if ($keszletkell)}
                {foreach $tetel.keszletinfo as $kkey => $keszlet}
                    <td class="datacell textalignright">{$keszlet}</td>
                {/foreach}
            {/if}
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="datacell">{at('Összesen')}</td>
            <td class="datacell"></td>
            <td class="datacell textalignright">{bizformat($snyito)}</td>
            {if ($ertektipus)}
                <td class="datacell textalignright">{bizformat($snyitoe)}</td>
            {/if}
        </tr>
    </tfoot>
</table>