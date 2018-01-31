{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattaccord.js"></script>
    <script type="text/javascript" src="/js/admin/default/leltar.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Leltár')}</h3>
        </div>
        <form id="leltariv" action="" target="_blank">
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
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
                    <label for="FoglalasEdit">{at('Foglalás számít')}:</label>
                    <input id="FoglalasEdit" type="checkbox" name="foglalasszamit">
                </div>
                <div class="matt-hseparator"></div>
                <div>
                    <label for="ArsavEdit">{at('Ársáv')}:</label>
                    <select id="ArsavEdit" name="arsav" class="mattable-important">
                        <option value="">{at('mindegy')}</option>
                        {foreach $arsavlist as $_mk}
                            <option value="{$_mk.caption}_{$_mk.valutanemid}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption} {$_mk.valutanem}</option>
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
                <a href="/admin/leltar/export" class="js-exportbutton">{at('Export')}</a>
                <input type="hidden" name="fafilter">
            </div>
            <div class="admin-form-footer">
            </div>
        </form>
    </div>
{/block}