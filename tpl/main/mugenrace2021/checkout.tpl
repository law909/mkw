{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mugenrace2021/checkout.js?v=3"></script>
{/block}

{block "body"}
    <div class="co-nav-spacer"></div>
    <div x-data="{ imagepath: '{$imagepath}'}">
        <div class="co-container js-checkout" x-data="checkout">
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
                            <input
                                id="loginUserEdit"
                                class="co-input"
                                :class="loginValidation.email && !loginValidation.email.valid ? 'error' : ''"
                                type="text"
                                x-model="login.email"
                            >
                            <div class="co-error" x-text="loginValidation.email && loginValidation.email.error"></div>
                        </div>
                        <div class="co-control-row">
                            <label for="loginPasswordEdit" class="co-label">{t('Jelszó')}</label>
                            <input
                                id="loginPasswordEdit"
                                class="co-input"
                                :class="loginValidation.jelszo && !loginValidation.jelszo.valid ? 'error' : ''"
                                type="password"
                                x-model="login.jelszo"
                            >
                            <div class="co-error" x-text="loginValidation.jelszo && loginValidation.jelszo.error"></div>
                        </div>
                        <div class="co-control-row">
                            <button
                                class="btn btn-primary"
                                @click="dologin()"
                            >{t('Belépés')}</button>
                        </div>
                    </div>
                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Kapcsolati adatok')}</h3>

                    <div class="co-control-row">
                        <label for="lastnameEdit" class="co-label">{t('Vezetéknév')} *</label>
                        <input id="lastnameEdit" class="co-input" type="text" x-model="data.vezeteknev">
                    </div>

                    <div class="co-control-row">
                        <label for="firstnameEdit" class="co-label">{t('Keresztnév')} *</label>
                        <input id="firstnameEdit" class="co-input" type="text" x-model="data.keresztnev">
                    </div>

                    <div class="co-control-row">
                        <label for="phoneEdit" class="co-label">{t('Telefon')} *</label>
                        <input id="phoneEdit" class="co-input" type="text" x-model="data.telefon">
                    </div>

                    <div class="co-control-row">
                        <label for="emailEdit" class="co-label">{t('Email')} *</label>
                        <input id="emailEdit" class="co-input" type="text" x-model="data.email">
                    </div>

                    <div class="co-control-row" x-show="regNeeded === '2'">
                        <label for="pw1Edit" class="co-label">{t('Jelszó 1')} *</label>
                        <input id="pw1Edit" class="co-input" type="password" x-model="data.password1">
                    </div>
                    <div class="co-control-row" x-show="regNeeded === '2'">
                        <label for="pw2Edit" class="co-label">{t('Jelszó 2')} *</label>
                        <input id="pw2Edit" class="co-input" type="password" x-model="data.password2">
                    </div>

                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Szállítási adatok')}</h3>

                    <div class="co-control-row">
                        <label for="deliverynameEdit" class="co-label">{t('Szállítási név')}</label>
                        <input id="deliverynameEdit" class="co-input" type="text" x-model="data.szallnev">
                    </div>
                    <div class="co-control-row co-col-container">
                        <div class="co-col co-col-20">
                            <label for="deliverypostalcodeEdit" class="co-label">{t('Ir.szám')} *</label>
                            <input id="deliverypostalcodeEdit" class="co-input" type="text" x-model="data.szallirszam">
                        </div>
                        <div class="co-col co-col-80">
                            <label for="deliverycityEdit" class="co-label">{t('Város')} *</label>
                            <input id="deliverycityEdit" class="co-input" type="text" x-model="data.szallvaros">
                        </div>
                    </div>
                    <div class="co-control-row">
                        <label for="deliverystreetEdit" class="co-label">{t('Utca')}</label>
                        <input id="deliverystreetEdit" class="co-input" type="text" x-model="data.szallutca">
                    </div>
                    <div class="co-control-row">
                        <label for="deliverynumEdit" class="co-label">{t('Házszám')}</label>
                        <input id="deliverynumEdit" class="co-input" type="text" x-model="data.szallhazszam">
                    </div>
                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Számlázási adatok')}</h3>
                    <div class="co-control-row">
                        <label for="inveqdeliveryEdit" class="co-label">
                            <input id="inveqdeliveryEdit" type="checkbox" x-model="data.inveqdel">
                            {t('Megegyezik a szállítási adatokkal')}
                        </label>
                    </div>
                    <div class="co-control-row" x-show="!data.inveqdel">
                        <label for="cegesEdit" class="co-label">
                            <input id="cegesEdit" type="checkbox" x-model="data.cegesvasarlo">
                            {t('Cégként vásárolok')}
                        </label>
                    </div>
                    <div class="co-control-row co-col-container" x-show="!data.inveqdel && data.cegesvasarlo">
                        <div class="=co-col co-col-20">
                            <label for="invoiceadoszamEdit" class="co-label">{t('Adószám')}</label>
                            <input id="invoiceadoszamEdit" class="co-input" type="text" x-model="data.adoszam">
                        </div>
                    </div>
                    <div class="co-control-row" x-show="!data.inveqdel">
                        <label for="invoicenameEdit" class="co-label">{t('Számlázási név')}</label>
                        <input id="invoicenameEdit" class="co-input" type="text" x-model="data.szlanev">
                    </div>
                    <div class="co-control-row co-col-container" x-show="!data.inveqdel">
                        <div class="co-col co-col-20">
                            <label for="invoicepostalcodeEdit" class="co-label">{t('Ir.szám')} *</label>
                            <input id="invoicepostalcodeEdit" class="co-input" type="text" x-model="data.irszam">
                        </div>
                        <div class="co-col co-col-80">
                            <label for="invoicecityEdit" class="co-label">{t('Város')} *</label>
                            <input id="invoicecityEdit" class="co-input" type="text" x-model="data.varos">
                        </div>
                    </div>
                    <div class="co-control-row" x-show="!data.inveqdel">
                        <label for="invoicestreetEdit" class="co-label">{t('Utca')}</label>
                        <input id="invoicestreetEdit" class="co-input" type="text" x-model="data.utca">
                    </div>
                    <div class="co-control-row" x-show="!data.inveqdel">
                        <label for="invoicenumEdit" class="co-label">{t('Házszám')}</label>
                        <input id="invoicenumEdit" class="co-input" type="text" x-model="data.hazszam">
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

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Üzenet a webáruháznak')}</h3>
                    <textarea x-model="data.webshopmessage" class="co-input" name="webshopmessage" rows="2"
                              placeholder="{t('pl. megrendeléssel, számlázással kapcsolatos kérések')}"></textarea>
                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Üzenet a futárnak')}</h3>
                    <textarea x-model="data.couriermessage" class="co-input" name="couriermessage" rows="2"
                              placeholder="{t('pl. kézbesítéssel kapcsolatos kérések')}"></textarea>
                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Véglegesítés')}</h3>
                    <label class="co-label">
                        <input x-model="data.akciohirlevel" name="akciohirlevel" type="checkbox">
                        {t('Igen, értesítsenek az akciókról')}
                    </label>
                    <label class="co-label">
                        <input x-model="data.ujdonsaghirlevel" name="ujdonsaghirlevel" type="checkbox">
                        {t('Igen, értesítsenek az újdonságokról')}
                    </label>
                    <label class="co-label">
                        <input x-model="aszfready" name="aszfready" type="checkbox">
                        Kérjük, a jelölőnégyzetbe helyezett pipával igazolja, hogy elolvasta, megértette, és elfogadta <a href="{$showaszflink}" target="empty"
                                                                                                                          class="js-chkaszf">ÁSZF</a>-ünket és
                        adatvédelmi nyilatkozatunkat.
                        Felhívjuk szíves figyelmét, hogy a „megrendelés elküldése” gombra történő kattintással Ön kötelező érvényű ajánlatot tesz a kosárba
                        helyezett termék megvásárlására, ami fizetési kötelezettséget von maga után.
                    </label>
                    <div>
                        <button class="btn btn-primary btn-order">{t('Megrendelés elküldése')}</button>
                    </div>
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