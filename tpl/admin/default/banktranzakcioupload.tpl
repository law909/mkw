{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
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
                    <div>
                        <label for="formatumedit">{at('Bank')}:</label>
                        <select id="formatumedit" name="formatum">
                            {foreach $formatumok as $_kulcs => $_nev}
                                <option value="{$_kulcs}"{if ($_kulcs == $valasztottformatum)} selected="selected"{/if}>{$_nev}</option>
                            {/foreach}
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="negativisedit">{at('Negatív (terhelés) tételek is')}:</label>
                        <input id="negativisedit" name="negativis" type="checkbox" value="1">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="fileedit">{at('Tranzakciós fájl')}:</label>
                        <input id="fileedit" name="toimport" type="file">
                    </div>
                </div>
            </div>
            <div class="mattkarb-footer">
                <a id="mattkarb-okbutton" href="#" class="js-upload">{at('OK')}</a>
            </div>
        </form>
    </div>
{/block}