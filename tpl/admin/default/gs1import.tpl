{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/gs1import.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('GS1 vonalkód import')}</h3>
        </div>
        <form id="gs1import" action="">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div>
                    <input name="toimport" type="file" accept=".xlsx,.xls">
                </div>
                <div class="matt-hseparator"></div>
                <a href="/admin/termek/gs1import" class="js-importbutton">{at('Import')}</a>
                <span class="js-importuzenet"></span>
                <p class="mattkarb-hint">
                    {at('A GS1-től visszakapott számkiadási fájlt várja – azt, ami a termékek listáján a "GS1 export" csoportos művelettel készült, kiadott GTIN-ekkel. A vonalkód a "GTIN (EAN) azonosító" oszlopból jön, a termék vagy változat azonosítója pedig a táblázat utolsó két oszlopából. Meglévő vonalkódot nem ír felül.')}
                </p>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
