{* Az import riportja, AJAX-ból töltve – lásd unastermekimport.js. *}
{if ($riport)}
    <div class="matt-hseparator"></div>
    {if ($riport.szarazfutas)}
        <div class="matt-messagecenter ui-widget ui-state-highlight" style="padding:5px;margin:5px 0;">
            {at('Száraz futás volt: semmi nem került mentésre, csak a párosítás eredménye látszik.')}
        </div>
    {/if}
    {if ($riport.megszakadt)}
        <div class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin:5px 0;">
            {at('A feldolgozás hiba miatt megszakadt, a hátralévő termékek nem kerültek sorra.')}
        </div>
    {/if}
    {if ($hianyzooszlopok)}
        <div style="margin:5px 0;">
            <strong>{at('A letöltött adatbázisból hiányzó oszlopok')}:</strong> {$hianyzooszlopok}
        </div>
    {/if}

    <table class="ui-widget ui-widget-content ui-corner-all" style="width:100%;border-collapse:collapse;margin:5px 0;">
        <tbody>
        <tr>
            <td style="padding:2px 5px;">{at('Feldolgozott UNAS termék')}</td>
            <td class="textalignright" style="padding:2px 5px;"><strong>{$riport.osszes}</strong></td>
            <td style="padding:2px 5px;">{at('Párosított változat-kombináció')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.valtozat_parositva}</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('Termék UNAS azonosítóval')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.parositott_termek_unasid}</td>
            <td style="padding:2px 5px;">{at('Változat UNAS azonosítóval')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.parositott_valtozat_unasid}</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('Változat cikkszámmal')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.parositott_valtozat_cikkszam}</td>
            <td style="padding:2px 5px;">{at('Termék cikkszámmal')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.parositott_termek_cikkszam}</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('Ebből aláhúzás → kötőjel cserével')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.cikkszam_csere_db}</td>
            <td style="padding:2px 5px;">{at('Azonosító alapján megtalált, érintetlenül hagyott')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.kihagyva_unasid|default:0}</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('Nem található a törzsben')}</td>
            <td class="textalignright redtext" style="padding:2px 5px;">{$riport.nem_talalt_db}</td>
            <td style="padding:2px 5px;">{at('Kétértelmű találat')}</td>
            <td class="textalignright redtext" style="padding:2px 5px;">{$riport.ketertelmu_db}</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('3 tulajdonságos termék (változat kimaradt)')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.harom_tulajdonsagu_db}</td>
            <td style="padding:2px 5px;">{at('Nem párosítható változat-kombináció')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.valtozat_nem_talalt_db}</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('UNAS azonosító felülírva')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.unasid_utkozes_db}</td>
            <td style="padding:2px 5px;">{at('Párosítatlan MKW változat')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.mkw_valtozat_parositatlan_db}</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('Írt termék (web mezők)')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.mezo_irva}</td>
            <td style="padding:2px 5px;">{at('Kép: letöltött / kihagyott / azonos tartalmú / hibás')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.kep_letoltve} / {$riport.kep_kihagyva} / {$riport.kep_duplikatum} / {$riport.kep_hiba_db}</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('Termékhez rendelt kép (főkép + galéria)')}</td>
            <td class="textalignright" style="padding:2px 5px;">{$riport.kep_hozzarendelve|default:0}</td>
            <td colspan="2" style="padding:2px 5px;"></td>
        </tr>
        </tbody>
    </table>

    <div style="margin:5px 0;">
        {if ($riport.nem_talalt_db)}
            <a href="{$csvurl}" class="ui-button ui-widget ui-state-default ui-corner-all">{at('A nem talált termékek listája (CSV)')}</a>
        {/if}
        <a href="{$naplourl}">{at('A letöltött termékadatbázis')}: {$fajl}</a>
    </div>

    {if (!$riport.szarazfutas)}
        <div style="margin:5px 0;">
            {if ($riport.kurzormentve)}
                {at('Az inkrementális kurzor eltárolva: a következő futás innen folytatja.')}
            {else}
                {at('A kurzor nem lépett (száraz futás, hiba vagy nem talált tétel miatt) – a következő futás ugyanezt az időszakot kéri le újra.')}
            {/if}
        </div>
    {/if}

    {if ($riport.hibak)}
        <div class="ui-widget ui-widget-content ui-corner-all" style="padding:5px;margin:5px 0;">
            <strong>{at('Hibák')}:</strong>
            <ul class="unstyled-list">
                {foreach $riport.hibak as $_hiba}
                    <li class="ui-state-error-text">{$_hiba}</li>
                {/foreach}
            </ul>
        </div>
    {/if}

    {* mintalisták; a teljes "nem talált" lista a letölthető CSV-ben van *}
    {foreach $reszletek as $_blokk}
        <div class="ui-widget ui-widget-content ui-corner-all" style="padding:5px;margin:5px 0;">
            <strong>{$_blokk.cim}</strong> ({$_blokk.osszes})
            {if ($_blokk.csonkolt)}
                <span>&ndash; {at('csak az első')} {$_blokk.lista|@count} {at('látszik')}</span>
            {/if}
            <table style="width:100%;border-collapse:collapse;">
                {foreach $_blokk.lista as $_sor}
                    <tr>
                        {foreach $_sor as $_kulcs => $_ertek}
                            <td style="padding:1px 5px;">{$_kulcs}: {$_ertek}</td>
                        {/foreach}
                    </tr>
                {/foreach}
            </table>
        </div>
    {/foreach}
{/if}
