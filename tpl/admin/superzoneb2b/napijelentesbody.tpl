<div class="js-napijelentesbody">
    {$sumsum = 0}
    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
        <thead>
            <tr>
                <th class="headercell"></th>
                <th class="headercell">Mennyiség</th>
                <th class="headercell">Nettó HUF</th>
                <th class="headercell">Bruttó HUF</th>
            </tr>
        </thead>
        <tbody>
            {$summenny = 0}
            {$sumnetto = 0}
            {$sumbrutto = 0}
            {foreach $napijelentes['napijelentes'] as $elem}
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
        {$sumsum = $sumsum + $sumbrutto}
    </table>
    {if (haveJog(20))}
    <div>Nagyker forgalom</div>
    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
        <thead>
            <tr>
                <th class="headercell">Partner</th>
                <th class="headercell">Partner cím</th>
                <th class="headercell">Bruttó HUF</th>
            </tr>
        </thead>
        <tbody>
            {$sumbrutto = 0}
            {foreach $napijelentes['nagykerforgalom'] as $elem}
                {$sumbrutto = $sumbrutto + $elem.bruttohuf}
                <tr>
                    <td class="datacell">{$elem.partnernev}</td>
                    <td class="datacell">{$elem.partnerirszam} {$elem.partnervaros}, {$elem.partnerutca} {$elem.partnerhazszam}</td>
                    <td class="textalignright datacell">{bizformat($elem.bruttohuf)}</td>
                </tr>
            {/foreach}
        </tbody>
        <tfoot>
            <tr>
                <td class="headercell">Nagyker összesen</td>
                <td></td>
                <td class="textalignright headercell">{bizformat($sumbrutto)}</td>
            </tr>
        </tfoot>
        {$sumsum = $sumsum + $sumbrutto}
    </table>
    <div>Utánvétes forgalom</div>
    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
        <thead>
        <tr>
            <th class="headercell">Partner</th>
            <th class="headercell">Partner cím</th>
            <th class="headercell">Bruttó HUF</th>
        </tr>
        </thead>
        <tbody>
        {$sumbrutto = 0}
        {foreach $napijelentes['utanvetesforgalom'] as $elem}
            {$sumbrutto = $sumbrutto + $elem.bruttohuf}
            <tr>
                <td class="datacell">{$elem.partnernev}</td>
                <td class="datacell">{$elem.partnerirszam} {$elem.partnervaros}, {$elem.partnerutca} {$elem.partnerhazszam}</td>
                <td class="textalignright datacell">{bizformat($elem.bruttohuf)}</td>
            </tr>
        {/foreach}
        </tbody>
        <tfoot>
        <tr>
            <td class="headercell">Utánvét összesen</td>
            <td></td>
            <td class="textalignright headercell">{bizformat($sumbrutto)}</td>
        </tr>
        </tfoot>
        {$sumsum = $sumsum + $sumbrutto}
    </table>
    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
        <tbody>
            <tr>
                <td class="datacell">Mindösszesen</td>
                <td class="textalignright datacell">{bizformat($sumsum)}</td>
            </tr>
        </tbody>
    </table>
    <div>Nem HUF forgalom</div>
    <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
        <thead>
        <tr>
            <th class="headercell">Partner</th>
            <th class="headercell">Partner cím</th>
            <th class="headercell">Bruttó</th>
        </tr>
        </thead>
        <tbody>
        {$cikl = 0}
        {while ($cikl < count($napijelentes['nemhufforgalom']))}
            {$valuta = $napijelentes['nemhufforgalom'][$cikl]['valutanemnev']}
            {$valutanev = $valuta}
            {$sumbrutto = 0}
            {while ($valuta === $napijelentes['nemhufforgalom'][$cikl]['valutanemnev']) && ($cikl < count($napijelentes['nemhufforgalom']))}
                {$elem = $napijelentes['nemhufforgalom'][$cikl]}
                {$sumbrutto = $sumbrutto + $elem.brutto}
                <tr>
                    <td class="datacell">{$elem.partnernev}</td>
                    <td class="datacell">{$elem.partnerirszam} {$elem.partnervaros}, {$elem.partnerutca} {$elem.partnerhazszam}</td>
                    <td class="textalignright datacell">{bizformat($elem.brutto)} {$valuta}</td>
                </tr>
                {$valuta = $napijelentes['nemhufforgalom'][$cikl]['valutanemnev']}
                {$cikl = $cikl + 1}
            {/while}
            <tr>
                <td class="headercell">{$valutanev} összesen</td>
                <td></td>
                <td class="textalignright headercell">{bizformat($sumbrutto)} {$valutanev}</td>
            </tr>
        {/while}
        </tbody>
        <tfoot>
        </tfoot>
    </table>
    {/if}
</div>