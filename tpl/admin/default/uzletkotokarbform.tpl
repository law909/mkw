<div id="mattkarb-header">
    <h3>{at('Üzletkötő')}</h3>
    <h4>{$uzletkoto.nev}</h4>
</div>
<form id="mattkarb-form" method="post" action="/admin/uzletkoto/save">
    <div id="mattkarb-tabs">
        <ul>
            <li><a href="#AltalanosTab">{at('Általános adatok')}</a></li>
            <li><a href="#ElerhetosegTab">{at('Elérhetőségek')}</a></li>
            {if ($setup.b2b)}
                <li><a href="#PartnerdefaultTab">{at('Partner alapadatok')}</a></li>
            {/if}
        </ul>
        <div id="AltalanosTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="NevEdit">{at('Név')}:</label></td>
                    <td colspan="3"><input id="NevEdit" name="nev" type="text" size="80" maxlength="255"
                                           value="{$uzletkoto.nev}" required autofocus></td>
                </tr>
                <tr>
                    <td><label for="IrszamEdit">{at('Cím')}:</label></td>
                    <td colspan="3">
                        <input id="IrszamEdit" name="irszam" type="text" size="6" maxlength="10"
                               value="{$uzletkoto.irszam}" placeholder="{at('ir.szám')}">
                        <input id="VarosEdit" name="varos" type="text" size="20" maxlength="40"
                               value="{$uzletkoto.varos}" placeholder="{at('város')}">
                        <input id="UtcaEdit" name="utca" type="text" size="40" maxlength="60" value="{$uzletkoto.utca}"
                               placeholder="{at('utca, házszám')}">
                    </td>
                </tr>
                <tr>
                    <td><label for="JutalekEdit">{at('Jutalék %')}:</label></td>
                    <td><input id="JutalekEdit" name="jutalek" type="number" step="any" value="{$uzletkoto.jutalek}"></td>
                </tr>
                <tr>
                    <td><label for="BelsoEdit">{at('Belső')}:</label></td>
                    <td><input id="BelsoEdit" name="belso" type="checkbox"{if ($uzletkoto.belso)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="FoEdit">{at('Vezető üzletkötő')}:</label></td>
                    <td><input id="FoEdit" name="fo" type="checkbox"{if ($uzletkoto.fo)} checked="checked"{/if}></td>
                </tr>
                <tr>
                    <td><label for="FoUkEdit">{at('Vezető üzletkötője')}:</label></td>
                    <td><select id="FoUkEdit" name="fouzletkoto">
                            <option value="">{at('válasszon')}</option>
                            {foreach $fouzletkotolist as $_szt}
                                <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="ElerhetosegTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                <tr>
                    <td><label for="TelefonEdit">{at('Telefon')}:</label></td>
                    <td><input id="TelefonEdit" name="telefon" type="text" size="40" maxlength="40"
                               value="{$uzletkoto.telefon}"></td>
                </tr>
                <tr>
                    <td><label for="MobilEdit">{at('Mobil')}:</label></td>
                    <td><input id="MobilEdit" name="mobil" type="text" size="40" maxlength="40"
                               value="{$uzletkoto.mobil}"></td>
                </tr>
                <tr>
                    <td><label for="FaxEdit">{at('Fax')}:</label></td>
                    <td><input id="FaxEdit" name="fax" type="text" size="40" maxlength="40" value="{$uzletkoto.fax}">
                    </td>
                </tr>
                <tr>
                    <td><label for="EmailEdit">{at('Email')}:</label></td>
                    <td><input id="EmailEdit" name="email" type="text" size="40" maxlength="100"
                               value="{$uzletkoto.email}" title="{at("Több címet is megadhat vesszővel elválasztva.")}"></td>
                </tr>
                <tr>
                    <td><label for="HonlapEdit">{at('Honlap')}:</label></td>
                    <td><input id="HonlapEdit" name="honlap" type="url" size="40" maxlength="200"
                               value="{$uzletkoto.honlap}"></td>
                </tr>
                </tbody>
            </table>
        </div>
        {if ($setup.b2b)}
        <div id="PartnerdefaultTab" class="mattkarb-page" data-visible="visible">
            <table>
                <tbody>
                    <tr>
                        <td><label for="SzamlatipusEdit">{at('Számla típus')}:</label></td>
                        <td><select id="SzamlatipusEdit" name="partnerszamlatipus">
                                <option value="">{at('válasszon')}</option>
                                {foreach $partnerszamlatipuslist as $_szt}
                                    <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="FizmodEdit">{at('Fizetési mód')}:</label></td>
                        <td><select id="FizmodEdit" name="partnerfizmod">
                                <option value="">{at('válasszon')}</option>
                                {foreach $partnerfizmodlist as $_fizmod}
                                    <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    {if ($setup.multilang)}
                        <tr>
                            <td><label for="BizonylatnyelvEdit">{at('Bizonylatok nyelve')}:</label></td>
                            <td><select id="BizonylatnyelvEdit" name="partnerbizonylatnyelv">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $partnerbizonylatnyelvlist as $_szt}
                                        <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                                    {/foreach}
                                </select></td>
                        </tr>
                    {/if}
                    <tr>
                        <td><label for="SzallmodEdit">{at('Szállítási mód')}:</label></td>
                        <td><select id="SzallmodEdit" name="partnerszallitasimod">
                                <option value="">{at('válasszon')}</option>
                                {foreach $partnerszallitasimodlist as $_szm}
                                    <option value="{$_szm.id}"{if ($_szm.selected)} selected="selected"{/if}>{$_szm.caption}</option>
                                {/foreach}
                            </select></td>
                    </tr>
                    {if ($setup.arsavok)}
                        <tr>
                            <td><label for="ValutanemEdit">{at('Valutanem')}:</label></td>
                            <td><select id="ValutanemEdit" name="partnervalutanem">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $partnervalutanemlist as $_vt}
                                        <option value="{$_vt.id}"{if ($_vt.selected)} selected="selected"{/if}>{$_vt.caption}</option>
                                    {/foreach}
                                </select></td>
                        </tr>
                        <tr>
                            <td><label for="TermekarEdit">{at('Ársáv')}:</label></td>
                            <td><select id="TermekarEdit" name="partnertermekarazonosito">
                                    <option value="">{at('válasszon')}</option>
                                    {foreach $partnertermekarazonositolist as $_ta}
                                        <option value="{$_ta.id}"{if ($_ta.selected)} selected="selected"{/if}>{$_ta.caption}</option>
                                    {/foreach}
                                </select></td>
                        </tr>
                    {/if}
                </tbody>
            </table>
        </div>
        {/if}
    </div>
    <input name="oper" type="hidden" value="{$oper}">
    <input name="id" type="hidden" value="{$uzletkoto.id}">

    <div class="mattkarb-footer">
        <input id="mattkarb-okbutton" type="submit" value="{at('OK')}">
        <a id="mattkarb-cancelbutton" href="#">{at('Mégsem')}</a>
    </div>
</form>
