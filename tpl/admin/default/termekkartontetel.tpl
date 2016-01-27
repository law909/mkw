<table>
    <thead>
        <tr>
            <th class="headercell"></th>
            <th class="headercell">Biz.szám</th>
            <th class="headercell"></th>
            <th class="headercell">Raktár</th>
            <th class="headercell">Kelt</th>
            <th class="headercell">Teljesítés</th>
            <th class="headercell">Esedékesség</th>
            <th class="headercell">Partner</th>
            <th class="headercell"></th>
            <th class="headercell textalignright">Nettó egys.ár</th>
            <th class="headercell textalignright">Bruttó egys.ár</th>
            <th class="headercell textalignright">Mennyiség</th>
            <th class="headercell textalignright">Nettó</th>
            <th class="headercell textalignright">Bruttó</th>
            <th>Változat</th>
        </tr>
    </thead>
    <tbody>
    {foreach $kartontetelek as $tetel}
        <tr>
            {if ($tetel.fej.storno)}
                <td class="datacell">S</td>
            {else}
                {if ($tetel.fej.stornozott)}
                    <td class="datacell">T</td>
                {else}
                    <td class="datacell"></td>
                {/if}
            {/if}
            <td class="datacell">{$tetel.fej.id}</td>
            <td class="datacell">{$tetel.fej.bizonylatnev}</td>
            <td class="datacell">{$tetel.fej.raktarnev}</td>
            <td class="datacell">{$tetel.fej.keltstr}</td>
            <td class="datacell">{$tetel.fej.teljesitesstr}</td>
            <td class="datacell">{$tetel.fej.esedekessegstr}</td>
            <td class="datacell">{$tetel.fej.szamlanev}</td>
            <td class="datacell">{$tetel.fej.szamlairszam} {$tetel.fej.szamlavaros} {$tetel.fej.szamlautca}</td>
            <td class="datacell textalignright">{bizformat($tetel.tetel.nettoegysar)}</td>
            <td class="datacell textalignright">{bizformat($tetel.tetel.bruttoegysar)}</td>
            <td class="datacell textalignright">{bizformat($tetel.tetel.mennyiseg*$tetel.tetel.irany)}</td>
            <td class="datacell textalignright">{bizformat($tetel.tetel.netto)}</td>
            <td class="datacell textalignright">{bizformat($tetel.tetel.brutto)}</td>
            <td>{$tetel.tetel.valtozatnev}</td>
        </tr>
    {/foreach}
    </tbody>
</table>