<div id="mattkarb-header">
    <h3>{at('Hír')}</h3>
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
                    <td colspan="2"><input id="LathatoCheck" name="lathato" type="checkbox"
                                           {if ($egyed.lathato)}checked="checked"{/if}>{at('Weboldalon látható')}</input></td>
                </tr>
                <tr>
                    <td><label for="DatumEdit">{at('Hír dátuma')}:</label></td>
                    <td><input id="DatumEdit" name="datum" type="text" size="12" data-datum="{$egyed.datumstr}" required></td>
                </tr>
                <tr>
                    <td><label for="ElsoDatumEdit">{at('Első megjelenés')}:</label></td>
                    <td><input id="ElsoDatumEdit" name="elsodatum" type="text" size="12" data-datum="{$egyed.elsodatumstr}" required></td>
                </tr>
                <tr>
                    <td><label for="UtolsoDatumEdit">{at('Utolsó megjelenés')}:</label></td>
                    <td><input id="UtolsoDatumEdit" name="utolsodatum" type="text" size="12" data-datum="{$egyed.utolsodatumstr}" required></td>
                </tr>
                <tr>
                    <td><label for="CimEdit">{at('Cím')}:</label></td>
                    <td colspan="5"><input id="CimEdit" name="cim" type="text" size="80" maxlength="255" value="{$egyed.cim}"></td>
                </tr>
                <tr>
                    <td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
                    <td><input id="SorrendEdit" name="sorrend" type="number" size="10" maxlength="10" value="{$egyed.sorrend}"></td>
                </tr>
                <tr>
                    <td><label for="ForrasEdit">{at('Forrás')}:</label></td>
                    <td colspan="5"><input id="ForrasEdit" name="forras" type="text" size="80" maxlength="255" value="{$egyed.forras}"></td>
                </tr>
                <tr>
                    <td><label for="LeadEdit">{at('Lead')}:</label></td>
                    <td colspan="5"><textarea id="LeadEdit" name="lead" cols="70">{$egyed.lead}</textarea></td>
                </tr>
                <tr>
                    <td><label for="SzovegEdit">{at('Szöveg')}:</label></td>
                    <td colspan="5"><textarea id="SzovegEdit" name="szoveg" cols="70">{$egyed.szoveg}</textarea></td>
                </tr>
                <tr>
                    <td><label for="SeoDescriptionEdit">{at('META leírás')}:</label></td>
                    <td><textarea id="SeoDescriptionEdit" name="seodescription" cols="70">{$egyed.seodescription}</textarea></td>
                </tr>
                <tr class="imageupload">
                    <td>{if ($egyed.kepurl)}<a class="js-toflyout" href="{$mainurl}{$egyed.kepurl}" target="_blank"><img
                                src="{$mainurl}{$egyed.kepurlsmall}" alt="{$egyed.kepleiras}" title="{$egyed.kepleiras}"/></a>{/if}</td>
                    <td>
                        <table>
                            <tbody>
                            <tr>
                                <td><label for="KepUrlEdit">{at('Kép')}:</label></td>
                                <td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$egyed.kepurl}"></td>
                                <td><a id="KepBrowseButton" class="js-kepbrowsebutton" href="#" data-id="{$egyed.id}"
                                       title="{at('Browse')}">{at('...')}</a></td>
                            </tr>
                            <tr>
                                <td><label for="KepLeirasEdit">{at('Kép leírása')}:</label></td>
                                <td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$egyed.kepleiras}"></td>
                                <td><a id="KepDelButton" class="js-kepdelbutton" href="#" data-id="{$egyed.id}" title="{at('Töröl')}"><span
                                            class="ui-icon ui-icon-circle-minus"></span></a></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
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