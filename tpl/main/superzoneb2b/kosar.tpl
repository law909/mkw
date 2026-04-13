{extends "base.tpl"}

{block "body"}
    <div class="row">
        <div class="col-md-12 js-cart">
            {if ($minkosarertekerror)}
                <div class="alert alert-danger">{$minkosarertekerror}</div>
            {/if}
            {if (count($tetellista)>0)}
                <div class="megrendelemcontainer">
                    <a href="{$prevuri}" class="btn btn-default">Continue shopping</a>
                    {if (!$minkosarertekerror)}
                        <a href="{$showcheckoutlink}" rel="nofollow" class="btn btn-red pull-right">
                            <i class="icon-ok icon-white"></i>
                            {t('Order')}
                        </a>
                    {/if}
                </div>
                <table class="table table-bordered">
                    {include 'kosartetellistsp.tpl'}
                </table>
                <div class="megrendelemcontainer">
                    <a href="{$prevuri}" class="btn btn-default">Continue shopping</a>
                    {if (!$minkosarertekerror)}
                        <a href="{$showcheckoutlink}" rel="nofollow" class="btn btn-red pull-right">
                            <i class="icon-ok icon-white"></i>
                            {t('Order')}
                        </a>
                    {/if}
                </div>
            {else}
                <h3>Your cart is empty!</h3>
                <a href="{$prevuri}" class="btn btn-default">Continue shopping</a>
            {/if}
        </div>
    </div>
{/block}
