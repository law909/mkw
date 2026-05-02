<div id="menukarb-header">
    <h3>{$egyed.nev}</h3>
</div>
<form id="menukarb-form" method="post" action="/admin/termekmenu/save" data-id="{$egyed.id}">
    <div id="menukarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
            <li><a href="#WebTab">{at('Webes adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td colspan="3"><input id="NevEdit" name="nev" type="text" size="83" maxlength="255" value="{$egyed.nev}" required autofocus></td>
                    <input id="ParentIdEdit" name="parentid" type="hidden" value="{$egyed.parentid}">
                </tr>
                <tr>
                    <td><label for="SorrendEdit">{at('Sorrend')}:</label></td>
                    <td><input id="SorrendEdit" name="sorrend" type="number" size="10" maxlength="10" value="{$egyed.sorrend}"></td>
                </tr>
                <tr>
                    <td><label for="ArukeresoidEdit">{at('Árukereső id')}:</label></td>
                    <td><input id="ArukeresoidEdit" name="arukeresoid" type="text" value="{$egyed.arukeresoid}"></td>
                </tr>
                </tbody>
            </table>
            {include 'termekmenuimagekarb.tpl'}
        </div>
        <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
            <div>
                <label for="NevL1Edit">{at('Név')}:</label>
                <input id="NevL1Edit" name="nev_l1" type="text" size="83" maxlength="255" value="{$egyed.nev_l1}">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="RovidleirasL1Edit">{at('Rövid leírás')}:</label>
                <input id="RovidleirasL1Edit" name="rovidleiras_l1" type="text" size="100" maxlength="255" value="{$egyed.rovidleiras_l1}">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="LeirasL1Edit">{at('Leírás')}:</label>
                <textarea id="LeirasL1Edit" name="leiras_l1" class="js-ckeditor">{$egyed.leiras_l1}</textarea>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="Leiras2L1Edit">{at('Leírás 2')}:</label>
                <textarea id="Leiras2L1Edit" name="leiras2_l1" class="js-ckeditor">{$egyed.leiras2_l1}</textarea>
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="Leiras3L1Edit">{at('Leírás 3')}:</label>
                <textarea id="Leiras3L1Edit" name="leiras3_l1" class="js-ckeditor">{$egyed.leiras3_l1}</textarea>
            </div>
        </div>
        <div id="WebTab" class="mattkarb-page">
            <input id="InaktivCheck" name="inaktiv" type="checkbox" {if ($egyed.inaktiv)}checked="checked"{/if}/>{at('Inaktív')}
            <table>
                <tbody>
                <tr>
                    <input id="LathatoCheck" name="lathato" type="checkbox"
                           {if ($egyed.lathato)}checked="checked"{/if}>{at('Látható')}
                </tr>
                <tr>
                    <td><label for="OldalCimEdit">{at('Lap címe')}:</label></td>
                    <td><input id="OldalCimEdit" name="oldalcim" type="text" size="100" maxlength="255" value="{$egyed.oldalcim}"></td>
                </tr>
                <tr>
                    <td><label for="RovidleirasEdit">{at('Rövid leírás')}:</label></td>
                    <td><input id="RovidleirasEdit" name="rovidleiras" type="text" size="100" maxlength="255" value="{$egyed.rovidleiras}"></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Leírás')}:</label></td>
                    <td><textarea id="LeirasEdit" name="leiras" class="js-ckeditor">{$egyed.leiras}</textarea></td>
                </tr>
                <tr>
                    <td><label for="Leiras2Edit">{at('Leírás 2')}:</label></td>
                    <td><textarea id="Leiras2Edit" name="leiras2" class="js-ckeditor">{$egyed.leiras2}</textarea></td>
                </tr>
                <tr>
                    <td><label for="Leiras3Edit">{at('Leírás 3')}:</label></td>
                    <td><textarea id="Leiras3Edit" name="leiras3" class="js-ckeditor">{$egyed.leiras3}</textarea></td>
                </tr>
                <tr>
                    <td><label for="SeoDescriptionEdit">{at('META leírás')}:</label></td>
                    <td><textarea id="SeoDescriptionEdit" name="seodescription" cols="70">{$egyed.seodescription}</textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="menukarb-okbutton" type="submit" value="{at('OK')}">
        <a id="menukarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
