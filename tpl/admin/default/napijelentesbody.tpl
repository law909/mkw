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
                <td class="textalignright datacell">{number_format($elem.mennyiseg, 0, ',', ' ')}</td>
                <td class="textalignright datacell">{number_format($elem.netto, 2, ',', ' ')}</td>
                <td class="textalignright datacell">{number_format($elem.brutto, 2, ',', ' ')}</td>
            </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr>
            <td class="headercell">Összesen</td>
            <td class="textalignright headercell">{number_format($summenny, 0, ',', ' ')}</td>
            <td class="textalignright headercell">{number_format($sumnetto, 2, ',', ' ')}</td>
            <td class="textalignright headercell">{number_format($sumbrutto, 2, ',', ' ')}</td>
        </tr>
    </tfoot>
</table>
