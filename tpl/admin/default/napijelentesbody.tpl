<table class="js-napijelentesbody">
    <thead>
        <tr>
            <th class="headercell">{$napijelentesdatum}</th>
            <th class="headercell">Mennyiség</th>
            <th class="headercell">Nettó HUF</th>
            <th class="headercell">Bruttó HUF</th>
        </tr>
    </thead>
    <tbody>
        {$summenny = 0}
        {$sumnetto = 0}
        {$sumbrutto = 0}
        {foreach $napijelenteslista as $elem}
            {$summenny = $summenny + $elem.mennyiseg}
            {$sumnetto = $sumnetto + $elem.netto}
            {$sumbrutto = $sumbrutto + $elem.brutto}
            <tr>
                <td class="datacell">{$elem.caption}</td>
                <td class="textalignright datacell">{bizformat($elem.mennyiseg, 0)}</td>
                <td class="textalignright datacell">{bizformat($elem.netto)}</td>
                <td class="textalignright datacell">{bizformat($elem.brutto)}</td>
            </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="headercell">Összesen</td>
            <td class="textalignright headercell">{bizformat($summenny, 0)}</td>
            <td class="textalignright headercell">{bizformat($sumnetto)}</td>
            <td class="textalignright headercell">{bizformat($sumbrutto)}</td>
        </tr>
    </tfoot>
</table>
