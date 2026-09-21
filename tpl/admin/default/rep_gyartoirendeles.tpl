{extends "../rep_base.tpl"}

{* A rep.css A4 állóra méretezi a riportokat; ez a lista ehhez túl széles, ezért fekvő
   tájolással és a papír teljes szélességével nyomtat, a cellák pedig nem törnek sorba. *}
{block "inhead"}
    <style>
        body {
            width: auto;
            max-width: none;
            margin: 0 1cm;
        }

        table {
            width: 100%;
        }

        table th,
        table td {
            white-space: nowrap;
        }

        @page {
            size: landscape;
            margin: 1cm;
        }
    </style>
{/block}

{block "body"}
    <h4>Gyártói rendelés</h4>
    <h5>{$datumstr}</h5>
    <h5>{$raktar}</h5>
    {if ($gyarto)}
        <h5>Gyártó: {$gyarto}</h5>
    {/if}
    {if ($termekfa)}
        <h5>Termékfa: {$termekfa}</h5>
    {/if}
    <h5>Rendelendő = optimális készlet − szabad készlet − érkezik; szabad készlet = {$szabadkeszletfelirat}</h5>
    <table>
        <thead>
        <tr>
            <th>Cikkszám</th>
            <th>Vonalkód</th>
            <th>Termék</th>
            <th>Változat</th>
            <th class="textalignright">Készlet</th>
            <th class="textalignright">Foglalt</th>
            <th class="textalignright">Szabad</th>
            <th class="textalignright">Érkezik</th>
            <th class="textalignright">Opt. készlet</th>
            <th class="textalignright">Rendelendő</th>
        </tr>
        </thead>
        <tbody>
        {$rendelendosum = 0}
        {foreach $lista as $elem}
            <tr>
                <td class="cell">{$elem.cikkszam}</td>
                <td class="cell">{$elem.vonalkod}</td>
                <td class="cell">{$elem.termeknev}</td>
                <td class="cell">{$elem.ertek1} {$elem.ertek2}</td>
                <td class="cell textalignright nowrap">{$elem.keszlet|string_format:"%g"}</td>
                <td class="cell textalignright nowrap">{$elem.foglalt|string_format:"%g"}</td>
                <td class="cell textalignright nowrap">{$elem.szabadkeszlet|string_format:"%g"}</td>
                <td class="cell textalignright nowrap">{$elem.erkezik|string_format:"%g"}</td>
                <td class="cell textalignright nowrap">{$elem.optkeszlet|string_format:"%g"}</td>
                <td class="cell textalignright nowrap redtext">{$elem.rendelendo|string_format:"%g"}</td>
            </tr>
            {$rendelendosum = $rendelendosum + $elem.rendelendo}
        {/foreach}
        </tbody>
        <tfoot class="pagenum">
        <tr>
            <td class="printdatum">{$printdatum}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tfoot>
        <tfoot class="sum">
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Összesen:</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="textalignright">{$rendelendosum|string_format:"%g"}</td>
        </tr>
        </tfoot>
    </table>
{/block}
