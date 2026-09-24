{$colspan = ($rowlevel) ? 3 : 1}
<table class="arbevetel-table">
    <thead>
    <tr>
        {if ($rowlevel)}<th class="headercell">{$rowlevel.caption}</th>{/if}
        <th class="headercell textalignright">{$valueheader|escape}</th>
        {if ($rowlevel)}<th class="headercell textalignright">{at('Arány')}</th>{/if}
    </tr>
    </thead>
    {if ($rowlevel)}
    <tbody>
    {foreach $items as $item}
        {if ($item.type == 'header')}
            <tr class="arbevetel-header arbevetel-header-{$item.level}">
                <td class="datacell arbevetel-level-{$item.level}" colspan="{$colspan}">{$item.label|escape}</td>
            </tr>
        {elseif ($item.type == 'subtotal')}
            <tr class="arbevetel-subtotal">
                <td class="datacell arbevetel-level-{$item.level}">{$item.label|escape} {at('összesen')}</td>
                <td class="datacell textalignright">{bizformat($item.ertek, $decimals)}</td>
                <td class="datacell textalignright">{if (isset($item.share))}{bizformat($item.share, 1)} %{/if}</td>
            </tr>
        {else}
            <tr class="arbevetel-row">
                <td class="datacell arbevetel-level-{$item.level}">{$item.row[$rowlevel.label]|escape}</td>
                <td class="datacell textalignright">{bizformat($item.ertek, $decimals)}</td>
                <td class="datacell textalignright">{if (isset($item.share))}{bizformat($item.share, 1)} %{/if}</td>
            </tr>
        {/if}
    {/foreach}
    </tbody>
    {/if}
    <tfoot>
    <tr>
        {if ($rowlevel)}<td class="datacell">{if ($levelcount)}{at('Mindösszesen')}{else}{at('Összesen')}{/if}</td>{/if}
        <td class="datacell textalignright">{if (!$rowlevel)}{at('Összesen')}: {/if}{bizformat($osszesen, $decimals)}</td>
        {if ($rowlevel)}<td class="datacell"></td>{/if}
    </tr>
    </tfoot>
</table>
