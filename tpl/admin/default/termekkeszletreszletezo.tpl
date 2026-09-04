<table>
    <thead>
        <tr>
            <th>{at('Raktár')}</th>
            <th>{at('Készlet')}</th>
            <th>{at('Foglalt')}</th>
            <th>{at('Érkezik')}</th>
        </tr>
    </thead>
    <tbody>
    {foreach $lista as $elem }
        <tr>
            <td>{$elem.raktarnev}</td>
            <td class="textalignright">{$elem.keszlet}</td>
            <td class="textalignright">{$elem.foglalt}</td>
            <td class="textalignright">{$elem.erkezik}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
