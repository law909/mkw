<table>
    <thead>
    <tr>
        <th class="headercell">{at('Tag neve')}</th>
        <th class="headercell">{at('Email')}</th>
        <th class="headercell">{at('Tanár neve')}</th>
        <th class="headercell">{at('Tanár egyéb')}</th>
        <th class="headercell">{at('Helyszín')}</th>
        <th class="headercell">{at('Mikor')}</th>
        <th class="headercell textalignright">{at('Óraszám')}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $tetelek as $key => $tetel}
        <tr>
            <td class="datacell">{$tetel.nev}</td>
            <td class="datacell">{$tetel.email}</td>
            <td class="datacell">{$tetel.tanar}</td>
            <td class="datacell">{$tetel.tanaregyeb}</td>
            <td class="datacell">{$tetel.helyszin}</td>
            <td class="datacell">{$tetel.mikor}</td>
            <td class="datacell textalignright">{$tetel.oraszam}</td>
        </tr>
    {/foreach}
    </tbody>
</table>