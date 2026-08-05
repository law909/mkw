<div id="mattkarb-header">
    <h3>{$pagetitle}</h3>
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
                    <td class="mattable-important"><label for="KeltEdit">{at('Kelt')}:</label></td>
                    <td><input id="KeltEdit" name="kelt" type="text" size="12" data-datum="{$egyed.keltstr}" class="mattable-important" required="required"></td>
                </tr>
                <tr>
                    <td class="mattable-important"><label for="HonnanPenztarEdit">{at('Honnan')}:</label></td>
                    <td>
                        <select id="HonnanPenztarEdit" name="honnanpenztar" class="mattable-important" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $penztarlist as $_mk}
                                <option value="{$_mk.id}" data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td class="mattable-important"><label for="HovaPenztarEdit">{at('Hová')}:</label></td>
                    <td>
                        <select id="HovaPenztarEdit" name="hovapenztar" class="mattable-important" required="required">
                            <option value="">{at('válasszon')}</option>
                            {foreach $penztarlist as $_mk}
                                <option value="{$_mk.id}" data-valutanem="{$_mk.valutanem}">{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="ValutanemEdit">{at('Valutanem')}:</label></td>
                    <td>
                        <select id="ValutanemEdit" name="valutanemselect" disabled="disabled">
                            <option value="">{at('válasszon')}</option>
                            {foreach $valutanemlist as $_mk}
                                <option value="{$_mk.id}">{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                    <td><label for="ArfolyamEdit">{at('Árfolyam')}:</label></td>
                    <td><input id="ArfolyamEdit" name="arfolyam" type="text" value="{$egyed.arfolyam}"></td>
                </tr>
                <tr>
                    <td><label for="PartnerEdit">{at('Partner')}:</label></td>
                    <td colspan="3">
                        <select id="PartnerEdit" name="partner">
                            <option value="">{at('válasszon')}</option>
                            {foreach $partnerlist as $_mk}
                                <option value="{$_mk.id}"{if ($_mk.selected)} selected="selected"{/if}>{$_mk.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                {if ($showerbizonylatszam)}
                    <tr>
                        <td><label for="ErbizonylatszamEdit">{at('Eredeti biz.szám')}:</label></td>
                        <td><input id="ErbizonylatszamEdit" name="erbizonylatszam" type="text" value="{$egyed.erbizonylatszam}"></td>
                    </tr>
                {/if}
                <tr>
                    <td><label for="MegjegyzesEdit">{at('Megjegyzés')}:</label></td>
                    <td colspan="7"><textarea id="MegjegyzesEdit" name="megjegyzes" rows="1" cols="100">{$egyed.megjegyzes}</textarea></td>
                </tr>
                </tbody>
            </table>
            <div class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                <table>
                    <tbody>
                    <tr>
                        <td><label for="SzovegEdit">{at('Szöveg')}:</label></td>
                        <td><input id="SzovegEdit" name="szoveg" size="60" value="{$egyed.szoveg}"></td>
                    </tr>
                    <tr>
                        <td class="mattable-important"><label for="JogcimEdit">{at('Jogcím')}:</label></td>
                        <td>
                            <select id="JogcimEdit" name="jogcim" class="mattable-important" required="required">
                                <option value="">{at('válasszon')}</option>
                                {foreach $jogcimlist as $_mk}
                                    <option value="{$_mk.id}">{$_mk.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="mattable-important"><label for="OsszegEdit">{at('Összeg')}:</label></td>
                        <td><input id="OsszegEdit" name="osszeg" type="number" step="any" required="required" value="{$egyed.osszeg}"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
