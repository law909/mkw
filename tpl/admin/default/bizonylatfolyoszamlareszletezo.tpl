<table>
    <thead>
        <tr>
            <th>{at('Dátum')}</th>
            <th>{at('Biz.szám')}</th>
            <th>{at('Fiz.mód')}</th>
            <th>{at('Összeg')}</th>
        </tr>
    </thead>
    <tbody>
    {foreach $lista as $elem }
        <tr>
            <td>{$elem.datum}</td>
            <td>{$elem.bizonylatszam}</td>
            <td>{$elem.fizmodnev}</td>
            <td class="textalignright">{$elem.brutto} {$elem.valutanemnev}</td>
        </tr>
    {/foreach}
    </tbody>
</table>