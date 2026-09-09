{* Amíg ez látszik, a gyakorlónak nem tud automatikus számla készülni: a title mondja meg, mi hiányzik. *}
{if ($resztvevo.szamlazasiakadaly)}
    <span class="text-danger" title="{$resztvevo.szamlazasiakadaly|escape}">
        <i class="fas fa-exclamation-triangle"></i> Hiányos számlázási adat
    </span>
{/if}
