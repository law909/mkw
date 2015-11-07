{extends "rep_base.tpl"}

{block "body"}
    <h4>Jutalék elszámolás</h4>
    <h5>{$tolstr} - {$igstr}</h5>
    <h5>{implode(', ', $cimkenevek)}</h5>
    <table>
        <thead>
        <tr>
            <th>Dátum</th>
            <th>Partner név</th>
            <th class="textalignright">Összeg</th>
        </tr>
        </thead>
        <tbody>
        {foreach $lista as $elem}
            <tr>
                <td class="cell">{$elem.datum}</td>
                <td class="cell">{$elem.partnernev}</td>
                <td class="cell textalignright">{bizformat($elem.osszeg)} {$elem.valutanem}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>Valutanem</th>
            <th class="textalignright">Összeg</th>
        </tr>
        </thead>
        <tbody>
        {foreach $valutanemosszesito as $elem}
            <tr>
                <td class="cell">{$elem.nev}</td>
                <td class="cell textalignright">{bizformat($elem.osszeg)}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/block}