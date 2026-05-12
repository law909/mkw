<div class="js-kintlevoseg">
    <table>
        <thead>
        <tr>
            <th>Lejárt kintlevőség</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {foreach $lejartkintlevoseg as $lk}
            <tr>
                <td>{$lk.nev}</td>
                <td class="textalignright">{bizformat($lk.egyenleg)}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th>Össz. kintlevőség</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {foreach $kintlevoseg as $lk}
            <tr>
                <td>{$lk.nev}</td>
                <td class="textalignright">{bizformat($lk.egyenleg)}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>