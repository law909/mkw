{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.jstree.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/default/jquery.mattkarb.js"></script>
    <script type="text/javascript" src="/js/admin/default/bizonylathelper.js"></script>
    <script type="text/javascript" src="/js/admin/superzoneb2b/appinit.js"></script>
{/block}

{block "kozep"}
    <div class="clearboth">
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Árfolyamok</div>
            </div>
            <div class="mainboxinner">
                <label for="ArfolyamDatumEdit">Dátum:</label>
                <input id="ArfolyamDatumEdit" name="datum" type="text" data-datum="{$today}">
                <button class="js-arfolyamdownload ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Letölt</span></button>
            </div>
        </div>
        {if (haveJog(20))}
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
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
        {/if}
    </div>
    <div class="clearboth">
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Napi jelentés</div>
            </div>
            <div class="mainboxinner">
                <div class="mainboxinner">
                    <label for="NapijelentesDatumEdit">Dátum:</label>
                    <input id="NapijelentesDatumEdit" name="datumtol" type="text" data-datum="{$today}"{if (!haveJog(20))} disabled="disabled"{/if}> - <input id="NapijelentesDatumigEdit" name="datumig" type="text" data-datum="{$today}"{if (!haveJog(20))} disabled="disabled"{/if}>
                    <button class="js-napijelentes ui-widget ui-button ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Frissít</span></button>
                </div>
                {include "napijelentesbody.tpl"}
            </div>
        </div>
        {if (haveJog(20))}
            <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
                <div class="ui-widget-header ui-corner-top">
                    <div class="mainboxinner ui-corner-top">Kintlevőségek</div>
                </div>
                <div class="mainboxinner">
                    <div class="mainboxinner">
                        <table>
                            <thead>
                                <tr>
                                    <th>Lejárt kintlevőség</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $lejartkintlevoseg as $lk}
                                <tr>
                                    <td>{$lk.nev}</td>
                                    <td class="textalignright">{bizformat($lk.egyenleg)}</td>
                                </tr>
                                {/foreach}
                            </tbody>
                        </table>
                        <table>
                            <thead>
                            <tr>
                                <th>Össz. kintlevőség</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach $kintlevoseg as $lk}
                                <tr>
                                    <td>{$lk.nev}</td>
                                    <td class="textalignright">{bizformat($lk.egyenleg)}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
                <div class="ui-widget-header ui-corner-top">
                    <div class="mainboxinner ui-corner-top">Spanyol kintlevőségek</div>
                </div>
                <div class="mainboxinner">
                    <div class="mainboxinner">
                        <table>
                            <thead>
                            <tr>
                                <th>Lejárt kintlevőség</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach $spanyollejartkintlevoseg as $lk}
                                <tr>
                                    <td>{$lk.nev}</td>
                                    <td class="textalignright">{bizformat($lk.egyenleg)}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                        <table>
                            <thead>
                            <tr>
                                <th>Össz. kintlevőség</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach $spanyolkintlevoseg as $lk}
                                <tr>
                                    <td>{$lk.nev}</td>
                                    <td class="textalignright">{bizformat($lk.egyenleg)}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        {/if}
    </div>
    <div class="clearboth">
        {if (haveJog(20))}
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Teljesíthető backorderek</div>
            </div>
            <div class="mainboxinner">
                <table>
                    <thead>
                        <tr>
                            <td>Biz.szám</td>
                            <td>Kelt</td>
                            <td>Határidő</td>
                            <td>Partner</td>
                            <td></td>
                        </tr>
                    </thead>
                    {foreach $teljesithetobackorderek as $mr}
                        <tr>
                            <td><a href="{$mr.printurl}" target="_blank" title="Nyomtatási kép">{$mr.id}</a></td>
                            <td><a href="{$mr.editurl}" target="_blank" title="Szerkesztés">{$mr.kelt}</a></td>
                            <td>{$mr.hatarido}</td>
                            <td>{$mr.partnernev}</td>
                            <td><a class="js-backorder" href="#" data-egyedid="{$mr.id}" title="{t('Backorder')}"><span class="ui-icon ui-icon-transferthick-e-w"></span></a></td>
                        </tr>
                    {/foreach}
                </table>
            </div>
        </div>
        {/if}
    </div>
    <div class="clearboth">
    <div id="mattkarb" class="mainbox balra">
    </div>
    </div>

{/block}