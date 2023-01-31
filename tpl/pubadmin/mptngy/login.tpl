{extends "base.tpl"}

{block "script"}
    <script src="/js/pubadmin/mptngy/login.js?v=8"></script>
{/block}

{block "body"}
    <div class="co-container" x-data="login" x-init="getLists">
        <div class="co-data-container">
            <div class="co-row co-flex-dir-row">
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
                </div>
            </div>
        </div>
    </div>
{/block}