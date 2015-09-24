{extends "rep_base.tpl"}

{block "body"}
<h4>{$raktarnev}-ban nincs, más raktárakban van</h4>
<h5>{$datum}</h5>
<table>
    <thead>
        <tr>
            <th>Cikkszám</th>
            <th>Név</th>
            <th>Változat</th>
            <th>Raktár</th>
            <th>Készlet</th>
        </tr>
    </thead>
    <tbody>
        {foreach $lista as $elem}
        <tr>
            <td class="cell">{$elem.cikkszam}</td>
            <td class="cell">{$elem.nev}</td>
            <td class="cell">{$elem.valtozatnev}</td>
            <td class="cell">{$elem.raktarnev}</td>
            <td class="cell textalignright">{$elem.keszlet}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
{/block}