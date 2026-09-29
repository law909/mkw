{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/uploaderror.js"></script>
    <script type="text/javascript" src="/js/admin/default/cgmtermekimport.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('CGM termék import')}</h3>
        </div>
        <form id="cgmtermekimport" action="">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div>
                    <input name="toimport" type="file" accept=".xlsx,.xls">
                </div>
                <div class="matt-hseparator"></div>
                <a href="/admin/cgmtermekimport/import" class="js-importbutton">{at('Import')}</a>
                <span class="js-importuzenet"></span>
                <p class="mattkarb-hint">
                    {at('Az 1. sor fejléc. Az A oszlopban "X"-szel jelölt sor a termék, a B oszlop azonos számú sorai a változatai. A változat cikkszáma C_D, mérete D, színe H, vonalkódja G. A termék neve az E oszlop a szín (H) nélkül, a kategóriája az I (a kategóriafában pontos névvel keresve), a bruttó kisker ára a K, a gyártója az M. A már meglévő cikkszámú terméket nem módosítja, csak a hiányzó változatait veszi fel.')}
                </p>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}
