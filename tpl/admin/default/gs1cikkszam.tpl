{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/gs1cikkszam.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('GS1 cikkszám frissítés')}</h3>
        </div>
        <form id="gs1cikkszam" action="">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div>
                    <input name="toimport" type="file" accept=".xlsx">
                </div>
                <div class="matt-hseparator"></div>
                <a href="/admin/termek/gs1cikkszam" class="js-frissitesbutton">{at('Frissítés')}</a>
                <span class="js-frissitesuzenet"></span>
                <p class="mattkarb-hint">
                    {at('A GS1-ből letöltött termékadat-táblázatot várja (pl. GTIN_2026_09_17_07_29.xlsx). Soronként a "GTIN (EAN) azonosító" alapján megkeresi a termékváltozatot, és a "További kereskedelmi áru azonosító" oszlopba a változat cikkszámát írja. Az eredményt letölti, az adatbázishoz nem nyúl.')}
                </p>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
