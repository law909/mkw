<div id="mattkarb-header">
    <h3>{at('Csapat')}</h3>
    <h4>{$csapat.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/csapat/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$csapat.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="LogoUrlEdit">{at('Logo')}:</label></td>
                    <td>
                        <table id="LogoImageEdit" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <tbody>
                            <tr class="imageupload">
                                <td>{if ($csapat.logourl)}<a class="js-toflyout" href="{$mainurl}{$csapat.logourl}" target="_blank"><img
                                            src="{$mainurl}{$csapat.logourlsmall}" alt="{$csapat.logoleiras}" title="{$csapat.logoleiras}"/></a>{/if}</td>
                                <td>
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td><label for="LogoUrlEdit">{at('Logo')}:</label></td>
                                            <td><input id="LogoUrlEdit" name="logourl" type="text" size="70" maxlength="255" value="{$csapat.logourl}"></td>
                                            <td><a id="LogoKepBrowseButton" class="js-kepbrowsebutton" href="#" data-id="{$csapat.id}"
                                                   title="{at('Browse')}">{at('...')}</a></td>
                                        </tr>
                                        <tr>
                                            <td><label for="LogoLeirasEdit">{at('Logo leírása')}:</label></td>
                                            <td><input id="LogoLeirasEdit" name="logoleiras" type="text" size="70" value="{$csapat.logoleiras}"></td>
                                            <td><a id="LogoKepDelButton" class="js-kepdelbutton" href="#" data-id="{$csapat.id}" title="{at('Töröl')}"><span
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
                    <td><label for="KepUrlEdit">{at('Kép')}:</label></td>
                    <td>
                        <table id="FoImageEdit" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                            <tbody>
                            <tr class="imageupload">
                                <td>{if ($csapat.kepurl)}<a class="js-toflyout" href="{$mainurl}{$csapat.kepurl}" target="_blank"><img
                                            src="{$mainurl}{$csapat.kepurlsmall}" alt="{$csapat.kepleiras}" title="{$csapat.kepleiras}"/></a>{/if}</td>
                                <td>
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td><label for="KepUrlEdit">{at('Kép')}:</label></td>
                                            <td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$csapat.kepurl}"></td>
                                            <td><a id="FoKepBrowseButton" class="js-kepbrowsebutton" href="#" data-id="{$csapat.id}"
                                                   title="{at('Browse')}">{at('...')}</a></td>
                                        </tr>
                                        <tr>
                                            <td><label for="KepLeirasEdit">{at('Kép leírása')}:</label></td>
                                            <td><input id="KepLeirasEdit" name="kepleiras" type="text" size="70" value="{$csapat.kepleiras}"></td>
                                            <td><a id="FoKepDelButton" class="js-kepdelbutton" href="#" data-id="{$csapat.id}" title="{at('Töröl')}"><span
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
                    <td><label for="LeirasEdit">{at('Leírás')}:</label></td>
                    <td><textarea id="LeirasEdit" name="leiras">{$csapat.leiras}</textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$csapat.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
