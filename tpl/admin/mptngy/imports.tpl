{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/mptngy/importsform.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{t('Termék importok')}</h3>
        </div>
        <form id="mattkarb-form" action="" method="post">
            <div id="mattkarb-tabs">
                <ul>
                    <li><a href="#DefaTab">{t('Importok')}</a></li>
                </ul>
                <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                    <div class="matt-hseparator"></div>
                    <div>
                        <label>Importálandó fájl:</label>
                        <input name="toimport" type="file">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <a href="/admin/import/mptngybiraloimport" class="js-mptngybiraloimport">Bíráló import</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
{/block}