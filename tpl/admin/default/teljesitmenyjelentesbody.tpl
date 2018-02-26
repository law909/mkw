<div class="js-teljesitmenyjelentesbody">
<table>
    <thead>
        <tr>
            <th></th>
            <th>Megrendelések száma</th>
            <th>Változás</th>
            <th>Számlák száma</th>
            <th>Változás</th>
            <th>Teljesítési ráta</th>
            <th>Megrendelés/nap</th>
            <th>Számla/nap</th>
        </tr>
    </thead>
    <tbody>
    {foreach $tjlista as $sor}
        <tr>
            <td>{$sor.ev}</td>
            <td class="textalignright">{$sor.megrendelesdb} db</td>
            <td class="textalignright">{bizformat($sor.megrendelesdbvalt)} %</td>
            <td class="textalignright">{$sor.szamladb} db</td>
            <td class="textalignright">{bizformat($sor.szamladbvalt)} %</td>
            <td class="textalignright">{bizformat($sor.teljratadb)} %</td>
            <td class="textalignright">{bizformat($sor.megrendelesdbpernap)} db</td>
            <td class="textalignright">{bizformat($sor.szamladbpernap)} db</td>
        </tr>
    {/foreach}
    </tbody>
</table>
<div class="matt-hseparator"></div>
<table>
    <thead>
    <tr>
        <th></th>
        <th>Megrendelés nettó</th>
        <th>Változás</th>
        <th>Számla nettó</th>
        <th>Változás</th>
        <th>Teljesítési ráta</th>
        <th>Megrendelés nettó/nap</th>
        <th>Számla nettó/nap</th>
    </tr>
    </thead>
    <tbody>
    {foreach $tjlista as $sor}
        <tr>
            <td>{$sor.ev}</td>
            <td class="textalignright">{bizformat($sor.megrendelesnetto)} HUF</td>
            <td class="textalignright">{bizformat($sor.megrendelesnettovalt)} %</td>
            <td class="textalignright">{bizformat($sor.szamlanetto)} HUF</td>
            <td class="textalignright">{bizformat($sor.szamlanettovalt)} %</td>
            <td class="textalignright">{bizformat($sor.teljratanetto)} %</td>
            <td class="textalignright">{bizformat($sor.megrendelesnettopernap)} HUF</td>
            <td class="textalignright">{bizformat($sor.szamlanettopernap)} HUF</td>
        </tr>
    {/foreach}
    </tbody>
</table>
<div class="matt-hseparator"></div>
<table>
    <thead>
    <tr>
        <th></th>
        <th>Nettó/megrendelés</th>
        <th>Változás</th>
        <th>Nettó/számla</th>
        <th>Változás</th>
        <th>Teljesítési ráta</th>
    </tr>
    </thead>
    <tbody>
    {foreach $tjlista as $sor}
        <tr>
            <td>{$sor.ev}</td>
            <td class="textalignright">{bizformat($sor.megrendelesnettoperdb)} HUF</td>
            <td class="textalignright">{bizformat($sor.megrendelesnettoperdbvalt)} %</td>
            <td class="textalignright">{bizformat($sor.szamlanettoperdb)} HUF</td>
            <td class="textalignright">{bizformat($sor.szamlanettoperdbvalt)} %</td>
            <td class="textalignright">{bizformat($sor.teljratanettoperdb)} %</td>
        </tr>
    {/foreach}
    </tbody>
</table>
</div>