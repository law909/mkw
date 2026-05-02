<div id="mattkarb-header">
    <h3>{at('Statikus lap')}</h3>
</div>
<form id="mattkarb-form" method="post" action="{$formaction}">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#TranslationTab">{at('Idegennyelvi adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Oldalcím')}:</label></td>
                    <td><input id="NevEdit" name="oldalcim" type="text" size="80" maxlength="255" value="{$egyed.oldalcim}"></td>
                </tr>
                <tr>
                    <td><label for="OldurlEdit">{at('Régi oldalcím')}:</label></td>
                    <td><input id="OldurlEdit" name="oldurl" type="text" size="80" maxlength="255" value="{$egyed.oldurl}"></td>
                </tr>
                <tr>
                    <td><label for="LeirasEdit">{at('Szöveg')}:</label></td>
                    <td><textarea id="LeirasEdit" name="szoveg" class="js-ckeditor">{$egyed.szoveg}</textarea></td>
                </tr>
                <tr>
                    <td><label for="SeoDescriptionEdit">{at('META leírás')}:</label></td>
                    <td><textarea id="SeoDescriptionEdit" name="seodescription" cols="70">{$egyed.seodescription}</textarea></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="TranslationTab" class="mattkarb-page" data-visible="visible">
            <div>
                <label for="NevL1Edit">{at('Oldalcím')}:</label>
                <input id="NevL1Edit" name="oldalcim_l1" type="text" size="80" maxlength="255" value="{$egyed.oldalcim_l1}">
            </div>
            <div class="matt-hseparator"></div>
            <div>
                <label for="LeirasL1Edit">{at('Szöveg')}:</label>
                <textarea id="LeirasL1Edit" name="szoveg_l1" class="js-ckeditor">{$egyed.szoveg_l1}</textarea>
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