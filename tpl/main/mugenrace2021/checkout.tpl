{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mugenrace2021/checkout.js?v=2"></script>
{/block}

{block "body"}
    <div class="co-nav-spacer"></div>
    <div x-data="{ imagepath: '{$imagepath}'}">
        <div class="co-container js-checkout" x-data="checkout" x-init="getLists">
            <div class="co-data-container">
                <div class="co-row co-flex-dir-row">
                    <div class="co-login-box">
                        <h3>{t('Új vásárló')}</h3>
                        <div class="co-radio-row">
                            <label for="regGuestEdit" class="co-radio-button">
                                <input id="regGuestEdit" type="radio" x-model="regNeeded" name="regneeded" value="1">
                                <span class="co-radio-text">{t('Vásárlás vendégként (regisztráció nélkül)')}</span>
                            </label>

                            <label for="regRegEdit" class="co-radio-button">
                                <input id="regRegEdit" type="radio" x-model="regNeeded" name="regneeded" value="2">
                                <span class="co-radio-text">{t('Vásárlás regisztrációval')}</span>
                            </label>

                        </div>
                    </div>
                    <div class="co-login-box">
                        <h3>{t('Regisztrált vásárló')}</h3>
                        <div class="co-control-row">
                            <label for="loginUserEdit" class="co-label">{t('Email')}</label>
                            <input id="loginUserEdit" class="co-input" type="text" x-model="login.email">
                        </div>
                        <div class="co-control-row">
                            <label for="loginPasswordEdit" class="co-label">{t('Jelszó')}</label>
                            <input id="loginPasswordEdit" class="co-input" type="password" x-model="login.password">
                        </div>
                        <div class="co-control-row">
                            <button class="btn btn-primary">{t('Belépés')}</button>
                        </div>
                    </div>
                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Kapcsolati adatok')}</h3>

                    <div class="co-control-row">
                        <label for="lastnameEdit" class="co-label">{t('Vezetéknév')} *</label>
                        <input id="lastnameEdit" class="co-input" type="text" x-model="contact.lastName">
                    </div>

                    <div class="co-control-row">
                        <label for="firstnameEdit" class="co-label">{t('Vezetéknév')} *</label>
                        <input id="firstnameEdit" class="co-input" type="text" x-model="contact.firstName">
                    </div>

                    <div class="co-control-row">
                        <label for="phoneEdit" class="co-label">{t('Telefon')} *</label>
                        <input id="phoneEdit" class="co-input" type="text" x-model="contact.phone">
                    </div>

                    <div class="co-control-row">
                        <label for="emailEdit" class="co-label">{t('Email')} *</label>
                        <input id="emailEdit" class="co-input" type="text" x-model="email">
                    </div>

                    <div class="co-control-row" x-show="regNeeded === '2'">
                        <label for="pw1Edit" class="co-label">{t('Jelszó 1')} *</label>
                        <input id="pw1Edit" class="co-input" type="password" x-model="password1">
                    </div>
                    <div class="co-control-row" x-show="regNeeded === '2'">
                        <label for="pw2Edit" class="co-label">{t('Jelszó 2')} *</label>
                        <input id="pw2Edit" class="co-input" type="password" x-model="password2">
                    </div>

                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Szállítási adatok')}</h3>

                    <div class="co-control-row">
                        <label for="deliverynameEdit" class="co-label">{t('Szállítási név')}</label>
                        <input id="deliverynameEdit" class="co-input" type="text" x-model="delivery.name">
                    </div>
                    <div class="co-control-row co-col-container">
                        <div class="co-col co-col-20">
                            <label for="deliverypostalcodeEdit" class="co-label">{t('Ir.szám')} *</label>
                            <input id="deliverypostalcodeEdit" class="co-input" type="text" x-model="delivery.postalcode">
                        </div>
                        <div class="co-col co-col-80">
                            <label for="deliverycityEdit" class="co-label">{t('Város')} *</label>
                            <input id="deliverycityEdit" class="co-input" type="text" x-model="delivery.city">
                        </div>
                    </div>
                    <div class="co-control-row">
                        <label for="deliverystreetEdit" class="co-label">{t('Utca')}</label>
                        <input id="deliverystreetEdit" class="co-input" type="text" x-model="delivery.street">
                    </div>
                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Számlázási adatok')}</h3>
                    <div class="co-control-row">
                        <label for="inveqdeliveryEdit" class="co-label">
                            {t('Megegyezik a szállítási adatokkal')}
                            <input id="inveqdeliveryEdit" class="" type="checkbox" x-model="inveqdel">
                        </label>
                    </div>
                    <div class="co-control-row" x-show="!inveqdel">
                        <label for="invoicenameEdit" class="co-label">{t('Számlázási név')}</label>
                        <input id="invoicenameEdit" class="co-input" type="text" x-model="invoice.name">
                    </div>
                    <div class="co-control-row co-col-container" x-show="!inveqdel">
                        <div class="co-col co-col-20">
                            <label for="invoicepostalcodeEdit" class="co-label">{t('Ir.szám')} *</label>
                            <input id="invoicepostalcodeEdit" class="co-input" type="text" x-model="invoice.postalcode">
                        </div>
                        <div class="co-col co-col-80">
                            <label for="invoicecityEdit" class="co-label">{t('Város')} *</label>
                            <input id="invoicecityEdit" class="co-input" type="text" x-model="invoice.city">
                        </div>
                    </div>
                    <div class="co-control-row" x-show="!inveqdel">
                        <label for="invoicestreetEdit" class="co-label">{t('Utca')}</label>
                        <input id="invoicestreetEdit" class="co-input" type="text" x-model="invoice.street">
                    </div>

                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Szállítási mód')}</h3>
                    <template x-for="(szallmod, i) in szallmodlist">
                        <div>
                            <label class="co-label">
                                <input type="radio" name="szallitasimod" x-model="selectedSzallitasimodIndex" :value="i">
                                <span x-text="szallmod.caption + ' ' + (szallmod.brutto ? szallmod.brutto : '')"></span>
                                <div class="co-legend" x-html="szallmod.leiras"></div>
                            </label>
                        </div>
                    </template>
                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Fizetési mód')}</h3>
                    <template x-for="(fizmod, i) in selectedSzallitasimod?.fizmodlist">
                        <div>
                            <label class="co-label">
                                <input type="radio" name="fizetesimod" x-model="selectedFizetesimodIndex" :value="i">
                                <span x-text="fizmod.nev"></span>
                                <div class="co-legend" x-html="fizmod.leiras"></div>
                            </label>
                        </div>
                    </template>
                </div>

            </div>
            <div class="co-summary-container">
                <template x-for="tetel in tetellist.tetellista">
                    <div class="co-termek-box">
                        <img
                            x-show="tetel.kiskepurl"
                            :src="imagepath + tetel.kiskepurl"
                            :alt="tetel.caption"
                            :title="tetel.caption"
                        >
                        <div x-text="tetel.caption"></div>
                        <div class="termek-cikkszam" x-text="tetel.cikkszam"></div>
                        <template x-for="valtozat in tetel.valtozatok">
                            <span class="co-termek-tul" x-text="valtozat.ertek + ' '"></span>
                        </template>
                        <div class="co-termek-ar-row">
                            <div class="co-termek-ar" x-text="tetel.bruttoegysar + ' ' + tetellist.valutanemnev"></div>
                            <div class="co-termek-ar" x-text="tetel.mennyiseg + ' ' + '{t('db')}'"></div>
                            <div class="font-bold co-termek-ar" x-text="tetel.brutto + ' ' + tetellist.valutanemnev"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
{/block}