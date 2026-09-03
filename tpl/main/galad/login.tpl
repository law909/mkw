{extends "basestone.tpl"}

{block "bodyclass"}class="signin"{/block}
{block "stonebody"}
    <div class="container">
        <form class="form-signin" method="post" action="/login/ment">
            <h2 class="form-signin-heading">{t('Belépés')}</h2>
            <label for="inputEmail" class="sr-only">{t('Email cím')}</label>
            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="{t('Email cím')}" required="" autofocus="">
            <label for="inputPassword" class="sr-only">{t('Jelszó')}</label>
            <input type="password" name="jelszo" id="inputPassword" class="form-control" placeholder="{t('Jelszó')}" required="">
            <button class="btn btn-lg btn-red btn-block" type="submit">{t('Belépés')}</button>
        </form>
    </div>
{/block}
