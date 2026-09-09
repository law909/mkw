{extends "base.tpl"}

{block "meta"}
    <meta property="og:type" content="product">
    <meta property="og:title" content="{$termek.caption|escape}">
    {if ($termek.fullkepurl)}<meta property="og:image" content="{$termek.fullkepurl}">{/if}
{/block}

{block "kozep"}
    <div class="hasab termeklap">
        <div class="galeria">
            <img class="fokep js-fokep" id="fokep"
                 src="{$termek.kepurl|default:$termek.kozepeskepurl}" alt="{$termek.caption|escape}">
            {if ($termek.kepek|@count) > 0}
                <div class="miniatur">
                    <button type="button" class="js-kepvalto" data-kep="{$termek.kepurl}">
                        <img src="{$termek.minikepurl}" alt="" loading="lazy">
                    </button>
                    {foreach $termek.kepek as $_kep}
                        <button type="button" class="js-kepvalto" data-kep="{$_kep.kepurl}">
                            <img src="{$_kep.minikepurl}" alt="{$_kep.leiras|escape}" loading="lazy">
                        </button>
                    {/foreach}
                </div>
            {/if}
        </div>

        <div class="adatok">
            <h1>{$termek.caption}</h1>
            <dl class="jellemzok">
                {if ($termek.cikkszam)}<dt>{t('Cikkszám')}</dt><dd>{$termek.cikkszam}</dd>{/if}
                {if ($termek.me)}<dt>{t('Mennyiségi egység')}</dt><dd>{$termek.me}</dd>{/if}
                {if ($termek.marka|default)}<dt>{t('Márka')}</dt><dd>{$termek.marka}</dd>{/if}
            </dl>

            {if ($termek.bruttohuf|default) > 0}
                <div class="lapar">{$termek.bruttohuf|bizformat:0} {$valutanemnev|default:'Ft'}</div>
            {/if}

            {if ($termek.rovidleiras)}<div class="lead">{$termek.rovidleiras}</div>{/if}
        </div>

        {if ($termek.leiras)}
            <section class="leiras">
                <h2>{t('Termékleírás')}</h2>
                {$termek.leiras}
            </section>
        {/if}

        {if ($hozzavasarolttermekek|default)}
            <section class="ajanlo">
                <h2>{t('Ezeket is nézze meg')}</h2>
                <div class="racs">
                    {foreach $hozzavasarolttermekek as $_termek}
                        {include "termekdoboz.tpl" termek=$_termek}
                    {/foreach}
                </div>
            </section>
        {/if}
    </div>
{/block}
