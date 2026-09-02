{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mpt/main.js"></script>
{/block}

{block "body"}
    <div x-data="mptadatok" x-cloak>
        <nav class="mpt-nav">
            <div class="mpt-nav-brand">{t('MPT tagság')}</div>
            <button class="mpt-nav-toggle" @click="menuOpen = !menuOpen" aria-label="{t('Menü')}">☰</button>
            <div class="mpt-nav-links" :class="menuOpen ? 'open' : ''">
                <button :class="lap === 'adataim' ? 'aktiv' : ''" @click="lapValt('adataim')">{t('Adataim')}</button>
                <button :class="lap === 'penzugyek' ? 'aktiv' : ''" @click="lapValt('penzugyek')">{t('Pénzügyek')}</button>
                <button :class="lap === 'jelszo' ? 'aktiv' : ''" @click="lapValt('jelszo')">{t('Jelszó módosítás')}</button>
                <a class="mpt-nav-logout" href="/logout">{t('Kilépés')}</a>
            </div>
        </nav>

        <div class="mpt-content">
            <div class="mpt-hiba" x-show="hiba" x-text="hiba"></div>

            <div x-show="lap === 'adataim'">
                <div class="mpt-card">
                    <h2>{t('Tagság')}</h2>
                    <div class="mpt-grid">
                        <div class="mpt-field">
                            <span class="mpt-label">{t('Tagság kezdete')}</span>
                            <div class="mpt-readonly" x-text="partner.mpt_tagsagdate || '—'"></div>
                        </div>
                        <div class="mpt-field">
                            <span class="mpt-label">{t('Tagkártya száma')}</span>
                            <div class="mpt-readonly" x-text="partner.mpt_tagkartya || '—'"></div>
                        </div>
                    </div>
                </div>

                <div class="mpt-card">
                    <h2>{t('Személyes adatok')}</h2>
                    <div class="mpt-grid">
                        <div class="mpt-field">
                            <label class="mpt-label" for="MegszolitasEdit">{t('Megszólítás')}</label>
                            <input id="MegszolitasEdit" class="mpt-input" type="text" maxlength="20" x-model="partner.mpt_megszolitas">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="NevEdit">{t('Név')} *</label>
                            <input id="NevEdit" class="mpt-input" :class="hibak.nev ? 'hibas' : ''" type="text" maxlength="255" x-model="partner.nev">
                            <div class="mpt-mezohiba" x-show="hibak.nev" x-text="hibak.nev"></div>
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="VezeteknevEdit">{t('Vezetéknév')}</label>
                            <input id="VezeteknevEdit" class="mpt-input" type="text" maxlength="255" x-model="partner.vezeteknev">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="KeresztnevEdit">{t('Keresztnév')}</label>
                            <input id="KeresztnevEdit" class="mpt-input" type="text" maxlength="255" x-model="partner.keresztnev">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="SzuletesEveEdit">{t('Születés éve')}</label>
                            <input id="SzuletesEveEdit" class="mpt-input" type="number" min="1900" max="2100" x-model="partner.mpt_szuleteseve">
                        </div>
                    </div>
                </div>

                <div class="mpt-card">
                    <h2>{t('Elérhetőség')}</h2>
                    <div class="mpt-grid">
                        <div class="mpt-field">
                            <label class="mpt-label" for="EmailEdit">{t('Email (belépési azonosító)')} *</label>
                            <input id="EmailEdit" class="mpt-input" :class="hibak.email ? 'hibas' : ''" type="email" maxlength="100" x-model="partner.email">
                            <div class="mpt-mezohiba" x-show="hibak.email" x-text="hibak.email"></div>
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="PrivatEmailEdit">{t('Privát email')}</label>
                            <input id="PrivatEmailEdit" class="mpt-input" type="email" maxlength="100" x-model="partner.mpt_privatemail">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="TelefonEdit">{t('Telefon')}</label>
                            <input id="TelefonEdit" class="mpt-input" type="text" maxlength="40" x-model="partner.telefon">
                        </div>
                    </div>
                </div>

                <div class="mpt-card">
                    <h2>{t('Számlázási adatok')}</h2>
                    <div class="mpt-grid">
                        <div class="mpt-field">
                            <label class="mpt-label" for="SzamlazasiNevEdit">{t('Számlázási név')}</label>
                            <input id="SzamlazasiNevEdit" class="mpt-input" type="text" maxlength="255" x-model="partner.mpt_szamlazasinev">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="AdoszamEdit">{t('Adószám')}</label>
                            <input id="AdoszamEdit" class="mpt-input" type="text" maxlength="13" placeholder="99999999-9-99" x-model="partner.adoszam">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="IrszamEdit">{t('Irányítószám')}</label>
                            <input id="IrszamEdit" class="mpt-input" type="text" maxlength="10" x-model="partner.irszam">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="VarosEdit">{t('Város')}</label>
                            <input id="VarosEdit" class="mpt-input" type="text" maxlength="40" x-model="partner.varos">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="UtcaEdit">{t('Utca')}</label>
                            <input id="UtcaEdit" class="mpt-input" type="text" maxlength="60" x-model="partner.utca">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="HazszamEdit">{t('Házszám')}</label>
                            <input id="HazszamEdit" class="mpt-input" type="text" maxlength="40" x-model="partner.hazszam">
                        </div>
                    </div>
                </div>

                <div class="mpt-card">
                    <h2>{t('Munkahely')}</h2>
                    <div class="mpt-grid">
                        <div class="mpt-field mpt-field-wide">
                            <label class="mpt-label" for="MunkahelyNevEdit">{t('Munkahely neve')}</label>
                            <input id="MunkahelyNevEdit" class="mpt-input" type="text" maxlength="255" x-model="partner.mpt_munkahelynev">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="MunkahelyIrszamEdit">{t('Irányítószám')}</label>
                            <input id="MunkahelyIrszamEdit" class="mpt-input" type="text" maxlength="10" x-model="partner.mpt_munkahelyirszam">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="MunkahelyVarosEdit">{t('Város')}</label>
                            <input id="MunkahelyVarosEdit" class="mpt-input" type="text" maxlength="40" x-model="partner.mpt_munkahelyvaros">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="MunkahelyUtcaEdit">{t('Utca')}</label>
                            <input id="MunkahelyUtcaEdit" class="mpt-input" type="text" maxlength="60" x-model="partner.mpt_munkahelyutca">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="MunkahelyHazszamEdit">{t('Házszám')}</label>
                            <input id="MunkahelyHazszamEdit" class="mpt-input" type="text" maxlength="40" x-model="partner.mpt_munkahelyhazszam">
                        </div>
                    </div>
                </div>

                <div class="mpt-card">
                    <h2>{t('Lakcím')}</h2>
                    <div class="mpt-grid">
                        <div class="mpt-field">
                            <label class="mpt-label" for="LakcimIrszamEdit">{t('Irányítószám')}</label>
                            <input id="LakcimIrszamEdit" class="mpt-input" type="text" maxlength="10" x-model="partner.mpt_lakcimirszam">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="LakcimVarosEdit">{t('Város')}</label>
                            <input id="LakcimVarosEdit" class="mpt-input" type="text" maxlength="40" x-model="partner.mpt_lakcimvaros">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="LakcimUtcaEdit">{t('Utca')}</label>
                            <input id="LakcimUtcaEdit" class="mpt-input" type="text" maxlength="60" x-model="partner.mpt_lakcimutca">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="LakcimHazszamEdit">{t('Házszám')}</label>
                            <input id="LakcimHazszamEdit" class="mpt-input" type="text" maxlength="40" x-model="partner.mpt_lakcimhazszam">
                        </div>
                    </div>
                </div>

                <div class="mpt-card">
                    <h2>{t('Végzettség')}</h2>
                    <div class="mpt-grid">
                        <div class="mpt-field">
                            <label class="mpt-label" for="VegzettsegEdit">{t('Végzettség')}</label>
                            <input id="VegzettsegEdit" class="mpt-input" type="text" maxlength="255" x-model="partner.mpt_vegzettseg">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="FokozatEdit">{t('Tudományos fokozat')}</label>
                            <input id="FokozatEdit" class="mpt-input" type="text" maxlength="40" x-model="partner.mpt_fokozat">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="DiplomaEveEdit">{t('Diploma éve')}</label>
                            <input id="DiplomaEveEdit" class="mpt-input" type="number" min="1900" max="2100" x-model="partner.mpt_diplomaeve">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="DiplomaHelyEdit">{t('Diploma iskolája')}</label>
                            <input id="DiplomaHelyEdit" class="mpt-input" type="text" maxlength="255" x-model="partner.mpt_diplomahely">
                        </div>
                        <div class="mpt-field mpt-field-wide">
                            <label class="mpt-label" for="EgyebDiplomaEdit">{t('Egyéb diplomák')}</label>
                            <input id="EgyebDiplomaEdit" class="mpt-input" type="text" maxlength="255" x-model="partner.mpt_egyebdiploma">
                        </div>
                    </div>
                </div>

                <div class="mpt-card">
                    <h2>{t('Tagság részletei')}</h2>
                    <div class="mpt-grid">
                        <div class="mpt-field">
                            <label class="mpt-label" for="TagsagformaEdit">{t('Tagság forma')}</label>
                            <select id="TagsagformaEdit" class="mpt-input" x-model="partner.mpt_tagsagforma">
                                <option value="">{t('válasszon')}</option>
                                <template x-for="item in tagsagformalist" :key="item.id">
                                    <option :value="item.id" x-text="item.caption"></option>
                                </template>
                            </select>
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="TagozatEdit">{t('Tagozat')}</label>
                            <select id="TagozatEdit" class="mpt-input" x-model="partner.mpt_tagozat">
                                <option value="">{t('válasszon')}</option>
                                <template x-for="item in tagozatlist" :key="item.id">
                                    <option :value="item.id" x-text="item.caption"></option>
                                </template>
                            </select>
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="Szekcio1Edit">{t('Szekció 1')}</label>
                            <select id="Szekcio1Edit" class="mpt-input" x-model="partner.mpt_szekcio1">
                                <option value="">{t('válasszon')}</option>
                                <template x-for="item in szekciolist" :key="item.id">
                                    <option :value="item.id" x-text="item.caption"></option>
                                </template>
                            </select>
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="Szekcio2Edit">{t('Szekció 2')}</label>
                            <select id="Szekcio2Edit" class="mpt-input" x-model="partner.mpt_szekcio2">
                                <option value="">{t('válasszon')}</option>
                                <template x-for="item in szekciolist" :key="item.id">
                                    <option :value="item.id" x-text="item.caption"></option>
                                </template>
                            </select>
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="Szekcio3Edit">{t('Szekció 3')}</label>
                            <select id="Szekcio3Edit" class="mpt-input" x-model="partner.mpt_szekcio3">
                                <option value="">{t('válasszon')}</option>
                                <template x-for="item in szekciolist" :key="item.id">
                                    <option :value="item.id" x-text="item.caption"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mpt-actions">
                    <button class="mpt-btn" :disabled="mentesfolyik" @click="ment()">{t('Mentés')}</button>
                    <span class="mpt-uzenet" x-show="uzenet" x-text="uzenet"></span>
                </div>
            </div>

            <div x-show="lap === 'penzugyek'">
                <div class="mpt-card">
                    <h2>{t('Pénzügyek')}</h2>
                    <div class="mpt-egyenleg">
                        <span class="mpt-label">{t('Egyenleg')}:</span>
                        <span :class="folyoszamla.egyenleg < 0 ? 'tartozas' : 'tulfizetes'" x-text="osszeg(folyoszamla.egyenleg)"></span>
                    </div>
                    <table class="mpt-tabla">
                        <thead>
                        <tr>
                            <th>{t('Év')}</th>
                            <th>{t('Típus')}</th>
                            <th class="jobbra">{t('Összeg')}</th>
                            <th>{t('Bizonylatszám')}</th>
                            <th>{t('Dátum')}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="(item, i) in folyoszamla.tetelek" :key="i">
                            <tr>
                                <td data-cim="{t('Év')}" x-text="item.vonatkozoev"></td>
                                <td data-cim="{t('Típus')}" x-text="item.tipusnev"></td>
                                <td data-cim="{t('Összeg')}" class="jobbra" :class="item.osszeg < 0 ? 'tartozas' : 'tulfizetes'" x-text="osszeg(item.osszeg)"></td>
                                <td data-cim="{t('Bizonylatszám')}" x-text="item.bizonylatszam"></td>
                                <td data-cim="{t('Dátum')}" x-text="item.datum"></td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <div class="mpt-ures" x-show="folyoszamla.tetelek.length === 0">{t('Nincs folyószámla tétel.')}</div>
                </div>
            </div>

            <div x-show="lap === 'jelszo'">
                <div class="mpt-card">
                    <h2>{t('Jelszó módosítás')}</h2>
                    <div class="mpt-hiba" x-show="jelszohiba" x-text="jelszohiba"></div>
                    <div class="mpt-grid">
                        <div class="mpt-field mpt-field-wide">
                            <label class="mpt-label" for="JelszoRegiEdit">{t('Jelenlegi jelszó')}</label>
                            <input id="JelszoRegiEdit" class="mpt-input" type="password" x-model="jelszo.jelszoregi">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="Jelszo1Edit">{t('Új jelszó')}</label>
                            <input id="Jelszo1Edit" class="mpt-input" type="password" x-model="jelszo.jelszo1">
                        </div>
                        <div class="mpt-field">
                            <label class="mpt-label" for="Jelszo2Edit">{t('Új jelszó megerősítése')}</label>
                            <input id="Jelszo2Edit" class="mpt-input" type="password" x-model="jelszo.jelszo2">
                        </div>
                    </div>
                </div>
                <div class="mpt-actions">
                    <button class="mpt-btn" @click="jelszoMentes()">{t('Mentés')}</button>
                    <span class="mpt-uzenet" x-show="jelszouzenet" x-text="jelszouzenet"></span>
                </div>
            </div>
        </div>
    </div>
{/block}
