<table>
    <thead>
    <tr>
        <th class="headercell">{at('Megrendelés')}</th>
        <th class="headercell">{at('Partner')}</th>
        <th class="headercell">{at('Kelt')}</th>
        <th class="headercell textalignright">{at('Érték')}</th>
        <th class="headercell">{at('Kapcsolt bizonylatok')}</th>
        <th class="headercell textalignright">{at('Fizetve')}</th>
        <th class="headercell textalignright">{at('Még fizetendő')}</th>
    </tr>
    </thead>
    <tbody>
    {$sertek = 0}
    {$sfizetve = 0}
    {$shatravan = 0}
    {foreach $sorok as $sor}
        {$sertek = $sertek + $sor.brutto}
        {$sfizetve = $sfizetve + $sor.osszesfizetve}
        {$shatravan = $shatravan + $sor.hatravan}
        <tr>
            <td class="datacell">
                {if ($sor.listaurl)}<a href="{$sor.listaurl}" target="_blank" title="{at('Ugrás a bizonylathoz')}">{$sor.id}</a>{else}{$sor.id}{/if}
            </td>
            <td class="datacell">{$sor.partnernev|escape}</td>
            <td class="datacell">{$sor.keltstr}</td>
            <td class="datacell textalignright">{bizformat($sor.brutto)} {$sor.valutanemnev}</td>
            <td class="datacell">
                {if ($sor.penztmozgat)}
                    <div>{at('Megrendelés')}: {at('fizetve')} {bizformat($sor.fizetve)}, {at('egyenleg')} {bizformat($sor.egyenleg)}</div>
                {/if}
                {foreach $sor.kapcsoltak as $k}
                    <div>
                        {$k.tipusnev}
                        {if ($k.listaurl)}<a href="{$k.listaurl}" target="_blank" title="{at('Ugrás a bizonylathoz')}">{$k.id}</a>{else}{$k.id}{/if}
                        ({$k.keltstr}): {bizformat($k.brutto)} {$k.valutanemnev}
                        {if ($k.penztmozgat)}, {at('fizetve')} {bizformat($k.fizetve)}, {at('egyenleg')} {bizformat($k.egyenleg)}{else}, {at('pénzt nem mozgat')}{/if}
                    </div>
                {/foreach}
            </td>
            <td class="datacell textalignright">{bizformat($sor.osszesfizetve)}</td>
            <td class="datacell textalignright{if ($sor.hatravan > 0)} kiegyenlitetlen{/if}">{bizformat($sor.hatravan)}</td>
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        <td class="datacell">{at('Összesen')}</td>
        <td class="datacell"></td>
        <td class="datacell"></td>
        <td class="datacell textalignright">{bizformat($sertek)}</td>
        <td class="datacell"></td>
        <td class="datacell textalignright">{bizformat($sfizetve)}</td>
        <td class="datacell textalignright">{bizformat($shatravan)}</td>
    </tr>
    </tfoot>
</table>
