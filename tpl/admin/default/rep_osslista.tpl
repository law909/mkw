{extends "../rep_base.tpl"}

{block "body"}
    <h4>{at('OSS kimutatás')} – {$negyedev|replace:'/':'. '}. {at('negyedév')}</h4>
    <h5>{at('Teljesítés')}: {$tol} – {$ig}</h5>
    {if ($hianyzoarfolyam)}
        <p class="lejart">{at('Hiányzó EKB árfolyam, ezek a tételek kimaradtak')}: {implode(', ', $hianyzoarfolyam)}.
            {at('A negyedév még nem zárult le, vagy az EKB nem érhető el; a forintos árfolyam kézzel is megadható.')}</p>
    {/if}
    <table>
        <thead>
        <tr>
            <th>{at('Valuta')}</th>
            <th>{at('Időszak')}</th>
            <th>{at('Árfolyam napja')}</th>
            <th class="textalignright">1 EUR</th>
            <th>{at('Forrás')}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $arfolyamok as $_a}
            <tr>
                <td class="cell">{$_a.valuta}</td>
                <td class="cell">{$_a.negyedev}</td>
                <td class="cell">{$_a.datum}</td>
                <td class="cell textalignright">{bizformat($_a.arfolyam, 4)}</td>
                <td class="cell">{if ($_a.kezi)}{at('kézi')}{else}EKB{/if}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>

    <h5>{at('Összesítő')}</h5>
    <table>
        <thead>
        <tr>
            <th>{at('Tagállam')}</th>
            <th class="textalignright">{at('ÁFA kulcs')}</th>
            <th class="textalignright">{at('Adóalap EUR')}</th>
            <th class="textalignright">{at('ÁFA EUR')}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $osszesito as $_s}
            <tr>
                <td class="cell">{$_s.iso3166} {$_s.orszagnev|escape}</td>
                <td class="cell textalignright">{bizformat($_s.afakulcs, 1)} %</td>
                <td class="cell textalignright nowrap">{bizformat($_s.nettoeur, 2)}</td>
                <td class="cell textalignright nowrap">{bizformat($_s.afaeur, 2)}</td>
            </tr>
        {foreachelse}
            <tr><td class="cell" colspan="4">{at('Nincs ilyen értékesítés a negyedévben.')}</td></tr>
        {/foreach}
        </tbody>
        <tfoot>
        <tr>
            <td class="cell bold" colspan="2">{at('Összesen')}</td>
            <td class="cell textalignright nowrap bold">{bizformat($osszesnetto, 2)}</td>
            <td class="cell textalignright nowrap bold">{bizformat($osszesafa, 2)}</td>
        </tr>
        </tfoot>
    </table>

    {if ($korrekciok)}
        <h5>{at('Korábbi időszakok korrekciói')}</h5>
        <table>
            <thead>
            <tr>
                <th>{at('Eredeti időszak')}</th>
                <th>{at('Tagállam')}</th>
                <th class="textalignright">{at('ÁFA kulcs')}</th>
                <th class="textalignright">{at('Adóalap EUR')}</th>
                <th class="textalignright">{at('ÁFA EUR')}</th>
            </tr>
            </thead>
            <tbody>
            {foreach $korrekciok as $_k}
                <tr>
                    <td class="cell">{$_k.negyedev}</td>
                    <td class="cell">{$_k.iso3166} {$_k.orszagnev|escape}</td>
                    <td class="cell textalignright">{bizformat($_k.afakulcs, 1)} %</td>
                    <td class="cell textalignright nowrap">{bizformat($_k.nettoeur, 2)}</td>
                    <td class="cell textalignright nowrap">{bizformat($_k.afaeur, 2)}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}

    {if ($ellenorizendo)}
        <h5 class="lejart">{at('Ellenőrizendő: EU-s magánvevő, de nem célországos ÁFA kulcs')}</h5>
        <table>
            <thead>
            <tr>
                <th>{at('Bizonylat')}</th>
                <th>{at('Teljesítés')}</th>
                <th>{at('Partner')}</th>
                <th>{at('Tagállam')}</th>
                <th class="textalignright">{at('ÁFA kulcs')}</th>
                <th class="textalignright">{at('Nettó')}</th>
            </tr>
            </thead>
            <tbody>
            {foreach $ellenorizendo as $_e}
                <tr>
                    <td class="cell nowrap">{$_e.id}</td>
                    <td class="cell nowrap">{$_e.teljesites}</td>
                    <td class="cell">{$_e.partnernev|escape}</td>
                    <td class="cell">{$_e.iso3166}</td>
                    <td class="cell textalignright">{bizformat($_e.afakulcs, 1)} %</td>
                    <td class="cell textalignright nowrap">{bizformat($_e.netto, 2)} {$_e.valutanemnev}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}

    <h5>{at('Tételek')}</h5>
    <table>
        <thead>
        <tr>
            <th>{at('Bizonylat')}</th>
            <th>{at('Teljesítés')}</th>
            <th>{at('Partner')}</th>
            <th>{at('Tagállam')}</th>
            <th class="textalignright">{at('ÁFA kulcs')}</th>
            <th class="textalignright">{at('Nettó')}</th>
            <th class="textalignright">{at('ÁFA')}</th>
            <th class="textalignright">{at('Nettó EUR')}</th>
            <th class="textalignright">{at('ÁFA EUR')}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $tetelek as $_t}
            <tr>
                <td class="cell nowrap">{$_t.id}{if ($_t.negyedev != $negyedev)} ({$_t.negyedev}){/if}</td>
                <td class="cell nowrap">{$_t.teljesites}</td>
                <td class="cell">{$_t.partnernev|escape}</td>
                <td class="cell">{$_t.iso3166}</td>
                <td class="cell textalignright">{bizformat($_t.afakulcs, 1)} %</td>
                <td class="cell textalignright nowrap">{bizformat($_t.netto, 2)} {$_t.valutanemnev}</td>
                <td class="cell textalignright nowrap">{bizformat($_t.afaertek, 2)} {$_t.valutanemnev}</td>
                <td class="cell textalignright nowrap">{bizformat($_t.nettoeur, 2)}</td>
                <td class="cell textalignright nowrap">{bizformat($_t.afaeur, 2)}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/block}
