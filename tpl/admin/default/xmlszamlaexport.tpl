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
                    <div>
                        <input id="TipusSzamlaEdit" class="js-tipus" name="tipus" type="radio" value="szamla" checked="checked">
                        <label for="TipusSzamlaEdit">{at('Számlák')}</label>
                        <input id="TipusElolegszamlaEdit" class="js-tipus" name="tipus" type="radio" value="elolegszamla">
                        <label for="TipusElolegszamlaEdit">{at('Előlegszámlák')}</label>
                    </div>
                    <fieldset class="mattkarb-doboz">
                        <legend>{at('Teljesítés szerinti időszak')}</legend>
                        {include "comp_idoszak.tpl" comptype="datum"}
                        <div>
                            <a href="/admin/xmlszamlaexport/download" class="js-downloadbutton" data-szures="teljesites">{at('Letölt')}</a>
                            <a href="/admin/xmlszamlaexport/sendemail" class="js-emailbutton" data-szures="teljesites">{at('Küld')}</a>
                        </div>
                    </fieldset>
                    <div class="matt-hseparator"></div>
                    <fieldset class="mattkarb-doboz">
                        <legend>{at('Utolsó feladott bizonylatszám')}</legend>
                        <div class="js-tipus-szamla">
                            <label for="utolsoszamlainput">{at('Utolsó feladott számla')}:</label>
                            <input id="utolsoszamlainput" name="utolsoszamla" value="{$utolsoszamla}">
                        </div>
                        <div class="js-tipus-szamla">
                            <label for="utolsoesetiszamlainput">{at('Utolsó feladott eseti számla')}:</label>
                            <input id="utolsoesetiszamlainput" name="utolsoesetiszamla" value="{$utolsoesetiszamla}">
                        </div>
                        <div class="js-tipus-elolegszamla" style="display:none">
                            <label for="utolsoelolegszamlainput">{at('Utolsó feladott előlegszámla')}:</label>
                            <input id="utolsoelolegszamlainput" name="utolsoelolegszamla" value="{$utolsoelolegszamla}">
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