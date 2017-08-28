{extends "../rep_base.tpl"}

{block "body"}
    <h4 xmlns="http://www.w3.org/1999/html">Készlet</h4>
    <h5>{$datumstr}</h5>
    <h5>{$raktar}</h5>
    <h5>{$nevfilter}</h5>
    <h5>{$foglalasstr}</h5>
    <h5>{$arsav}</h5>
    <table>
        <thead>
        <tr>
            <th>Cikkszám</th>
            <th>Termék</th>
            <th>Változat</th>
            <th class="textalignright">Készlet</th>
            <th class="textalignright">Ár</th>
        </tr>
        </thead>
        <tbody>
        {$sum = 0}
        {foreach $lista as $elem}
            <tr>
                <td class="cell">{$elem.cikkszam}</td>
                <td class="cell">{$elem.termeknev}</td>
                <td class="cell">{$elem.ertek1} {$elem.ertek2}</td>
                <td class="cell textalignright nowrap">{$elem.keszlet}</td>
                <td class="cell textalignright nowrap">{$elem.ar}</td>
            </tr>
            {$sum = $sum + $elem.keszlet}
        {/foreach}
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td>Összesen:</td>
            <td class="textalignright">{$sum}</td>
            <td></td>
        </tr>
        </tfoot>
    </table>
{/block}