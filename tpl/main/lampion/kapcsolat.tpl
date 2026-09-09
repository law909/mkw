{extends "base.tpl"}

{block "kozep"}
    <div class="hasab urlap">
        <h1>{t('Írjon nekünk')}</h1>
        <p class="lead">{t('Kérdése van egy termékkel vagy a kínálatunkkal kapcsolatban? Töltse ki az űrlapot, és hamarosan válaszolunk.')}</p>

        <form action="/kapcsolat/ment" method="post">
            <div class="mezo{if ($hibak.nev|default)} hibas{/if}">
                <label for="nev">{t('Név')}</label>
                <input id="nev" name="nev" type="text" value="{$nev|default|escape}" required>
                <span class="hiba">{$hibak.nev|default}</span>
            </div>
            <div class="mezo{if ($hibak.email|default)} hibas{/if}">
                <label for="email1">{t('Emailcím')}</label>
                <input id="email1" name="email1" type="email" value="{$email1|default|escape}" required>
                <span class="hiba">{$hibak.email|default}</span>
            </div>
            <div class="mezo{if ($hibak.email|default)} hibas{/if}">
                <label for="email2">{t('Emailcím megerősítése')}</label>
                <input id="email2" name="email2" type="email" value="{$email2|default|escape}" required>
            </div>
            <div class="mezo">
                <label for="telefon">{t('Telefonszám')}</label>
                <input id="telefon" name="telefon" type="text" value="{$telefon|default|escape}">
            </div>
            <div class="mezo{if ($hibak.tema|default)} hibas{/if}">
                <label for="tema">{t('Témakör')}</label>
                <select id="tema" name="tema" required>
                    <option value="">{t('válasszon')}</option>
                    {foreach $temalista as $_tema}
                        <option value="{$_tema.id}"{if ($_tema.selected)} selected{/if}>{$_tema.caption|default}</option>
                    {/foreach}
                </select>
                <span class="hiba">{$hibak.tema|default}</span>
            </div>
            <div class="mezo">
                <label for="szoveg">{t('Üzenet')}</label>
                <textarea id="szoveg" name="szoveg" rows="6" required>{$szoveg|default|escape}</textarea>
            </div>
            <button type="submit" class="gomb">{t('Üzenet küldése')}</button>
        </form>
    </div>
{/block}
