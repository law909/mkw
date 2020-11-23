<div class="row top-margin-10 bottom-margin-10">
    <div class="col">
        <button class="btn btn-darshan js-refresh">Frissít <i class="fas fa-sync-alt"></i></button>
    </div>
</div>
{$sorszam = 1}
{foreach $resztvevolist as $resztvevo}
    <div class="row js-resztvevo top-margin-10 color-bkg-darshan">
        {if ($future)}
            <div class="col-1">&nbsp;</div>
            <div class="col">{$sorszam}. {$resztvevo.nev} ({$resztvevo.email})</div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        {if ($resztvevo.berlet)}
                            Bérlet: {$resztvevo.alkalom}/{$resztvevo.elfogyottalkalom}
                        {else}
                            NINCS bérlete
                        {/if}
                    </div>
                </div>
            </div>
            <div class="col"></div>
        {else}
            {if ($resztvevo.megjelent)}
                <div class="col-md-1 bg-success">&nbsp;</div>
            {else}
                <div class="col-md-1 bg-danger">&nbsp;</div>
            {/if}
            <div class="col-md-5{if ($resztvevo.new)} text-danger{/if}">
                {$sorszam}. {$resztvevo.nev} ({$resztvevo.email})
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col">
                {if ($resztvevo.berlet)}
                    Bérlet: {$resztvevo.alkalom}/{$resztvevo.elfogyottalkalom}
                {elseif ($resztvevo.orajegy)}
                    Órajegyet vett
                {else}
                    NINCS bérlete
                {/if}
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-darshan js-buy" data-type="1" data-id="{$resztvevo.id}" data-price="{$resztvevo.type1price}">
                            Órajegy
                        </button>
                    </div>
                    <div class="col">
                        <button class="btn btn-darshan js-buy" data-type="2" data-id="{$resztvevo.id}" data-price="{$resztvevo.type2price}">
                            4-es bérlet
                        </button>
                    </div>
                </div>
            </div>
            <button class="col-md-2 btn btn-darshan top-bottom-margin-5 js-setmegjelent" data-id="{$resztvevo.id}" {if (!$resztvevo.megjelent)}data-mustbuy="{$resztvevo.mustbuy}"{/if}>{if (!$resztvevo.megjelent)}Megérkezett{else}Nem érkezett meg{/if}</button>
        {/if}
    </div>
    {$sorszam = $sorszam + 1}
{/foreach}
<div class="row top-margin-10 bottom-margin-10">
    <div class="col">
        <button class="btn btn-darshan js-newpartner">Új gyakorló</button>
    </div>
</div>