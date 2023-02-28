{extends "base.tpl"}

{block "body"}
    <div class="co-container">
        <div class="co-data-container">
            <div class="co-row co-flex-dir-row">
                <div class="co-col-100 padding">
                    <h3>{t('Bejelentkezés')}</h3>
                    <form method="POST" action="/pubadmin/login">
                        <div class="co-control-row">
                            <label for="loginUserEdit" class="co-label">{t('Email')}</label>
                            <input id="loginUserEdit" class="co-input" type="email" name="email">
                        </div>
                        <div class="co-control-row">
                            <label for="loginPasswordEdit" class="co-label">{t('Jelszó')}</label>
                            <input id="loginPasswordEdit" class="co-input" type="password" name="jelszo">
                        </div>
                        <div class="co-control-row">
                            <button class="btn btn-primary" type="submit">{t('Belépés')}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/block}