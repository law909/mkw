{extends "base.tpl"}

{block "script"}
    <script src="/js/main/mpt/login.js"></script>
{/block}

{block "body"}
    <div class="mpt-loginbox" x-data="login">
        <h1>{t('MPT tagi belépés')}</h1>
        {if ($sikertelen)}
            <div class="mpt-hiba">{t('Hibás emailcím vagy jelszó.')}</div>
        {/if}
        <div class="mpt-hiba" x-show="hiba" x-text="hiba"></div>
        <div class="mpt-field">
            <label class="mpt-label" for="EmailEdit">{t('Email')}</label>
            <input id="EmailEdit" class="mpt-input" type="email" x-model="adat.email" @keyup.enter="belep()">
        </div>
        <div class="mpt-field">
            <label class="mpt-label" for="JelszoEdit">{t('Jelszó')}</label>
            <input id="JelszoEdit" class="mpt-input" type="password" x-model="adat.jelszo" @keyup.enter="belep()">
        </div>
        <div class="mpt-field">
            <button class="mpt-btn" @click="belep()">{t('Belépés')}</button>
        </div>
    </div>
{/block}
