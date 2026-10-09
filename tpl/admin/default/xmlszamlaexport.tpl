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
                    {mezocsoport cim="Bizonylat"}
                        {mezo cimke="Típus" szeles=true}
                            <div class="mattkarb-pipak">
                                <span class="mattkarb-pipacimke">
                                    <input id="TipusSzamlaEdit" class="js-tipus" name="tipus" type="radio" value="szamla" checked="checked">
                                    <label for="TipusSzamlaEdit">{at('Számlák')}</label>
                                </span>
                                <span class="mattkarb-pipacimke">
                                    <input id="TipusElolegszamlaEdit" class="js-tipus" name="tipus" type="radio" value="elolegszamla">
                                    <label for="TipusElolegszamlaEdit">{at('Előlegszámlák')}</label>
                                </span>
                            </div>
                        {/mezo}
                    {/mezocsoport}
                    <fieldset class="mattkarb-doboz">
                        <legend>{at('Teljesítés szerinti időszak')}</legend>
                        {mezocsoport egyoszlop=true}
                            {include "comp_idoszak.tpl" comptype="datum" mezo=true}
                        {/mezocsoport}
                        <div class="arsav-gombok">
                            <a href="/admin/xmlszamlaexport/download" class="js-downloadbutton" data-szures="teljesites">{at('Letölt')}</a>
                            <a href="/admin/xmlszamlaexport/sendemail" class="js-emailbutton" data-szures="teljesites">{at('Küld')}</a>
                        </div>
                    </fieldset>
                    <fieldset class="mattkarb-doboz">
                        <legend>{at('Utolsó feladott bizonylatszám')}</legend>
                        {mezocsoport egyoszlop=true}
                            {mezo cimke="Utolsó feladott számla" for="utolsoszamlainput" class="js-tipus-szamla"}
                                <input id="utolsoszamlainput" name="utolsoszamla" value="{$utolsoszamla}">
                            {/mezo}
                            {mezo cimke="Utolsó feladott eseti számla" for="utolsoesetiszamlainput" class="js-tipus-szamla"}
                                <input id="utolsoesetiszamlainput" name="utolsoesetiszamla" value="{$utolsoesetiszamla}">
                            {/mezo}
                            {mezo cimke="Utolsó feladott előlegszámla" for="utolsoelolegszamlainput" class="js-tipus-elolegszamla" rejtve=true}
                                <input id="utolsoelolegszamlainput" name="utolsoelolegszamla" value="{$utolsoelolegszamla}">
                            {/mezo}
                        {/mezocsoport}
                        <div class="arsav-gombok">
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