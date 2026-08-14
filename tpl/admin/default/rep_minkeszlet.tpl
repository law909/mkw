{extends "../rep_base.tpl"}

{block "body"}
    <h4>Minimum készlet alatt</h4>
    <h5>{$datumstr}</h5>
    <h5>{$raktar}</h5>
    {if ($masikraktar)}
        <h5>Ebből a raktárból kiszolgálható: {$masikraktar}</h5>
    {/if}
    {if ($gyarto)}
        <h5>Gyártó: {$gyarto}</h5>
    {/if}
    {if ($termekfa)}
        <h5>Termékfa: {$termekfa}</h5>
    {/if}
    {if ($uselimit)}
        <h5>A minimum készlet helyett figyelt készlet: {$limit|string_format:"%g"}</h5>
    {/if}
    <table>
        <thead>
        <tr>
            <th>Cikkszám</th>
            <th>Vonalkód</th>
            <th>Termék</th>
            <th>Változat</th>
            <th class="textalignright">Készlet</th>
            <th class="textalignright">Min. készlet</th>
            <th class="textalignright">Hiány</th>
            {if ($masikraktar)}
                <th class="textalignright">{$masikraktar}</th>
            {/if}
        </tr>
        </thead>
        <tbody>
        {$hianysum = 0}
        {foreach $lista as $elem}
            <tr>
                <td class="cell">{$elem.cikkszam}</td>
                <td class="cell">{$elem.vonalkod}</td>
                <td class="cell">{$elem.termeknev}</td>
                <td class="cell">{$elem.ertek1} {$elem.ertek2}</td>
                <td class="cell textalignright nowrap">{$elem.keszlet|string_format:"%g"}</td>
                <td class="cell textalignright nowrap">{$elem.minkeszlet|string_format:"%g"}</td>
                <td class="cell textalignright nowrap redtext">{$elem.hiany|string_format:"%g"}</td>
                {if ($masikraktar)}
                    <td class="cell textalignright nowrap">{$elem.masikkeszlet|string_format:"%g"}</td>
                {/if}
            </tr>
            {$hianysum = $hianysum + $elem.hiany}
        {/foreach}
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Összesen:</td>
            <td></td>
            <td></td>
            <td class="textalignright">{$hianysum|string_format:"%g"}</td>
            {if ($masikraktar)}
                <td></td>
            {/if}
        </tr>
        </tfoot>
    </table>
{/block}
