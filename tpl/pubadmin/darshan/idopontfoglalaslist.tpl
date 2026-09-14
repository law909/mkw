<div class="row top-margin-10 bottom-margin-10">
    <div class="col">
        <button class="btn btn-darshan js-idopontrefresh">Frissít <i class="fas fa-sync-alt"></i></button>
        {if ($idopontid)}
            <button class="btn btn-darshan js-idopontlemond" data-idopontid="{$idopontid}"
                    data-idopontdatum="{$idopontdatum}">Lemond <i class="fas fa-archive"></i></button>
        {/if}
    </div>
</div>
{$sorszam = 1}
{foreach $foglalaslist as $foglalas}
    <div class="row top-margin-10 color-bkg-darshan">
        <button class="col-md-5 btn text-left js-idopontpartneredit" data-id="{$foglalas.id}">
            {$sorszam}. {$foglalas.nev|escape} ({$foglalas.email|escape}{if ($foglalas.telefon)}, {$foglalas.telefon|escape}{/if})
            {if ($foglalas.nincspartner)}
                <span class="badge badge-warning">Még nincs partner</span>
            {else}
                {include "comp_szamlazasiakadaly.tpl" resztvevo=$foglalas}
            {/if}
            {if ($foglalas.szamlanev)}
                <span class="d-block small">Számlán: {$foglalas.szamlanev|escape}</span>
            {/if}
        </button>
        <div class="col-md-3">
            {if ($foglalas.online)}Online{else}Élő{/if}{if ($foglalas.fizetve)} - fizetve{/if}
        </div>
        <div class="col-md-4">
            {if ($foglalas.lemondva)}
                LEMONDTA
            {else}
                <button class="btn btn-darshan top-bottom-margin-5 js-setidopontmegjelent" data-id="{$foglalas.id}">
                    {if (!$foglalas.megjelent)}Megérkezett{else}Nem érkezett meg{/if}
                </button>
            {/if}
        </div>
    </div>
    {$sorszam = $sorszam + 1}
{/foreach}
{if (!$foglalaslist|@count)}
    <div class="row top-margin-10">
        <div class="col">Erre az időpontra nincs foglalás.</div>
    </div>
{/if}
{if ($idopontid)}
    <div class="row top-margin-10 bottom-margin-10">
        <div class="col">
            <button class="btn btn-darshan js-newidopontpartner">Új gyakorló</button>
        </div>
    </div>
{/if}
