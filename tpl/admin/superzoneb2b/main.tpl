{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/bizonylathelper.js"></script>
    <script type="text/javascript" src="/js/admin/superzoneb2b/appinit.js"></script>
{/block}

{block "kozep"}
    <div class="component-container">
        {include "../default/comp_noallapot.tpl"}
    </div>
    <div class="component-container">
        <div class="ui-widget ui-widget-content ui-corner-all">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Árfolyamok</div>
            </div>
            <div class="mainboxinner">
                <label for="ArfolyamDatumEdit">Dátum:</label>
                <input id="ArfolyamDatumEdit" name="datum" type="text" data-datum="{$today}">
                <button class="js-arfolyamdownload ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                        class="ui-button-text">Letölt</span></button>
            </div>
        </div>
    </div>
    {if (haveJog(20))}
        <div class="component-container">
            <div class="ui-widget ui-widget-content ui-corner-all">
                <div class="ui-widget-header ui-corner-top">
                    <div class="mainboxinner ui-corner-top">Egy raktárban nincs, de a többiben van</div>
                </div>
                <div class="mainboxinner">
                    <form action="/admin/lista/boltbannincsmasholvan" target="_blank">
                        <div>
                            <label for="RaktarEdit">Raktár, amelyikben nincs:</label>
                            <select id="RaktarEdit" name="raktar">
                                <option value="">{t('válasszon')}</option>
                                {foreach $raktarlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div>
                            <label for="MinKeszletEdit">Min. készlet:</label>
                            <input id="MinKeszletEdit" type="number" name="minkeszlet" value="2">
                            <input type="submit" class="ui-widget ui-button ui-state-default ui-corner-all" value="OK">
                        </div>
                        <div>
                            <label>Termékcsoport:</label>
                            <span class="js-boltbannincstermekfabutton" data-text="{t('válasszon')}">{t('válasszon')}</span>
                            <input class="js-boltbannincstermekfainput" name="termekfa" type="hidden">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {/if}
    <div class="component-container">
        <div class="ui-widget ui-widget-content ui-corner-all">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Napi jelentés</div>
            </div>
            <div class="mainboxinner">
                <div class="mainboxinner">
                    <label for="NapijelentesDatumEdit">Dátum:</label>
                    <input id="NapijelentesDatumEdit" name="datumtol" type="text" data-datum="{$today}"{if (!haveJog(20))} disabled="disabled"{/if}> - <input
                        id="NapijelentesDatumigEdit" name="datumig" type="text" data-datum="{$today}"{if (!haveJog(20))} disabled="disabled"{/if}>
                    <button class="js-napijelentes ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                            class="ui-button-text">Frissít</span></button>
                </div>
                {include "napijelentesbody.tpl"}
            </div>
        </div>
    </div>
    {if (haveJog(20))}
        <div class="component-container">
            <div class="ui-widget ui-widget-content ui-corner-all">
                <div class="ui-widget-header ui-corner-top">
                    <div class="mainboxinner ui-corner-top">Kintlevőségek</div>
                </div>
                <div class="mainboxinner">
                    <div class="mainboxinner">
                        <button class="js-refreshkintlevoseg ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                                class="ui-button-text">Frissít</span></button>
                        <div class="js-kintlevoseg"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="component-container">
        <div class="ui-widget ui-widget-content ui-corner-all">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Spanyol kintlevőségek</div>
            </div>
            <div class="mainboxinner">
                <div class="mainboxinner">
                    <button class="js-refreshspanyolkintlevoseg ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                            class="ui-button-text">Frissít</span></button>
                    <div class="js-spanyolkintlevoseg"></div>
                </div>
            </div>
        </div>
        <div class="component-container">
    {/if}
    {if (haveJog(20))}
        <div class="component-container">
            <div class="ui-widget ui-widget-content ui-corner-all">
                <div class="ui-widget-header ui-corner-top">
                    <div class="mainboxinner ui-corner-top">Teljesíthető backorderek</div>
                </div>
                <div class="mainboxinner">
                    <button class="js-refreshteljesithetobackorderek ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                            class="ui-button-text">Frissít</span></button>
                    <div class="js-teljesithetobackorderek"></div>
                </div>
            </div>
        </div>
        <div class="component-container">
            <div class="ui-widget ui-widget-content ui-corner-all">
                <div class="ui-widget-header ui-corner-top">
                    <div class="mainboxinner ui-corner-top">Woocommerce webhook hiba napló</div>
                </div>
                <div class="mainboxinner">
                    <table class="ui-widget-content">
                        <thead>
                        <tr>
                            <th class="mattable-cell mattable-rborder">Dátum</th>
                            <th class="mattable-cell mattable-rborder">Típus</th>
                            <th class="mattable-cell mattable-rborder">Azonosító</th>
                            <th class="mattable-cell mattable-rborder">Hibaüzenet</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $apierrorlog as $logitem}
                            <tr>
                                <td class="datacell mattable-rborder mattable-tborder redtext">{$logitem['created']}</td>
                                <td class="datacell mattable-rborder mattable-tborder">{$logitem['type']}</td>
                                <td class="datacell mattable-rborder mattable-tborder">{$logitem['objectid']}</td>
                                <td class="datacell mattable-rborder mattable-tborder">{$logitem['message']}</td>
                                <td class="datacell mattable-tborder">
                                    <button class="js-apierrorlogclose ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"
                                            data-id="{$logitem['id']}"><span
                                            class="ui-button-text">Rendben</span></button>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="component-container">
            <div class="ui-widget ui-widget-content ui-corner-all">
                <div class="ui-widget-header ui-corner-top">
                    <div class="mainboxinner ui-corner-top">Woocommerce feltöltés</div>
                </div>
                <div class="mainboxinner">
                    <button class="js-wctermekmenu ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                            class="ui-button-text">Termék menük</span></button>
                    <button class="js-wctermekvaltozatadattipus ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                            class="ui-button-text">Termékváltozat adattípusok</span></button>
                    <button class="js-wctermekvaltozatertek ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                            class="ui-button-text">Termékváltozat értékek</span></button>
                    <button class="js-wctermekcimkek ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                            class="ui-button-text">Termék címkék</span></button>
                    <button class="js-wctermek ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                            class="ui-button-text">Termék</span></button>
                    <button class="js-wctermekarak ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span
                            class="ui-button-text">Árak</span></button>
                </div>
            </div>
        </div>
    {/if}
    <div class="component-container">
        <div id="mattkarb">
        </div>
        <div class="component-container">
{/block}