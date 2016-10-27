{extends "../rep_base.tpl"}

{block "body"}
    <h4 xmlns="http://www.w3.org/1999/html">Bizonylatétel lista</h4>
    <h5>{$datumnev} {$tolstr} - {$igstr}</h5>
    <h5>{$partnernev}</h5>
    <h5>{$cimkenevek}</h5>
    <table>
        <thead>
        <tr>
            <th>Cikkszám</th>
            <th>Név</th>
            <th class="textalignright">Mennyiség</th>
            {if ($ertektipus)}
                <th class="textalignright">Érték</th>
            {/if}
            {if ($keszletkell)}
                {foreach $raktarlista as $raktar}
                    <th>{$raktar}</th>
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
                <td class="cell">{$tetel.cikkszam}</td>
                <td class="cell">{$tetel.nev} {$tetel.ertek1} {$tetel.ertek2}</td>
                <td class="cell textalignright nowrap">{bizformat($tetel.mennyiseg)}</td>
                {if ($ertektipus)}
                    <td class="cell textalignright nowrap">{bizformat($tetel.ertek)}</td>
                {/if}
                {if ($keszletkell)}
                    {foreach $tetel.keszletinfo as $kkey => $keszlet}
                        <td class="cell textalignright nowrap">{$keszlet}</td>
                    {/foreach}
                {/if}
            </tr>
        {/foreach}
        </tbody>
        <tfoot>
        <tr>
            <td class="cell">Összesen</td>
            <td class="cell"></td>
            <td class="cell textalignright nowrap">{bizformat($snyito)}</td>
            {if ($ertektipus)}
                <td class="cell textalignright nowrap">{bizformat($snyitoe)}</td>
            {/if}
            {if ($keszletkell)}
                {foreach $raktarlista as $raktar}
                    <td class="cell"></td>
                {/foreach}
            {/if}
        </tr>
        </tfoot>
    </table>
{/block}