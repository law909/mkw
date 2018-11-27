{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/pdfszamlaexport.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('PDF számla küldés')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('PDF számla küldés')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="pdfszamlaexport" action="" target="_blank">
                    <div>
                    <label>{at('Utolsó feladott számla')}:</label>
                    <input id="utolsoszamlainput" name="utolsoszamla" value="{$utolsoszamla}">
                    </div>
                    <div>
                    <label>{at('Utolsó feladott eseti számla')}:</label>
                    <input id="utolsoesetiszamlainput" name="utolsoesetiszamla" value="{$utolsoesetiszamla}">
                    </div>
                    <div>
                        <a href="/admin/pdfszamlaexport/download" class="js-downloadbutton">{at('Letölt')}</a>
                        <a href="/admin/pdfszamlaexport/sendemail" class="js-emailbutton">{at('Küld')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}