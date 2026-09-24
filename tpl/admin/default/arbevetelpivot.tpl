{$labelcols = ($termeksor) ? 3 : 1}
{$colspan = $labelcols + count($periods) + 1}
<div class="arbevetel-pivotwrap">
<table class="arbevetel-table arbevetel-pivot">
    <caption>{$valueheader|escape}</caption>
    <thead>
    <tr>
        {if ($termeksor)}
            <th class="headercell">{at('Cikkszám')}</th>
            <th class="headercell">{at('Név')}</th>
            <th class="headercell">{at('Változat')}</th>
        {else}
            <th class="headercell">{if ($rowlevel)}{$rowlevel.caption}{/if}</th>
        {/if}
        {foreach $periods as $_period}
            <th class="headercell textalignright">{$_period}</th>
        {/foreach}
        <th class="headercell textalignright">{at('Összesen')}</th>
    </tr>
    </thead>
    {if ($termeksor || $rowlevel)}
    <tbody>
    {foreach $items as $item}
        {if ($item.type == 'header')}
            <tr class="arbevetel-header arbevetel-header-{$item.level}">
                <td class="datacell arbevetel-level-{$item.level}" colspan="{$colspan}">{$item.label|escape}</td>
            </tr>
        {else}
            {if ($item.type == 'subtotal')}
                {$_values = $item}
                <tr class="arbevetel-subtotal">
                    <td class="datacell arbevetel-level-{$item.level}" colspan="{$labelcols}">{$item.label|escape} {at('összesen')}</td>
            {else}
                {$_values = $item.row}
                <tr class="arbevetel-row">
                {if ($termeksor)}
                    <td class="datacell arbevetel-level-{$item.level}">{$item.row.cikkszam|escape}</td>
                    <td class="datacell">{$item.row.nev|escape}</td>
                    <td class="datacell">{$item.row.ertek1|escape} {$item.row.ertek2|escape}</td>
                {else}
                    <td class="datacell arbevetel-level-{$item.level}">{$item.row[$rowlevel.label]|escape}</td>
                {/if}
            {/if}
                {foreach $periods as $_i => $_period}
                    {$_key = 'p'|cat:$_i}
                    {$_v = $_values[$_key]}
                    <td class="datacell textalignright">{if ($_v != 0)}{bizformat($_v, $decimals)}{/if}</td>
                {/foreach}
                <td class="datacell textalignright arbevetel-pivot-total">{bizformat($_values.ertek, $decimals)}</td>
            </tr>
        {/if}
    {/foreach}
    </tbody>
    {/if}
    <tfoot>
    <tr>
        <td class="datacell" colspan="{$labelcols}">{if ($levelcount || $rowlevel || $termeksor)}{at('Mindösszesen')}{else}{at('Összesen')}{/if}</td>
        {foreach $coltotals as $_total}
            <td class="datacell textalignright">{bizformat($_total, $decimals)}</td>
        {/foreach}
        <td class="datacell textalignright">{bizformat($total, $decimals)}</td>
    </tr>
    </tfoot>
</table>
</div>
