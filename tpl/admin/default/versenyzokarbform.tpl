<div id="mattkarb-header">
    <h3>{at('Versenyző')}</h3>
    <h4>{$versenyzo.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/versenyzo/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$versenyzo.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="VersenysorozatEdit">{at('Versenysorozat')}:</label></td>
                    <td><input id="VersenysorozatEdit" name="versenysorozat" type="text" size="80" maxlength="255" value="{$versenyzo.versenysorozat}"></td>
                </tr>
                <tr>
                    <td><label for="CsapatEdit">{at('Csapat')}:</label></td>
                    <td><select id="CsapatEdit" name="csapat">
                            <option value="">{at('válasszon')}</option>
                            {foreach $csapatlist as $csapat}
                                <option value="{$csapat.id}"{if ($csapat.selected)} selected="selected"{/if}>{$csapat.nev}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="KepUrlEdit">{at('Index kép')}:</label></td>
                    <td>
                        <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <tbody>
                            <tr class="imageupload">
                                <td>{if ($versenyzo.kepurl)}<a class="js-toflyout" href="{$mainurl}{$versenyzo.kepurl}" target="_blank"><img
                                            src="{$mainurl}{$versenyzo.kepurlsmall}" alt="{$versenyzo.kepleiras}" title="{$versenyzo.kepleiras}"/></a>{/if}</td>
                                <td>
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td><label for="KepUrlEdit">{at('Kép')}:</label></td>
                                            <td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$versenyzo.kepurl}"></td>
                                            <td><a id="KepBrowseButton" class="js-kepbrowsebutton" href="#" data-id="{$versenyzo.id}"
                                                   title="{at('Browse')}">{at('...')}</a></td>
                                        </tr>
                                        <tr>
                                            <td><label for="KepLeirasEdit">{at('Kép leírása')}:</label></td>
                                            <td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$versenyzo.kepleiras}"></td>
                                            <td><a id="KepDelButton" class="js-kepdelbutton" href="#" data-id="{$versenyzo.id}" title="{at('Töröl')}"><span
                                                        class="ui-icon ui-icon-circle-minus"></span></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><label for="KepUrl1Edit">{at('Adatlap kép 1')}:</label></td>
                    <td>
                        <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <tbody>
                            <tr class="imageupload">
                                <td>{if ($versenyzo.kepurl1)}<a class="js-toflyout" href="{$mainurl}{$versenyzo.kepurl1}" target="_blank"><img
                                            src="{$mainurl}{$versenyzo.kepurl1small}" alt="{$versenyzo.kepleiras1}" title="{$versenyzo.kepleiras1}"/></a>{/if}
                                </td>
                                <td>
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td><label for="KepUrl1Edit">{at('Kép')}:</label></td>
                                            <td><input id="KepUrl1Edit" name="kepurl1" type="text" size="70" maxlength="255" value="{$versenyzo.kepurl1}"></td>
                                            <td><a id="Kep1BrowseButton" class="js-kepbrowsebutton" href="#" data-id="{$versenyzo.id}"
                                                   title="{at('Browse')}">{at('...')}</a></td>
                                        </tr>
                                        <tr>
                                            <td><label for="KepLeiras1Edit">{at('Kép leírása')}:</label></td>
                                            <td><input id="KepLeiras1Edit" name="kepleiras1" type="text" size="70" value="{$versenyzo.kepleiras1}"></td>
                                            <td><a id="Kep1DelButton" class="js-kepdelbutton" href="#" data-id="{$versenyzo.id}" title="{at('Töröl')}"><span
                                                        class="ui-icon ui-icon-circle-minus"></span></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><label for="KepUrl2Edit">{at('Adatlap kép 2')}:</label></td>
                    <td>
                        <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <tbody>
                            <tr class="imageupload">
                                <td>{if ($versenyzo.kepurl2)}<a class="js-toflyout" href="{$mainurl}{$versenyzo.kepurl2}" target="_blank"><img
                                            src="{$mainurl}{$versenyzo.kepurl2small}" alt="{$versenyzo.kepleiras2}" title="{$versenyzo.kepleiras2}"/></a>{/if}
                                </td>
                                <td>
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td><label for="KepUrl2Edit">{at('Kép')}:</label></td>
                                            <td><input id="KepUrl2Edit" name="kepurl2" type="text" size="70" maxlength="255" value="{$versenyzo.kepurl2}"></td>
                                            <td><a id="Kep2BrowseButton" class="js-kepbrowsebutton" href="#" data-id="{$versenyzo.id}"
                                                   title="{at('Browse')}">{at('...')}</a></td>
                                        </tr>
                                        <tr>
                                            <td><label for="KepLeiras2Edit">{at('Kép leírása')}:</label></td>
                                            <td><input id="KepLeiras2Edit" name="kepleiras2" type="text" size="70" value="{$versenyzo.kepleiras2}"></td>
                                            <td><a id="Kep2DelButton" class="js-kepdelbutton" href="#" data-id="{$versenyzo.id}" title="{at('Töröl')}"><span
                                                        class="ui-icon ui-icon-circle-minus"></span></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><label for="KepUrl3Edit">{at('Adatlap kép 3')}:</label></td>
                    <td>
                        <table class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <tbody>
                            <tr class="imageupload">
                                <td>{if ($versenyzo.kepurl3)}<a class="js-toflyout" href="{$mainurl}{$versenyzo.kepurl3}" target="_blank"><img
                                            src="{$mainurl}{$versenyzo.kepurl3small}" alt="{$versenyzo.kepleiras3}" title="{$versenyzo.kepleiras3}"/></a>{/if}
                                </td>
                                <td>
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td><label for="KepUrl3Edit">{at('Kép')}:</label></td>
                                            <td><input id="KepUrl3Edit" name="kepurl3" type="text" size="70" maxlength="255" value="{$versenyzo.kepurl3}"></td>
                                            <td><a id="Kep3BrowseButton" class="js-kepbrowsebutton" href="#" data-id="{$versenyzo.id}"
                                                   title="{at('Browse')}">{at('...')}</a></td>
                                        </tr>
                                        <tr>
                                            <td><label for="KepLeiras3Edit">{at('Kép leírása')}:</label></td>
                                            <td><input id="KepLeiras3Edit" name="kepleiras3" type="text" size="70" value="{$versenyzo.kepleiras3}"></td>
                                            <td><a id="Kep3DelButton" class="js-kepdelbutton" href="#" data-id="{$versenyzo.id}" title="{at('Töröl')}"><span
                                                        class="ui-icon ui-icon-circle-minus"></span></a></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><label for="RovidleirasEdit">{at('Rövid leírás')}:</label></td>
                    <td><textarea id="RovidleirasEdit" name="rovidleiras" cols="80" rows="2">{$versenyzo.rovidleiras}</textarea></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Leírás')}:</label></td>
                    <td><textarea id="LeirasEdit" name="leiras">{$versenyzo.leiras}</textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$versenyzo.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
