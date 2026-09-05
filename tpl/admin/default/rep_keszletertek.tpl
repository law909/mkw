{extends "../rep_base.tpl"}

{* A rep.css A4 állóra méretezi a riportokat; ez a lista ehhez túl széles, ezért fekvő
   tájolással és a papír teljes szélességével nyomtat. *}
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

        tr.reteg td {
            font-size: 90%;
            color: #555;
        }

        tr.reteg td:first-child {
            padding-left: 2em;
        }

        @page {
            size: landscape;
            margin: 1cm;
        }
    </style>
{/block}

{block "body"}
    <h4>Készletérték (FIFO)</h4>
    <h5>{$datumstr}</h5>
    <h5>{$raktar}</h5>
    {if $nevfilter}<h5>Termék: {$nevfilter}</h5>{/if}
    {if $termekfa}<h5>Termékfa: {$termekfa}</h5>{/if}
    {if $menetkozben}
        <h5 class="redtext">Múltbeli dátum: az érték menet közben számolva, nem a tárolt.</h5>
    {else}
        <h5>Számítva: {$utolsoszamitas}</h5>
    {/if}
    <p class="noprint"><a href="javascript:window.print()">Nyomtatás</a></p>

    <table>
        <thead>
        <tr>
            <th>Raktár</th>
            <th>Cikkszám</th>
            <th>Termék</th>
            <th>Változat</th>
            <th class="textalignright">Készlet</th>
            <th class="textalignright">Egységérték</th>
            <th class="textalignright">Érték</th>
            <th>Bevét</th>
            <th>Teljesítés</th>
        </tr>
        </thead>
        <tbody>
        {foreach $lista as $elem}
            <tr>
                <td class="cell">{$elem.raktarnev}</td>
                <td class="cell">{$elem.cikkszam}</td>
                <td class="cell">{$elem.termeknev}</td>
                <td class="cell">{$elem.ertek1} {$elem.ertek2}</td>
                <td class="cell textalignright nowrap">{$elem.mennyiseg}</td>
                <td class="cell textalignright nowrap{if $elem.becsult} redtext{/if}">{$elem.egysegertek}</td>
                <td class="cell textalignright nowrap">{$elem.ertek}</td>
                <td class="cell"></td>
                <td class="cell"></td>
            </tr>
            {foreach $elem.retegek as $reteg}
                <tr class="reteg">
                    <td class="cell"></td>
                    <td class="cell"></td>
                    <td class="cell"></td>
                    <td class="cell"></td>
                    <td class="cell textalignright nowrap">{$reteg.mennyiseg}</td>
                    <td class="cell textalignright nowrap{if $reteg.becsult} redtext{/if}">{$reteg.egysegar}</td>
                    <td class="cell"></td>
                    <td class="cell">{$reteg.bizonylatszam}</td>
                    <td class="cell">{$reteg.teljesites}</td>
                </tr>
            {/foreach}
        {/foreach}
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Összesen:</td>
            <td class="textalignright">{$osszmennyiseg}</td>
            <td></td>
            <td class="textalignright">{$osszertek}</td>
            <td></td>
            <td></td>
        </tr>
        </tfoot>
    </table>

    {if $fedezetlen}
        <h4>Fedezetlen készlet</h4>
        <p>Ezekben a csoportokban több a kiadás, mint a bevét – nincs mit értékelni.
            A sorok javítási munkalistának valók.</p>
        <table>
            <thead>
            <tr>
                <th>Raktár</th>
                <th>Cikkszám</th>
                <th>Termék</th>
                <th>Változat</th>
                <th class="textalignright">Hiány</th>
            </tr>
            </thead>
            <tbody>
            {foreach $fedezetlen as $elem}
                <tr>
                    <td class="cell">{$elem.raktarnev}</td>
                    <td class="cell">{$elem.cikkszam}</td>
                    <td class="cell">{$elem.termeknev}</td>
                    <td class="cell">{$elem.ertek1} {$elem.ertek2}</td>
                    <td class="cell textalignright nowrap redtext">{$elem.mennyiseg}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}

    <p>Nyomtatva: {$printdatum}</p>
{/block}
