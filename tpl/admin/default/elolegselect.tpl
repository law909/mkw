{if ($hiba)}
    <p class="bizonylat-elolegvalaszto-hiba">{$hiba}</p>
{else}
    <table class="kiegyenlitetlenselect">
        <thead>
        <tr>
            <td>{at('Bizonylat')}</td>
            <td>{at('Kelt')}</td>
            <td>{at('Teljesítés')}</td>
            <td class="textalignright">{at('Egyenleg')}</td>
            <td class="textalignright">{at('Már beszámítva')}</td>
            <td class="textalignright">{at('Beszámítható nettó')}</td>
            <td class="textalignright">{at('Beszámítható bruttó')}</td>
        </tr>
        </thead>
        <tbody>
        {foreach $elolegek as $eloleg}
            <tr data-bizszam="{$eloleg.id}">
                <td>{$eloleg.id}</td>
                <td>{$eloleg.keltstr}</td>
                <td>{$eloleg.teljesitesstr}</td>
                <td class="textalignright">{bizformat($eloleg.egyenleg)}</td>
                <td class="textalignright">{bizformat($eloleg.beszamitott)}</td>
                <td class="textalignright">{bizformat($eloleg.netto)}</td>
                <td class="textalignright">{bizformat($eloleg.brutto)}</td>
            </tr>
        {foreachelse}
            <tr>
                <td colspan="7">{if ($szallitoieloleg)}{at('Nincs beszámítható szállítói előleg ehhez a partnerhez.')}{else}{at('Nincs beszámítható előlegszámla ehhez a partnerhez.')}{/if}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}
