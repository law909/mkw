{* A kimenő sor, AJAX-ból töltve – lásd unasrendeles.js. *}
<div style="margin:5px 0;">
    {foreach $osszesito as $_allapot => $_db}{$_allapot}: <strong>{$_db}</strong>{if !$_db@last} | {/if}{/foreach}
    {if (!$osszesito)}{at('A kimenő sor üres.')}{/if}
</div>

{if ($sorok)}
    <table class="ui-widget ui-widget-content ui-corner-all unastable">
        <thead>
        <tr>
            <th>{at('Létrejött')}</th>
            <th>{at('UNAS azonosító')}</th>
            <th>{at('Külső azonosító')}</th>
            <th>{at('Bizonylat')}</th>
            <th>{at('Típus')}</th>
            <th>{at('Állapot')}</th>
            <th>{at('Próbálkozás')}</th>
            <th>{at('Utolsó hiba')}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {foreach $sorok as $_sor}
            <tr>
                <td>{$_sor.created}</td>
                <td>{$_sor.unaskey}</td>
                <td>{$_sor.unaskulsokey}</td>
                <td>{$_sor.bizonylat}</td>
                <td>{$_sor.tipus}</td>
                <td class="{if ($_sor.allapot == 'hiba')}redtext{/if}">{$_sor.allapot}</td>
                <td class="textalignright">{$_sor.probalkozas}</td>
                <td>{$_sor.utolsohiba}</td>
                <td>
                    {if ($_sor.allapot != 'fuggo')}
                        <a href="#" class="js-unasoutboxujra" data-id="{$_sor.id}">{at('Újrafuttat')}</a>
                    {/if}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <div>{at('A legutóbbi 100 sor látszik.')}</div>
{/if}
