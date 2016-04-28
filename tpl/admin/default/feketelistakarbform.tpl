<div id="mattkarb-header">
    <h3>{t('Feketelista')}</h3>
</div>
<form id="mattkarb-form" method="post" action="/admin/feketelista/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table><tbody>
                <tr>
                    <td><label for="EmailEdit">{t('Email/IP cím')}:</label></td>
                    <td><input id="EmailEdit" name="email" type="text" size="80" maxlength="255" value="{$egyed.email}"></td>
                </tr>
                <tr>
                    <td><label for="OkEdit">{t('OK')}:</label></td>
                    <td><textarea id="OkEdit" name="ok">{$egyed.ok}</textarea></td>
                </tr>
                </tbody></table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$egyed.id}">
    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
    </div>
</form>