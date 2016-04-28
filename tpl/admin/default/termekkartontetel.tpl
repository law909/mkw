<table class="termekkarton">
    <thead>
        <tr>
            <th class="headercell"></th>
            <th class="headercell">Biz.szám</th>
            <th class="headercell"></th>
            {if ($maintheme != 'mkwcansas')}
            <th class="headercell">Raktár</th>
            {/if}
            <th class="headercell">Kelt</th>
            <th class="headercell">Teljesítés</th>
            <th class="headercell">Partner</th>
            <th class="headercell textalignright">Nettó egys.ár</th>
            <th class="headercell textalignright">Bruttó egys.ár</th>
            <th class="headercell textalignright">Mennyiség</th>
            <th class="headercell textalignright">Nettó</th>
            <th class="headercell textalignright">Bruttó</th>
            <th>Készlet</th>
            <th>Változat</th>
        </tr>
    </thead>
    <tbody>
    {$smenny = 0}
    {$gmenny = $nyito}
    {$snetto = 0}
    {$sbrutto = 0}
    {$besum = 0}
    {$kisum = 0}
    {foreach $kartontetelek as $tetel}
        {if (!$tetel.fej.rontott)}
            {$smenny = $smenny + ($tetel.tetel.mennyiseg * $tetel.tetel.irany)}
            {$gmenny = $gmenny + ($tetel.tetel.mennyiseg * $tetel.tetel.irany)}
            {$snetto = $snetto + ($tetel.tetel.netto * $tetel.tetel.irany)}
            {$sbrutto = $sbrutto + ($tetel.tetel.brutto * $tetel.tetel.irany)}
            {if ($tetel.tetel.irany < 0)}
                {$kisum = $kisum + $tetel.tetel.mennyiseg}
            {else}
                {$besum = $besum + $tetel.tetel.mennyiseg}
            {/if}
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
            {if ($maintheme != 'mkwcansas')}
            <td class="datacell">{$tetel.fej.raktarnev}</td>
            {/if}
            <td class="datacell">{$tetel.fej.keltstr}</td>
            <td class="datacell">{$tetel.fej.teljesitesstr}</td>
            <td class="datacell">{$tetel.fej.szamlanev}</td>
            <td class="datacell textalignright">{bizformat($tetel.tetel.nettoegysar)}</td>
            <td class="datacell textalignright">{bizformat($tetel.tetel.bruttoegysar)}</td>
            <td class="datacell textalignright">{bizformat($tetel.tetel.mennyiseg*$tetel.tetel.irany)}</td>
            <td class="datacell textalignright">{bizformat($tetel.tetel.netto)}</td>
            <td class="datacell textalignright">{bizformat($tetel.tetel.brutto)}</td>
            <td class="datacell textalignright">{bizformat($gmenny)}</td>
            <td>{$tetel.tetel.valtozatnev}</td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="datacell"></td>
            <td class="datacell">Összesen</td>
            <td class="datacell"></td>
            {if ($maintheme != 'mkwcansas')}
            <td class="datacell"></td>
            {/if}
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell textalignright">{bizformat($smenny)}</td>
            <td class="datacell textalignright">{bizformat($snetto)}</td>
            <td class="datacell textalignright">{bizformat($sbrutto)}</td>

        </tr>
        <tr>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            {if ($maintheme != 'mkwcansas')}
            <td class="datacell"></td>
            {/if}
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="bold textalignright">Nyitó:</td>
            <td class="datacell textalignright bold">{bizformat($nyito)}</td>

        </tr>
        <tr>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            {if ($maintheme != 'mkwcansas')}
            <td class="datacell"></td>
            {/if}
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="bold textalignright">Be:</td>
            <td class="datacell textalignright bold">{bizformat($besum)}</td>

        </tr>
        <tr>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            {if ($maintheme != 'mkwcansas')}
            <td class="datacell"></td>
            {/if}
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="bold textalignright">Ki:</td>
            <td class="datacell textalignright bold">{bizformat($kisum)}</td>

        </tr>
        <tr>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            {if ($maintheme != 'mkwcansas')}
            <td class="datacell"></td>
            {/if}
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="datacell"></td>
            <td class="bold textalignright">Záró:</td>
            <td class="datacell textalignright bold">{bizformat($nyito + $besum - $kisum)}</td>

        </tr>
    </tfoot>
</table>
