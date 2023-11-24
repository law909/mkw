{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/partnertermekkedvezmenyuploadform.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Partner termék kedvezmény import')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Importok')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div>Csak NAGYKERESKEDELMI VEVŐK típusú partnerekkel dolgozik!</div>
                <div class="matt-hseparator"></div>
                <div>
                    <a href="/admin/partnertermekkedvezmenyupload/del" class="js-del">Előzőek törlése</a>
                </div>
                <div class="matt-hseparator"></div>
                <div>
                    <form id="uploadform" action="" method="post">
                        <div>
                            <label>Alap kedvezmény %:</label>
                            <input name="szazalek" type="text" value="20">
                        </div>
                        <div class="matt-hseparator"></div>
                        <div>
                            <label>Kedvezmények excel tábla:</label>
                            <input name="toimport" type="file">
                        </div>
                    </form>
                    <a href="/admin/partnertermekkedvezmenyupload/upload" class="js-upload">Import</a>
                </div>
            </div>
        </div>
    </div>
{/block}