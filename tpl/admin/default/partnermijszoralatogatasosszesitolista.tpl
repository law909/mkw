{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/partnermijszoralatogatasosszesitolista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Óralatogatás összesítő lista')}</h3>
        </div>
        <form id="partnermijszoralatogatasosszesito" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <div class="matt-hseparator"></div>
                <div>
                    <label for="EvEdit">{at('Év')}:</label>
                    <input id="EvEdit" type="text" name="ev">
                </div>
                <div class="matt-hseparator"></div>
                <a href="#" class="js-refresh">{at('Frissít')}</a>
                <a href="/admin/partnermijszoralatogatasosszesitolista/export" class="js-exportbutton">{at('Export')}</a>
                <div class="matt-hseparator"></div>
                <div id="eredmeny"></div>
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}