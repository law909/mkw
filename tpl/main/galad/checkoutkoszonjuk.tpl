{extends "base.tpl"}

{block "body"}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>{t('Köszönjük')}</h3>
            <p>{t('Köszönjük a megrendelését.')}</p>
            {if ($megrendelesszam)}
                <p>{t('A rendelés száma')}: <strong>{$megrendelesszam}</strong></p>
            {/if}
        </div>
    </div>
{/block}
