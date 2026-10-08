{extends "../base.tpl"}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Pagecache törlés')}</h3>
        </div>
        <div class="mattkarb-page">
            {if ($torolve !== null)}
                <div class="matt-messagecenter ui-widget ui-state-highlight">
                    {at('A pagecache kiürítve')}: {$torolve} {at('oldal törölve')}.
                </div>
            {/if}
            {if ($mappahiba)}
                <div class="matt-messagecenter ui-widget ui-state-error">
                    {at('A config.ini mainpagecachepath beállításában megadott mappa nem létezik')}: {$mappa}
                </div>
            {/if}
            {if (!$bekapcsolva)}
                <div class="matt-messagecenter ui-widget ui-state-error">
                    {at('Ezen a telepítésen a pagecache nincs bekapcsolva (config.ini: pagecache = 1).')}
                </div>
            {/if}
            <p>{at('A webshop gyorsítótárazott oldalait törli: a következő látogatás mindegyiket újra előállítja. Tartalom (termék, kategória, statikus lap) módosítása után akkor kell, ha a változásnak a lejárati idő előtt látszania kell.')}</p>
            <p><strong>{at('Mappa')}:</strong> {$mappa}</p>
            <p><strong>{at('Tárolt oldalak')}:</strong> {$fajldb}</p>
            <form method="post" action="/admin/pagecache/clear">
                <div class="admin-form-footer">
                    <button type="submit" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">{at('Törlés')}</button>
                </div>
            </form>
        </div>
    </div>
{/block}
