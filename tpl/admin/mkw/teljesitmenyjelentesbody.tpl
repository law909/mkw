<table>
    <thead>
        <tr>
            <th></th>
            <th>Megrendelések száma</th>
            <th>Számlák száma</th>
            <th>Teljesítési ráta</th>
            <th>Megrendelés/nap</th>
            <th>Számla/nap</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Aktuális év mai napig</td>
            <td class="textalignright">{$tjlista.megrendelesdb} db</td>
            <td class="textalignright">{$tjlista.szamladb} db</td>
            <td class="textalignright">{bizformat($tjlista.teljratadb)} %</td>
            <td class="textalignright">{bizformat($tjlista.megrendelespernapdb)} db</td>
            <td class="textalignright">{bizformat($tjlista.szamlapernapdb)} db</td>
        </tr>
        <tr>
            <td>Előző év megfelelő időszaka</td>
            <td class="textalignright">{$tjlista.elozomegrendelesdb} db</td>
            <td class="textalignright">{$tjlista.elozoszamladb} db</td>
            <td class="textalignright">{bizformat($tjlista.elozoteljratadb)} %</td>
            <td class="textalignright">{bizformat($tjlista.elozomegrendelespernapdb)} db</td>
            <td class="textalignright">{bizformat($tjlista.elozoszamlapernapdb)} db</td>
        </tr>
        <tr>
            <td>Változás</td>
            <td class="textalignright">{bizformat($tjlista.megrendelesvaltdb)} %</td>
            <td class="textalignright">{bizformat($tjlista.szamlavaltdb)} %</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
<div class="matt-hseparator"></div>
<table>
    <thead>
    <tr>
        <th></th>
        <th>Megrendelés nettó</th>
        <th>Számla nettó</th>
        <th>Teljesítési ráta</th>
        <th>Megrendelés nettó/nap</th>
        <th>Számla nettó/nap</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Aktuális év mai napig</td>
        <td class="textalignright">{bizformat($tjlista.megrendeleshuf)} HUF</td>
        <td class="textalignright">{bizformat($tjlista.szamlahuf)} HUF</td>
        <td class="textalignright">{bizformat($tjlista.teljratahuf)} %</td>
        <td class="textalignright">{bizformat($tjlista.megrendelespernaphuf)} HUF</td>
        <td class="textalignright">{bizformat($tjlista.szamlapernaphuf)} HUF</td>
    </tr>
    <tr>
        <td>Előző év megfelelő időszaka</td>
        <td class="textalignright">{bizformat($tjlista.elozomegrendeleshuf)} HUF</td>
        <td class="textalignright">{bizformat($tjlista.elozoszamlahuf)} HUF</td>
        <td class="textalignright">{bizformat($tjlista.elozoteljratahuf)} %</td>
        <td class="textalignright">{bizformat($tjlista.elozomegrendelespernaphuf)} HUF</td>
        <td class="textalignright">{bizformat($tjlista.elozoszamlapernaphuf)} HUF</td>
    </tr>
    <tr>
        <td>Változás</td>
        <td class="textalignright">{bizformat($tjlista.megrendelesvalthuf)} %</td>
        <td class="textalignright">{bizformat($tjlista.szamlavalthuf)} %</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
<div class="matt-hseparator"></div>
<table>
    <thead>
    <tr>
        <th></th>
        <th>Átlag nettó/megrendelés</th>
        <th>Átlag nettó/számla</th>
        <th>Teljesítési ráta</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Aktuális év mai napig</td>
        <td class="textalignright">{bizformat($tjlista.megrendelesatlaghuf)} HUF</td>
        <td class="textalignright">{bizformat($tjlista.szamlaatlaghuf)} HUF</td>
        <td class="textalignright">{bizformat($tjlista.teljrataatlaghuf)} %</td>
    </tr>
    <tr>
        <td>Előző év megfelelő időszaka</td>
        <td class="textalignright">{bizformat($tjlista.elozomegrendelesatlaghuf)} HUF</td>
        <td class="textalignright">{bizformat($tjlista.elozoszamlaatlaghuf)} HUF</td>
        <td class="textalignright">{bizformat($tjlista.elozoteljrataatlaghuf)} %</td>
    </tr>
    <tr>
        <td>Változás</td>
        <td class="textalignright">{bizformat($tjlista.megrendelesatlagvalthuf)} %</td>
        <td class="textalignright">{bizformat($tjlista.szamlaatlagvalthuf)} %</td>
        <td></td>
    </tr>
    </tbody>
</table>