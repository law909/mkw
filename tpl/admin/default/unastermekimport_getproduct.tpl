{* A getProduct lekérdezés eredménye, AJAX-ból töltve – lásd unastermekimport.js. *}
<div class="matt-hseparator"></div>

<div style="margin:5px 0;">
    <strong>{at('Kérés')}:</strong>
    {foreach $keres as $kulcs => $ertek}{$kulcs|escape}={$ertek|escape}{if !$ertek@last}, {/if}{/foreach}
    &mdash; {at('getProduct hívások ebben az órában')}:
    {at('egy termékes')} {$keret.egy}/{$keret.limitegy},
    {at('több termékes')} {$keret.tobb}/{$keret.limittobb}
</div>

{if ($tobbtermekes)}
    <div class="matt-messagecenter ui-widget ui-state-highlight" style="padding:5px;margin:5px 0;">
        {at('Ez több termékes getProduct volt: abból PREMIUM csomagon óránként csak 30 hívás van, míg egy termék lekérdezéséből 1000.')}
    </div>
{/if}

{if (!$termekek)}
    <div class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin:5px 0;">
        {at('Az UNAS nem adott vissza terméket erre a szűrőre.')}
    </div>
{/if}

{foreach $termekek as $t}
    <table class="ui-widget ui-widget-content ui-corner-all" style="width:100%;border-collapse:collapse;margin:5px 0;">
        <tbody>
        <tr>
            <td style="padding:2px 5px;width:12em;">{at('UNAS azonosító')}</td>
            <td style="padding:2px 5px;"><strong>{$t.id|escape}</strong></td>
            <td style="padding:2px 5px;width:12em;">{at('Cikkszám')}</td>
            <td style="padding:2px 5px;"><strong>{$t.cikkszam|escape}</strong></td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('Név')}</td>
            <td style="padding:2px 5px;" colspan="3">{$t.nev|escape}</td>
        </tr>
        <tr>
            <td style="padding:2px 5px;">{at('Alap státusz')}</td>
            <td style="padding:2px 5px;">{$t.statusz|escape}</td>
            <td style="padding:2px 5px;">{at('Mennyiségi egység')}</td>
            <td style="padding:2px 5px;">{$t.me|escape}</td>
        </tr>
        {if ($t.modositas)}
            <tr>
                <td style="padding:2px 5px;">{at('Utolsó módosítás')}</td>
                <td style="padding:2px 5px;" colspan="3">{$t.modositas|escape}</td>
            </tr>
        {/if}
        </tbody>
    </table>

    <div style="margin:5px 0;">
        <strong>{at('Változatok')}:</strong>
        {if (!$t.valtozatok)}
            <span class="ui-state-error-text">{at('nincs Variants blokk a válaszban')}</span>
        {else}
            <ul style="margin:2px 0 2px 20px;">
                {foreach $t.valtozatok as $v}
                    <li>
                        <strong>{$v.nev|escape}</strong>:
                        {foreach $v.ertekek as $e}
                            {$e.nev|escape}{if ($e.arkulonbozet)} ({$e.arkulonbozet|escape}){/if}{if !$e@last} | {/if}
                        {/foreach}
                    </li>
                {/foreach}
            </ul>
        {/if}
    </div>

    <div style="margin:5px 0;">
        <strong>{at('Készlet')}:</strong>
        {if (!$t.keszletek)}
            <span>{at('nincs Stocks blokk a válaszban')}</span>
        {else}
            <ul style="margin:2px 0 2px 20px;">
                {foreach $t.keszletek as $k}
                    <li>
                        {if ($k.kombinacio)}{$k.kombinacio|escape}: {/if}{$k.mennyiseg|escape}
                        {if ($k.raktar)} ({at('raktár')}: {$k.raktar|escape}){/if}
                    </li>
                {/foreach}
            </ul>
        {/if}
    </div>

    <div style="margin:5px 0;">
        <strong>{at('Képek')}:</strong> {$t.kepek|@count}
        {if ($t.kepek)}
            <ul style="margin:2px 0 2px 20px;">
                {foreach $t.kepek as $kep}
                    <li>{if ($kep.fokep)}<strong>{at('főkép')}</strong>: {/if}{$kep.url|escape}{if ($kep.alt)} &mdash; {$kep.alt|escape}{/if}</li>
                {/foreach}
            </ul>
        {/if}
    </div>
    <div class="matt-hseparator"></div>
{/foreach}

<div style="margin:5px 0;">
    <strong>{at('Nyers válasz')}</strong>
    {if ($dumpfajl)}<span>({$dumpfajl|escape})</span>{/if}
    {if (!$nyersteljes)}<span class="ui-state-error-text">&mdash; {at('a válasz csonkolva, a teljes XML a storage/logs mappában van')}</span>{/if}
    <pre style="max-height:400px;overflow:auto;background:#f5f5f5;padding:5px;border:1px solid #ddd;white-space:pre-wrap;word-break:break-all;">{$nyers|escape}</pre>
</div>
