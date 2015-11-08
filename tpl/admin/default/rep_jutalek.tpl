{extends "rep_base.tpl"}

{block "body"}
    <h4>Jutalék elszámolás</h4>
    <h5>{$tolstr} - {$igstr}</h5>
    <h5>{implode(', ', $cimkenevek)}</h5>
    <table>
        <thead>
        <tr>
            <th>Esedékesség</th>
            <th>Befizetés dátuma</th>
            <th>Bizonylatszám</th>
            <th>Partner</th>
            <th>Üzletkötő</th>
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
            <th class="textalignright">Income</th>
            <th class="textalignright">Comission %</th>
            <th class="textalignright">Comission</th>
        </tr>
        </thead>
        <tbody>
        {foreach $lista as $elem}
            <tr>
                <td class="cell">{$elem.hivatkozottdatum}</td>
                <td class="cell">{$elem.datum}</td>
                <td class="cell">{$elem.hivatkozottbizonylat}</td>
                <td class="cell">{$elem.partnernev}</td>
                <td class="cell">{$elem.uzletkotonev}</td>
                <td class="cell textalignright">{bizformat($elem.brutto)} {$elem.valutanemnev}</td>
                <td class="cell textalignright">{bizformat($elem.uzletkotojutalek)} %</td>
                <td class="cell textalignright">{bizformat($elem.brutto * $elem.uzletkotojutalek / 100)} {$elem.valutanemnev}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/block}