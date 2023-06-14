{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mugenrace2021/checkout.js?v=3"></script>
{/block}

{block "body"}
    <div class="co-nav-spacer"></div>
    <div x-data="{ imagepath: '{$imagepath}' }">
        <div class="co-container js-checkout" x-data="checkout">
            <div class="co-data-container">
                <template x-if="!data.id">
                    <div class="co-row co-flex-dir-row">
                        <div class="co-login-box">
                            <h3>{t('Új vásárló')}</h3>
                            <div class="co-radio-row">
                                <label for="regGuestEdit" class="co-radio-button">
                                    <input
                                        id="regGuestEdit"
                                        type="radio"
                                        x-model="regNeeded"
                                        name="regneeded"
                                        value="1"
                                    >
                                    <span class="co-radio-text">{t('Vásárlás vendégként (regisztráció nélkül)')}</span>
                                </label>

                                <label for="regRegEdit" class="co-radio-button">
                                    <input
                                        id="regRegEdit"
                                        type="radio"
                                        x-model="regNeeded"
                                        name="regneeded"
                                        value="2"
                                    >
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
                </template>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Kapcsolati adatok')}</h3>

                    <div class="co-control-row">
                        <label for="lastnameEdit" class="co-label">{t('Vezetéknév')} *</label>
                        <input
                            id="lastnameEdit"
                            class="co-input"
                            :class="validation.vezeteknev && !validation.vezeteknev.valid ? 'error' : ''"
                            type="text"
                            x-model="data.vezeteknev"
                        >
                        <div class="co-error" x-text="validation.vezeteknev && validation.vezeteknev.error"></div>
                    </div>

                    <div class="co-control-row">
                        <label for="firstnameEdit" class="co-label">{t('Keresztnév')} *</label>
                        <input
                            id="firstnameEdit"
                            class="co-input"
                            :class="validation.keresztnev && !validation.keresztnev.valid ? 'error' : ''"
                            type="text"
                            x-model="data.keresztnev"
                        >
                        <div class="co-error" x-text="validation.keresztnev && validation.keresztnev.error"></div>
                    </div>

                    <div class="co-control-row">
                        <label for="phoneEdit" class="co-label">{t('Telefon')} *</label>
                        <input
                            id="phoneEdit"
                            class="co-input"
                            :class="validation.telefon && !validation.telefon.valid ? 'error' : ''"
                            type="text"
                            x-model="data.telefon"
                        >
                        <div class="co-error" x-text="validation.telefon && validation.telefon.error"></div>
                    </div>

                    <div class="co-control-row">
                        <label for="emailEdit" class="co-label">{t('Email')} *</label>
                        <input
                            id="emailEdit"
                            class="co-input"
                            :class="validation.email && !validation.email.valid ? 'error' : ''"
                            type="text"
                            x-model="data.email"
                        >
                        <div class="co-error" x-text="validation.email && validation.email.error"></div>
                    </div>

                    <div class="co-control-row" x-show="regNeeded === '2'">
                        <label for="pw1Edit" class="co-label">{t('Jelszó 1')} *</label>
                        <input
                            id="pw1Edit"
                            class="co-input"
                            :class="validation.password1 && !validation.password1.valid ? 'error' : ''"
                            type="password"
                            x-model="data.password1"
                        >
                        <div class="co-error" x-text="validation.password1 && validation.password1.error"></div>
                    </div>
                    <div class="co-control-row" x-show="regNeeded === '2'">
                        <label for="pw2Edit" class="co-label">{t('Jelszó 2')} *</label>
                        <input
                            id="pw2Edit"
                            class="co-input"
                            :class="validation.password2 && !validation.password2.valid ? 'error' : ''"
                            type="password"
                            x-model="data.password2"
                        >
                        <div class="co-error" x-text="validation.password2 && validation.password2.error"></div>
                    </div>

                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Szállítási adatok')}</h3>

                    <div class="co-control-row">
                        <label for="deliverynameEdit" class="co-label">{t('Szállítási név')} *</label>
                        <input
                            id="deliverynameEdit"
                            class="co-input"
                            :class="validation.szallnev && !validation.szallnev.valid ? 'error' : ''"
                            type="text"
                            x-model="data.szallnev"
                        >
                        <div class="co-error" x-text="validation.szallnev && validation.szallnev.error"></div>
                    </div>
                    <div class="co-control-row co-col-container">
                        <div class="co-col co-col-20">
                            <label for="deliverypostalcodeEdit" class="co-label">{t('Ir.szám')} *</label>
                            <input
                                id="deliverypostalcodeEdit"
                                class="co-input"
                                :class="validation.szallirszam && !validation.szallirszam.valid ? 'error' : ''"
                                type="text"
                                x-model="data.szallirszam"
                            >
                            <div class="co-error" x-text="validation.szallirszam && validation.szallirszam.error"></div>
                        </div>
                        <div class="co-col co-col-80">
                            <label for="deliverycityEdit" class="co-label">{t('Város')} *</label>
                            <input
                                id="deliverycityEdit"
                                class="co-input"
                                :class="validation.szallvaros && !validation.szallvaros.valid ? 'error' : ''"
                                type="text"
                                x-model="data.szallvaros"
                            >
                            <div class="co-error" x-text="validation.szallvaros && validation.szallvaros.error"></div>
                        </div>
                    </div>
                    <div class="co-control-row">
                        <label for="deliverystreetEdit" class="co-label">{t('Utca')} *</label>
                        <input
                            id="deliverystreetEdit"
                            class="co-input"
                            :class="validation.szallutca && !validation.szallutca.valid ? 'error' : ''"
                            type="text"
                            x-model="data.szallutca"
                        >
                        <div class="co-error" x-text="validation.szallutca && validation.szallutca.error"></div>
                    </div>
                    <div class="co-control-row">
                        <label for="deliverynumEdit" class="co-label">{t('Házszám')}</label>
                        <input
                            id="deliverynumEdit"
                            class="co-input"
                            :class="validation.szallhazszam && !validation.szallhazszam.valid ? 'error' : ''"
                            type="text"
                            x-model="data.szallhazszam"
                        >
                        <div class="co-error" x-text="validation.szallhazszam && validation.szallhazszam.error"></div>
                    </div>
                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Számlázási adatok')}</h3>
                    <div class="co-control-row">
                        <label for="inveqdeliveryEdit" class="co-label">
                            <input
                                id="inveqdeliveryEdit"
                                type="checkbox"
                                x-model="data.inveqdel"
                            >
                            {t('Megegyezik a szállítási adatokkal')}
                        </label>
                    </div>
                    <div class="co-control-row" x-show="!data.inveqdel">
                        <label for="cegesEdit" class="co-label">
                            <input
                                id="cegesEdit"
                                type="checkbox"
                                x-model="data.cegesvasarlo"
                            >
                            {t('Cégként vásárolok')}
                        </label>
                    </div>
                    <div class="co-control-row co-col-container" x-show="!data.inveqdel && data.cegesvasarlo">
                        <div class="=co-col co-col-20">
                            <label for="invoiceadoszamEdit" class="co-label">{t('Adószám')} *</label>
                            <input
                                id="invoiceadoszamEdit"
                                class="co-input"
                                :class="validation.adoszam && !validation.adoszam.valid ? 'error' : ''"
                                type="text"
                                x-model="data.adoszam"
                            >
                            <div class="co-error" x-text="validation.adoszam && validation.adoszam.error"></div>
                        </div>
                    </div>
                    <div class="co-control-row" x-show="!data.inveqdel">
                        <label for="invoicenameEdit" class="co-label">{t('Számlázási név')} *</label>
                        <input
                            id="invoicenameEdit"
                            class="co-input"
                            :class="validation.szlanev && !validation.szlanev.valid ? 'error' : ''"
                            type="text"
                            x-model="data.szlanev"
                        >
                        <div class="co-error" x-text="validation.szlanev && validation.szlanev.error"></div>
                    </div>
                    <div class="co-control-row co-col-container" x-show="!data.inveqdel">
                        <div class="co-col co-col-20">
                            <label for="invoicepostalcodeEdit" class="co-label">{t('Ir.szám')} *</label>
                            <input
                                id="invoicepostalcodeEdit"
                                class="co-input"
                                :class="validation.irszam && !validation.irszam.valid ? 'error' : ''"
                                type="text"
                                x-model="data.irszam"
                            >
                            <div class="co-error" x-text="validation.irszam && validation.irszam.error"></div>
                        </div>
                        <div class="co-col co-col-80">
                            <label for="invoicecityEdit" class="co-label">{t('Város')} *</label>
                            <input
                                id="invoicecityEdit"
                                class="co-input"
                                :class="validation.varos && !validation.varos.valid ? 'error' : ''"
                                type="text"
                                x-model="data.varos"
                            >
                            <div class="co-error" x-text="validation.varos && validation.varos.error"></div>
                        </div>
                    </div>
                    <div class="co-control-row" x-show="!data.inveqdel">
                        <label for="invoicestreetEdit" class="co-label">{t('Utca')} *</label>
                        <input
                            id="invoicestreetEdit"
                            class="co-input"
                            :class="validation.utca && !validation.utca.valid ? 'error' : ''"
                            type="text"
                            x-model="data.utca"
                        >
                        <div class="co-error" x-text="validation.utca && validation.utca.error"></div>
                    </div>
                    <div class="co-control-row" x-show="!data.inveqdel">
                        <label for="invoicenumEdit" class="co-label">{t('Házszám')}</label>
                        <input
                            id="invoicenumEdit"
                            class="co-input"
                            :class="validation.hazszam && !validation.hazszam.valid ? 'error' : ''"
                            type="text"
                            x-model="data.hazszam"
                        >
                        <div class="co-error" x-text="validation.hazszam && validation.hazszam.error"></div>
                    </div>
                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Szállítási mód')} *</h3>
                    <div :class="validation.szallitasimod && !validation.szallitasimod.valid ? 'error-border' : ''">
                        <template x-for="(szallmod, i) in szallmodlist">
                            <div>
                                <label class="co-label">
                                    <input
                                        type="radio"
                                        name="szallitasimod"
                                        x-model="selectedSzallitasimodIndex"
                                        :value="i"
                                    >
                                    <span x-text="szallmod.caption + ' ' + (szallmod.brutto ? szallmod.brutto : '')"></span>
                                    <div class="co-legend" x-html="szallmod.leiras"></div>
                                </label>
                            </div>
                        </template>
                        <div class="co-error" x-text="validation.szallitasimod && validation.szallitasimod.error"></div>
                    </div>
                </div>

                <div class="co-row co-flex-dir-column">
                    <h3>{t('Fizetési mód')} *</h3>
                    <div :class="validation.fizetesimod && !validation.fizetesimod.valid ? 'error-border' : ''">
                        <template x-for="(fizmod, i) in selectedSzallitasimod?.fizmodlist">
                            <div>
                                <label class="co-label">
                                    <input
                                        type="radio"
                                        name="fizetesimod"
                                        x-model="selectedFizetesimodIndex"
                                        :value="i"
                                    >
                                    <span x-text="fizmod.nev"></span>
                                    <div class="co-legend" x-html="fizmod.leiras"></div>
                                </label>
                            </div>
                        </template>
                        <div class="co-error" x-text="validation.fizetesimod && validation.fizetesimod.error"></div>
                    </div>
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
                    <label class="co-label" :class="validation.aszfready && !validation.aszfready.valid ? 'error-border' : ''">
                        <input
                            x-model="data.aszfready"
                            name="aszfready"
                            type="checkbox"
                        >
                        {t('Kérjük, a jelölőnégyzetbe helyezett pipával igazolja, hogy elolvasta, megértette, és elfogadta ÁSZF-ünket és adatvédelmi nyilatkozatunkat.')}
                        <a href="{$showaszflink}" target="empty" class="js-chkaszf">{t('ÁSZF')}</a>
                        {t('Felhívjuk szíves figyelmét, hogy a „megrendelés elküldése” gombra történő kattintással Ön kötelező érvényű ajánlatot tesz a kosárba helyezett termék megvásárlására, ami fizetési kötelezettséget von maga után.')}
                        <div class="co-error" x-text="validation.aszfready && validation.aszfready.error"></div>
                    </label>
                    <div>
                        <button class="btn btn-primary btn-order" @click="save()">{t('Megrendelés elküldése')}</button>
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