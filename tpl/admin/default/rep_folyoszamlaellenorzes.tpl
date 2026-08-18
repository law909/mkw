{extends "../rep_base.tpl"}

{block "body"}
    <h4>Folyószámla ellenőrzés</h4>
    <h5 class="printdatum">{$keltstr}</h5>

    <table>
        <thead>
        <tr>
            <th>Ellenőrzés</th>
            <th class="textalignright">Talált sor</th>
        </tr>
        </thead>
        <tbody>
        {foreach $ellenorzesek as $ell}
            <tr>
                <td class="cell">{$ell.nev}</td>
                <td class="cell textalignright{if ($ell.db)} lejart bold{/if}">{$ell.db}</td>
            </tr>
        {/foreach}
        <tr>
            <td class="cell bold">Összesen</td>
            <td class="cell textalignright bold">{$osszesen}</td>
        </tr>
        </tbody>
    </table>

    {if (!$osszesen)}
        <h5>Nincs eltérés: minden pénzmozgás a bizonylatával együtt mozog.</h5>
    {/if}

    {foreach $ellenorzesek as $ell}
        {if ($ell.db)}
            <h4>{$ell.nev} ({$ell.db})</h4>
            <h5>{$ell.leiras}</h5>
            <table>
                <thead>
                <tr>
                    <th>Pénzmozgás</th>
                    <th>Bizonylat</th>
                    <th>Partner</th>
                    <th>Dátum</th>
                    <th class="textalignright">Összeg</th>
                    <th>Megjegyzés</th>
                </tr>
                </thead>
                <tbody>
                {foreach $ell.rows as $sor}
                    <tr>
                        <td class="cell nowrap">{$sor.penzmozgas}</td>
                        <td class="cell nowrap">{$sor.bizonylat}</td>
                        <td class="cell">{$sor.partner}</td>
                        <td class="cell nowrap">{$sor.datum}</td>
                        <td class="cell textalignright nowrap">{bizformat($sor.osszeg)} {$sor.valutanem}</td>
                        <td class="cell">{$sor.megjegyzes}</td>
                    </tr>
                {/foreach}
                </tbody>
            </table>
            {if ($ell.db > $rowlimit)}
                <h5 class="lejart">A táblázat az első {$rowlimit} sort mutatja a {$ell.db}-ből.</h5>
            {/if}
        {/if}
    {/foreach}
{/block}
