{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/keszletlista.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Készlet')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('Készlet')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="keszlet" action="" target="_blank">
                    <div>
                        <label for="DatumEdit">{at('Dátum')}:</label>
                        <input id="DatumEdit" name="datum" data-datum="{$datum}">
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_raktarselect.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="KeszletEdit">{at('Készlet')}:</label>
                        <select id="KeszletEdit" name="keszlet">
                            <option value="1">{at('minden')}</option>
                            <option value="2">{at('ami van')}</option>
                            <option value="3">{at('ami nincs')}</option>
                            <option value="4">{at('ami negatív')}</option>
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="NevEdit">{at('Termék')}:</label>
                        <input id="NevEdit" type="text" name="nevfilter">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="FoglalasEdit">{at('Foglalás számít')}:</label>
                        <input id="FoglalasEdit" type="checkbox" name="foglalasszamit">
                    </div>
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="MinkeszletEdit">{at('Minimum készlet számít')}:</label>
                        <input id="MinkeszletEdit" type="checkbox" name="minkeszletszamit">
                    </div>
                    {if ($setup.multilang)}
                        <div class="matt-hseparator"></div>
                        {include "comp_nyelvselect.tpl"}
                    {/if}
                    <div class="matt-hseparator"></div>
                    <div>
                        <label for="ArsavEdit">{at('Ársáv')}:</label>
                        <select id="ArsavEdit" name="arsav" class="mattable-important">
                            <option value="">{at('mindegy')}</option>

                            <!-- TODO: arsav -->
                            {foreach $arsavlist as $_mk}
                                <option
                                    value="{$_mk.id}_{$_mk.valutanemid}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption} {$_mk.valutanem}</option>
                            {/foreach}
                        </select>
                        <select id="NettoBruttoEdit" name="nettobrutto">
                            <option value="netto">{at('nettó')}</option>
                            <option value="brutto">{at('bruttó')}</option>
                        </select>
                    </div>
                    <div class="matt-hseparator"></div>
                    {include "comp_termekfa.tpl"}
                    <div class="matt-hseparator"></div>
                    <div>
                        <input type="hidden" name="fafilter">
                        <a href="/admin/keszletlista/get" class="js-okbutton">{at('OK')}</a>
                        <a href="/admin/keszletlista/export" class="js-exportbutton">{at('Export')}</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}