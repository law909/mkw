{extends "rep_base.tpl"}

{block "body"}
    <h4 xmlns="http://www.w3.org/1999/html">Készlet</h4>
    <h5>{$datumstr}</h5>
    <h5>{$raktar}</h5>
    <table>
        <thead>
        <tr>
            <th>Cikkszám</th>
            <th>Termék</th>
            <th>Változat</th>
            <th class="textalignright">Készlet</th>
        </tr>
        </thead>
        <tbody>
        {foreach $lista as $elem}
            <tr>
                <td class="cell">{$elem.cikkszam}</td>
                <td class="cell">{$elem.termeknev}</td>
                <td class="cell">{$elem.ertek1} {$elem.ertek2}</td>
                <td class="cell textalignright nowrap">{$elem.keszlet}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/block}