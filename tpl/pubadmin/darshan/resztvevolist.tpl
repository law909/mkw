{foreach $resztvevolist as $resztvevo}
    <div class="row js-resztvevo topmargin10 color-bkg-darshan">
        {if ($future)}
            <div class="col-1"></div>
            <div class="col">{$resztvevo.nev} ({$resztvevo.email})</div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        {if ($resztvevo.berlet)}
                            Bérlet: {$resztvevo.alkalom}/{$resztvevo.elfogyottalkalom} (az aktuális nélkül)
                        {else}
                            NINCS bérlete
                        {/if}
                    </div>
                </div>
            </div>
            <div class="col"></div>
        {else}
            {if ($resztvevo.megjelent)}
                <div class="col-1 bg-success"></div>
            {else}
                <div class="col-1 bg-danger"></div>
            {/if}
            <div class="col{if ($resztvevo.new)} text-danger{/if}">
                {$resztvevo.nev} ({$resztvevo.email})
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                {if ($resztvevo.berlet)}
                    Bérlet: {$resztvevo.alkalom}/{$resztvevo.elfogyottalkalom} (az aktuális nélkül)
                {else}
                    NINCS bérlete
                {/if}
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-darshan js-buy" data-type="1" data-id="{$resztvevo.id}">Órajegy</button>
                        <button class="btn btn-darshan js-buy" data-type="2" data-id="{$resztvevo.id}">4-es bérlet</button>
                    </div>
                </div>
            </div>
            <button class="col btn btn-darshan js-setmegjelent" data-id="{$resztvevo.id}">{if (!$resztvevo.megjelent)}Megérkezett{else}Nem érkezett meg{/if}</button>
        {/if}
    </div>
{/foreach}
<div class="row topmargin10">
    <div class="col">
        <button class="btn btn-darshan">Új gyakorló</button>
    </div>
</div>