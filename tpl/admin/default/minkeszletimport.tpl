{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/minkeszletimport.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Min./opt. készlet import')}</h3>
        </div>
        <form id="minkeszletimport" action="">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div>
                    <input name="toimport" type="file" accept=".xlsx,.xls">
                </div>
                <div class="matt-hseparator"></div>
                <a href="/admin/minkeszletimport/import" class="js-importbutton">{at('Import')}</a>
                <span class="js-importuzenet"></span>
                <p class="mattkarb-hint">
                    {at('A termékek listáján a "Min./opt. készlet export" csoportos művelettel készült fájlt várja: raktáranként egy minimum és egy optimum oszlop. Csak a fájlban szereplő termékek és változatok, és csak a fájlban szereplő oszlopok változnak; az üres vagy nulla raktárcella a raktáras felülírás törlését jelenti. Az oszlopokat a fejléc "id_min_név" / "id_opt_név" előtagja köti a raktárhoz és a készletszinthez, a név szabadon átírható. A régi, csak minimumot tartalmazó fájlok is betölthetők.')}
                </p>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
