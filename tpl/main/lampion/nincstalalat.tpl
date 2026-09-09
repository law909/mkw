{extends "base.tpl"}

{block "kozep"}
    <div class="hasab uzenetlap">
        <h1>{t('Nincs találat')}</h1>
        <p>{t('Erre a keresésre nem találtunk terméket. Próbálja meg más szóval, vagy böngésszen a kategóriák között.')}</p>
        <a class="gomb" href="/">{t('Vissza a főoldalra')}</a>

        {if ($ajanlotttermekek|default)}
            <section class="blokk">
                <h2>{t('Ajánlatunk')}</h2>
                <div class="racs">
                    {foreach $ajanlotttermekek as $_termek}
                        {include "termekdoboz.tpl" termek=$_termek}
                    {/foreach}
                </div>
            </section>
        {/if}
    </div>
{/block}
