{extends "base.tpl"}

{block "script"}
    {$osszesen=0}
    {foreach $tetellista as $tetel}
        {$osszesen=$osszesen+$tetel.bruttohuf}
    {/foreach}
    <script>
        fbq('track', 'ViewCart', {
            value: {number_format($osszesen,0,',','')},
            currency: '{$valutanemnev}'
        });
    </script>
{/block}

{block "kozep"}
    <div class="container page-header static-page__header">
        <div class="row">
            <div class="col">
            {include 'morzsa.tpl'}
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h1 class="page-header__title">{t('Kosár')}</h1>
            </div>
        </div>
    </div>
    <div class="container cart-page whitebg">
        <div class="row">
            <div class="col flex-cc flex-col js-cart">
                {if ($minkosarertekerror)}
                    <div class="alert alert-danger">{$minkosarertekerror}</div>
                {/if}
                {if (count($tetellista)>0)}
                    <div class="megrendelemcontainer flex-cb">
                        <a href="{$prevuri}" class="button bordered okbtn">{t('Folytatom a vásárlást')}</a>
                        {if (!$minkosarertekerror)}
                            <a href="{$showcheckoutlink}" rel="nofollow" class="button primary cartbtn pull-right">
                                <i class="icon cart icon__click"></i>
                                {t('Megrendelem')}
                            </a>
                        {/if}
                    </div>
                    <table class="cart-page__table table table-bordered">
                        {include 'kosartetellist.tpl'}
                    </table>
                    <div class="megrendelemcontainer flex-cb">
                        <a href="{$prevuri}" class="button bordered okbtn">{t('Folytatom a vásárlást')}</a>
                        {if (!$minkosarertekerror)}
                            <a href="{$showcheckoutlink}" rel="nofollow" class="button primary cartbtn pull-right">
                                <i class="icon cart icon__click"></i>
                                {t('Megrendelem')}
                            </a>
                        {/if}
                    </div>
                {else}
                    <h3>{t('Az Ön kosara üres')}.</h3>
                    <a href="{$prevuri}" class="button bordered okbtn">{t('Folytatom a vásárlást')}</a>
                {/if}
            </div>
        </div>
    </div>
{/block}
