{* A takarítás riportja, AJAX-ból töltve – lásd unaskepcleanup.js. *}
{if ($riport)}
    <div class="matt-hseparator"></div>
    {if ($riport.uzenet)}
        <div class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin:5px 0;">
            {$riport.uzenet}
        </div>
    {/if}

    <table class="ui-widget ui-widget-content ui-corner-all" style="width:100%;border-collapse:collapse;margin:5px 0;">
        <tbody>
        <tr>
            <td style="padding:2px 5px;">{at('Fájl a mappában')}</td>
            <td class="textalignright" style="padding:2px 5px;"><strong>{$riport.fajl}</strong> ({$meret.osszes})</td>
            <td style="padding:2px 5px;">{at('Hivatkozott képnév az adatbázisban')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.hivatkozott} ({$riport.oszlop} {at('oszlop')})</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('Megtartva')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.megtartva} ({$meret.megtartva})</td>
            <td style="padding:2px 5px;">{at('Árva')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.arva} ({$meret.arva})</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('Hiányzó fájlra mutató hivatkozás')}</td>
            <td class="textalignright{if ($riport.hianyzo_db)} redtext{/if}" style="padding:2px 5px;">{$riport.hianyzo_db}</td>
            <td style="padding:2px 5px;">{at('Törölve')}</td>
            <td class="textalignright" style="padding:2px 5px;">
                {if ($torles)}{$riport.torolve} ({$meret.torolve}){else}&ndash;{/if}
            </td>
        </tr>
        </tbody>
    </table>

    {if (!$torles && !$riport.megallt)}
        <div class="matt-messagecenter ui-widget ui-state-highlight" style="padding:5px;margin:5px 0;">
            {at('Ez csak számolás volt: egyetlen fájl sem törlődött.')}
        </div>
    {/if}
    {if ($riport.almappa)}
        <div style="margin:5px 0;">{at('Almappa a képmappában')}: {$riport.almappa} &ndash; {at('ezekbe nem megyünk be.')}</div>
    {/if}

    {if ($riport.hiba)}
        <div class="ui-widget ui-widget-content ui-corner-all" style="padding:5px;margin:5px 0;">
            <strong>{at('Nem törölhető fájlok')}</strong> ({$riport.hiba_db})
            <ul class="unstyled-list">
                {foreach $riport.hiba as $_nev}
                    <li class="ui-state-error-text">{$_nev}</li>
                {/foreach}
            </ul>
        </div>
    {/if}

    {if ($riport.hianyzo)}
        <div class="ui-widget ui-widget-content ui-corner-all" style="padding:5px;margin:5px 0;">
            <strong>{at('Hiányzó fájlra mutató hivatkozás')}</strong> ({$riport.hianyzo_db})
            <span>&ndash; {at('az adatbázishoz nem nyúlunk, ezeket kézzel kell rendezni')}</span>
            {if ($riport.hianyzo_db > $riport.hianyzo|@count)}
                <span>&ndash; {at('csak az első')} {$riport.hianyzo|@count} {at('látszik')}</span>
            {/if}
            <ul class="unstyled-list">
                {foreach $riport.hianyzo as $_nev}
                    <li>{$_nev}</li>
                {/foreach}
            </ul>
        </div>
    {/if}

    {if ($riport.lista)}
        <div class="ui-widget ui-widget-content ui-corner-all" style="padding:5px;margin:5px 0;">
            <strong>{at('Árva fájlok')}</strong> ({$riport.arva})
            {if ($riport.arva > $riport.lista|@count)}
                <span>&ndash; {at('csak az első')} {$riport.lista|@count} {at('látszik')}</span>
            {/if}
            <ul class="unstyled-list">
                {foreach $riport.lista as $_nev}
                    <li>{$_nev}</li>
                {/foreach}
            </ul>
        </div>
    {/if}
{/if}
