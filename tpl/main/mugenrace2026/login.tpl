{extends "base.tpl"}

{block "kozep"}
    <div class="container-full login-page">
        <div class="row flex-cc">
            <div class="col login-page__left">

            </div>
            <div class="col flex-cc flex-col login-page__right">


                <div class="form-header">
                    <h3 class="title-header"><span>{t('Jelentkezzen be')}</span></h3>
                </div>
                {if ($sikertelen)}
                    <div class="error">
                        <h4>{t('A bejelentkezés nem sikerült')}</h4>
                    </div>
                {/if}
                <form id="Loginform" action="/login/ment" method="post">
                    <fieldset>
                        <div class="controls chk-controloffset">
                            <input name="email" type="email" class="" placeholder="{t('email')} *" required data-errormsg1="{t('Adja meg az emailcímét')}"
                                   data-errormsg2="{t('Kérjük emailcímet adjon meg.')}">
                        </div>
                        <div class="controls chk-controloffset">
                            <input name="jelszo" type="password" class="" placeholder="{t('jelszó')} *" value="">
                        </div>
                        <div class="row chk-actionrow">
                            <div class="span">
                                <button type="submit" class="button primary okbtn">{t('Belépés')}</button>
                            </div>
                        </div>
                        <div class="row chk-actionrow">
                            <div class="span">
                                <a class="span js-passreminder" href>{t('Kattintson ide, ha elfelejtette a jelszavát')}!</a>
                            </div>
                        </div>
                    </fieldset>
                </form>

                {* <div class="divider"></div> *}

            </div>
        </div>
    </div>
{/block}
