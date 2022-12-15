{extends "base.tpl"}

{block "body"}
    <div class="cart-nav-spacer"></div>
<div class="cart-container js-cart">
    {if (count($tetellista)>0)}
    <div class="cart-megrendelem-container">
        <button onclick="location = '{$prevuri}'" class="cart-folytatom btn btn-secondary">
            {t('Folytatom a vásárlást')}
        </button>
        <button onclick="location = '{$showcheckoutlink}'" class="cart-megrendelem btn btn-primary">
            {t('Megrendelem')}
        </button>
    </div>
    <table class="table table-bordered">
        {include 'kosartetellist.tpl'}
    </table>
    <div class="cart-megrendelem-container">
        <button onclick="location = '{$prevuri}'" class="cart-folytatom btn btn-secondary">
            {t('Folytatom a vásárlást')}
        </button>
        <button onclick="location = '{$showcheckoutlink}'" class="cart-megrendelem btn btn-primary">
            {t('Megrendelem')}
        </button>
    </div>
    {else}
        <h3>{t('Az Ön kosara üres')}.</h3>
        <button onclick="location = '{$prevuri}'" class="btn okbtn">{t('Folytatom a vásárlást')}</button>
    {/if}
</div>
{/block}
