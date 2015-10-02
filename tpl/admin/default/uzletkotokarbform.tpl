<div id="mattkarb-header">
    <h3>{t('Üzletkötő')}</h3>
    <h4>{$uzletkoto.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/uzletkoto/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{t('Általános adatok')}</a></li>
            <li><a href="#ElerhetosegTab">{t('Elérhetőségek')}</a></li>
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{t('Név')}:</label></td>
                    <td colspan="3"><input id="NevEdit" name="nev" type="text" size="80" maxlength="255"
                                           value="{$uzletkoto.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="IrszamEdit">{t('Cím')}:</label></td>
                    <td colspan="3">
                        <input id="IrszamEdit" name="irszam" type="text" size="6" maxlength="10"
                               value="{$uzletkoto.irszam}" placeholder="{t('ir.szám')}">
                        <input id="VarosEdit" name="varos" type="text" size="20" maxlength="40"
                               value="{$uzletkoto.varos}" placeholder="{t('város')}">
                        <input id="UtcaEdit" name="utca" type="text" size="40" maxlength="60" value="{$uzletkoto.utca}"
                               placeholder="{t('utca, házszám')}">
                    </td>
                </tr>
                <tr>
                    <td><label for="JutalekEdit">{t('Jutalék %')}:</label></td>
                    <td><input id="JutalekEdit" name="jutalek" type="number" step="any" value="{$uzletkoto.jutalek}"></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="ElerhetosegTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="TelefonEdit">{t('Telefon')}:</label></td>
                    <td><input id="TelefonEdit" name="telefon" type="text" size="40" maxlength="40"
                               value="{$uzletkoto.telefon}"></td>
                </tr>
                <tr>
                    <td><label for="MobilEdit">{t('Mobil')}:</label></td>
                    <td><input id="MobilEdit" name="mobil" type="text" size="40" maxlength="40"
                               value="{$uzletkoto.mobil}"></td>
                </tr>
                <tr>
                    <td><label for="FaxEdit">{t('Fax')}:</label></td>
                    <td><input id="FaxEdit" name="fax" type="text" size="40" maxlength="40" value="{$uzletkoto.fax}">
                    </td>
                </tr>
                <tr>
                    <td><label for="EmailEdit">{t('Email')}:</label></td>
                    <td><input id="EmailEdit" name="email" type="email" size="40" maxlength="100"
                               value="{$uzletkoto.email}"></td>
                </tr>
                <tr>
                    <td><label for="HonlapEdit">{t('Honlap')}:</label></td>
                    <td><input id="HonlapEdit" name="honlap" type="url" size="40" maxlength="200"
                               value="{$uzletkoto.honlap}"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$uzletkoto.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{t('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{t('Mégsem')}</a>
    </div>
</form>
