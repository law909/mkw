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
            {mezocsoport}
                {mezo cimke="Név" for="NevEdit" szeles=true}
                    <input id="NevEdit" name="nev" type="text" size="80" maxlength="255"
                        value="{$uzletkoto.nev}" required autofocus>
                {/mezo}
                {mezo cimke="Cím" for="IrszamEdit" szeles=true}
                    <div class="mattkarb-mezogomb">
                        <input id="IrszamEdit" name="irszam" type="text" size="6" maxlength="10"
                            value="{$uzletkoto.irszam}" placeholder="{at('ir.szám')}">
                        <input id="VarosEdit" name="varos" type="text" size="20" maxlength="40"
                            value="{$uzletkoto.varos}" placeholder="{at('város')}">
                        <input id="UtcaEdit" name="utca" type="text" size="40" maxlength="60" value="{$uzletkoto.utca}"
                            placeholder="{at('utca, házszám')}">
                    </div>
                {/mezo}
                {mezo cimke="Jutalék %" for="JutalekEdit"}
                    <input id="JutalekEdit" name="jutalek" type="number" step="any" value="{$uzletkoto.jutalek}">
                {/mezo}
                {mezo cimke="Belső" for="BelsoEdit"}
                    <input id="BelsoEdit" name="belso" type="checkbox"{if ($uzletkoto.belso)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Vezető üzletkötő" for="FoEdit"}
                    <input id="FoEdit" name="fo" type="checkbox"{if ($uzletkoto.fo)} checked="checked"{/if}>
                {/mezo}
                {mezo cimke="Vezető üzletkötője" for="FoUkEdit"}
                    <select id="FoUkEdit" name="fouzletkoto">
                        <option value="">{at('válasszon')}</option>
                        {foreach $fouzletkotolist as $_szt}
                            <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                        {/foreach}
                    </select>
                {/mezo}
            {/mezocsoport}
        </div>
        <div id="ElerhetosegTab" class="mattkarb-page" data-visible="visible">
            {mezocsoport}
                {mezo cimke="Telefon" for="TelefonEdit"}
                    <input id="TelefonEdit" name="telefon" type="text" size="40" maxlength="40"
                        value="{$uzletkoto.telefon}">
                {/mezo}
                {mezo cimke="Mobil" for="MobilEdit"}
                    <input id="MobilEdit" name="mobil" type="text" size="40" maxlength="40"
                        value="{$uzletkoto.mobil}">
                {/mezo}
                {mezo cimke="Fax" for="FaxEdit"}
                    <input id="FaxEdit" name="fax" type="text" size="40" maxlength="40" value="{$uzletkoto.fax}">
                {/mezo}
                {mezo cimke="Email" for="EmailEdit"}
                    <input id="EmailEdit" name="email" type="text" size="40" maxlength="100"
                        value="{$uzletkoto.email}" title="{at("Több címet is megadhat vesszővel elválasztva.")}">
                {/mezo}
                {mezo cimke="Honlap" for="HonlapEdit"}
                    <input id="HonlapEdit" name="honlap" type="url" size="40" maxlength="200"
                        value="{$uzletkoto.honlap}">
                {/mezo}
            {/mezocsoport}
        </div>
        {if ($setup.b2b)}
            <div id="PartnerdefaultTab" class="mattkarb-page" data-visible="visible">
                {mezocsoport}
                    {mezo cimke="Számla típus" for="SzamlatipusEdit"}
                        <select id="SzamlatipusEdit" name="partnerszamlatipus">
                            <option value="">{at('válasszon')}</option>
                            {foreach $partnerszamlatipuslist as $_szt}
                                <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {mezo cimke="Fizetési mód" for="FizmodEdit"}
                        <select id="FizmodEdit" name="partnerfizmod">
                            <option value="">{at('válasszon')}</option>
                            {foreach $partnerfizmodlist as $_fizmod}
                                <option value="{$_fizmod.id}"{if ($_fizmod.selected)} selected="selected"{/if}>{$_fizmod.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {if ($setup.multilang)}
                        {mezo cimke="Bizonylatok nyelve" for="BizonylatnyelvEdit"}
                            <select id="BizonylatnyelvEdit" name="partnerbizonylatnyelv">
                                <option value="">{at('válasszon')}</option>
                                {foreach $partnerbizonylatnyelvlist as $_szt}
                                    <option value="{$_szt.id}"{if ($_szt.selected)} selected="selected"{/if}>{$_szt.caption}</option>
                                {/foreach}
                            </select>
                        {/mezo}
                    {/if}
                    {mezo cimke="Szállítási mód" for="SzallmodEdit"}
                        <select id="SzallmodEdit" name="partnerszallitasimod">
                            <option value="">{at('válasszon')}</option>
                            {foreach $partnerszallitasimodlist as $_szm}
                                <option value="{$_szm.id}"{if ($_szm.selected)} selected="selected"{/if}>{$_szm.caption}</option>
                            {/foreach}
                        </select>
                    {/mezo}
                    {if ($setup.arsavok)}
                        {mezo cimke="Valutanem" for="ValutanemEdit"}
                            <select id="ValutanemEdit" name="partnervalutanem">
                                <option value="">{at('válasszon')}</option>
                                {foreach $partnervalutanemlist as $_vt}
                                    <option value="{$_vt.id}"{if ($_vt.selected)} selected="selected"{/if}>{$_vt.caption}</option>
                                {/foreach}
                            </select>
                        {/mezo}
                        {mezo cimke="Ársáv" for="TermekarEdit"}
                            <select id="TermekarEdit" name="arsav">
                                <option value="">{at('válasszon')}</option>
                                {foreach $arsavlist as $_ta}
                                    <option value="{$_ta.id}"{if ($_ta.selected)} selected="selected"{/if}>{$_ta.caption}</option>
                                {/foreach}
                            </select>
                        {/mezo}
                    {/if}
                {/mezocsoport}
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
