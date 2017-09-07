{extends "../rep_base.tpl"}

{block "body"}
    <h4>Munkaidő lista</h4>
    <h5>{$tolstr} - {$igstr}</h5>
    <h5>{$dolgozonev}</h5>
    <table>
        <thead>
        <tr>
            <th>Dátum</th>
            <th>Belépés</th>
            <th>Kilépés</th>
            <th></th>
            <th class="textalignright">Eltöltött idő (perc)</th>
        </tr>
        </thead>
        <tbody>
        {$sum = 0}
        {foreach $lista as $elem}
            {$sum = $sum + $elem.ido}
            <tr>
                <td class="cell">{$elem.datum}</td>
                <td class="cell">{$elem.belepes}</td>
                <td class="cell">{$elem.kilepes}</td>
                <td class="cell">{$elem.jelenlettipus}</td>
                <td class="cell textalignright">{$elem.ido}</td>
            </tr>
        {/foreach}
        {$ora = intdiv($sum, 60)}
        {$perc = $sum % 60}
        {if ($perc >= 15 && $perc < 45)}
            {$ora = $ora + 0.5}
        {elseif ($perc >= 45)}
            {$ora = $ora + 1}
        {/if}
        </tbody>
        <tfoot>
            <tr>
                <th>Összesen</th>
                <th></th>
                <th></th>
                <th></th>
                <th class="textalignright">{$sum} perc</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="textalignright">{$ora} óra</th>
            </tr>
        </tfoot>
    </table>
{/block}