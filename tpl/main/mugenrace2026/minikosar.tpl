{* <a id="minikosar" class="side-cart__open pull-right" href="{$kosargetlink}" rel="nofollow"> *}

    <div class="side-cart__open pull-right" rel="nofollow">
        {if ($kosar.termekdb)}
            <div class="mini-cart" data-empty="0">
                <span class="mini-cart__counter">
                    {number_format($kosar.termekdb, 0, ',', ' ')}
                </span>
                <i class="icon cart white"></i>
                {* &nbsp;{t('termék')} *}
            </div>
            {* <span>
                {number_format($kosar.brutto, 0, ',', ' ')} {$kosar.valutanem} {t('a kosarában')}
            </span> *}
        {else}
            <div class="mini-cart" data-empty="1">
                <i class="icon cart white"></i>
                {* {t('Kosár')} *}
            </div>
        {/if}
    </div>
    {* </a> *}
    
    {if !($smarty.server.REQUEST_URI|strpos:'/kosar/get' !== false)}
        <div class="side-cart js-cart">
            <div class="side-cart__header">
                <h3>{t('Kosár')}</h3>
                <i class="icon close side-cart__close icon__click"></i>
            </div>
            <div class="side-cart__body">
                {if $kosar.tetellista|@count > 0}
                    {$tetellista=$kosar.tetellista}
                    {if (count($tetellista)>0)}
                        <table class="cart-page__table table table-bordered">
                            {include 'kosartetellist.tpl'}
                        </table>
                    {/if}
                {/if}
            </div>
            <div class="side-cart__footer">
                <div class="megrendelemcontainer flex-cb">
                    <a href="{$prevuri}" class="button bordered okbtn">{t('Folytatom a vásárlást')}</a>
                    <a href="{$showcheckoutlink}" rel="nofollow" class="button primary cartbtn pull-right">
                        <i class="icon cart icon__click"></i>
                        {t('Megrendelem')}
                    </a>
                </div>
            </div>
        </div>
    {/if}
