{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/leltarimport.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Leltár tény adat import')}</h3>
            <div>{$leltarfej.nev} {$leltarfej.nyitasstr}</div>
            <div>{$leltarfej.raktarnev}</div>
        </div>
        <form id="leltarimport" action="">
            <input type="hidden" name="leltarid" value="{$leltarfej.id}">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div>
                    <input name="toimport" type="file">
                </div>
                <div class="matt-hseparator"></div>
                <a href="/admin/leltarfej/import" class="js-importbutton">{at('Import')}</a>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}