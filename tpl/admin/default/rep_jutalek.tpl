{extends "rep_base.tpl"}

{block "body"}
    <h4 xmlns="http://www.w3.org/1999/html">Jutalék elszámolás</h4>
    <h5>{$tolstr} - {$igstr}</h5>
    <h5>{implode(', ', $cimkenevek)}</h5>
    <table>
        <thead>
        <tr>
            <th>Esedékes.</th>
            <th>Befizetés dátuma</th>
            <th>Bizonylatszám</th>
            <th>Partner</th>
            <th>Üzletkötő</th>
            <th></th>
            <th class="textalignright">Beérkezett összeg</th>
            <th class="textalignright">Jutalék %</th>
            <th class="textalignright">Jutalék</th>
        </tr>
        <tr>
            <th>Payment due</th>
            <th>Date of income</th>
            <th>Invoice nr.</th>
            <th>Customer</th>
            <th>Agent</th>
            <th></th>
            <th class="textalignright">Income</th>
            <th class="textalignright">Comission %</th>
            <th class="textalignright">Comission</th>
        </tr>
        </thead>
        <tbody>
        {$sumincome = 0}
        {$sumcom = 0}
        {foreach $lista as $elem}
            <tr>
                <td class="cell nowrap">{$elem.hivatkozottdatum}</td>
                <td class="cell nowrap">{$elem.datum}</td>
                <td class="cell">{$elem.hivatkozottbizonylat}</td>
                <td class="cell">{$elem.partnernev}</td>
                <td class="cell">{$elem.uzletkotonev}</td>
                <td class="cell">{$elem.type}</td>
                <td class="cell textalignright nowrap">{bizformat($elem.brutto)} {$elem.valutanemnev}</td>
                <td class="cell textalignright">{bizformat($elem.uzletkotojutalek)} %</td>
                <td class="cell textalignright nowrap">{bizformat($elem.jutalekosszeg)} {$elem.valutanemnev}</td>
            </tr>
            {$sumincome = $sumincome + $elem.brutto}
            {$sumcom = $sumcom + $elem.jutalekosszeg}
        {/foreach}
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td class="cell textalignright nowrap">{bizformat($sumincome)} {$elem.valutanemnev}</td>
            <td></td>
            <td class="cell textalignright nowrap">{bizformat($sumcom)} {$elem.valutanemnev}</td>
        </tr>
        </tfoot>
    </table>
{/block}