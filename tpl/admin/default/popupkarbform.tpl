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
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit"}
                    <input id="NevEdit" name="nev" type="text" value="{$egyed.nev}" title="csak belső azonosításra, nem jelenik meg">
                {/mezo}
                {mezo cimke="Megjelenés késleltetése (mp)" for="DisplayTimeEdit"}
                    <div class="mattkarb-mezogomb">
                        <input id="DisplayTimeEdit" name="displaytime" type="number" value="{$egyed.displaytime}">
                        <input id="trCheck"
                            name="triggerafterprevious"
                            type="checkbox"
                        {if ($egyed.triggerafterprevious)}checked="checked"{/if}>{at('Az előző popup bezárása után')}
                    </div>
                {/mezo}
                {mezo cimke="Overlay háttérszín" for="overlaybackgroundcolorEdit"}
                    <input id="overlaybackgroundcolorEdit" name="overlaybackgroundcolor" type="text" value="{$egyed.overlaybackgroundcolor}"
                        title="#rrggbb">
                {/mezo}
                {mezo cimke="Overlay átlátszóság" for="overlayopacityEdit"}
                    <input id="overlayopacityEdit" name="overlayopacity" type="number" step="any" value="{$egyed.overlayopacity}">
                {/mezo}
                {mezo cimke="Cím" for="headertextEdit"}
                    <input id="headertextEdit" name="headertext" type="text" value="{$egyed.headertext}">
                {/mezo}
                {mezo cimke="Szöveg" for="bodytextEdit" szeles=true}
                    <textarea id="bodytextEdit" name="bodytext" type="text">{$egyed.bodytext}</textarea>
                {/mezo}
                {mezo cimke='"Bezár" gomb felirat' for="closebuttontextEdit"}
                    <input id="closebuttontextEdit" name="closebuttontext" type="text" value="{$egyed.closebuttontext}">
                {/mezo}
                {mezo cimke='"Bezár" gomb betűszín' for="closebuttoncolorEdit"}
                    <input id="closebuttoncolorEdit" name="closebuttoncolor" type="text" value="{$egyed.closebuttoncolor}" title="#rrggbb">
                {/mezo}
                {mezo cimke='"Bezár" gomb háttérszín' for="closebuttonbackgroundcolorEdit"}
                    <input id="closebuttonbackgroundcolorEdit" name="closebuttonbackgroundcolor" type="text" value="{$egyed.closebuttonbackgroundcolor}"
                        title="#rrggbb">
                {/mezo}
                {mezo cimke="Tartalom szélessége" for="contentwidthEdit"}
                    <input id="contentwidthEdit" name="contentwidth" type="text" value="{$egyed.contentwidth}" title="% vagy px vagy más CSS mértékegység">
                {/mezo}
                {mezo cimke="Tartalom magassága" for="contentheightEdit"}
                    <input id="contentheightEdit" name="contentheight" type="text" value="{$egyed.contentheight}"
                        title="% vagy px vagy más CSS mértékegység">
                {/mezo}
                {mezo cimke="Tartalom teteje" for="contenttopEdit"}
                    <input id="contenttopEdit" name="contenttop" type="text" value="{$egyed.contenttop}" title="% vagy px vagy más CSS mértékegység">
                {/mezo}
                {mezo cimke="Sorrend" for="popuporderEdit"}
                    <input id="popuporderEdit" name="popuporder" type="number" value="{$egyed.popuporder}">
                {/mezo}
            {/mezocsoport}
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