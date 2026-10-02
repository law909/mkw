{extends "../base.tpl"}

{* bejelentkezés előtt nincs dolgozói téma, a modern.css sem töltődik: a lap stílusa önálló (style.css .login-*) *}
{block "inhead"}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="/js/admin/default/login.js"></script>
{/block}

{block "kozep"}
    <div class="login-oldal">
        <form id="loginform" class="login-kartya" method="POST" action="{$loginurl}">
            <div class="login-fej">
                <div class="login-alkalmazas">{at('Billy Admin')}</div>
                <h1 class="login-ceg">{if ($tulajnev)}{$tulajnev}{else}{at('Bejelentkezés')}{/if}</h1>
                {if ($teszt)}
                    <span class="login-teszt">{at('TESZT')}</span>
                {/if}
            </div>
            {if ($hiba|default:0)}
                <div class="login-hiba" role="alert">{at('Hibás e-mail cím vagy jelszó.')}</div>
            {/if}
            <label class="login-cimke" for="LoginEmail">{at('Email')}</label>
            <input id="LoginEmail" class="login-mezo" name="email" type="text" autocomplete="username" autocapitalize="none"
                   spellcheck="false" required autofocus>
            <label class="login-cimke" for="LoginJelszo">{at('Jelszó')}</label>
            <div class="login-jelszo">
                <input id="LoginJelszo" class="login-mezo" name="jelszo" type="password" autocomplete="current-password" required>
                <button type="button" class="login-mutat js-jelszomutato" title="{at('Jelszó megjelenítése')}" aria-label="{at('Jelszó megjelenítése')}"
                        aria-pressed="false">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <button class="login-gomb" name="ok" type="submit">{at('Bejelentkezés')}</button>
        </form>
    </div>
{/block}
