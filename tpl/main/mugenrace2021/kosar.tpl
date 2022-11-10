{extends "base.tpl"}

{block "body"}
    <div class="cart-nav-spacer"></div>
<div class="cart-container js-cart">
    {if (count($tetellista)>0)}
    <div class="cart-megrendelem-container">
        <a href="{$prevuri}" class="cart-folytatom btn btn-secondary">
            {t('Folytatom a vásárlást')}
        </a>
        <a href="{$showcheckoutlink}" rel="nofollow" class="cart-megrendelem btn btn-primary">
            {t('Megrendelem')}
        </a>
    </div>
    <table class="table table-bordered">
        {include 'kosartetellist.tpl'}
    </table>
    <div class="cart-megrendelem-container">
        <a href="{$prevuri}" class="cart-folytatom btn btn-secondary">
            {t('Folytatom a vásárlást')}
        </a>
        <a href="{$showcheckoutlink}" rel="nofollow" class="cart-megrendelem btn btn-primary">
            {t('Megrendelem')}
        </a>
    </div>
    {else}
        <h3>{t('Az Ön kosara üres')}.</h3>
        <a href="{$prevuri}" class="btn okbtn">{t('Folytatom a vásárlást')}</a>
    {/if}
</div>
{/block}
