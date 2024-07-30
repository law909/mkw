{extends "../rep_base.tpl"}

{block "body"}
    <h4>Beérkezett pénz / Income</h4>
    <h5>{$tolstr} - {$igstr}</h5>
    <h5>{$cimkenevek}</h5>
    <h5>{$bankszamlaszam}</h5>
    <table>
        <thead>
        <tr>
            <th>Dátum</th>
            <th>Bizonylat</th>
            <th>Partner név</th>
            <th class="textalignright">Összeg</th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Document no.</th>
            <th>Partner name</th>
            <th class="textalignright">Value</th>
        </tr>
        </thead>
        <tbody>
        {foreach $lista as $elem}
            <tr>
                <td class="cell">{$elem.datum}</td>
                <td class="cell">{$elem.hivatkozottbizonylat}</td>
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
        <tr>
            <th>Currency</th>
            <th class="textalignright">Value</th>
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