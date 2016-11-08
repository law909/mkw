{extends "../rep_base.tpl"}

{block "body"}
    <h4 xmlns="http://www.w3.org/1999/html">Bizonylatétel lista</h4>
    <h5>{$datumnev} {$tolstr} - {$igstr}</h5>
    {if ($partnernev)}<h5>{$partnernev}</h5>{/if}
    {if ($raktarnev)}<h5>{$raktarnev}</h5>{/if}
    {if ($uknev)}<h5>{$uknev}</h5>{/if}
    {if ($fizmodnev)}<h5>{$fizmodnev}</h5>{/if}
    {if ($cimkenevek)}<h5>{$cimkenevek}</h5>{/if}
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
        <tr>
            <th>Item no.</th>
            <th>Name</th>
            <th class="textalignright">Quantity</th>
            {if ($ertektipus)}
                <th class="textalignright">Value</th>
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
            <td class="cell">Összesen / Total</td>
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