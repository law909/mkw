<table>
    <thead>
        <tr>
            <th>{at('Időpont')}</th>
            <th>{at('Módosította')}</th>
            <th>{at('Erről')}</th>
            <th>{at('Erre')}</th>
        </tr>
    </thead>
    <tbody>
    {foreach $lista as $elem}
        <tr>
            <td>{$elem.datum}</td>
            <td>{$elem.dolgozonev}</td>
            <td>{$elem.regi}</td>
            <td>{$elem.uj}</td>
        </tr>
    {foreachelse}
        <tr>
            <td colspan="4">{at('Nincs naplóbejegyzés.')}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
