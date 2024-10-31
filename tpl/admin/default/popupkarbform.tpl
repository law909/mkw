<div id="mattkarb-header">
    {if ($egyed.kepurlsmall)}
        <img class="mattedit-headerimage" src="{$mainurl}{$egyed.kepurlsmall}"/>
    {/if}
    <h3>{at('Blogposzt')}</h3>
    <h4><a href="{$mainurl}/blog/{$egyed.slug}" target="_blank">{$egyed.cim}</a></h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/popup/save" data-id="{$egyed.id}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <input id="InaktivCheck" name="inaktiv" type="checkbox"
                   {if ($egyed.inaktiv)}checked="checked"{/if}>{at('Inaktív')}
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" value="{$egyed.nev}"></td>
                </tr>
                <tr>
                    <td><label for="DisplayTimeEdit">{at('Megjelenés késleltetése (mp)')}:</label></td>
                    <td><input id="DisplayTimeEdit" name="displaytime" type="number" value="{$egyed.displaytime}">
                        <input id="trCheck"
                               name="triggerafterprevious"
                               type="checkbox"
                               {if ($egyed.triggerafterprevious)}checked="checked"{/if}>{at('Az előző popup bezárása után')}
                    </td>
                </tr>
                <tr>
                    <td><label for="overlaybackgroundcolorEdit">{at('Overlay háttérszín')}:</label></td>
                    <td><input id="overlaybackgroundcolorEdit" name="overlaybackgroundcolor" type="text" value="{$egyed.overlaybackgroundcolor}"></td>
                </tr>
                <tr>
                    <td><label for="overlayopacityEdit">{at('Overlay átlátszóság')}:</label></td>
                    <td><input id="overlayopacityEdit" name="overlayopacity" type="number" value="{$egyed.overlayopacity}"></td>
                </tr>
                <tr>
                    <td><label for="headertextEdit">{at('Cím')}:</label></td>
                    <td><input id="headertextEdit" name="headertext" type="text" value="{$egyed.headertext}"></td>
                </tr>
                <tr>
                    <td><label for="bodytextEdit">{at('Szöveg')}:</label></td>
                    <td><textarea id="bodytextEdit" name="bodytext" type="text">{$egyed.bodytext}</textarea></td>
                </tr>
                <tr>
                    <td><label for="closebuttontextEdit">{at('"Bezár" gomb felirat')}:</label></td>
                    <td><input id="closebuttontextEdit" name="closebuttontext" type="text" value="{$egyed.closebuttontext}"></td>
                </tr>
                <tr>
                    <td><label for="closebuttoncolorEdit">{at('"Bezár" gomb betűszín')}:</label></td>
                    <td><input id="closebuttoncolorEdit" name="closebuttoncolor" type="text" value="{$egyed.closebuttoncolor}"></td>
                </tr>
                <tr>
                    <td><label for="closebuttonbackgroundcolorEdit">{at('"Bezár" gomb háttérszín')}:</label></td>
                    <td><input id="closebuttonbackgroundcolorEdit" name="closebuttonbackgroundcolor" type="text" value="{$egyed.closebuttonbackgroundcolor}">
                    </td>
                </tr>
                <tr>
                    <td><label for="contentwidthEdit">{at('Tartalom szélessége')}:</label></td>
                    <td><input id="contentwidthEdit" name="contentwidth" type="text" value="{$egyed.contentwidth}"></td>
                </tr>
                <tr>
                    <td><label for="contentheightEdit">{at('Tartalom magassága')}:</label></td>
                    <td><input id="contentheightEdit" name="contentheight" type="text" value="{$egyed.contentheight}"></td>
                </tr>
                <tr>
                    <td><label for="contenttopEdit">{at('Tartalom teteje')}:</label></td>
                    <td><input id="contenttopEdit" name="contenttop" type="text" value="{$egyed.contenttop}"></td>
                </tr>
                <tr>
                    <td><label for="popuporderEdit">{at('Sorrend')}:</label></td>
                    <td><input id="popuporderEdit" name="popuporder" type="number" value="{$egyed.popuporder}"></td>
                </tr>
                </tbody>
            </table>
            <div>
                <table id="FoImageEdit" class="ui-widget ui-widget-content ui-corner-all mattable-repeatable">
                    <tbody>
                    <tr class="imageupload">
                        <td>{if ($egyed.kepurl)}<a class="js-toflyout" href="{$mainurl}{$egyed.kepurl}" target="_blank"><img
                                    src="{$mainurl}{$egyed.kepurlsmall}"/></a>{/if}</td>
                        <td>
                            <table>
                                <tbody>
                                <tr>
                                    <td><label for="KepUrlEdit">{at('Háttérkép')}:</label></td>
                                    <td><input id="KepUrlEdit" name="kepurl" type="text" size="70" maxlength="255" value="{$egyed.kepurl}"></td>
                                    <td><a id="FoKepBrowseButton" href="#" data-id="{$egyed.id}" title="{at('Browse')}">{at('...')}</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
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