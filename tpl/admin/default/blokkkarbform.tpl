<div id="mattkarb-header">
    <h3>{at('Blokk')}</h3>
    <h4>{$blokk.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/blokk/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#Blokk1Tab">{at('Blokk 1')}</a></li>
            <li><a href="#Blokk2Tab">{at('Blokk 2')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$blokk.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
                    <td><input id="SorrendEdit" name="sorrend" type="number" value="{$blokk.sorrend}"></td>
                </tr>
                <tr>
                    <td><label for="LathatoEdit">{at('Látható')}:</label></td>
                    <td><input id="LathatoEdit" name="lathato" type="checkbox"{if ($blokk.lathato)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="TipusEdit">{at('Típus')}:</label></td>
                    <td><select id="TipusEdit" name="tipus">
                            {foreach $tipuslist as $key => $val}
                                <option value="{$key}"{if ($blokk.tipus == $key)} selected="selected"{/if}>{at($val)}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="ClassEdit">{at('CSS class')}:</label></td>
                    <td><input id="ClassEdit" name="cssclass" type="text" size="80" maxlength="255" value="{$blokk.cssclass}"></td>
                </tr>
                <tr>
                    <td><label for="StyleEdit">{at('CSS style')}:</label></td>
                    <td><input id="StyleEdit" name="cssstyle" type="text" size="80" maxlength="255" value="{$blokk.cssstyle}"></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="Blokk1Tab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="HatterkepurlEdit">{at('Háttérkép url')}:</label></td>
                    <td>
                        <input id="HatterkepurlEdit" name="hatterkepurl" type="text" size="70" maxlength="255" value="{$blokk.hatterkepurl}">
                        <a class="js-blokkbrowsebutton" href="#" data-target="HatterkepurlEdit">{at('...')}</a>
                        <a class="js-blokkdelbutton" href="#" data-target="HatterkepurlEdit"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </td>
                </tr>
                <tr>
                    <td><label for="VideourlEdit">{at('Video url')}:</label></td>
                    <td>
                        <input id="VideourlEdit" name="videourl" type="text" size="70" maxlength="255" value="{$blokk.videourl}">
                        <a class="js-blokkbrowsebutton" href="#" data-target="VideourlEdit">{at('...')}</a>
                        <a class="js-blokkdelbutton" href="#" data-target="VideourlEdit"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </td>
                </tr>
                <tr>
                    <td><label for="CimEdit">{at('Cím')}:</label></td>
                    <td><input id="CimEdit" name="cim" type="text" size="80" maxlength="255" value="{$blokk.cim}"></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Leírás')}:</label></td>
                    <td><textarea id="LeirasEdit" name="leiras">{$blokk.leiras}</textarea></td>
                </tr>
                <tr>
                    <td><label for="GombfeliratEdit">{at('Gomb felirat')}:</label></td>
                    <td><input id="GombfeliratEdit" name="gombfelirat" type="text" size="80" maxlength="255" value="{$blokk.gombfelirat}"></td>
                </tr>
                <tr>
                    <td><label for="GomburlEdit">{at('Gomb url')}:</label></td>
                    <td><input id="GomburlEdit" name="gomburl" type="text" size="80" maxlength="255" value="{$blokk.gomburl}"></td>
                </tr>
                <tr>
                    <td><label for="SzovegigazitasEdit">{at('Szöveg igazítás')}:</label></td>
                    <td><select id="SzovegigazitasEdit" name="szovegigazitas">
                            {foreach $szovegigazitaslist as $key => $val}
                                <option value="{$key}"{if ($blokk.szovegigazitas == $key)} selected="selected"{/if}>{at($val)}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="BlokkmagassagEdit">{at('Blokk magasság')}:</label></td>
                    <td><select id="BlokkmagassagEdit" name="blokkmagassag">
                            {foreach $blokkmagassaglist as $key => $val}
                                <option value="{$key}"{if ($blokk.blokkmagassag == $key)} selected="selected"{/if}>{at($val)}</option>
                            {/foreach}
                        </select></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="Blokk2Tab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="Hatterkepurl2Edit">{at('Háttérkép url 2')}:</label></td>
                    <td>
                        <input id="Hatterkepurl2Edit" name="hatterkepurl2" type="text" size="70" maxlength="255" value="{$blokk.hatterkepurl2}">
                        <a class="js-blokkbrowsebutton" href="#" data-target="Hatterkepurl2Edit">{at('...')}</a>
                        <a class="js-blokkdelbutton" href="#" data-target="Hatterkepurl2Edit"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </td>
                </tr>
                <tr>
                    <td><label for="Videourl2Edit">{at('Video url 2')}:</label></td>
                    <td>
                        <input id="Videourl2Edit" name="videourl2" type="text" size="70" maxlength="255" value="{$blokk.videourl2}">
                        <a class="js-blokkbrowsebutton" href="#" data-target="Videourl2Edit">{at('...')}</a>
                        <a class="js-blokkdelbutton" href="#" data-target="Videourl2Edit"><span class="ui-icon ui-icon-circle-minus"></span></a>
                    </td>
                </tr>
                <tr>
                    <td><label for="Cim2Edit">{at('Cím 2')}:</label></td>
                    <td><input id="Cim2Edit" name="cim2" type="text" size="80" maxlength="255" value="{$blokk.cim2}"></td>
                </tr>
                <tr>
                    <td><label for="Leiras2Edit">{at('Leírás 2')}:</label></td>
                    <td><textarea id="Leiras2Edit" name="leiras2">{$blokk.leiras2}</textarea></td>
                </tr>
                <tr>
                    <td><label for="Gombfelirat2Edit">{at('Gomb felirat 2')}:</label></td>
                    <td><input id="Gombfelirat2Edit" name="gombfelirat2" type="text" size="80" maxlength="255" value="{$blokk.gombfelirat2}"></td>
                </tr>
                <tr>
                    <td><label for="Gomburl2Edit">{at('Gomb url 2')}:</label></td>
                    <td><input id="Gomburl2Edit" name="gomburl2" type="text" size="80" maxlength="255" value="{$blokk.gomburl2}"></td>
                </tr>
                <tr>
                    <td><label for="Szovegigazitas2Edit">{at('Szöveg igazítás 2')}:</label></td>
                    <td><select id="Szovegigazitas2Edit" name="szovegigazitas2">
                            {foreach $szovegigazitaslist as $key => $val}
                                <option value="{$key}"{if ($blokk.szovegigazitas2 == $key)} selected="selected"{/if}>{at($val)}</option>
                            {/foreach}
                        </select></td>
                </tr>
                <tr>
                    <td><label for="Blokkmagassag2Edit">{at('Blokk magasság 2')}:</label></td>
                    <td><select id="Blokkmagassag2Edit" name="blokkmagassag2">
                            {foreach $blokkmagassaglist as $key => $val}
                                <option value="{$key}"{if ($blokk.blokkmagassag2 == $key)} selected="selected"{/if}>{at($val)}</option>
                            {/foreach}
                        </select></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$blokk.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
