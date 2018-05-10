<table>
    <thead>
    <tr>
        <th class="headercell textalignleft">{at('Bizonylatszám')}</th>
        <th class="headercell textalignleft">{at('Státusz')}</th>
        <th class="headercell textalignleft">{at('Kelt')}</th>
        <th class="headercell textalignleft">{at('Teljesítés')}</th>
        <th class="headercell textalignleft">{at('Partner')}</th>
        <th class="headercell textalignleft">{at('Partner címe')}</th>
        <th class="headercell textalignleft">{at('Cikkszám')}</th>
        <th class="headercell textalignleft">{at('Termék')}</th>
        <th class="headercell textalignright">{at('Mennyiség')}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $tetelek as $tetel}
        <tr>
            <td class="cell">
                {$tetel.id}
            </td>
            <td class="cell">
                {$tetel.statusznev}
            </td>
            <td class="cell">
                {$tetel.kelt}
            </td>
            <td class="cell">
                {$tetel.teljesites}
            </td>
            <td class="cell">
                {$tetel.partnernev}
            </td>
            <td class="cell">
                {$tetel.partnerirszam} {$tetel.partnervaros} {$tetel.partnerutca} {$tetel.partnerhazszam}
            </td>
            <td class="cell">
                {$tetel.cikkszam}
            </td>
            <td class="cell">{$tetel.nev} {$tetel.ertek1} {$tetel.ertek2}</td>
            <td class="cell textalignright">{bizformat($tetel.mennyiseg)}</td>
        </tr>
    {/foreach}
    </tbody>
</table>