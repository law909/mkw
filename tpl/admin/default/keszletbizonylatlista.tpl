{* A foglaló / érkeztető bizonylatok a készletsorok Foglalt–Érkezik linkjének modaljában;
   a bizonylatszám a bizonylatszámra szűrt listanézetre visz. *}
{if ($lista)}
    <table>
        <thead>
        <tr>
            <th>{at('Bizonylatszám')}</th>
            <th>{at('Kelt')}</th>
            <th>{at('Partner')}</th>
            <th>{if ($erkezik)}{at('Érkezik')}{else}{at('Foglalt')}{/if}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $lista as $sor}
            <tr>
                <td>{if ($sor.url)}<a href="{$sor.url}" target="_blank">{$sor.id}</a>{else}{$sor.id}{/if}</td>
                <td>{$sor.kelt}</td>
                <td>{$sor.partnernev|escape}</td>
                <td class="textalignright">{$sor.mennyiseg}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{else}
    {at('Nincs ilyen bizonylat.')}
{/if}
