{* A résztvevő neve a listában: a név a jelentkezésé, a „Számlán" sor a partnertörzsé, ha eltér. *}
{$sorszam}. {$resztvevo.nev|escape} ({$resztvevo.email|escape})
{if ($resztvevo.nincspartner)}
    <span class="badge badge-warning"
          title="Az órarendből jelentkezett, és ezzel az emailcímmel még nincs partnerünk. A partner a Megérkezett vagy egy vásárlás gombra jön létre a jelentkezés adataival – vagy most, ha a nevére kattintasz és mentesz.">Még nincs partner</span>
{else}
    {include "comp_szamlazasiakadaly.tpl"}
{/if}
{if ($resztvevo.szamlanev)}
    <span class="d-block small">Számlán: {$resztvevo.szamlanev|escape}</span>
{/if}
