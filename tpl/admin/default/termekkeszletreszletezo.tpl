<table>
    <thead>
        <tr>
            <th>Raktár</th>
            <th>Készlet</th>
        </tr>
    </thead>
    <tbody>
    {foreach $lista as $elem }
        <tr>
            <td>{$elem.raktarnev}</td>
            <td class="textalignright">{$elem.keszlet}</td>
        </tr>
    {/foreach}
    </tbody>
</table>