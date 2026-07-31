{* Az import eredményblokkja. A böngésző URL-je nem változhat, ezért az importálás AJAX-ból
   indul, és csak ez a rész töltődik újra – lásd koltsegszamlaimport.js. *}
{if ($hibauzenet)}
    <div class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin:5px 0;">
        <strong>{at('Hiba')}:</strong> {$hibauzenet}
    </div>
{/if}
{if ($eredmeny)}
    <div class="matt-hseparator"></div>
    <div>
        <strong>{at('Időszak')}:</strong> {$eredmeny.idoszak},
        <strong>{at('NAV-nál talált számla')}:</strong> {$eredmeny.digestdb},
        <strong>{at('új költségszámla')}:</strong> {$eredmeny.ujdb},
        <strong>{at('már megvolt')}:</strong> {$eredmeny.letezodb},
        <strong>{at('hibás')}:</strong> {$eredmeny.hibadb}
    </div>
    {if ($eredmeny.megszakadt)}
        <div class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin:5px 0;">
            {at('A feldolgozás hiba miatt megszakadt, a hátralévő számlák nem készültek el.')}
        </div>
    {/if}
    {if (!$eredmeny.datummentve)}
        <div style="margin:5px 0;">
            {at('Hiba miatt az időszak vége nem került eltárolásra, a hibás számlák a következő importnál újra sorra kerülnek.')}
        </div>
    {/if}
    {if ($eredmeny.tetelek)}
        <table class="ui-widget ui-widget-content ui-corner-all" style="width:100%;border-collapse:collapse;">
            <thead>
            <tr class="ui-widget-header">
                <th style="text-align:left;padding:2px 5px;">{at('Számlaszám')}</th>
                <th style="text-align:left;padding:2px 5px;">{at('Szállító')}</th>
                <th style="text-align:left;padding:2px 5px;">{at('Kelt')}</th>
                <th style="text-align:right;padding:2px 5px;">{at('Nettó')}</th>
                <th style="text-align:left;padding:2px 5px;">{at('Állapot')}</th>
            </tr>
            </thead>
            <tbody>
            {foreach $eredmeny.tetelek as $_tetel}
                <tr>
                    <td style="padding:2px 5px;">{$_tetel.szamlaszam}</td>
                    <td style="padding:2px 5px;">{$_tetel.szallito}</td>
                    <td style="padding:2px 5px;">{$_tetel.kelt}</td>
                    <td class="textalignright" style="padding:2px 5px;">{$_tetel.netto} {$_tetel.valutanem}</td>
                    <td style="padding:2px 5px;">
                        {if ($_tetel.statusz == 'uj' || $_tetel.statusz == 'letezo')}
                            {if ($_tetel.statusz == 'uj')}{at('elkészült')}:{else}{at('már megvolt')}:{/if}
                            <a href="/admin/koltsegszamlafej/viewkarb?id={$_tetel.bizonylatszam|escape:'url'}&oper=edit"
                               target="_blank">{$_tetel.bizonylatszam}</a>
                        {else}
                            <span class="ui-state-error-text">{at('hiba')}: {$_tetel.uzenet}</span>
                        {/if}
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
{/if}
