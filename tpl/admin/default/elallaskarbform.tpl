<div id="mattkarb-header">
    <h3>{at('Elállás a szerződéstől')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/elallas/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#NaploTab">{at('Napló')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table><tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td><input id="NevEdit" name="nev" type="text" size="80" maxlength="255" value="{$egyed.nev}"></td>
                </tr>
                <tr>
                    <td><label for="EmailEdit">{at('Email')}:</label></td>
                    <td><input id="EmailEdit" name="email" type="text" size="80" maxlength="255" value="{$egyed.email}"></td>
                </tr>
                <tr>
                    <td><label for="BizonylatEdit">{at('Bizonylat')}:</label></td>
                    <td><input id="BizonylatEdit" name="bizonylat" type="text" size="30" maxlength="30" value="{$egyed.bizonylat}"></td>
                </tr>
                <tr>
                    <td><label for="SzovegEdit">{at('Szöveg')}:</label></td>
                    <td><textarea id="SzovegEdit" name="szoveg" rows="6" cols="80">{$egyed.szoveg}</textarea></td>
                </tr>
            </tbody></table>
        </div>
        <div id="NaploTab" class="mattkarb-page" data-visible="visible">
            {foreach $naplok as $naplo}
                {include 'elallaselallasnaplokarb.tpl'}
            {/foreach}
            <a class="js-naplonewbutton" href="#" title="{at('Új')}"><span class="ui-icon ui-icon-circle-plus"></span></a>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
