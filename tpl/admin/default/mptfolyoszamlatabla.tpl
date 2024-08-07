<table id="mptfolyoszamlatabla">
    <thead>
    <tr>
        <th>Vonatkozó év</th>
        <th>Típus</th>
        <th class="textalignright">Összeg</th>
        <th>Bizonylatszám</th>
        <th>Dátum</th>
    </tr>
    </thead>
    <tbody>
    {$_ev = 0}
    {$_egyenleg = 0}
    {foreach $partner.mptfolyoszamla as $fsz}
        {if ($_ev !== $fsz.vonatkozoev)}
            <tr>
                <td></td>
            </tr>
        {/if}
        <tr>
            <td>{if ($_ev !== $fsz.vonatkozoev)}{$fsz.vonatkozoev}{/if}</td>
            <td class="{if ($fsz.irany>0)}befizetes{else}eloiras{/if}">{$fsz.tipusnev}</td>
            <td class="{if ($fsz.irany>0)}befizetes{else}eloiras{/if} textalignright">{$fsz.osszeg * $fsz.irany}</td>
            <td>{$fsz.bizonylatszam}</td>
            <td>{$fsz.datum}</td>
            <td><a href="#" class="js-mptfolyoszamladel" data-id="{$fsz.id}">Törlés</a></td>
        </tr>
        {$_egyenleg = $_egyenleg + $fsz.osszeg * $fsz.irany}
        {$_ev = $fsz.vonatkozoev}
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        <td colspan="2">Egyenleg</td>
        <td class="textalignright">{$_egyenleg}</td>
    </tr>
    </tfoot>
</table>
