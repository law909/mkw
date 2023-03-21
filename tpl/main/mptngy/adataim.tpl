{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mptngy/adataim.js?v=2"></script>
{/block}

{block "body"}
    <div class="co-container" x-data="adataim" x-init="getLists">
        <div class="co-data-container">
            <div class="co-row co-flex-dir-column">
                <h4>{t('Számlázási adatok')}</h4>
                <div class="co-control-row">
                    <div :class="validation.invcsoportos && !validation.invcsoportos.valid ? 'error-border' : ''">
                        <label for="regInvSajatEdit" class="co-label">
                            <input id="regInvSajatEdit" type="radio" x-model="reg.invcsoportos" name="reginvcsoportos" value="2">
                            <span>{t('Én/saját cégem fog fizetni')}</span>
                        </label>
                        <label for="regInvCsoportosEdit" class="co-label">
                            <input id="regInvCsoportosEdit" type="radio" x-model="reg.invcsoportos" name="reginvcsoportos" value="1">
                            <span>{t('A munkáltatóm fog fizetni')}</span>
                        </label>
                    </div>
                    <div class="co-error" x-text="validation.invcsoportos && validation.invcsoportos.error"></div>
                </div>
                <div class="co-control-row" x-show="reg.invcsoportos === '2'">
                    <div :class="validation.invmaganszemely && !validation.invmaganszemely.valid ? 'error-border' : ''">
                        <label for="regInvMaganszemelyEdit" class="co-label">
                            <input id="regInvMaganszemelyEdit" type="radio" x-model="reg.invmaganszemely" name="reginvmaganszemely" value="1">
                            <span>{t('Magánszemélyként fogadom be a számlát')}</span>
                        </label>
                        <label for="regInvCegEdit" class="co-label">
                            <input id="regInvCegEdit" type="radio" x-model="reg.invmaganszemely" name="reginvmaganszemely" value="2">
                            <span>{t('Cégként fogadom be a számlát')}</span>
                        </label>
                    </div>
                    <div class="co-error" x-text="validation.invmaganszemely && validation.invmaganszemely.error"></div>
                </div>
                <div class="co-control-row">
                    <label for="regInvNevEdit" class="co-label">{t('Név')}*</label>
                    <input
                        id="regInvNevEdit"
                        class="co-input"
                        :class="validation.szlanev && !validation.szlanev.valid ? 'error' : ''"
                        type="text"
                        x-model="reg.szlanev"
                    >
                    <div class="co-error" x-text="validation.szlanev && validation.szlanev.error"></div>
                </div>
                <div class="co-control-row co-col-container">
                    <div class="co-col co-col-20">
                        <label for="regInvIrszamEdit" class="co-label">{t('Ir.szám')}*</label>
                        <input
                            id="regInvIrszamEdit"
                            class="co-input"
                            :class="validation.irszam && !validation.irszam.valid ? 'error' : ''"
                            type="text"
                            x-model="reg.irszam"
                        >
                        <div class="co-error" x-text="validation.irszam && validation.irszam.error"></div>
                    </div>
                    <div class="co-col co-col-80">
                        <label for="regInvVarosEdit" class="co-label">{t('Város')}*</label>
                        <input
                            id="regInvVarosEdit"
                            class="co-input"
                            :class="validation.varos && !validation.varos.valid ? 'error' : ''"
                            type="text"
                            x-model="reg.varos"
                        >
                        <div class="co-error" x-text="validation.varos && validation.varos.error"></div>
                    </div>
                </div>
                <div class="co-control-row">
                    <label for="regInvUtcaEdit" class="co-label">{t('Utca')}*</label>
                    <input
                        id="regInvUtcaEdit"
                        class="co-input"
                        :class="validation.utca && !validation.utca.valid ? 'error' : ''"
                        type="text"
                        x-model="reg.utca"
                    >
                    <div class="co-error" x-text="validation.utca && validation.utca.error"></div>
                </div>
                <div class="co-control-row">
                    <label for="regInvAdoszamEdit" class="co-label">{t('Adószám')}</label>
                    <input
                        id="regInvAdoszamEdit"
                        class="co-input"
                        :class="validation.adoszam && !validation.adoszam.valid ? 'error' : ''"
                        type="text"
                        x-model="reg.adoszam"
                    >
                    <div class="co-error" x-text="validation.adoszam && validation.adoszam.error"></div>
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
                <div class="co-control-row">
                    <label for="regMunkahelyEdit" class="co-label">{t('Munkahely')}</label>
                    <input id="regMunkahelyEdit" class="co-input" type="text" x-model="reg.mpt_munkahelynev">
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
                    <label for="vipvacsEdit" class="co-label">
                        <input id="vipvacsEdit" type="checkbox" x-model="reg.mptngyvipvacsora">
                        {t('1. nap állófogadáson részt veszek')}
                    </label>
                </div>
                <div class="co-control-row">
                    <label for="nap2Edit" class="co-label">
                        <input id="nap2Edit" type="checkbox" x-model="reg.mptngynapreszvetel2">
                        {t('2. nap részt veszek')}
                    </label>
                </div>
                <div class="co-control-row">
                    <label for="bankettEdit" class="co-label">
                        <input id="bankettEdit" type="checkbox" x-model="reg.mptngybankett">
                        {t('2. nap banketten részt veszek')}
                    </label>
                </div>
                <div class="co-control-row">
                    <label for="nap3Edit" class="co-label">
                        <input id="nap3Edit" type="checkbox" x-model="reg.mptngynapreszvetel3">
                        {t('3. nap részt veszek')}
                    </label>
                </div>
                <div class="co-control-row">
                    <label for="nemveszresztEdit" class="co-label">
                        <input id="nemveszresztEdit" type="checkbox" x-model="reg.mptngynemveszreszt">
                        {t('Nem veszek részt, csak szerző vagyok')}
                    </label>
                    <div class="co-error" x-text="validation.mptngynemveszreszt && validation.mptngynemveszreszt.error"></div>
                </div>
                <div class="co-control-row">
                    <label for="diakEdit" class="co-label">
                        <input id="diakEdit" type="checkbox" x-model="reg.mptngydiak">
                        {t('Diák vagyok')}
                    </label>
                </div>
                <div class="co-control-row">
                    <label for="nyugdijasEdit" class="co-label">
                        <input id="nyugdijasEdit" type="checkbox" x-model="reg.mptngynyugdijas">
                        {t('Nyugdíjas vagyok')}
                    </label>
                </div>
                <div class="co-control-row">
                    <label for="mptEdit" class="co-label">
                        <input id="mptEdit" type="checkbox" x-model="reg.mptngympttag">
                        {t('MPT tag vagyok')}
                    </label>
                </div>
                <div class="co-control-row">
                    <div :class="validation.mptngyszerepkor && !validation.mptngyszerepkor.valid ? 'error-border' : ''">
                        <template x-for="(szkor, i) in szerepkorlist">
                            <label class="co-label">
                                <input type="radio" name="szerepkor" x-model="reg.mptngyszerepkor" :value="szkor.id">
                                <span x-text="szkor.nevtr"></span>
                            </label>
                        </template>
                    </div>
                    <div class="co-error" x-text="validation.mptngyszerepkor && validation.mptngyszerepkor.error"></div>
                </div>
            </div>
            <div class="co-row co-flex-dir-column">
                <div class="co-control-row">
                    <button class="btn btn-primary" @click="save()">{t('Mentés')}</button>
                    <button class="btn btn-secondary" @click="cancel()">{t('Mégsem')}</button>
                </div>
            </div>
        </div>
    </div>
    </div>
{/block}