<div id="mattkarb-header" data-partnerautocomplete="{$setup.partnerautocomplete}">
    <h3>{at('Bank tranzakció')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="AzonEdit">{at('Azonosító')}:</label></td>
                    <td><input id="AzonEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.azonosito}" disabled></td>
                </tr>
                <tr>
                    <td><label for="KonyvelesdatumEdit">{at('Könyvelés dátuma')}:</label></td>
                    <td><input id="KonyvelesdatumEdit" name="konyvelesdatum" type="text" value="{$egyed.konyvelesdatumstr}" disabled></td>
                </tr>
                <tr>
                    <td><label for="ErteknapEdit">{at('Értéknap')}:</label></td>
                    <td><input id="ErteknapEdit" name="erteknapdatum" type="text" value="{$egyed.erteknapstr}" disabled></td>
                </tr>
                <tr>
                    <td><label for="OsszegEdit">{at('Összeg')}:</label></td>
                    <td><input id="OsszegEdit" name="osszeg" type="text" value="{$egyed.osszeg}" disabled></td>
                </tr>
                <tr>
                    <td><label for="Kozl1Edit">{at('Közlemény 1')}:</label></td>
                    <td><input id="Kozl1Edit" name="kozlemeny1" type="text" value="{$egyed.kozlemeny1}" disabled></td>
                </tr>
                <tr>
                    <td><label for="Kozl2Edit">{at('Közlemény 2')}:</label></td>
                    <td><input id="Kozl2Edit" name="kozlemeny2" type="text" value="{$egyed.kozlemeny2}" disabled></td>
                </tr>
                <tr>
                    <td><label for="Kozl3Edit">{at('Közlemény 3')}:</label></td>
                    <td><input id="Kozl3Edit" name="kozlemeny3" type="text" value="{$egyed.kozlemeny3}" disabled></td>
                </tr>
                <tr>
                    <td><label for="BizonylatszamokEdit">{at('Bizonylatszámok')}:</label></td>
                    <td><input id="BizonylatszamokEdit" name="bizonylatszamok" type="text" value="{$egyed.bizonylatszamok}"></td>
                </tr>
                <tr>
                    <td class="mattable-important"><label for="PartnerEdit">{at('Partner')}:</label></td>
                    {if ($setup.partnerautocomplete)}
                        <td colspan="7">
                            <input id="PartnerEdit" type="text" name="partnerautocomlete" class="js-partnerautocomplete mattable-important"
                                   value="{$egyed.partnernev}" size=90>
                            <input class="js-partnerid" name="partner" type="hidden" value="{$egyed.partner}">
                        </td>
                    {else}
                        <td colspan="7">
                            <select id="PartnerEdit" name="partner" class="js-partnerid mattable-important">
                                <option value="">{at('válasszon')}</option>
                                {foreach $partnerlist as $_mk}
                                    <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    {/if}
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>