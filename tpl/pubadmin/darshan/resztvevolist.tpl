{foreach $resztvevolist as $resztvevo}
    <div class="row js-resztvevo topmargin10 color-bkg-darshan">
        <div class="col{if ($resztvevo.new)} text-danger{/if}">
            {$resztvevo.nev} ({$resztvevo.email})
        </div>
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
            <div class="row">
                <div class="col">
                    <button class="btn btn-primary">Órajegy</button>
                    <button class="btn btn-primary">4-es bérlet</button>
                </div>
            </div>
        </div>
        <button class="col btn btn-success"{if (!$resztvevo.berlet)} disabled{/if}>Jelen</button>
    </div>
{/foreach}