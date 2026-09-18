{extends "../base.tpl"}

{block "inhead"}
    {include "../partials/form.scripts.tpl"}
    <script type="text/javascript" src="/js/admin/default/valtozatosszevonas.js"></script>
{/block}

{block "kozep"}
    <div id="mattkarb">
        <div id="mattkarb-header">
            <h3>{at('Változat összevonás')}</h3>
        </div>
        <div id="DefaTab" class="mattkarb-page" data-visible="visible">
            <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                <tbody>
                <tr>
                    <td><label for="OsszevonasTermekEdit">{at('Termék')}:</label></td>
                    <td colspan="2">
                        <input id="OsszevonasTermekEdit" class="js-termekselect termekselect" type="text" size="60"
                               placeholder="{at('név, cikkszám vagy vonalkód')}">
                        <input class="js-termekid" type="hidden" value="">
                        <span class="js-termekjelzo"></span>
                    </td>
                </tr>
                <tr>
                    <td><label for="OsszevonasForrasEdit">{at('Erről a változatról')}:</label></td>
                    <td colspan="2">
                        <select id="OsszevonasForrasEdit" class="js-forras">
                            <option value="">{at('válasszon')}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="OsszevonasCelEdit">{at('Erre a változatra')}:</label></td>
                    <td colspan="2">
                        <select id="OsszevonasCelEdit" class="js-cel">
                            <option value="">{at('válasszon')}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="OsszevonasTorlesEdit">{at('Az „erről” változat törlése az összevonás után')}:</label></td>
                    <td colspan="2"><input id="OsszevonasTorlesEdit" class="js-forrastorles" type="checkbox"></td>
                </tr>
                </tbody>
            </table>
            <div class="matt-hseparator"></div>
            <a href="#" class="js-okbutton">{at('OK')}</a>
            <span class="js-osszevonasuzenet"></span>
            <div class="js-statisztika"></div>
            <p class="mattkarb-hint">
                {at('Az „erről” változatra hivatkozó minden sor (bizonylattétel, munkalap, kosár, leltártétel, raktáras minimum és optimális készlet) az „erre” változatra íródik át, a bizonylattételek származtatott mezőivel — változat értékei, adattípusai, cikkszáma — együtt. A FIFO rétegek a termékre újraszámolódnak. A művelet nem visszafordítható.')}
            </p>
        </div>
    </div>
{/block}
