{extends "../rep_base.tpl"}

{block "body"}
    <h4 xmlns="http://www.w3.org/1999/html">Időszaki pénztárjelentés</h4>
    <h5>{$tolstr} - {$igstr}</h5>
    <h5>{$penztarnev}</h5>
    <table>
        <thead>
        <tr>
            <th>Dátum</th>
            <th>Bizonylatszám</th>
            <th>Partner</th>
            <th class="textalignright">Befizetés</th>
            <th class="textalignright">Kifizetés</th>
            <th class="textalignright">Egyenleg</th>
        </tr>
        </thead>
        <tbody>
        {$be = 0}
        {$ki = 0}
        {$egyenleg = 0}
        {foreach $lista as $elem}
            <tr>
                <td class="cell nowrap">{$elem.kelt}</td>
                <td class="cell">{$elem.id}</td>
                <td class="cell">{$elem.partnernev}</td>
                {if ($elem.kelt)}
                {if ($elem.irany > 0)}
                    {$be = $be + $elem.brutto}
                    {$egyenleg = $egyenleg + $elem.brutto}
                    <td class="cell textalignright nowrap">{bizformat($elem.brutto)}</td>
                    <td class="cell textalignright nowrap">{bizformat(0)}</td>
                {else}
                    {$ki = $ki + $elem.brutto}
                    {$egyenleg = $egyenleg - $elem.brutto}
                    <td class="cell textalignright nowrap">{bizformat(0)}</td>
                    <td class="cell textalignright nowrap">{bizformat($elem.brutto)}</td>
                {/if}
                {else}
                    {$egyenleg = $egyenleg + $elem.brutto}
                    <td class="cell textalignright nowrap">{bizformat(0)}</td>
                    <td class="cell textalignright nowrap">{bizformat(0)}</td>
                {/if}
                <td class="cell textalignright nowrap">{bizformat($egyenleg)}</td>
            </tr>
        {/foreach}
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td>Total</td>
            <td class="cell textalignright nowrap">{bizformat($be)}</td>
            <td class="cell textalignright nowrap">{bizformat($ki)}</td>
            <td class="cell textalignright nowrap">{bizformat($egyenleg)}</td>
        </tr>
        </tfoot>
    </table>
{/block}