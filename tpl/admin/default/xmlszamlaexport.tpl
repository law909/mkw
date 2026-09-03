{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/xmlszamlaexport.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('XML számla küldés')}</h3>
        </div>
        <div id="mattkarb-tabs">
            <ul>
                <li><a href="#DefaTab">{at('XML számla küldés')}</a></li>
            </ul>
            <div id="DefaTab" class="mattkarb-page" data-visible="visible">
                <form id="xmlszamlaexport" action="" target="_blank">
                    {* a két doboz külön szűr: melyiknek a gombját nyomták, azt a szerver ebből tudja meg *}
                    <input name="szures" type="hidden" value="">
                    <fieldset class="mattkarb-doboz">
                        <legend>{at('Kelt szerinti időszak')}</legend>
                        {include "comp_idoszak.tpl" comptype="datum"}
                        <div>
                            <a href="/admin/xmlszamlaexport/download" class="js-downloadbutton" data-szures="kelt">{at('Letölt')}</a>
                            <a href="/admin/xmlszamlaexport/sendemail" class="js-emailbutton" data-szures="kelt">{at('Küld')}</a>
                        </div>
                    </fieldset>
                    <div class="matt-hseparator"></div>
                    <fieldset class="mattkarb-doboz">
                        <legend>{at('Utolsó feladott bizonylatszám')}</legend>
                        <div>
                            <label for="utolsoszamlainput">{at('Utolsó feladott számla')}:</label>
                            <input id="utolsoszamlainput" name="utolsoszamla" value="{$utolsoszamla}">
                        </div>
                        <div>
                            <label for="utolsoesetiszamlainput">{at('Utolsó feladott eseti számla')}:</label>
                            <input id="utolsoesetiszamlainput" name="utolsoesetiszamla" value="{$utolsoesetiszamla}">
                        </div>
                        <div>
                            <a href="/admin/xmlszamlaexport/download" class="js-downloadbutton" data-szures="szam">{at('Letölt')}</a>
                            <a href="/admin/xmlszamlaexport/sendemail" class="js-emailbutton" data-szures="szam">{at('Küld')}</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="admin-form-footer">
        </div>
    </div>
{/block}