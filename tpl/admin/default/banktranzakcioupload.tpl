{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/banktranzakcioupload.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Bank tranzakció feltöltés')}</h3>
        </div>
        <form id="mattkarb-form" method="post" action="/admin/banktranzakcio/upload">
            <div id="mattkarb-tabs">
                <ul>
                    <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
                </ul>
                <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
                    <label for="fileedit">{at('Tranzakciós fájl')}:</label>
                    <input id="fileedit" name="toimport" type="file">
                </div>
            </div>
            <div class="mattkarb-footer">
                <a id="mattkarb-okbutton" href="#" class="js-upload">{at('OK')}</a>
            </div>
        </form>
    </div>
{/block}