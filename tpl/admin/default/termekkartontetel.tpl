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
    {$smenny = 0}
    {$snetto = 0}
    {$sbrutto = 0}
    {foreach $kartontetelek as $tetel}
        {if (!$tetel.fej.rontott)}
            {$smenny = $smenny + ($tetel.tetel.mennyiseg * $tetel.tetel.irany)}
            {$snetto = $snetto + ($tetel.tetel.netto * $tetel.tetel.irany)}
            {$sbrutto = $sbrutto + ($tetel.tetel.brutto * $tetel.tetel.irany)}
        {/if}
        <tr>
            {if ($tetel.fej.storno)}
                <td class="datacell">S</td>
            {else}
                {if ($tetel.fej.stornozott)}
                    <td class="datacell">T</td>
                {else}
                    {if ($tetel.fej.rontott)}
                        <td class="datacell">R</td>
                    {else}
                        <td class="datacell"></td>
                    {/if}
                {/if}
            {/if}
            <td class="datacell"><a href="{$tetel.fej.printurl}" target="_blank" title="Nyomtatási kép">{$tetel.fej.id}</a></td>
            <td class="datacell"><a href="{$tetel.fej.editurl}" target="_blank" title="Szerkesztés / nyomtatási kép">{$tetel.fej.bizonylatnev}</a></td>
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
    <tfoot>
        <tr>
            <td class="datacell"></td>
            <td class="datacell">Összesen</td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell textalignright">{bizformat($smenny)}</td>
            <td class="datacell textalignright">{bizformat($snetto)}</td>
            <td class="datacell textalignright">{bizformat($sbrutto)}</td>
            <td>{$tetel.tetel.valtozatnev}</td>

        </tr>
    </tfoot>
</table>