{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mptngy/login.js?v=1"></script>
{/block}

{block "body"}
    <div class="co-container" x-data="login" x-init="getLists">
        <div class="co-data-container">
            <div class="co-row co-flex-dir-row" x-show="!regNeeded">
                <div class="co-col-100 padding">
                    <h3>{t('Bejelentkezés')}</h3>
                    <div class="co-control-row">
                        <label for="loginUserEdit" class="co-label">{t('Email')}</label>
                        <input id="loginUserEdit" class="co-input" type="text" x-model="login.email">
                    </div>
                    <div class="co-control-row">
                        <label for="loginPasswordEdit" class="co-label">{t('Jelszó')}</label>
                        <input id="loginPasswordEdit" class="co-input" type="password" x-model="login.jelszo">
                    </div>
                    <div class="co-control-row">
                        <button class="btn btn-primary" @click="dologin()">{t('Belépés')}</button>
                    </div>
                    <div class="co-control-row">
                        <span> - vagy - </span>
                    </div>
                    <div class="co-control-row">
                        <button class="btn btn-secondary" @click="regNeeded = true">{t('Regisztráció')}</button>
                    </div>
                </div>
            </div>
            <div class="co-row co-flex-dir-row" x-show="regNeeded">
                <div class="co-col-100 padding">

                    <div class="co-row co-flex-dir-column">
                        <h3>{t('Regisztráció')}</h3>
                        <div class="co-control-row">
                            <label for="regNevEdit" class="co-label">{t('Név')}</label>
                            <input id="regNevEdit" class="co-input" type="text" x-model="reg.nev">
                        </div>
                        <div class="co-control-row">
                            <label for="regTelEdit" class="co-label">{t('Telefon')}</label>
                            <input id="regTelEdit" class="co-input" type="text" x-model="reg.telefon">
                        </div>
                        <div class="co-control-row">
                            <label for="regEmailEdit" class="co-label">{t('Email')}</label>
                            <input id="regEmailEdit" class="co-input" type="text" x-model="reg.email">
                        </div>
                        <div class="co-control-row co-col-container">
                            <div class="co-col co-col-50">
                                <label for="regPw1Edit" class="co-label">{t('Jelszó')}</label>
                                <input id="regPw1Edit" class="co-input" type="password" x-model="reg.jelszo1">
                            </div>
                            <div class="co-col co-col-50">
                                <label for="regPw2Edit" class="co-label">{t('Jelszó ismét')}</label>
                                <input id="regPw2Edit" class="co-input" type="password" x-model="reg.jelszo2">
                            </div>
                        </div>
                    </div>

                    <div class="co-row co-flex-dir-column">
                        <h4>{t('Számlázási adatok')}</h4>
                        <div class="co-control-row">
                            <label for="regInvNevEdit" class="co-label">{t('Név')}</label>
                            <input id="regInvNevEdit" class="co-input" type="text" x-model="reg.szlanev">
                        </div>
                        <div class="co-control-row co-col-container">
                            <div class="co-col co-col-20">
                                <label for="regInvIrszamEdit" class="co-label">{t('Ir.szám')}</label>
                                <input id="regInvIrszamEdit" class="co-input" type="text" x-model="reg.irszam">
                            </div>
                            <div class="co-col co-col-80">
                                <label for="regInvVarosEdit" class="co-label">{t('Város')}</label>
                                <input id="regInvVarosEdit" class="co-input" type="text" x-model="reg.varos">
                            </div>
                        </div>
                        <div class="co-control-row">
                            <label for="regInvUtcaEdit" class="co-label">{t('Utca')}</label>
                            <input id="regInvUtcaEdit" class="co-input" type="text" x-model="reg.utca">
                        </div>
                        <div class="co-control-row">
                            <label for="regInvAdoszamEdit" class="co-label">{t('Adószám')}</label>
                            <input id="regInvAdoszamEdit" class="co-input" type="text" x-model="reg.adoszam">
                        </div>
                        <div class="co-control-row">
                            <label for="regInvBankEdit" class="co-label">{t('Bankszámlaszám')}</label>
                            <input id="regInvBankEdit" class="co-input" type="text" x-model="reg.mptngybankszamlaszam">
                        </div>
                        <div class="co-control-row">
                            <label for="regCsopEdit" class="co-label">{t('Csoportos fizetés')}</label>
                            <input id="regCsopEdit" class="co-input" type="text" x-model="reg.mptngycsoportosfizetes">
                        </div>
                        <div class="co-control-row">
                            <label for="regKapcsolatEdit" class="co-label">{t('Kapcsolat név')}</label>
                            <input id="regKapcsolatEdit" class="co-input" type="text" x-model="reg.mptngykapcsolatnev">
                        </div>
                    </div>

                    <div class="co-row co-flex-dir-column">
                        <h4>{t('Levelezési adatok')}</h4>
                        <div class="co-control-row">
                            <label for="postaleqEdit" class="co-label">
                                <input id="postaleqEdit" type="checkbox" x-model="postaleqinv">
                                {t('Megegyezik a számlázási adatokkal')}
                            </label>
                        </div>
                        <div class="co-control-row co-col-container">
                            <div class="co-col co-col-20">
                                <label for="regPostalIrszamEdit" class="co-label">{t('Ir.szám')}</label>
                                <input id="regPostalIrszamEdit" class="co-input" type="text" x-model="reg.lirszam">
                            </div>
                            <div class="co-col co-col-80">
                                <label for="regPostalVarosEdit" class="co-label">{t('Város')}</label>
                                <input id="regPostalVarosEdit" class="co-input" type="text" x-model="reg.lvaros">
                            </div>
                        </div>
                        <div class="co-control-row">
                            <label for="regPostalUtcaEdit" class="co-label">{t('Utca')}</label>
                            <input id="regPostalUtcaEdit" class="co-input" type="text" x-model="reg.lutca">
                        </div>
                    </div>

                    <div class="co-row co-flex-dir-column">
                        <h4>{t('Egyéb adatok')}</h4>
                        <div class="co-control-row">
                            <label for="nap1Edit" class="co-label">
                                <input id="nap1Edit" type="checkbox" x-model="reg.mptngynapreszvetel1">
                                {t('1. nap részt veszek')}
                            </label>
                        </div>
                        <div class="co-control-row">
                            <label for="nap2Edit" class="co-label">
                                <input id="nap2Edit" type="checkbox" x-model="reg.mptngynapreszvetel2">
                                {t('2. nap részt veszek')}
                            </label>
                        </div>
                        <div class="co-control-row">
                            <label for="nap3Edit" class="co-label">
                                <input id="nap3Edit" type="checkbox" x-model="reg.mptngynapreszvetel3">
                                {t('3. nap részt veszek')}
                            </label>
                        </div>
                        <div class="co-control-row">
                            <label for="vipvacsEdit" class="co-label">
                                <input id="vipvacsEdit" type="checkbox" x-model="reg.mptngyvipvacsora">
                                {t('VIP vacsorán részt veszek')}
                            </label>
                        </div>
                        <div class="co-control-row">
                            <label for="diakEdit" class="co-label">
                                <input id="diakEdit" type="checkbox" x-model="reg.mptngydiak">
                                {t('Diák/nyugdíjas vagyok')}
                            </label>
                        </div>
                        <div class="co-control-row">
                            <label for="mptEdit" class="co-label">
                                <input id="mptEdit" type="checkbox" x-model="reg.mptngympttag">
                                {t('MPT tag vagyok')}
                            </label>
                        </div>
                        <div class="co-control-row">
                            <template x-for="(szkor, i) in szerepkorlist">
                                <div>
                                    <label class="co-label">
                                        <input type="radio" name="szerepkor" x-model="reg.mptngyszerepkor" :value="szkor.id">
                                        <span x-text="szkor.nev"></span>
                                    </label>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="co-control-row">
                        <button class="btn btn-primary" @click="save()">{t('Regisztráció')}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}