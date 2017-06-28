{extends "../base.tpl"}

{block "inhead"}
    <script type="text/javascript" src="/js/admin/default/jquery.form.js"></script>
    <script type="text/javascript" src="/js/admin/darshan/appinit.js"></script>
{/block}

{block "kozep"}
    <div class="clearboth">
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">{at('Pénzt VESZEK KI')}</div>
            </div>
            <div class="mainboxinner">
                <form id="KipenztarForm" method="post" action="{$formaction}">
                    <input id="KiIranyEdit" name="irany" value="-1" type="hidden">
                    <input name="quick" value="1" type="hidden">
                    <input name="oper" value="add" type="hidden">
                    <table>
                        <tbody>
                            <tr>
                                <td class="mattable-important"><label for="KeltEdit">{at('Mikor')}:</label></td>
                                <td><input id="KiKeltEdit" name="kelt" type="text" size="12" data-datum="{$keltstr}" class="mattable-important" required="required"></td>
                            </tr>
                            <tr>
                                <td><label for="KiPenztarEdit">{at('Honnan')}:</label></td>
                                <td>
                                    <select id="KiPenztarEdit" name="penztar" required="required">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $penztarlist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="mattable-important"><label for="KiPartnerEdit">{at('Kinek adom')}:</label></td>
                                <td colspan="3">
                                    <select id="KiPartnerEdit" name="partner" class="mattable-important" required="required">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $partnerlist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="KiMegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                                <td colspan="7"><textarea id="KiMegjegyzesEdit" name="megjegyzes" rows="1" cols="50"></textarea></td>
                            </tr>
                            <tr>
                                <td><label for="KiSzovegEdit">{at('Mire veszem ki')}:</label></td>
                                <td><input id="KiSzovegEdit" name="tetelszoveg" value=""></td>
                            </tr>
                            <tr>
                                <td class="mattable-important"><label for="KiJogcimEdit">{at('Jogcím')}:</label></td>
                                <td>
                                    <select id="KiJogcimEdit" name="teteljogcim" class="mattable-important" required="required">
                                        <option value="">{at('válasszon')}</option>
                                        {foreach $jogcimlist as $_mk}
                                            <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="mattable-important"><label for="KiHivatkozottBizonylatEdit">{at('Hivatkozott bizonylat')}:</label></td>
                                <td>
                                    <input id="KiHivatkozottBizonylatEdit" name="tetelhivatkozottbizonylat" value="">
                                    <a class="js-kihivatkozottbizonylatbutton">{at('Keresés')}</a>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="KiEsedekessegEdit">{at('Esedékesség')}:</label></td>
                                <td><input id="KiEsedekessegEdit" name="tetelhivatkozottdatum" readonly></td>
                            </tr>
                            <tr>
                                <td><label for="KiOsszegEdit">{at('Összeg')}:</label></td>
                                <td><input id="KiOsszegEdit" name="tetelosszeg" type="number" required="required" step="any"></td>
                            </tr>
                            <tr>
                                <td>
                                    <input id="KiOKButton" type="submit" value="OK">
                                    <a id="KiCancelButton" href="#"><span>Mégsem</span></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">{at('Pénzt TESZEK BE')}</div>
            </div>
            <div class="mainboxinner">
                <form id="BepenztarForm" method="post" action="{$formaction}">
                    <input id="BeIranyEdit" name="irany" value="1" type="hidden">
                    <input name="quick" value="1" type="hidden">
                    <input name="oper" value="add" type="hidden">
                    <table>
                        <tbody>
                        <tr>
                            <td class="mattable-important"><label for="KeltEdit">{at('Mikor')}:</label></td>
                            <td><input id="BeKeltEdit" name="kelt" type="text" size="12" data-datum="{$keltstr}" class="mattable-important" required="required"></td>
                        </tr>
                        <tr>
                            <td><label for="BePenztarEdit">{at('Hova')}:</label></td>
                            <td>
                                <select id="BePenztarEdit" name="penztar" required="required">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $penztarlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if} data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="BePartnerEdit">{at('Kitől kaptam')}:</label></td>
                            <td colspan="3">
                                <select id="BePartnerEdit" name="partner" class="mattable-important" required="required">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $partnerlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="BeMegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                            <td colspan="7"><textarea id="BeMegjegyzesEdit" name="megjegyzes" rows="1" cols="50"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="BeSzovegEdit">{at('Mire kaptam')}:</label></td>
                            <td><input id="BeSzovegEdit" name="tetelszoveg" value=""></td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="BeJogcimEdit">{at('Jogcím')}:</label></td>
                            <td>
                                <select id="BeJogcimEdit" name="teteljogcim" class="mattable-important" required="required">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $jogcimlist as $_mk}
                                        <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="mattable-important"><label for="BeHivatkozottBizonylatEdit">{at('Hivatkozott bizonylat')}:</label></td>
                            <td>
                                <input id="BeHivatkozottBizonylatEdit" name="tetelhivatkozottbizonylat" value="">
                                <a class="js-behivatkozottbizonylatbutton">{at('Keresés')}</a>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="BeEsedekessegEdit">{at('Esedékesség')}:</label></td>
                            <td><input id="BeEsedekessegEdit" name="tetelhivatkozottdatum" readonly></td>
                        </tr>
                        <tr>
                            <td><label for="BeOsszegEdit">{at('Összeg')}:</label></td>
                            <td><input id="BeOsszegEdit" name="tetelosszeg" type="number" required="required" step="any"></td>
                        </tr>
                        <tr>
                            <td>
                                <input id="BeOKButton" type="submit" value="OK">
                                <a id="BeCancelButton" href="#"><span>Mégsem</span></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class="clearboth">
        <div class="mainbox ui-widget ui-widget-content ui-corner-all balra">
            <div class="ui-widget-header ui-corner-top">
                <div class="mainboxinner ui-corner-top">Vettem valamit a Darshannak</div>
            </div>
            <div class="mainboxinner">
                <div class="mainboxinner">
                </div>
            </div>
        </div>
    </div>
{/block}