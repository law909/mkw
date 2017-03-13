{extends "base.tpl"}

{block "script"}
    <script src="/js/main/superzoneb2b/regisztracio.js"></script>
{/block}

{block "body"}
    <div class="col-md-8 col-md-offset-2">
        <h3>Create new customer</h3>

        <form id="RegisztracioForm" class="form-horizontal" action="/regisztracio/ment" method="post">

            <div>
                <label class="control-label" for="VezeteknevEdit">{t('First name')}*:</label>
                <input id="KeresztnevEdit" name="keresztnev" type="text" class="form-control" value="{$keresztnev}" required>
            </div>
            <div>
                <label class="control-label" for="VezeteknevEdit">{t('Last name')}*:</label>
                <input id="VezeteknevEdit" name="vezeteknev" type="text" class="form-control" value="{$vezeteknev}" required>
            </div>
            <div>
                <label class="control-label" for="EmailEdit">{t('Email')}*:</label>
                <input id="EmailEdit" name="email" type="email" class="form-control" value="{$email}" required>
            </div>
            <div>
                <label class="control-label" for="TelefonEdit">{t('Phone')}:</label>
                <input id="TelefonEdit" name="telefon" type="text" class="form-control" value="{$telefon}">
            </div>

            <h4>Billing address</h4>

            <div>
                <label class="control-label" for="SzamlazasiNevEdit">{t('Name')}*:</label>
                <input id="SzamlazasiNevEdit" name="nev" type="text" class="form-control" value="{$nev}" required>
            </div>
            <div>
                <label class="control-label" for="SzamlazasiAdoszamEdit">{t('VAT ID')}*:</label>
                <input id="SzamlazasiAdoszamEdit" name="adoszam" type="text" class="form-control" value="{$adoszam}" required>
            </div>
            <div>
                <label class="control-label" for="SzamlazasiIrszamEdit">{t('Postal code')}*:</label>
                <input id="SzamlazasiIrszamEdit" name="irszam" type="text" class="form-control" value="{$irszam}" required>
            </div>
            <div>
                <label class="control-label" for="SzamlazasiVarosEdit">{t('City')}*:</label>
                <input id="SzamlazasiVarosEdit" name="varos" type="text" class="form-control" value="{$varos}" required>
            </div>
            <div>
                <label class="control-label" for="SzamlazasiUtcaEdit">{t('Street')}*:</label>
                <input id="SzamlazasiUtcaEdit" name="utca" type="text" class="form-control" value="{$utca}" required>
            </div>

            <h4>Payment info</h4>
            <div>
                <label class="control-label" for="BankNevEdit">{t('Bank name')}*:</label>
                <input id="BankNevEdit" name="banknev" type="text" class="form-control" value="{$banknev}" required>
            </div>
            <div>
                <label class="control-label" for="BankcimEdit">{t('Bank address')}*:</label>
                <input id="BankcimEdit" name="bankcim" type="text" class="form-control" value="{$bankcim}" required>
            </div>
            <div>
                <label class="control-label" for="IbanEdit">{t('IBAN')}*:</label>
                <input id="IbanEdit" name="iban" type="text" class="form-control" value="{$iban}" required>
            </div>
            <div>
                <label class="control-label" for="SwiftEdit">{t('SWIFT')}*:</label>
                <input id="SwiftEdit" name="swift" type="text" class="form-control" value="{$swift}" required>
            </div>

            <h4>Delivery address</h4>

            <div class="acc-copyszamlaadat">
                <a class="js-copyszamlaadat">Copy billing address </a>
            </div>
            <div>
                <label class="control-label" for="SzallitasiNevEdit">{t('Name')}:</label>
                <input id="SzallitasiNevEdit" name="szallnev" type="text" class="form-control" value="{$szallnev}">
            </div>
            <div>
                <label class="control-label" for="SzallitasiIrszamEdit">{t('Postal code')}:</label>
                <input id="SzallitasiIrszamEdit" name="szallirszam" type="text" class="form-control" value="{$szallirszam}">
            </div>
            <div>
                <label class="control-label" for="SzallitasiVarosEdit">{t('City')}:</label>
                <input id="SzallitasiVarosEdit" name="szallvaros" type="text" class="form-control" value="{$szallvaros}">
            </div>
            <div>
                <label class="control-label" for="SzallitasiUtcaEdit">{t('Street')}:</label>
                <input id="SzallitasiUtcaEdit" name="szallutca" type="text" class="form-control" value="{$szallutca}">
            </div>

            <h4>Discounts</h4>
            {foreach $discountlist as $discount}
                <div>
                    <label class="control-label" for="KedvEdit_{$discount.id}">{$discount.nev}:</label>
                    <input id="KedvEdit_{$discount.id}" name="kedvezmeny_{$discount.id}" type="number" class="form-control"
                           value="{$discount.kedvezmeny}">
                    <input type="hidden" name="kedvezmenyid[]" value="{$discount.id}">
                    <input type="hidden" name="kedvezmenyoper_{$discount.id}" value="{$discount.oper}">
                    <input type="hidden" name="kedvezmenytermekcsoport_{$discount.id}" value="{$discount.tcsid}">
                </div>
            {/foreach}

            <h4>Password</h4>

            <div>
                <label class="control-label">Password*:</label>
                <input id="Jelszo1Edit" name="jelszo1" type="password" class="form-control" required>
            </div>
            <div>
                <label class="control-label">Password again*:</label>
                <input id="Jelszo2Edit" name="jelszo2" type="password" class="form-control" required>
            </div>

            <button type="submit" class="btn okbtn">Save</button>
        </form>
    </div>
{/block}