<div>Új partnerek száma: {$ujpartnercount}</div>
<table>
    {foreach $ujpartnerlista as $k => $ujp}
        <tr>
            <td>{$ujp.datum}</td>
            <td>{$ujp.nev}</td>
            <td>{$ujp.email}.</td>
        </tr>
    {/foreach}
</table>