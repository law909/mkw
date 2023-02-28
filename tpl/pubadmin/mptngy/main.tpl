{extends "base.tpl"}

{block "script"}
    <script src="/js/pubadmin/mptngy/main.js?v=1"></script>
{/block}

{block "body"}
    <div x-data="main">
        <div class="co-container co-header" x-cloak>
            <div class="co-col-50 padding">
                <div>{$me.nev}</div>
            </div>
            <div class="padding">
                <button class="btn btn-secondary" @click="biralas()">{t('Szakmai anyagok')}</button>
            </div>
            <div class="padding">
                <button class="btn btn-secondary" @click="beallitasok()">{t('Beállítások')}</button>
            </div>
            <div class="padding">
                <button class="btn btn-secondary" @click="logout()">{t('Kijelentkezés')}</button>
            </div>
        </div>
    </div>
    {block "main"}
        
    {/block}
{/block}