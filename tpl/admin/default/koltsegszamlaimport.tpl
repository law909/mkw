{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/koltsegszamlaimport.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('NAV bejövő számla import')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Import')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                {if ($figyelmeztetes)}
                    <div class="matt-messagecenter ui-widget ui-state-error" style="padding:5px;margin-bottom:5px;">
                        {$figyelmeztetes}
                    </div>
                {/if}

                <form id="koltsegszamlaimport" method="post" action="/admin/koltsegszamlaimport/process"
                      data-hibauzenet="{at('A letöltés nem fejeződött be. Nézze meg a hibanaplót, majd próbálja újra.')}">
                    <div>
                        <label for="TolEdit">{at('Időszak')}:</label>
                        <input id="TolEdit" name="tol" data-datum="{$toldatum}" value="{$toldatum}">
                        <input id="IgEdit" name="ig" data-datum="{$igdatum}" value="{$igdatum}">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        {at('A NAV legfeljebb')} {$maxnap} {at('napos időszakra ad számlalistát.')}
                    </div>
                    <div class="admin-form-footer">
                        <input type="submit" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" value="{at('Számlák letöltése')}">
                    </div>
                </form>

                {* ide kerül az import eredménye, a koltsegszamlaimport_eredmeny.tpl-ből, AJAX-szal *}
                <div id="importeredmeny"></div>
            </div>
        </div>
    </div>
{/block}
