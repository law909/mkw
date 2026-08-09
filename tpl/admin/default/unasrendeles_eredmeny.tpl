{* Egy lehúzás vagy kézi import eredménye, AJAX-ból töltve – lásd unasrendeles.js. *}
<div class="matt-hseparator"></div>

{if ($osszesito.csakletoltes|default)}
    <div class="matt-messagecenter ui-widget ui-state-highlight" style="padding:5px;margin:5px 0;">
        {at('Csak letöltés: bizonylat nem készült, és az import kurzor sem lépett.')}
    </div>
    <table class="ui-widget ui-widget-content ui-corner-all unastable">
        <tbody>
        <tr>
            <td>{at('Letöltött rendelés')}</td>
            <td class="textalignright"><strong>{$osszesito.talalt}</strong></td>
            <td>{at('Lekért lap')}</td>
            <td class="textalignright">{$osszesito.lapok}</td>
        </tr>
        </tbody>
    </table>
    {if ($osszesito.fajlok)}
        <div style="margin:5px 0;">
            <strong>{at('Elmentett XML a storage/logs mappában')}:</strong>
            <ul class="unstyled-list">
                {foreach $osszesito.fajlok as $_f}
                    <li>{$_f}</li>
                {/foreach}
            </ul>
        </div>
    {/if}
    {if ($osszesito.hiba)}
        <div class="redtext">{at('Hiba')}: {$osszesito.eredmenyek[0].hiba|default}</div>
    {/if}
{else}
<table class="ui-widget ui-widget-content ui-corner-all unastable">
    <tbody>
    <tr>
        <td>{at('Feldolgozott rendelés')}</td>
        <td class="textalignright"><strong>{$osszesito.feldolgozva}</strong></td>
        <td>{at('Lekért lap')}</td>
        <td class="textalignright">{$osszesito.lapok}</td>
    </tr>
    <tr>
        <td>{at('Új bizonylat')}</td>
        <td class="textalignright">{$osszesito.uj}</td>
        <td>{at('Már megvolt')}</td>
        <td class="textalignright">{$osszesito.letezo}</td>
    </tr>
    <tr>
        <td>{at('Hiba')}</td>
        <td class="textalignright{if ($osszesito.hiba)} redtext{/if}">{$osszesito.hiba}</td>
        <td>{at('Kurzor')}</td>
        <td class="textalignright">{$osszesito.kurzor|date_format:"%Y-%m-%d %H:%M"}</td>
    </tr>
    </tbody>
</table>

{if ($eredmenyek)}
    <table class="ui-widget ui-widget-content ui-corner-all unastable">
        <thead>
        <tr>
            <th>{at('UNAS azonosító')}</th>
            <th>{at('Eredmény')}</th>
            <th>{at('Bizonylat')}</th>
            <th>{at('Hiba')}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $eredmenyek as $_e}
            <tr>
                <td>{$_e.unaskey}</td>
                <td>{$_e.statusz}</td>
                <td>{$_e.bizonylat}</td>
                <td class="{if ($_e.hiba)}redtext{/if}">{$_e.hiba}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
{/if}
{/if}
