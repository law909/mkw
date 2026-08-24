<div class="row top-margin-10 bottom-margin-10">
    <div class="col">
        <button class="btn btn-darshan js-idopontrefresh">Frissít <i class="fas fa-sync-alt"></i></button>
    </div>
</div>
{$sorszam = 1}
{foreach $foglalaslist as $foglalas}
    <div class="row top-margin-10 color-bkg-darshan">
        <div class="col-md-5">
            {$sorszam}. {$foglalas.nev} ({$foglalas.email}{if ($foglalas.telefon)}, {$foglalas.telefon}{/if})
        </div>
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
