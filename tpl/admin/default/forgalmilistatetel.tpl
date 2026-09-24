<table class="arbevetel-table">
    <thead>
    <tr>
        <th class="headercell">{at('Cikkszám')}</th>
        <th class="headercell">{at('Név')}</th>
        <th class="headercell">{at('Változat')}</th>
        <th class="headercell textalignright">{at('Mennyiség')}</th>
        <th class="headercell">{at('ME')}</th>
        <th class="headercell textalignright">{$valueheader|escape}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $items as $item}
        {if ($item.type == 'header')}
            <tr class="arbevetel-header arbevetel-header-{$item.level}">
                <td class="datacell arbevetel-level-{$item.level}" colspan="6">{$item.label|escape}</td>
            </tr>
        {elseif ($item.type == 'subtotal')}
            <tr class="arbevetel-subtotal">
                <td class="datacell arbevetel-level-{$item.level}" colspan="3">{$item.label|escape} {at('összesen')}</td>
                <td class="datacell textalignright">{bizformat($item.mennyiseg, $mennyisegdecimals)}</td>
                <td class="datacell"></td>
                <td class="datacell textalignright">{bizformat($item.ertek, $decimals)}</td>
            </tr>
        {else}
            <tr class="arbevetel-row">
                <td class="datacell arbevetel-level-{$item.level}">{$item.row.cikkszam|escape}</td>
                <td class="datacell">{$item.row.nev|escape}</td>
                <td class="datacell">{$item.row.ertek1|escape} {$item.row.ertek2|escape}</td>
                <td class="datacell textalignright">{bizformat($item.row.mennyiseg, $mennyisegdecimals)}</td>
                <td class="datacell">{$item.row.me|escape}</td>
                <td class="datacell textalignright">{bizformat($item.row.ertek, $decimals)}</td>
            </tr>
        {/if}
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        <td class="datacell" colspan="3">{if ($levelcount)}{at('Mindösszesen')}{else}{at('Összesen')}{/if}</td>
        <td class="datacell textalignright">{bizformat($osszesenmennyiseg, $mennyisegdecimals)}</td>
        <td class="datacell"></td>
        <td class="datacell textalignright">{bizformat($osszesen, $decimals)}</td>
    </tr>
    </tfoot>
</table>
