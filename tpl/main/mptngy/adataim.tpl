{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mptngy/adataim.js?v=1"></script>
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
                <div class="co-control-row">
                    <button class="btn btn-primary" @click="save()">{t('Mentés')}</button>
                    <button class="btn btn-secondary" @click="cancel()">{t('Mégsem')}</button>
                </div>
            </div>
        </div>
    </div>
{/block}