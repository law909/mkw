{extends "base.tpl"}

{block "script"}
		<script src="/js/main/superzone/cart.js"></script>
{/block}

{block "body"}
<div class="row">
    <div class="col-md-12 js-cart">
        {if (count($tetellista)>0)}
        <div class="megrendelemcontainer">
            <a href="{$prevuri}" class="btn btn-default">Continue shopping</a>
            <a href="{$showcheckoutlink}" rel="nofollow" class="btn btn-red pull-right">
                <i class="icon-ok icon-white"></i>
                {t('Order')}
            </a>
        </div>
        <table class="table table-bordered">
            {include 'kosartetellist.tpl'}
        </table>
        <div class="megrendelemcontainer">
            <a href="{$prevuri}" class="btn btn-default">Continue shopping</a>
            <a href="{$showcheckoutlink}" rel="nofollow" class="btn btn-red pull-right">
                <i class="icon-ok icon-white"></i>
                {t('Order')}
            </a>
        </div>
        {else}
            <h3>Your cart is empty!</h3>
            <a href="{$prevuri}" class="btn btn-default">Continue shopping</a>
        {/if}
    </div>
</div>
{/block}
